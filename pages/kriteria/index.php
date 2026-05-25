<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$rows = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar"><h2>Data Kriteria</h2><div><a class="btn btn-primary" href="tambah.php">Tambah Kriteria</a><a class="btn btn-print" href="cetak.php" target="_blank">Cetak</a></div></div>
    <div class="table-responsive"><table id="data-table">
        <thead><tr><th>No</th><th>Kode</th><th>Nama Kriteria</th><th>Bobot</th><th>Tipe</th><th>Aksi</th></tr></thead>
        <tbody><?php foreach ($rows as $i => $row): ?><tr><td><?= $i + 1 ?></td><td><?= e($row['kode_kriteria']) ?></td><td><?= e($row['nama_kriteria']) ?></td><td><?= e($row['bobot']) ?></td><td><?= e($row['tipe']) ?></td><td><a class="btn btn-warning" href="edit.php?id=<?= e($row['id_kriteria']) ?>">Edit</a><a class="btn btn-danger" href="hapus.php?id=<?= e($row['id_kriteria']) ?>" data-confirm="Hapus kriteria ini?">Hapus</a></td></tr><?php endforeach; ?></tbody>
    </table></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
