<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);

$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
$totalBobot = array_sum(array_map(fn($k) => (float) $k['bobot'], $kriteria));
if (!$kriteria || $totalBobot <= 0) {
    flash('danger', 'Kriteria dan bobot belum lengkap.');
    redirect('pages/perhitungan/index.php');
}

$siswa = $pdo->query('SELECT * FROM siswa ORDER BY kode_siswa ASC')->fetchAll();
$hasil = [];
foreach ($siswa as $row) {
    $stmt = $pdo->prepare('SELECT id_kriteria, nilai FROM penilaian WHERE id_siswa=?');
    $stmt->execute([$row['id_siswa']]);
    $nilai = [];
    foreach ($stmt as $n) { $nilai[$n['id_kriteria']] = (float) $n['nilai']; }
    if (count($nilai) < count($kriteria)) {
        continue;
    }
    $nilaiS = 1.0;
    foreach ($kriteria as $k) {
        $w = (float) $k['bobot'] / $totalBobot;
        $pangkat = $k['tipe'] === 'cost' ? -$w : $w;
        $nilaiS *= pow(max((float) $nilai[$k['id_kriteria']], 0.0001), $pangkat);
    }
    $hasil[] = ['id_siswa' => $row['id_siswa'], 'nilai_s' => $nilaiS];
}

if (!$hasil) {
    flash('danger', 'Belum ada siswa dengan nilai lengkap untuk semua kriteria.');
    redirect('pages/perhitungan/index.php');
}

$totalS = array_sum(array_column($hasil, 'nilai_s'));
foreach ($hasil as &$row) {
    $row['nilai_v'] = $row['nilai_s'] / $totalS;
}
unset($row);
usort($hasil, fn($a, $b) => $b['nilai_v'] <=> $a['nilai_v']);

$tahunAjaran = date('Y') . '/' . (date('Y') + 1);
$tanggal = date('Y-m-d');
$pdo->beginTransaction();
$pdo->exec('DELETE FROM hasil_wp');
$stmt = $pdo->prepare('INSERT INTO hasil_wp (id_siswa, nilai_s, nilai_v, ranking, tahun_ajaran, tanggal_pengumuman) VALUES (?, ?, ?, ?, ?, ?)');
foreach ($hasil as $index => $row) {
    $stmt->execute([$row['id_siswa'], $row['nilai_s'], $row['nilai_v'], $index + 1, $tahunAjaran, $tanggal]);
}
$pdo->commit();
flash('success', 'Perhitungan Weighted Product berhasil diproses.');
redirect('pages/perhitungan/index.php');
