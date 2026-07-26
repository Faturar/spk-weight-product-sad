<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

$role = user_role();
$reports = [
    [
        'type' => 'siswa',
        'title' => 'Report Data Siswa',
        'description' => 'Daftar kode siswa, nama, NIS, nomor HP, dan jenis kelamin.',
        'roles' => ['admin', 'pembina'],
    ],
    [
        'type' => 'kriteria',
        'title' => 'Report Data Kriteria',
        'description' => 'Daftar kode kriteria, nama kriteria, bobot, dan tipe benefit/cost.',
        'roles' => ['admin'],
    ],
    [
        'type' => 'penilaian',
        'title' => 'Report Penilaian Siswa',
        'description' => 'Rekap nilai setiap siswa pada seluruh kriteria.',
        'roles' => ['admin', 'pembina'],
    ],
    [
        'type' => 'hasil-seleksi',
        'title' => 'Report Hasil Seleksi',
        'description' => 'Ranking akhir Weighted Product beserta nilai V dan tanggal pengumuman.',
        'roles' => ['admin', 'pembina', 'kepala_sekolah'],
    ],
];

$visibleReports = array_values(array_filter($reports, fn($report) => in_array($role, $report['roles'], true)));

include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar">
        <h2>Report PDF</h2>
    </div>
    <p>Pilih laporan yang ingin diexport. Setiap PDF otomatis mencantumkan tanggal dan jam ketika report digenerate.</p>
    <div class="report-grid">
        <?php foreach ($visibleReports as $report): ?>
            <div class="report-item">
                <h3><?= e($report['title']) ?></h3>
                <p><?= e($report['description']) ?></p>
                <a class="btn btn-print" href="export.php?type=<?= e($report['type']) ?>">Export PDF</a>
            </div>
        <?php endforeach; ?>
        <?php if (!$visibleReports): ?>
            <p>Belum ada report yang tersedia untuk role Anda.</p>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
