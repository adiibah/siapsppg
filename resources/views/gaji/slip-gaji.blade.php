<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $gaji->relawan->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            padding: 20px;
            color: black;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid black;
            overflow: hidden;
        }

        .header {
            background: white;
            color: black;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid black;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
        }

        .slip-content {
            padding: 30px;
        }

        .section {
            margin-bottom: 30px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 20px;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-weight: 700;
            color: black;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 15px;
            border-left: 4px solid black;
            padding-left: 10px;
        }

        .info-row {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 15px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-label {
            font-weight: 600;
            color: #333;
        }

        .info-value {
            color: black;
            word-break: break-word;
        }

        .gaji-utama {
            margin-top: 30px;
            padding: 20px;
            background: white;
            color: black;
            border: 2px solid black;
            text-align: center;
        }

        .gaji-utama .label {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .gaji-utama .value {
            font-size: 28px;
            font-weight: 700;
        }

        .footer {
            background: white;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ccc;
        }

        .footer-buttons {
            text-align: center;
            padding: 30px;
        }

        .btn {
            padding: 10px 20px;
            margin: 0 5px;
            border: 1px solid black;
            border-radius: 0;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
            background: white;
            color: black;
        }

        .btn-print:hover, .btn-back:hover {
            background: #f0f0f0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .footer-buttons {
                display: none;
            }

            .container {
                border: none;
                margin: 0;
                max-width: 100%;
            }
        }

        @media (max-width: 600px) {
            .info-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .header h1 {
                font-size: 18px;
            }

            .gaji-utama .value {
                font-size: 24px;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .footer-buttons {
                display: none;
            }

            .container {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                max-width: 100%;
            }
        }

        @media (max-width: 600px) {
            .info-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .header h1 {
                font-size: 18px;
            }

            .final-salary .value {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SLIP GAJI</h1>
            <p>PT. SIAP SPPG</p>
        </div>

        <div class="slip-content">
            <!-- Data Karyawan -->
            <div class="section">
                <div class="section-title">DATA KARYAWAN</div>
                <div class="info-row">
                    <div class="info-label">ID Relawan</div>
                    <div class="info-value">{{ $gaji->relawan->id_relawan }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ strtoupper($gaji->relawan->nama) }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jabatan</div>
                    <div class="info-value">{{ $gaji->relawan->role }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Bagian</div>
                    <div class="info-value">{{ $gaji->relawan->bagian }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Kehadiran</div>
                    <div class="info-value">{{ $gaji->kehadiran ?? 0 }} hari</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. Rekening</div>
                    <div class="info-value">{{ $gaji->nomor_rekening ?? 'Belum ditetapkan' }}</div>
                </div>
            </div>

            <!-- Periode Gaji -->
            <div class="section">
                <div class="section-title">PERIODE GAJI</div>
                <div class="info-row">
                    <div class="info-label">Bulan</div>
                    <div class="info-value">
                        @php
                            $bulanArray = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                        @endphp
                        {{ $bulanArray[$gaji->bulan] ?? 'N/A' }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tahun</div>
                    <div class="info-value">{{ $gaji->tahun }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">{{ ucfirst($gaji->status) }}</div>
                </div>
            </div>

            <!-- Gaji Utama -->
            <div class="gaji-utama">
                <div class="label">GAJI YANG DITERIMA</div>
                <div class="value">Rp{{ number_format($gaji->gaji_bersih, 2, ',', '.') }}</div>
            </div>

            <!-- Keterangan -->
            @if ($gaji->keterangan)
                <div class="section">
                    <div class="section-title">KETERANGAN</div>
                    <div class="info-value">{{ $gaji->keterangan }}</div>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>Slip gaji ini bersifat rahasia dan hanya untuk pemegang yang dituju.</p>
            <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
        </div>

        <div class="footer-buttons">
            <button class="btn btn-print" onclick="window.print()">Cetak</button>
            <button class="btn btn-back" onclick="window.history.back()">Kembali</button>
        </div>
    </div>
</body>
</html>
