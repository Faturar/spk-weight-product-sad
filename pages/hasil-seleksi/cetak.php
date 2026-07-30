<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$rows = $pdo->query('SELECT h.*, s.kode_siswa, s.nama_siswa FROM hasil_wp h JOIN siswa s ON s.id_siswa=h.id_siswa ORDER BY h.ranking ASC')->fetchAll();
report_header('Laporan Hasil Seleksi');
?>
<?php if ($rows): ?><p><strong>Terbaik:</strong> <?= e($rows[0]['nama_siswa']) ?> sebagai anggota Pramuka Inti berprestasi terbaik.</p><?php endif; ?>
<table><thead><tr><th>Ranking</th><th>Kode Siswa</th><th>Nama Siswa</th><th>Total Nilai / V</th><th>Tahun Ajaran</th><th>Tanggal Pengumuman</th></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><td><?= e($row['ranking']) ?></td><td><?= e($row['kode_siswa']) ?></td><td><?= e($row['nama_siswa']) ?></td><td><?= e(number_format((float) $row['nilai_v'], 4)) ?></td><td><?= e($row['tahun_ajaran']) ?></td><td><?= e(date('d-m-Y', strtotime($row['tanggal_pengumuman']))) ?></td></tr><?php endforeach; ?></tbody></table>
<?php report_footer(); ?>
