# TODO - UI/UX Presensi

## Step 1

- [x] Analisis file halaman presensi (`resources/views/page_absen.blade.php` dan `public-scan.blade.php`).

## Step 2

- [x] Perbarui `resources/views/page_absen.blade.php`:
    - [x] Tambahkan overlay guide (kotak target) di atas area scanner
    - [x] Perjelas status UI (loading/success/warning/error) yang konsisten
    - [x] Pause/lock scanning setelah scan terdeteksi untuk cegah double submit
    - [x] Perbaiki teks panduan agar lebih operasional

## Step 3 (opsional)

- [ ] Samakan UI/UX `resources/views/public-scan.blade.php` dengan desain yang baru

## Step 4

- [ ] Testing manual: scan valid/invalid + verifikasi tidak double scan
