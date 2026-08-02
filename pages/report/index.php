<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/report_data.php';
require_roles(['admin']);

$reports = report_options();
$selectedType = $_GET['type'] ?? '';
$selectedReport = $selectedType !== '' ? get_report_data($pdo, $selectedType) : null;

include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <div class="toolbar">
        <h2>Report PDF</h2>
    </div>
    <p>Pilih laporan yang ingin diexport. Setiap PDF otomatis mencantumkan tanggal dan jam ketika report digenerate.</p>
    <div class="report-grid">
        <?php foreach ($reports as $report): ?>
        <div class="report-item <?= $selectedType === $report['type'] ? 'report-item-active' : '' ?>">
            <h3><?= e($report['title']) ?></h3>
            <p><?= e($report['description']) ?></p>
            <a class="btn btn-primary" href="index.php?type=<?= e($report['type']) ?>">Lihat Report</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if ($selectedType !== '' && !$selectedReport): ?>
<div class="alert alert-danger">Report tidak ditemukan.</div>
<?php endif; ?>

<?php if ($selectedReport): ?>
<div class="card report-preview">
    <div class="toolbar">
        <div>
            <h2><?= e($selectedReport['title']) ?></h2>
            <p>Preview data sebelum file PDF dibuat.</p>
        </div>
        <div>
            <a class="btn btn-print" href="export.php?type=<?= e($selectedType) ?>">Export PDF</a>
        </div>
    </div>

    <div class="report-meta">
        <img src="<?= e(asset_url('logo.png')) ?>" alt="Logo MTs Nurul Falah Areman" class="report-meta-logo">
        <strong>MTs Nurul Falah Areman</strong>
        <span>Digenerate: <?= e(date('d-m-Y H:i')) ?> WIB</span>
        <span>Total data: <?= count($selectedReport['rows']) ?></span>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <?php foreach ($selectedReport['columns'] as $column): ?>
                    <th><?= e($column['label']) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selectedReport['rows'] as $row): ?>
                <tr>
                    <?php foreach ($selectedReport['columns'] as $column): ?>
                    <td><?= e($row[$column['key']] ?? '') ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <?php if (!$selectedReport['rows']): ?>
                <tr>
                    <td colspan="<?= count($selectedReport['columns']) ?>" class="text-center">
                        <?= e($selectedReport['empty_message']) ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
