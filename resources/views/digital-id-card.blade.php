<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital ID Card - {{ $relawan->nama ?? 'SIAP SPPG' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, #0d1117 0%, #010409 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #e6edf3;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            padding: 30px 20px;
            max-width: 700px;
            margin: 0 auto;
        }

        .page-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #e6edf3;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .id-card {
            width: 540px;
            height: 870px;
            background: linear-gradient(180deg, #161b22 0%, #0d1117 100%);
            border: 1px solid #30363d;
            border-radius: 32px;
            padding: 35px 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.9), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .id-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(88, 166, 255, 0.03) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .card-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            text-align: center;
            height: 100%;
            justify-content: flex-start;
        }

        .header-label {
            font-size: 13px;
            font-weight: 700;
            color: #58a6ff;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 10px;
        }

        .brand-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 46px;
            font-weight: 900;
            color: #ffffff;
            font-style: italic;
            letter-spacing: -1.5px;
            margin: 0 0 6px 0;
            text-shadow: 0 2px 8px rgba(88, 166, 255, 0.3);
            position: relative;
            z-index: 3;
        }

        .subtitle {
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: #8b949e;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 18px;
            position: relative;
            z-index: 3;
        }

        .divider {
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #58a6ff, transparent);
            margin: 15px 0 20px 0;
        }

        .photo-wrapper {
            margin: 20px 0 25px 0;
        }

        .photo-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #58a6ff;
            overflow: hidden;
            background: linear-gradient(135deg, #0d1117, #161b22);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(88, 166, 255, 0.4),
                        inset 0 0 12px rgba(88, 166, 255, 0.15);
        }

        .photo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #21262d, #161b22);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #58a6ff;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .qr-wrapper {
            background: #ffffff;
            border-radius: 10px;
            padding: 8px;
            margin: 20px 0;
            width: 140px;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(88, 166, 255, 0.25);
        }

        .qr-wrapper img {
            width: 100%;
            height: 100%;
        }

        .staff-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 23px;
            font-weight: 900;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 12px 0 6px 0;
            position: relative;
            z-index: 3;
        }

        .staff-id {
            font-family: 'Courier New', monospace;
            font-size: 17px;
            font-weight: 700;
            color: #58a6ff;
            letter-spacing: 2px;
            margin-bottom: 12px;
            text-transform: uppercase;
            position: relative;
            z-index: 3;
        }

        .staff-badge {
            display: inline-block;
            background: rgba(88, 166, 255, 0.15);
            border: 1.5px solid #58a6ff;
            color: #58a6ff;
            padding: 8px 18px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.7px;
            text-transform: uppercase;
            margin-bottom: 15px;
            position: relative;
            z-index: 3;
        }

        .department-badge {
            display: inline-block;
            background: rgba(139, 148, 158, 0.2);
            border: 1.5px solid #8b949e;
            color: #b0bec5;
            padding: 7px 14px;
            border-radius: 18px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            position: relative;
            z-index: 3;
        }

        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 25px;
            width: 540px;
            justify-content: center;
        }

        .btn {
            flex: 1;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-cancel {
            background: #21262d;
            color: #8b949e;
            border: 1px solid #30363d;
        }

        .btn-cancel:hover {
            background: #30363d;
            color: #c9d1d9;
            border-color: #444c56;
        }

        .btn-download {
            background: linear-gradient(135deg, #58a6ff 0%, #1f6feb 100%);
            color: #ffffff;
            border: none;
            box-shadow: 0 0 20px rgba(88, 166, 255, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-download::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-download:hover {
            box-shadow: 0 0 30px rgba(88, 166, 255, 0.6);
            transform: translateY(-2px);
        }

        .btn-download:hover::before {
            left: 100%;
        }

        .btn-download:active {
            transform: translateY(0);
        }

        /* Icon styles */
        .icon {
            width: 16px;
            height: 16px;
            display: inline-block;
        }

        /* Loading state */
        .btn.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .btn.loading::after {
            content: '';
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }

            .id-card {
                padding: 30px 20px;
                max-width: 100%;
            }

            .brand-title {
                font-size: 28px;
            }

            .staff-name {
                font-size: 16px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Digital ID Card</h1>

        <div class="id-card" id="idCard">
            <div class="card-content">
                <div class="header-label">Staff Kitchen</div>
                <div class="brand-title">SIAP SPPG</div>
                <div class="subtitle">Tlahab Lor</div>
                <div class="divider"></div>

                <div class="photo-wrapper">
                    <div class="photo-circle">
                        @if($relawan->foto && file_exists(public_path('storage/' . $relawan->foto)))
                            <img src="{{ asset('storage/' . $relawan->foto) }}" alt="{{ $relawan->nama }}">
                        @else
                            <div class="photo-placeholder">
                                <span>FOTO</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="qr-wrapper" id="qrCode">
                    <img src="{{ $qrCode ?: 'https://api.qrserver.com/v1/create-qr-code/?size=128x128&data=' . urlencode($relawan->id_relawan) . '&bgcolor=ffffff&color=000000' }}" 
                         alt="QR Code" 
                         loading="lazy"
                         crossorigin="anonymous">
                </div>

                <div class="staff-name">{{ strtoupper($relawan->nama) }}</div>
                <div class="staff-id">{{ $relawan->id_relawan }}</div>
                
                <div class="staff-badge">{{ $relawan->role }}</div>
                <div class="department-badge">{{ $relawan->bagian }}</div>
            </div>
        </div>

        <div class="button-group">
            <button class="btn btn-cancel" onclick="window.history.back();">
                <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6.4 18.3L17.7 7M17.7 18.3L6.4 7"/>
                </svg>
                BATAL
            </button>
            <button class="btn btn-download" id="downloadBtn" onclick="downloadAsImage();">
                <svg class="icon" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                </svg>
                UNDUH PNG
            </button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        async function downloadAsImage() {
            const btn = document.getElementById('downloadBtn');
            const card = document.getElementById('idCard');
            
            try {
                btn.classList.add('loading');
                btn.disabled = true;

                // Dapatkan dimensi kartu
                const rect = card.getBoundingClientRect();
                const scale = 2;

                const canvas = await html2canvas(card, {
                    backgroundColor: null,
                    scale: scale,
                    allowTaint: true,
                    useCORS: true,
                    logging: false,
                    imageTimeout: 0,
                    width: rect.width,
                    height: rect.height,
                    windowHeight: rect.height,
                    windowWidth: rect.width
                });

                const link = document.createElement('a');
                link.href = canvas.toDataURL('image/png');
                link.download = `ID_Card_{{ strtoupper($relawan->id_relawan) }}_${new Date().getTime()}.png`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            } catch (error) {
                console.error('Error:', error);
                alert('Gagal mengunduh kartu. Silakan coba lagi.');
            } finally {
                btn.classList.remove('loading');
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>
