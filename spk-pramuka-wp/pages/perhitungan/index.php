<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
$rows = $pdo->query('SELECT h.*, s.kode_siswa, s.nama_siswa FROM hasil_wp h JOIN siswa s ON s.id_siswa=h.id_siswa ORDER BY h.ranking ASC')->fetchAll();
$nilai = [];
if ($rows) {
    $ids = array_column($rows, 'id_siswa');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM penilaian WHERE id_siswa IN ($placeholders)");
    $stmt->execute($ids);
    foreach ($stmt as $n) { $nilai[$n['id_siswa']][$n['id_kriteria']] = $n['nilai']; }
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar"><h2>Perhitungan Weighted Product</h2><div><a class="btn btn-primary" href="proses.php">Proses Perhitungan</a><a class="btn btn-danger" href="reset.php" data-confirm="Reset seluruh hasil WP?">Reset Hasil</a><a class="btn btn-print" href="cetak.php" target="_blank">Cetak Hasil WP</a></div></div>
    <div class="table-responsive"><table>
        <thead><tr><th>Ranking</th><th>Kode Siswa</th><th>Nama Siswa</th><?php foreach ($kriteria as $k): ?><th><?= e($k['kode_kriteria']) ?></th><?php endforeach; ?><th>Nilai S</th><th>Nilai V</th></tr></thead>
        <tbody>
        <?php if (!$rows): ?><tr><td colspan="<?= count($kriteria) + 5 ?>" class="text-center">Belum ada hasil. Klik Proses Perhitungan.</td></tr><?php endif; ?>
        <?php foreach ($rows as $row): ?><tr>
            <td><span class="badge"><?= e($row['ranking']) ?></span></td><td><?= e($row['kode_siswa']) ?></td><td><?= e($row['nama_siswa']) ?></td>
            <?php foreach ($kriteria as $k): ?><td><?= e($nilai[$row['id_siswa']][$k['id_kriteria']] ?? '-') ?></td><?php endforeach; ?>
            <td><?= e(number_format((float) $row['nilai_s'], 4)) ?></td><td><?= e(number_format((float) $row['nilai_v'], 4)) ?></td>
        </tr><?php endforeach; ?>
        </tbody>
    </table></div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
