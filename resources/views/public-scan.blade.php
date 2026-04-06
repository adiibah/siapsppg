<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instant Scan - SIAP SPPG</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-6 rounded-2xl shadow-xl w-full max-w-md text-center">
        <h1 class="text-2xl font-bold text-indigo-600 mb-2">SIAP SPPG</h1>
        <p class="text-gray-500 mb-6 text-sm">Arahkan kamera ke QR Code Anda</p>
        
        <div id="reader" class="rounded-xl overflow-hidden border-4 border-indigo-100"></div>
        
        <div class="mt-6 text-xs text-gray-400">
            &copy; 2026 Presensi Relawan
        </div>
    </div>

    <script>
        function onScanSuccess(decodedText) {
            html5QrcodeScanner.clear(); // Berhenti scan sementara
            
            fetch('/scan/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id_relawan: decodedText })
            })
            .then(res => res.json())
            .then(data => {
                Swal.fire({
                    icon: data.status,
                    title: data.message,
                    text: data.nama ? 'Relawan: ' + data.nama : '',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => location.reload());
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>