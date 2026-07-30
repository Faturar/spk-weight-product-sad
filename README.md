# SPK Pemilihan Anggota Pramuka Inti Berprestasi Metode Weighted Product

Aplikasi Tugas Akhir berbasis PHP native, JavaScript native, CSS biasa, dan MySQL untuk menentukan anggota Pramuka Inti berprestasi di MTs Nurul Falah Areman.

## Cara Import Database

1. Buka XAMPP/Laragon, aktifkan Apache dan MySQL.
2. Buka phpMyAdmin.
3. Import file `database/spk_pramuka_wp.sql`.
4. Database `spk_pramuka_wp` beserta data awal akan dibuat otomatis.

## Konfigurasi Database

Edit file `config/database.php` jika username, password, host, atau nama database berbeda.

Default konfigurasi:

```php
$host = 'localhost';
$dbname = 'spk_pramuka_wp';
$username = 'root';
$password = '';
```

## Cara Menjalankan Aplikasi

1. Salin folder `spk-pramuka-wp` ke dalam folder `htdocs` XAMPP atau `www` Laragon.
2. Pastikan database sudah diimport.
3. Buka browser ke `http://localhost/spk-pramuka-wp/login.php`.
4. Login menggunakan akun admin default.

## Akun Login Default

| Role | Username | Password |
| --- | --- | --- |
| Admin | `admin` | `admin123` |

Password pada database sudah disimpan menggunakan `password_hash()`. Jika ingin membuat hash baru, jalankan `php seed_password.php`.

## Penjelasan Metode Weighted Product

Metode Weighted Product (WP) menghitung peringkat alternatif berdasarkan perkalian nilai kriteria yang dipangkatkan dengan bobot ternormalisasi.

1. Bobot kriteria dinormalisasi dengan rumus `Wj = bobot / total bobot`.
2. Nilai vektor S dihitung dengan rumus `S_i = ∏(X_ij ^ W_j)` untuk kriteria benefit.
3. Jika ada kriteria cost, pangkat bobot dibuat negatif.
4. Nilai vektor V dihitung dengan rumus `V_i = S_i / Total_S`.
5. Ranking ditentukan dari nilai V terbesar ke terkecil.

Pada data default, bobot C1, C2, C3, C4 adalah 5, 4, 3, 4 sehingga total bobot 16 dan normalisasi bobotnya 0.3125, 0.25, 0.1875, 0.25.
