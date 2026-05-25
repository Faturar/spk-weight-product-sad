<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/forms.php';
require_roles(['admin']);
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM kriteria WHERE id_kriteria=?');
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) { flash('danger', 'Data kriteria tidak ditemukan.'); redirect('pages/kriteria/index.php'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = require_fields($_POST, ['kode_kriteria' => 'Kode kriteria', 'nama_kriteria' => 'Nama kriteria', 'bobot' => 'Bobot', 'tipe' => 'Tipe']);
    if (!$errors && (float) $_POST['bobot'] <= 0) $errors[] = 'Bobot harus lebih dari 0.';
    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE kriteria SET kode_kriteria=?, nama_kriteria=?, bobot=?, tipe=? WHERE id_kriteria=?');
        $stmt->execute([trim($_POST['kode_kriteria']), trim($_POST['nama_kriteria']), (float) $_POST['bobot'], $_POST['tipe'], $id]);
        flash('success', 'Data kriteria berhasil diperbarui.');
        redirect('pages/kriteria/index.php');
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="card"><h2>Edit Kriteria</h2><?php print_errors($errors); ?><form method="post">
    <div class="form-grid">
        <div class="form-group"><label>Kode Kriteria</label><input name="kode_kriteria" required value="<?= e($_POST['kode_kriteria'] ?? $data['kode_kriteria']) ?>"></div>
        <div class="form-group"><label>Nama Kriteria</label><input name="nama_kriteria" required value="<?= e($_POST['nama_kriteria'] ?? $data['nama_kriteria']) ?>"></div>
        <div class="form-group"><label>Bobot</label><input type="number" step="0.01" min="0.01" name="bobot" required value="<?= e($_POST['bobot'] ?? $data['bobot']) ?>"></div>
        <div class="form-group"><label>Tipe</label><select name="tipe" required><?php foreach (['benefit','cost'] as $tipe): ?><option value="<?= e($tipe) ?>" <?= $data['tipe'] === $tipe ? 'selected' : '' ?>><?= e($tipe) ?></option><?php endforeach; ?></select></div>
    </div><button class="btn btn-primary" type="submit">Simpan</button><a class="btn" href="index.php">Kembali</a>
</form></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
