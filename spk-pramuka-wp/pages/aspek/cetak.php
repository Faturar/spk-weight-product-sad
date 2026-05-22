<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$idKriteria = (int) ($_GET['id_kriteria'] ?? 0);
$sql = 'SELECT a.*, k.kode_kriteria, k.nama_kriteria FROM aspek a JOIN kriteria k ON k.id_kriteria=a.id_kriteria';
$params = [];
if ($idKriteria) { $sql .= ' WHERE a.id_kriteria=?'; $params[] = $idKriteria; }
$sql .= ' ORDER BY k.kode_kriteria ASC, a.id_aspek ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
report_header('Laporan Data Aspek');
?>
<table><thead><tr><th>No</th><th>Kriteria</th><th>Nama Aspek</th><th>Nilai</th><th>Keterangan</th></tr></thead><tbody><?php foreach ($rows as $i => $row): ?><tr><td><?= $i + 1 ?></td><td><?= e($row['kode_kriteria'] . ' - ' . $row['nama_kriteria']) ?></td><td><?= e($row['nama_aspek']) ?></td><td><?= e($row['nilai']) ?></td><td><?= e($row['keterangan']) ?></td></tr><?php endforeach; ?></tbody></table>
<?php report_footer(); ?>
