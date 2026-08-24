# eSKM — Website Survei Kepuasan Masyarakat (CodeIgniter 3)

Aplikasi web survei kepuasan masyarakat, dibangun dengan **CodeIgniter 3**, terinspirasi dari
[skm.bandung.go.id](https://skm.bandung.go.id) dengan 9 pertanyaan unsur pelayanan yang diambil dari
kuesioner Google Form **Dinas Kesehatan Kota Bandung**, mengacu pada **Permenpan RB No. 14 Tahun 2017**.

## Fitur

- Form survei publik (biodata responden + 9 unsur pelayanan) di `/survei`
- Halaman terima kasih dengan kode bukti pengisian
- Login admin (`/admin/login`)
- Dashboard admin: Nilai IKM otomatis, mutu pelayanan (A/B/C/D), grafik per unsur & demografi
- Data responden: daftar (dengan filter bulan/tahun + pagination), detail jawaban, hapus data
- Export rekap ke **Excel** (`.xls`) dan **laporan PDF siap cetak** (`Print → Save as PDF`)

## Struktur Folder

```
skm-ci3/
├── application/        <- kode aplikasi (controllers, models, views, config)
├── system/              <- core framework CodeIgniter 3.1.13 (jangan diubah)
├── assets/              <- (opsional) logo, gambar tambahan
├── database.sql         <- skema database + data awal
├── index.php
└── .htaccess
```

## Instalasi

### 1. Kebutuhan
- PHP 7.4 – 8.1 (disarankan; CI 3.1.13 juga kompatibel dengan PHP 8.x versi baru)
- MySQL / MariaDB
- Web server (Apache/Nginx) — atau bisa langsung pakai XAMPP/Laragon untuk lokal

### 2. Import Database
Buat database lalu import `database.sql` (database & tabel akan otomatis dibuat oleh file ini):

```bash
mysql -u root -p < database.sql
```

Atau import lewat phpMyAdmin: buat database baru → menu *Import* → pilih `database.sql`.

Database ini otomatis membuat:
- 1 akun admin default → **username: `admin`**, **password: `admin123`** (⚠️ segera ganti setelah login pertama)
- 9 unsur pelayanan beserta pilihan jawabannya (sudah sesuai kuesioner Google Form Dinas Kesehatan)
- 1 baris pengaturan instansi (nama, alamat, dsb — bisa diedit langsung di tabel `tb_pengaturan`)

### 3. Konfigurasi Database
Edit `application/config/database.php`, sesuaikan bagian ini dengan kredensial MySQL Anda:

```php
$db['default'] = array(
    ...
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'db_skm_dinkes',
    ...
);
```

### 4. Konfigurasi Base URL
Edit `application/config/config.php`, isi `base_url` sesuai lokasi aplikasi Anda, contoh:

```php
$config['base_url'] = 'http://localhost/skm-ci3/';
```

Jika di-deploy ke domain resmi, contoh:

```php
$config['base_url'] = 'https://skm.namadomainanda.go.id/';
```

### 5. Jalankan
- **XAMPP/Laragon**: letakkan folder `skm-ci3` di `htdocs`/`www`, aktifkan module `mod_rewrite`, lalu akses
  `http://localhost/skm-ci3/`
- **PHP built-in server** (untuk uji coba cepat):
  ```bash
  php -S localhost:8000
  ```
  lalu buka `http://localhost:8000/`

### 6. Akses Aplikasi
| Halaman | URL |
|---|---|
| Form survei publik | `/survei` |
| Login admin | `/admin/login` |
| Dashboard admin | `/admin/dashboard` |

## Mengganti Password Admin

Password admin di-hash dengan `password_hash()` (bcrypt). Untuk mengganti lewat SQL, gunakan PHP untuk membuat hash baru:

```bash
php -r "echo password_hash('password_baru_anda', PASSWORD_BCRYPT);"
```

Lalu update kolom `password` pada tabel `tb_admin` dengan hasil hash tersebut.

## Menyesuaikan Pertanyaan / Unsur Pelayanan

Semua pertanyaan & pilihan jawaban disimpan di database (tabel `tb_unsur` dan `tb_unsur_pilihan`), bukan hardcode
di kode — jadi bisa diedit langsung lewat phpMyAdmin tanpa mengubah kode aplikasi, misalnya jika instansi Anda
ingin menambah/mengurangi unsur pelayanan.

## Menyesuaikan Identitas Instansi

Edit tabel `tb_pengaturan` (nama instansi, alamat, kepala instansi, deskripsi survei, dll). Data ini otomatis
tampil di header/footer halaman survei, dashboard admin, serta laporan Excel/PDF.

## Perhitungan Nilai IKM

Mengacu pada **Permenpan RB No. 14 Tahun 2017**:

- Bobot nilai rata-rata tertimbang = 1 ÷ jumlah unsur = 1 ÷ 9 = **0,111**
- NRR per unsur = total nilai jawaban unsur ÷ jumlah responden
- NRR tertimbang = NRR × bobot
- **Nilai IKM = (Total NRR tertimbang seluruh unsur) × 25**

Kategori mutu pelayanan:

| Nilai IKM | Mutu | Kinerja |
|---|---|---|
| 88,31 – 100,00 | A | Sangat Baik |
| 76,61 – 88,30 | B | Baik |
| 65,00 – 76,60 | C | Kurang Baik |
| 25,00 – 64,99 | D | Tidak Baik |

## Export Laporan

- **Excel**: `admin/export/hasil` — menghasilkan file `.xls` (tabel HTML yang dikenali Microsoft Excel) berisi
  tabel "Hasil IKM Berdasarkan Responden": jawaban tiap responden per unsur beserta baris agregat
  (Jumlah Nilai Unsur, NRR, NRR Tertimbang, Nilai Index, IKM, Mutu, Kinerja). Tombolnya ada di halaman
  `admin/hasil` dan mengikuti filter bulan/tahun yang sedang aktif. Tidak memerlukan library tambahan (composer).
- **PDF**: `admin/export/pdf` — laporan ditampilkan dulu sebagai halaman web; gunakan tombol **"Cetak / Simpan PDF"**
  (memakai fitur *Print to PDF* bawaan browser). Jika ingin PDF native tanpa harus lewat dialog cetak browser,
  Anda bisa memasang library seperti **Dompdf** atau **TCPDF** lewat Composer dan mengganti isi method
  `pdf()` pada `application/controllers/admin/Export.php`.

## Catatan Keamanan

- Segera ganti password admin default setelah instalasi.
- Jangan lupa ganti `encryption_key` di `application/config/config.php` dengan string acak Anda sendiri sebelum
  digunakan di server produksi.
- Set `ENVIRONMENT` di `index.php` menjadi `'production'` saat sudah live, agar pesan error PHP tidak tampil ke publik.
# skm_dinkes
