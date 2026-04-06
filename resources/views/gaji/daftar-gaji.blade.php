<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Gaji Relawan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 25px 30px;
            border-bottom: 2px solid #e9ecef;
        }

        .filter-group {
            display: grid;
            grid-template-columns: 150px 150px auto auto;
            gap: 15px;
            align-items: center;
        }

        .filter-group label {
            font-weight: 600;
            color: #333;
        }

        .filter-group select {
            padding: 10px 15px;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .filter-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .filter-group button {
            padding: 10px 25px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .filter-group button:hover {
            background: #764ba2;
        }

        .table-section {
            padding: 30px;
        }

        .table-title {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: 600;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table thead {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border: 1px solid #dee2e6;
        }

        table td {
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            color: #666;
        }

        table tbody tr:hover {
            background: #f8f9fa;
        }

        table tbody tr:nth-child(even) {
            background: #ffffff;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .btn-slip {
            padding: 8px 15px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-slip:hover {
            background: #c82333;
        }

        .total-section {
            background: #ecf0f1;
            padding: 20px 30px;
            border-top: 2px solid #bdc3c7;
            text-align: right;
            font-weight: 600;
            font-size: 16px;
        }

        .total-section .label {
            color: #333;
            margin-right: 20px;
        }

        .total-section .value {
            color: #27ae60;
            font-size: 18px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
        }

        .btn-cetak {
            padding: 12px 30px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-cetak:hover {
            background: #218838;
        }

        @media (max-width: 768px) {
            .filter-group {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 20px;
            }

            table {
                font-size: 12px;
            }

            table th,
            table td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Daftar Gaji Relawan</h1>
            <p>SIAP SPPG - Manajemen Penggajian</p>
        </div>

        <div class="filter-section">
            <form method="GET" action="{{ route('gaji.daftar-gaji') }}">
                <div class="filter-group">
                    <div>
                        <label>Bulan</label>
                        <select name="bulan">
                            <option value="">- Pilih -</option>
                            @foreach ($bulanArray as $num => $nama)
                                <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Tahun</label>
                        <select name="tahun">
                            <option value="">- Pilih -</option>
                            @for ($year = date('Y'); $year >= 2020; $year--)
                                <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <button type="submit">📥 Tampilkan Data</button>
                    </div>
                    <div>
                        <button type="button" class="btn-cetak" onclick="window.print()">🖨️ Cetak Daftar Gaji Relawan</button>
                    </div>
                </div>
            </form>
        </div>

        @if ($gajis->count() > 0)
            <div class="table-section">
                <div class="table-title">
                    Daftar Gaji Relawan - Bulan : {{ $bulanArray[$bulan] ?? 'N/A' }} , Tahun : {{ $tahun }}
                </div>

                <table>
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>ID</th>
                            <th>Nama Relawan</th>
                            <th>Bagian</th>
                            <th>Kehadiran</th>
                            <th>No. Rekening</th>
                            <th class="text-right">Nominal Gaji</th>
                            <th class="text-center">Slip Gaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gajis as $key => $gaji)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $gaji->relawan->id_relawan }}</td>
                                <td>{{ $gaji->relawan->nama }}</td>
                                <td>
                                    <span style="background: #e7f3ff; color: #0056b3; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                                        {{ $gaji->relawan->bagian }}
                                    </span>
                                </td>
                                <td>{{ $gaji->nomor_rekening ?? '-' }}</td>
                                <td class="text-right">
                                    <strong>Rp{{ number_format($gaji->gaji_bersih, 2, ',', '.') }}</strong>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('gaji.slip-gaji', [$gaji->relawan->id_relawan, $bulan, $tahun]) }}"
                                       class="btn-slip" target="_blank">Cetak</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-section">
                    <span class="label">TOTAL GAJI :</span>
                    <span class="value">Rp{{ number_format($totalGaji, 2, ',', '.') }}</span>
                </div>
            </div>
        @else
            <div class="table-section">
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p style="font-size: 16px;">📭 Tidak ada data gaji untuk bulan dan tahun yang dipilih.</p>
                </div>
            </div>
        @endif

        <div class="footer">
            <button class="btn-cetak" onclick="window.print()">🖨️ Cetak Daftar Gaji Relawan</button>
        </div>
    </div>

    <script>
        @media print {
            .filter-section, .footer {
                display: none;
            }
        }
    </script>
</body>
</html>
