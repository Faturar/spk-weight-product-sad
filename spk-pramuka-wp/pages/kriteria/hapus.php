<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$stmt = $pdo->prepare('DELETE FROM kriteria WHERE id_kriteria = ?');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);
flash('success', 'Data kriteria berhasil dihapus.');
redirect('pages/kriteria/index.php');
