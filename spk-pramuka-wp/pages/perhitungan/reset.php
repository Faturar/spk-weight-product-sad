<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$pdo->exec('DELETE FROM hasil_wp');
flash('success', 'Hasil perhitungan berhasil direset.');
redirect('pages/perhitungan/index.php');
