<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/forms.php';
require_roles(['admin']);
$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM siswa WHERE id_siswa = ?');
$stmt->execute([$id]);
$data = $stmt->fetch();
if (!$data) { flash('danger', 'Data siswa tidak ditemukan.'); redirect('pages/siswa/index.php'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = require_fields($_POST, ['kode_siswa' => 'Kode siswa', 'nama_siswa' => 'Nama siswa', 'nis' => 'NIS', 'no_handphone' => 'No handphone', 'jenis_kelamin' => 'Jenis kelamin', 'kelas' => 'Kelas']);
    if (!$errors) {
        $stmt = $pdo->prepare('UPDATE siswa SET kode_siswa=?, nama_siswa=?, nis=?, no_handphone=?, jenis_kelamin=?, kelas=? WHERE id_siswa=?');
        $stmt->execute([trim($_POST['kode_siswa']), trim($_POST['nama_siswa']), trim($_POST['nis']), trim($_POST['no_handphone']), $_POST['jenis_kelamin'], trim($_POST['kelas']), $id]);
        flash('success', 'Data siswa berhasil diperbarui.');
        redirect('pages/siswa/index.php');
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <h2>Edit Siswa</h2>
    <?php print_errors($errors); ?>
    <form method="post">
        <div class="form-grid">
            <div class="form-group"><label>Kode Siswa</label><input name="kode_siswa" required value="<?= e($_POST['kode_siswa'] ?? $data['kode_siswa']) ?>"></div>
            <div class="form-group"><label>Nama Siswa</label><input name="nama_siswa" required value="<?= e($_POST['nama_siswa'] ?? $data['nama_siswa']) ?>"></div>
            <div class="form-group"><label>NIS</label><input name="nis" required value="<?= e($_POST['nis'] ?? $data['nis']) ?>"></div>
            <div class="form-group"><label>No Handphone</label><input name="no_handphone" required value="<?= e($_POST['no_handphone'] ?? $data['no_handphone']) ?>"></div>
            <div class="form-group"><label>Jenis Kelamin</label><select name="jenis_kelamin" required><?php foreach (['Laki-laki','Perempuan'] as $jk): ?><option value="<?= e($jk) ?>" <?= ($data['jenis_kelamin'] === $jk ? 'selected' : '') ?>><?= e($jk) ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label>Kelas</label><input name="kelas" required value="<?= e($_POST['kelas'] ?? $data['kelas']) ?>"></div>
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button><a class="btn" href="index.php">Kembali</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
