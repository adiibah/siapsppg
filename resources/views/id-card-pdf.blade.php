<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        @page{margin:0;size:54mm 87mm}
        html,body{height:100%}
        body{
            background:#0d1117;
            font-family: 'Montserrat', Arial, sans-serif;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            display:flex;align-items:center;justify-content:center;
            padding:0; /* remove extra padding so PDF page fits exactly */
        }

        .card-wrap{width:54mm; height:87mm; margin:0 auto}

        .card{
            background:#161b22;
            border:1px solid #2b3036;
            border-radius:18px;
            padding:4mm 4mm 4mm 4mm; /* smaller padding to fit */
            box-shadow:0 6px 18px rgba(0,0,0,0.6);
            color:#fff;
            width:100%;
            height:87mm; /* force card to exactly one page */
            overflow:hidden;
            position:relative;
        }

        /* Header */
        .header-label{
            font-size:8px;
            color:#26d0ff; /* cyan */
            letter-spacing:3px;
            text-transform:uppercase;
            text-align:center;
            margin-bottom:3px;
            font-weight:700;
        }

        .brand{
            text-align:center;
            font-size:22px;
            font-weight:800;
            font-style:italic;
            color:#ffffff;
            margin-bottom:4px;
            line-height:1;
        }

        .logo-small{width:36px;margin:0 auto 6px auto}
        .logo-small img{width:100%;height:auto;display:block}

        .photo{
            width:30mm;height:30mm;margin:4px auto 6px auto;border-radius:50%;
            border:3px solid #26d0ff;overflow:hidden;display:block;background:#111;
            box-shadow:0 6px 14px rgba(38,208,255,0.12), inset 0 0 8px rgba(38,208,255,0.06);
        }
        .photo img{width:100%;height:100%;object-fit:cover;display:block}

        .name{font-size:11pt;font-weight:800;text-align:center;text-transform:uppercase;color:#fff;margin-top:6px}
        .staff-id{font-family:monospace;color:#26d0ff;font-weight:700;text-align:center;margin-top:3px;font-size:9px}

        .badge{display:inline-block;margin:6px auto 0 auto;padding:5px 10px;background:#0f1417;border-radius:999px;border:1px solid rgba(255,255,255,0.03);color:#cfefff;font-size:8px;text-align:center}

        /* QR box */
        .qr-box{background:#fff;padding:6px;border-radius:6px;width:36mm;margin:6px auto 4px auto;display:block;text-align:center}
        .qr-box img{width:40px;height:40px}

        .section-center{text-align:center}

        .footer-bar{height:3px;background:linear-gradient(90deg,#1e3c72,#2a5298);border-radius:2px;margin-top:6px;position:absolute;left:4mm;right:4mm;bottom:4mm}

        /* DomPDF safe styles: avoid complex flex in PDF rendering */
    </style>
</head>
<body>
    <div class="card-wrap">
        <div class="card">
            <div class="section-center">
                <div class="header-label">STAFF KITCHEN</div>
                <div class="logo-small">
                    @if(file_exists(public_path('images/logo.png')))
                        <img src="{{ public_path('images/logo.png') }}" alt="logo">
                    @elseif(file_exists(public_path('images/logo.svg')))
                        <img src="{{ public_path('images/logo.svg') }}" alt="logo">
                    @endif
                </div>
                <div class="brand">SIAP SPPG</div>
            </div>

            <div class="section-center">
                <div class="photo">
                    @if(!empty($relawan->foto) && file_exists(public_path('storage/' . $relawan->foto)))
                        <img src="{{ public_path('storage/' . $relawan->foto) }}" alt="{{ $relawan->nama }}">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;color:#26d0ff;font-weight:700;font-size:10px;">FOTO</div>
                    @endif
                </div>
            </div>

            <div class="section-center">
                <div class="name">{{ strtoupper($relawan->nama) }}</div>
                <div class="staff-id">{{ $relawan->id_relawan }}</div>
                <div class="badge">{{ $relawan->role }}</div>
            </div>

            <div class="qr-box" style="page-break-inside:avoid">
                @if(!empty($qrCode))
                    <img src="{{ $qrCode }}" alt="QR Code">
                @else
                    {{-- fallback use Google Charts (DomPDF may not fetch external URLs) --}}
                    <img src="https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl={{ urlencode($relawan->id_relawan) }}&choe=UTF-8" alt="QR">
                @endif
                <div style="margin-top:6px;font-size:9px;color:#2b3036">{{ $relawan->id_relawan }}</div>
            </div>

            <div class="footer-bar"></div>
        </div>
    </div>
</body>
</html>
