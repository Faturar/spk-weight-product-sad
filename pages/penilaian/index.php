<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin', 'pembina']);
$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
$siswa = $pdo->query('SELECT * FROM siswa ORDER BY kode_siswa ASC')->fetchAll();
$nilaiStmt = $pdo->query('SELECT * FROM penilaian');
$nilai = [];
foreach ($nilaiStmt as $n) { $nilai[$n['id_siswa']][$n['id_kriteria']] = $n['nilai']; }
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar"><h2>Penilaian Siswa</h2></div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>No</th><th>Kode</th><th>Nama Siswa</th><?php foreach ($kriteria as $k): ?><th><?= e($k['kode_kriteria']) ?></th><?php endforeach; ?><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($siswa as $i => $row): ?><tr>
                <td><?= $i + 1 ?></td><td><?= e($row['kode_siswa']) ?></td><td><?= e($row['nama_siswa']) ?></td>
                <?php foreach ($kriteria as $k): ?><td><?= e($nilai[$row['id_siswa']][$k['id_kriteria']] ?? '-') ?></td><?php endforeach; ?>
                <td><a class="btn btn-primary" href="input.php?id_siswa=<?= e($row['id_siswa']) ?>">Input/Edit</a><a class="btn btn-danger" href="hapus.php?id_siswa=<?= e($row['id_siswa']) ?>" data-confirm="Hapus nilai siswa ini?">Hapus</a></td>
            </tr><?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
