<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin', 'pembina']);
$stmt = $pdo->prepare('DELETE FROM penilaian WHERE id_siswa=?');
$stmt->execute([(int) ($_GET['id_siswa'] ?? 0)]);
flash('success', 'Data penilaian berhasil dihapus.');
redirect('pages/penilaian/index.php');
