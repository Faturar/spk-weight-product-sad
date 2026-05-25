<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
$rows = $pdo->query('SELECT h.*, s.kode_siswa, s.nama_siswa FROM hasil_wp h JOIN siswa s ON s.id_siswa=h.id_siswa ORDER BY h.ranking ASC')->fetchAll();
$nilai = [];
if ($rows) {
    $ids = array_column($rows, 'id_siswa');
    $stmt = $pdo->prepare('SELECT * FROM penilaian WHERE id_siswa IN (' . implode(',', array_fill(0, count($ids), '?')) . ')');
    $stmt->execute($ids);
    foreach ($stmt as $n) { $nilai[$n['id_siswa']][$n['id_kriteria']] = $n['nilai']; }
}
report_header('Laporan Hasil Weighted Product');
?>
<table><thead><tr><th>Ranking</th><th>Kode</th><th>Nama Siswa</th><?php foreach ($kriteria as $k): ?><th><?= e($k['kode_kriteria']) ?></th><?php endforeach; ?><th>Nilai S</th><th>Nilai V</th></tr></thead><tbody><?php foreach ($rows as $row): ?><tr><td><?= e($row['ranking']) ?></td><td><?= e($row['kode_siswa']) ?></td><td><?= e($row['nama_siswa']) ?></td><?php foreach ($kriteria as $k): ?><td><?= e($nilai[$row['id_siswa']][$k['id_kriteria']] ?? '-') ?></td><?php endforeach; ?><td><?= e(number_format((float) $row['nilai_s'], 4)) ?></td><td><?= e(number_format((float) $row['nilai_v'], 4)) ?></td></tr><?php endforeach; ?></tbody></table>
<?php report_footer(); ?>
