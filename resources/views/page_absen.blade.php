<!DOCTYPE html>
<html lang="id">
<head>
  <base target="_top">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Scanner SIAP SPPG TLAHAB LOR</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/html5-qrcode"></script>
  <style>
    :root {
      --brand: #38bdf8;
      --brand-2: rgba(56, 189, 248, 0.4);
      --bg-dim: rgba(0, 0, 0, 0.4);
      --panel: rgba(255, 255, 255, 0.05);
      --panel-border: rgba(255, 255, 255, 0.15);
      --success: #86efac;
      --warning: #facc15;
      --danger: #fca5a5;
    }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 25%, #0369a1 50%, #0f172a 75%, #1e293b 100%);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      color: white;
      box-sizing: border-box;
      position: relative;
      overflow: hidden;
    }

    /* Scanner overlay guide */
    .scanner-overlay {
      position: absolute;
      inset: 0;
      display: grid;
      place-items: center;
      pointer-events: none;
      z-index: 3;
    }

    .scanner-overlay .frame {
      width: 250px;
      height: 250px;
      border-radius: 18px;
      border: 2px solid rgba(56, 189, 248, 0.65);
      box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.35);
      position: relative;
      background: rgba(0, 0, 0, 0.12);
    }

    .scanner-overlay .frame::before,
    .scanner-overlay .frame::after {
      content: '';
      position: absolute;
      width: 64px;
      height: 4px;
      background: rgba(56, 189, 248, 0.9);
      border-radius: 999px;
      opacity: 0.95;
    }

    .scanner-overlay .frame::before {
      top: 22px;
      left: 28px;
      transform: translateX(0);
      box-shadow: 0 0 16px rgba(56, 189, 248, 0.5);
    }

    .scanner-overlay .frame::after {
      bottom: 22px;
      right: 28px;
      width: 64px;
    }

    .scanner-overlay .hint {
      margin-top: 265px;
      width: min(320px, 100%);
      text-align: center;
      font-size: 12px;
      color: rgba(226, 232, 240, 0.9);
      text-shadow: 0 2px 10px rgba(0,0,0,0.35);
      line-height: 1.4;
    }

    /* torch button accessibility (kept by library, just ensure z-index) */
    #reader .html5-qrcode__torch {
      z-index: 4 !important;
    }

    /* Make overlay frame responsive */
    @media (max-width: 420px) {
      .scanner-overlay .frame {
        width: 220px;
        height: 220px;
      }
      .scanner-overlay .hint {
        margin-top: 235px;
      }
    }


    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle at 20% 50%, rgba(56, 189, 248, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 80% 80%, rgba(15, 23, 42, 0.5) 0%, transparent 50%);
      pointer-events: none;
      z-index: 1;
    }

    .container {
      width: 100%;
      max-width: 420px;
      padding: 35px 25px;
      text-align: center;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(25px);
      -webkit-backdrop-filter: blur(25px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 30px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 60px rgba(56, 189, 248, 0.2);
      position: relative;
      z-index: 2;
    }

    .logo-container {
      margin-bottom: 20px;
    }

    .logo-img {
      width: 100px;
      height: auto;
      filter: drop-shadow(0 4px 10px rgba(0,0,0,0.5));
      margin-bottom: 15px;
      border-radius: 50%;
    }

    .sppg-title {
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 1.5px;
      color: #e2e8f0;
      text-transform: uppercase;
      margin-bottom: 5px;
      line-height: 1.4;
    }

    h2 {
      margin: 0 0 5px 0;
      font-weight: 800;
      letter-spacing: -0.5px;
      text-shadow: 0 2px 10px rgba(0,0,0,0.3);
      color: #38bdf8;
      font-size: 22px;
    }

    p.subtitle {
      font-size: 9px;
      color: rgba(255, 255, 255, 0.5);
      margin-bottom: 25px;
      text-transform: uppercase;
      letter-spacing: 2px;
      font-weight: 700;
    }

    .scanner-wrapper {
      position: relative;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 0 40px rgba(56, 189, 248, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.1);
      background: black;
    }

    #reader {
      width: 100%;
      border: none !important;
    }

    #result-box {
      margin-top: 25px;
      padding: 20px;
      border-radius: 20px;
      background: rgba(0, 0, 0, 0.4);
      min-height: 85px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 14px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      text-align: center;
    }

    .loading-spinner {
      border: 3px solid rgba(255, 255, 255, 0.1);
      border-top: 3px solid #38bdf8;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      animation: spin 1s linear infinite;
      margin-bottom: 10px;
    }

    .info-text {
      font-size: 12px;
      color: rgba(226, 232, 240, 0.8);
      margin-top: 12px;
      line-height: 1.5;
    }

    .back-button {
      display: inline-block;
      margin-top: 18px;
      padding: 10px 18px;
      color: #fff;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: 999px;
      text-decoration: none;
      font-size: 12px;
      letter-spacing: 0.8px;
      transition: background 0.25s ease, transform 0.25s ease;
    }

    .back-button:hover {
      background: rgba(56, 189, 248, 0.18);
      transform: translateY(-1px);
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo-container">
      <img src="https://tse4.mm.bing.net/th/id/OIP.imhsy5gDwX4hRolJ8oJE1AHaHV?rs=1&pid=ImgDetMain&o=7&rm=3" class="logo-img" alt="Logo Tlahab Lor">
      <div class="sppg-title">Satuan Pelayanan Pemenuhan Gizi (SPPG)<br>Tlahab Lor</div>
    </div>

    <h2>SIAP SPPG TLAHAB LOR</h2>
    <p class="subtitle">Instant Attendance Scanner System</p>
    
    <div class="scanner-wrapper">
      <div id="reader"></div>
      <div class="scanner-overlay" aria-hidden="true">
        <div class="frame"></div>
        <div class="hint">Posisikan QR di dalam kotak • Tunggu sebentar sampai diproses otomatis</div>
      </div>
    </div>


    <div id="result-box">
      <div id="status-content">
        <div style="font-size: 20px; margin-bottom: 5px">📷</div>
        <div style="opacity: 0.8">Arahkan kamera ke QR Code Relawan</div>
      </div>
    </div>

    <div class="info-text">Pastikan kode QR mengandung ID relawan yang valid sesuai database.</div>
    <a href="{{ url('/') }}" class="back-button">Kembali</a>
  </div>

  <script>
    const resultBox = document.getElementById('result-box');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let isProcessing = false;
    let scanLockedUntil = 0;

    function lockScan(ms = 2500) {
      scanLockedUntil = Date.now() + ms;
      isProcessing = true;
    }

    function unlockScan() {
      isProcessing = false;
    }

    function onScanSuccess(decodedText) {
      if (Date.now() < scanLockedUntil) return;
      if (isProcessing) return;

      lockScan(2500);


      resultBox.style.background = "rgba(56, 189, 248, 0.15)";
      resultBox.style.borderColor = "rgba(56, 189, 248, 0.4)";
      resultBox.innerHTML = `
        <div class="loading-spinner"></div>
        <div style="color: #38bdf8">MEMPROSES ABSENSI...</div>
      `;

      fetch('/scan/process', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ id_relawan: decodedText })
      })
      .then(res => res.json())
      .then(data => {
        renderResult(data);
      })
      .catch(() => {
        renderResult({ status: 'error', message: 'Gagal terhubung ke server!' });
      })
      .finally(() => {
        setTimeout(() => {
          resetUI();
          unlockScan();
        }, 2500);
      });

    }

    function renderResult(res) {
      const success = res.status === 'success';
      const icon = success ? '✅' : (res.status === 'warning' ? '⚠️' : '❌');
      const color = success ? '#86efac' : (res.status === 'warning' ? '#facc15' : '#fca5a5');
      const bg = success ? 'rgba(34, 197, 94, 0.2)' : (res.status === 'warning' ? 'rgba(250, 204, 21, 0.15)' : 'rgba(239, 68, 68, 0.2)');
      const border = success ? 'rgba(34, 197, 94, 0.4)' : (res.status === 'warning' ? 'rgba(250, 204, 21, 0.4)' : 'rgba(239, 68, 68, 0.4)');
      const nameLine = res.nama ? `<div style="color: rgba(226, 232, 240, 0.8); font-size: 14px; margin-top: 8px;">Relawan: ${res.nama}</div>` : '';

      resultBox.innerHTML = `
        <div style="font-size: 28px; margin-bottom: 5px">${icon}</div>
        <div style="color: ${color}; font-size: 16px;">${res.message}</div>
        ${nameLine}
        <div style="margin-top:10px; font-size:12px; opacity:0.75">${success ? 'Berhasil tersimpan.' : (res.status === 'warning' ? 'Tidak bisa scan lebih dari 2x hari ini.' : 'Coba scan ulang QR.' )}</div>
      `;
      resultBox.style.background = bg;
      resultBox.style.borderColor = border;
    }


    function resetUI() {
      resultBox.style.background = "rgba(0, 0, 0, 0.4)";
      resultBox.style.borderColor = "rgba(255, 255, 255, 0.1)";
      resultBox.innerHTML = `
        <div style="font-size: 20px; margin-bottom: 5px">📷</div>
        <div style="opacity: 0.8">Siap Scan Berikutnya</div>
        <div style="margin-top:6px; font-size:12px; opacity:0.75">Arahkan QR ke dalam kotak</div>
      `;
    }


    const html5QrcodeScanner = new Html5QrcodeScanner(
      "reader",
      {
        fps: 20,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0,
        showTorchButtonIfSupported: true,
        disableFlip: false
      },
      false
    );

    html5QrcodeScanner.render(onScanSuccess);
  </script>
</body>
</html>
