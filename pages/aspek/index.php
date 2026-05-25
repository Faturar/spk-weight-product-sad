<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$idKriteria = (int) ($_GET['id_kriteria'] ?? 0);
$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
$sql = 'SELECT a.*, k.kode_kriteria, k.nama_kriteria FROM aspek a JOIN kriteria k ON k.id_kriteria=a.id_kriteria';
$params = [];
if ($idKriteria) { $sql .= ' WHERE a.id_kriteria=?'; $params[] = $idKriteria; }
$sql .= ' ORDER BY k.kode_kriteria ASC, a.id_aspek ASC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar"><h2>Data Aspek/Subkriteria</h2><div><a class="btn btn-primary" href="tambah.php">Tambah Aspek</a><a class="btn btn-print" href="cetak.php<?= $idKriteria ? '?id_kriteria=' . e($idKriteria) : '' ?>" target="_blank">Cetak</a></div></div>
    <form method="get" class="toolbar"><select name="id_kriteria"><option value="0">Semua Kriteria</option><?php foreach ($kriteria as $k): ?><option value="<?= e($k['id_kriteria']) ?>" <?= $idKriteria === (int) $k['id_kriteria'] ? 'selected' : '' ?>><?= e($k['kode_kriteria'] . ' - ' . $k['nama_kriteria']) ?></option><?php endforeach; ?></select><button class="btn btn-primary">Tampilkan</button></form>
    <div class="table-responsive"><table><thead><tr><th>No</th><th>Kriteria</th><th>Nama Aspek</th><th>Nilai</th><th>Keterangan</th><th>Aksi</th></tr></thead><tbody><?php foreach ($rows as $i => $row): ?><tr><td><?= $i + 1 ?></td><td><?= e($row['kode_kriteria'] . ' - ' . $row['nama_kriteria']) ?></td><td><?= e($row['nama_aspek']) ?></td><td><?= e($row['nilai']) ?></td><td><?= e($row['keterangan']) ?></td><td><a class="btn btn-warning" href="edit.php?id=<?= e($row['id_aspek']) ?>">Edit</a><a class="btn btn-danger" href="hapus.php?id=<?= e($row['id_aspek']) ?>" data-confirm="Hapus aspek ini?">Hapus</a></td></tr><?php endforeach; ?></tbody></table></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
