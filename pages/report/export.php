<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/pdf_report.php';
require_once __DIR__ . '/../../includes/report_data.php';
require_roles(['admin']);

$type = $_GET['type'] ?? '';
$report = get_report_data($pdo, $type);

if (!$report) {
    http_response_code(404);
    exit('Report tidak ditemukan.');
}

send_pdf_report($report['title'], $report['columns'], $report['rows'], $report['filename']);
