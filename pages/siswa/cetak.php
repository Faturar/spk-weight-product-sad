<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin', 'pembina']);
$rows = $pdo->query('SELECT * FROM siswa ORDER BY kode_siswa ASC')->fetchAll();
report_header('Laporan Data Siswa');
?>
<table>
    <thead><tr><th>No</th><th>Kode</th><th>Nama</th><th>NIS</th><th>No HP</th><th>Jenis Kelamin</th></tr></thead>
    <tbody><?php foreach ($rows as $i => $row): ?><tr><td><?= $i + 1 ?></td><td><?= e($row['kode_siswa']) ?></td><td><?= e($row['nama_siswa']) ?></td><td><?= e($row['nis']) ?></td><td><?= e($row['no_handphone']) ?></td><td><?= e($row['jenis_kelamin']) ?></td></tr><?php endforeach; ?></tbody>
</table>
<?php report_footer(); ?>
