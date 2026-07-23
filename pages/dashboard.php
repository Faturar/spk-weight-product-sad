<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

// Data ringkasan untuk kartu statistik di dashboard.
$jumlahSiswa = (int) $pdo->query('SELECT COUNT(*) FROM siswa')->fetchColumn();
$jumlahKriteria = (int) $pdo->query('SELECT COUNT(*) FROM kriteria')->fetchColumn();
$jumlahAspek = (int) $pdo->query('SELECT COUNT(*) FROM aspek')->fetchColumn();
$jumlahPenilaian = (int) $pdo->query('SELECT COUNT(DISTINCT id_siswa) FROM penilaian')->fetchColumn();

// Mengambil lima ranking terbaik dari hasil perhitungan WP untuk ditampilkan cepat.
$ranking = $pdo->query('SELECT h.*, s.kode_siswa, s.nama_siswa FROM hasil_wp h JOIN siswa s ON s.id_siswa = h.id_siswa ORDER BY h.ranking ASC LIMIT 5')->fetchAll();
include __DIR__ . '/../includes/header.php';
?>
<div class="grid">
    <div class="stat">Jumlah Siswa <b><?= e($jumlahSiswa) ?></b></div>
    <div class="stat">Jumlah Kriteria <b><?= e($jumlahKriteria) ?></b></div>
    <div class="stat">Jumlah Aspek <b><?= e($jumlahAspek) ?></b></div>
    <div class="stat">Data Penilaian <b><?= e($jumlahPenilaian) ?></b></div>
</div>

<div class="card">
    <h2>Ranking 5 Besar</h2>
    <div class="table-responsive">
        <table>
            <thead><tr><th>Ranking</th><th>Kode</th><th>Nama Siswa</th><th>Nilai V</th></tr></thead>
            <tbody>
            <?php if (!$ranking): ?>
                <tr><td colspan="4" class="text-center">Belum ada hasil perhitungan.</td></tr>
            <?php endif; ?>
            <?php foreach ($ranking as $row): ?>
                <tr>
                    <td><span class="badge"><?= e($row['ranking']) ?></span></td>
                    <td><?= e($row['kode_siswa']) ?></td>
                    <td><?= e($row['nama_siswa']) ?></td>
                    <td><?= e(number_format((float) $row['nilai_v'], 4)) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
