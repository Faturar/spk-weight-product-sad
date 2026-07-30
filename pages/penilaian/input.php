<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_roles(['admin']);

// id_siswa dari URL menentukan siswa yang nilainya akan diinput atau diedit.
$idSiswa = (int) ($_GET['id_siswa'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM siswa WHERE id_siswa=?');
$stmt->execute([$idSiswa]);
$siswa = $stmt->fetch();
if (!$siswa) { flash('danger', 'Data siswa tidak ditemukan.'); redirect('pages/penilaian/index.php'); }
$kriteria = $pdo->query('SELECT * FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();

// Mengambil nilai lama agar form edit menampilkan pilihan yang sudah pernah disimpan.
$nilaiStmt = $pdo->prepare('SELECT * FROM penilaian WHERE id_siswa=?');
$nilaiStmt->execute([$idSiswa]);
$nilaiLama = [];
foreach ($nilaiStmt as $row) { $nilaiLama[$row['id_kriteria']] = $row['nilai']; }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Setiap kriteria wajib diberi nilai skala 1 sampai 5.
    foreach ($kriteria as $k) {
        $nilai = $_POST['nilai'][$k['id_kriteria']] ?? '';
        if ($nilai === '' || !is_numeric($nilai) || $nilai < 1 || $nilai > 5) {
            $errors[] = 'Nilai ' . $k['kode_kriteria'] . ' wajib angka 1 sampai 5.';
        }
    }
    if (!$errors) {
        // Transaksi memastikan semua nilai kriteria tersimpan bersama-sama.
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO penilaian (id_siswa, id_kriteria, nilai) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE nilai=VALUES(nilai), updated_at=CURRENT_TIMESTAMP');
        foreach ($kriteria as $k) {
            $stmt->execute([$idSiswa, $k['id_kriteria'], (float) $_POST['nilai'][$k['id_kriteria']]]);
        }
        $pdo->commit();
        flash('success', 'Nilai siswa berhasil disimpan.');
        redirect('pages/penilaian/index.php');
    }
}
include __DIR__ . '/../../includes/header.php';
?>
<div class="card">
    <h2>Input Nilai: <?= e($siswa['kode_siswa'] . ' - ' . $siswa['nama_siswa']) ?></h2>
    <?php if ($errors): ?><div class="alert alert-danger"><ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="post">
        <?php foreach ($kriteria as $k): ?>
            <div class="form-group">
                <label><?= e($k['kode_kriteria'] . ' - ' . $k['nama_kriteria']) ?></label>
                <select name="nilai[<?= e($k['id_kriteria']) ?>]" required>
                    <option value="">Pilih Nilai</option>
                    <?php foreach ([1=>'Sangat Kurang Baik',2=>'Kurang Baik',3=>'Cukup Baik',4=>'Baik',5=>'Sangat Baik'] as $angka => $label): ?>
                        <option value="<?= $angka ?>" <?= (string) ($_POST['nilai'][$k['id_kriteria']] ?? $nilaiLama[$k['id_kriteria']] ?? '') === (string) $angka ? 'selected' : '' ?>><?= $angka ?> - <?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endforeach; ?>
        <button class="btn btn-primary">Simpan</button><a class="btn" href="index.php">Kembali</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
