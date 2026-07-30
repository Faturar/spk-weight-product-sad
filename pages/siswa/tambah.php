<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/forms.php';
require_roles(['admin']);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi data siswa sebelum disimpan ke tabel siswa.
    $errors = require_fields($_POST, ['kode_siswa' => 'Kode siswa', 'nama_siswa' => 'Nama siswa', 'nis' => 'NIS', 'no_handphone' => 'No handphone', 'jenis_kelamin' => 'Jenis kelamin', 'kelas' => 'Kelas']);
    if (!$errors) {
        // Data yang sudah valid disimpan memakai prepared statement.
        $stmt = $pdo->prepare('INSERT INTO siswa (kode_siswa, nama_siswa, nis, no_handphone, jenis_kelamin, kelas) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([trim($_POST['kode_siswa']), trim($_POST['nama_siswa']), trim($_POST['nis']), trim($_POST['no_handphone']), $_POST['jenis_kelamin'], trim($_POST['kelas'])]);
        flash('success', 'Data siswa berhasil ditambahkan.');
        redirect('pages/siswa/index.php');
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <h2>Tambah Siswa</h2>
    <?php print_errors($errors); ?>
    <form method="post">
        <div class="form-grid">
            <div class="form-group"><label>Kode Siswa</label><input name="kode_siswa" required value="<?= e($_POST['kode_siswa'] ?? '') ?>"></div>
            <div class="form-group"><label>Nama Siswa</label><input name="nama_siswa" required value="<?= e($_POST['nama_siswa'] ?? '') ?>"></div>
            <div class="form-group"><label>NIS</label><input name="nis" required value="<?= e($_POST['nis'] ?? '') ?>"></div>
            <div class="form-group"><label>No Handphone</label><input name="no_handphone" required value="<?= e($_POST['no_handphone'] ?? '') ?>"></div>
            <div class="form-group"><label>Jenis Kelamin</label><select name="jenis_kelamin" required><option value="">Pilih</option><option>Laki-laki</option><option>Perempuan</option></select></div>
            <div class="form-group"><label>Kelas</label><input name="kelas" required value="<?= e($_POST['kelas'] ?? '') ?>"></div>
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button><a class="btn" href="index.php">Kembali</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
