<?php
// Konfigurasi koneksi database MySQL yang dipakai oleh seluruh halaman aplikasi.
$host = 'localhost';
$dbname = 'spk_pramuka_wp';
$username = 'root';
$password = '';

try {
    // PDO digunakan agar query database lebih aman dan mudah memakai prepared statement.
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Jika koneksi gagal, aplikasi dihentikan dan pesan error diamankan dengan htmlspecialchars.
    die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
