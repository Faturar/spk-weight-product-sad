<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$stmt = $pdo->prepare('DELETE FROM aspek WHERE id_aspek = ?');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);
flash('success', 'Data aspek berhasil dihapus.');
redirect('pages/aspek/index.php');
