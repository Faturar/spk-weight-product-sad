<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/forms.php';
require_roles(['admin']);
$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = require_fields($_POST, ['id_kriteria' => 'Kriteria', 'nama_aspek' => 'Nama aspek', 'nilai' => 'Nilai']);
    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO aspek (id_kriteria, nama_aspek, nilai, keterangan) VALUES (?, ?, ?, ?)');
        $stmt->execute([(int) $_POST['id_kriteria'], trim($_POST['nama_aspek']), (int) $_POST['nilai'], trim($_POST['keterangan'] ?? '')]);
        flash('success', 'Data aspek berhasil ditambahkan.');
        redirect('pages/aspek/index.php');
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="card"><h2>Tambah Aspek</h2><?php print_errors($errors); ?><form method="post">
    <div class="form-grid"><div class="form-group"><label>Kriteria</label><select name="id_kriteria" required><option value="">Pilih</option><?php foreach ($kriteria as $k): ?><option value="<?= e($k['id_kriteria']) ?>"><?= e($k['kode_kriteria'] . ' - ' . $k['nama_kriteria']) ?></option><?php endforeach; ?></select></div><div class="form-group"><label>Nama Aspek</label><input name="nama_aspek" required></div><div class="form-group"><label>Nilai</label><input type="number" min="1" max="5" name="nilai" required></div><div class="form-group"><label>Keterangan</label><textarea name="keterangan"></textarea></div></div>
    <button class="btn btn-primary">Simpan</button><a class="btn" href="index.php">Kembali</a>
</form></div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
