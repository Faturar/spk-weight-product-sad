<aside class="sidebar">
    <div class="brand">
        <img src="<?= e(asset_url('logo.png')) ?>" alt="Logo SPK Pramuka WP" class="brand-logo">
        <div>SPK WP<br><span>Pramuka Inti</span></div>
    </div>
    <nav>
        <a href="<?= e(base_url('index.php')) ?>">Dashboard</a>
        <a href="<?= e(base_url('pages/siswa/index.php')) ?>">Data Siswa</a>
        <a href="<?= e(base_url('pages/kriteria/index.php')) ?>">Data Kriteria</a>
        <a href="<?= e(base_url('pages/aspek/index.php')) ?>">Data Aspek</a>
        <a href="<?= e(base_url('pages/penilaian/index.php')) ?>">Penilaian</a>
        <a href="<?= e(base_url('pages/perhitungan/index.php')) ?>">Perhitungan WP</a>
        <a href="<?= e(base_url('pages/hasil-seleksi/index.php')) ?>">Hasil Seleksi</a>
        <a href="<?= e(base_url('pages/report/index.php')) ?>">Report PDF</a>
        <a href="<?= e(base_url('logout.php')) ?>" class="logout">Logout</a>
    </nav>
</aside>
