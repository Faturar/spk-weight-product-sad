<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$keyword = trim($_GET['q'] ?? '');
$stmt = $pdo->prepare('SELECT * FROM siswa WHERE nama_siswa LIKE ? OR kode_siswa LIKE ? OR nis LIKE ? OR kelas LIKE ? ORDER BY kode_siswa ASC');
$search = '%' . $keyword . '%';
$stmt->execute([$search, $search, $search, $search]);
$siswa = $stmt->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar">
        <h2>Data Siswa</h2>
        <div>
            <?php if (can_manage_master()): ?><a class="btn btn-primary" href="tambah.php">Tambah Siswa</a><?php endif; ?>
            <a class="btn btn-print" href="cetak.php" target="_blank">Cetak</a>
        </div>
    </div>
    <form class="toolbar" method="get">
        <input type="search" name="q" placeholder="Cari siswa..." value="<?= e($keyword) ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>
    <div class="table-responsive">
        <table>
            <thead><tr><th>No</th><th>Kode</th><th>Nama</th><th>NIS</th><th>No HP</th><th>Jenis Kelamin</th><th>Kelas</th><?php if (can_manage_master()): ?><th>Aksi</th><?php endif; ?></tr></thead>
            <tbody>
            <?php foreach ($siswa as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td><td><?= e($row['kode_siswa']) ?></td><td><?= e($row['nama_siswa']) ?></td><td><?= e($row['nis']) ?></td><td><?= e($row['no_handphone']) ?></td><td><?= e($row['jenis_kelamin']) ?></td><td><?= e($row['kelas']) ?></td>
                    <?php if (can_manage_master()): ?><td><a class="btn btn-warning" href="edit.php?id=<?= e($row['id_siswa']) ?>">Edit</a><a class="btn btn-danger" href="hapus.php?id=<?= e($row['id_siswa']) ?>" data-confirm="Hapus data siswa ini?">Hapus</a></td><?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if (!$siswa): ?><tr><td colspan="8" class="text-center">Data tidak ditemukan.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
