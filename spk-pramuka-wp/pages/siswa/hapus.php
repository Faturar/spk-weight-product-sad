<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);
$stmt = $pdo->prepare('DELETE FROM siswa WHERE id_siswa = ?');
$stmt->execute([(int) ($_GET['id'] ?? 0)]);
flash('success', 'Data siswa berhasil dihapus.');
redirect('pages/siswa/index.php');
