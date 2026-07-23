<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

// Hasil seleksi diambil dari tabel hasil_wp yang sudah diproses di menu perhitungan.
$rows = $pdo->query('SELECT h.*, s.kode_siswa, s.nama_siswa FROM hasil_wp h JOIN siswa s ON s.id_siswa=h.id_siswa ORDER BY h.ranking ASC')->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar"><h2>Hasil Seleksi</h2><a class="btn btn-print" href="cetak.php" target="_blank">Cetak Hasil Seleksi</a></div>
    <?php if ($rows && (int) $rows[0]['ranking'] === 1): ?><div class="alert alert-success">Anggota Pramuka Inti berprestasi terbaik adalah <strong><?= e($rows[0]['nama_siswa']) ?></strong>.</div><?php endif; ?>
    <div class="table-responsive"><table><thead><tr><th>Ranking</th><th>Kode Siswa</th><th>Nama Siswa</th><th>Total Nilai / V</th><th>Tahun Ajaran</th><th>Tanggal Pengumuman</th></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><td><span class="badge"><?= e($row['ranking']) ?></span></td><td><?= e($row['kode_siswa']) ?></td><td><?= e($row['nama_siswa']) ?></td><td><?= e(number_format((float) $row['nilai_v'], 4)) ?></td><td><?= e($row['tahun_ajaran']) ?></td><td><?= e(date('d-m-Y', strtotime($row['tanggal_pengumuman']))) ?></td></tr><?php endforeach; ?><?php if (!$rows): ?><tr><td colspan="6" class="text-center">Belum ada hasil seleksi.</td></tr><?php endif; ?></tbody></table></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
