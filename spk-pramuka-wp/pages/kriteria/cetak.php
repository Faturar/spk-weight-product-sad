<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$rows = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
report_header('Laporan Data Kriteria');
?>
<table><thead><tr><th>No</th><th>Kode</th><th>Nama Kriteria</th><th>Bobot</th><th>Tipe</th></tr></thead><tbody><?php foreach ($rows as $i => $row): ?><tr><td><?= $i + 1 ?></td><td><?= e($row['kode_kriteria']) ?></td><td><?= e($row['nama_kriteria']) ?></td><td><?= e($row['bobot']) ?></td><td><?= e($row['tipe']) ?></td></tr><?php endforeach; ?></tbody></table>
<?php report_footer(); ?>
