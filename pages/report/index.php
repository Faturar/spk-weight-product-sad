<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);

$reports = [
    [
        'type' => 'siswa',
        'title' => 'Report Data Siswa',
        'description' => 'Daftar kode siswa, nama, NIS, nomor HP, dan jenis kelamin.',
    ],
    [
        'type' => 'kriteria',
        'title' => 'Report Data Kriteria',
        'description' => 'Daftar kode kriteria, nama kriteria, bobot, dan tipe benefit/cost.',
    ],
    [
        'type' => 'penilaian',
        'title' => 'Report Penilaian Siswa',
        'description' => 'Rekap nilai setiap siswa pada seluruh kriteria.',
    ],
    [
        'type' => 'hasil-seleksi',
        'title' => 'Report Hasil Seleksi',
        'description' => 'Ranking akhir Weighted Product beserta nilai V dan tanggal pengumuman.',
    ],
];

include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar">
        <h2>Report PDF</h2>
    </div>
    <p>Pilih laporan yang ingin diexport. Setiap PDF otomatis mencantumkan tanggal dan jam ketika report digenerate.</p>
    <div class="report-grid">
        <?php foreach ($reports as $report): ?>
            <div class="report-item">
                <h3><?= e($report['title']) ?></h3>
                <p><?= e($report['description']) ?></p>
                <a class="btn btn-print" href="export.php?type=<?= e($report['type']) ?>">Export PDF</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
