<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/forms.php';
require_roles(['admin']);
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM aspek WHERE id_aspek=?');
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) { flash('danger', 'Data aspek tidak ditemukan.'); redirect('pages/aspek/index.php'); }
$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = require_fields($_POST, ['id_kriteria' => 'Kriteria', 'nama_aspek' => 'Nama aspek', 'nilai' => 'Nilai']);
    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE aspek SET id_kriteria=?, nama_aspek=?, nilai=?, keterangan=? WHERE id_aspek=?');
        $stmt->execute([(int) $_POST['id_kriteria'], trim($_POST['nama_aspek']), (int) $_POST['nilai'], trim($_POST['keterangan'] ?? ''), $id]);
        flash('success', 'Data aspek berhasil diperbarui.');
        redirect('pages/aspek/index.php');
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="card"><h2>Edit Aspek</h2><?php print_errors($errors); ?><form method="post">
    <div class="form-grid"><div class="form-group"><label>Kriteria</label><select name="id_kriteria" required><?php foreach ($kriteria as $k): ?><option value="<?= e($k['id_kriteria']) ?>" <?= (int) $data['id_kriteria'] === (int) $k['id_kriteria'] ? 'selected' : '' ?>><?= e($k['kode_kriteria'] . ' - ' . $k['nama_kriteria']) ?></option><?php endforeach; ?></select></div><div class="form-group"><label>Nama Aspek</label><input name="nama_aspek" required value="<?= e($_POST['nama_aspek'] ?? $data['nama_aspek']) ?>"></div><div class="form-group"><label>Nilai</label><input type="number" min="1" max="5" name="nilai" required value="<?= e($_POST['nilai'] ?? $data['nilai']) ?>"></div><div class="form-group"><label>Keterangan</label><textarea name="keterangan"><?= e($_POST['keterangan'] ?? $data['keterangan']) ?></textarea></div></div>
    <button class="btn btn-primary">Simpan</button><a class="btn" href="index.php">Kembali</a>
</form></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
