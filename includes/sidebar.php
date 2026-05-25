<?php $role = user_role(); ?>
<aside class="sidebar">
    <div class="brand">SPK WP<br><span>Pramuka Inti</span></div>
    <nav>
        <a href="<?= e(base_url('index.php')) ?>">Dashboard</a>
        <?php if (in_array($role, ['admin', 'pembina'], true)): ?>
            <a href="<?= e(base_url('pages/siswa/index.php')) ?>">Data Siswa</a>
        <?php endif; ?>
        <?php if ($role === 'admin'): ?>
            <a href="<?= e(base_url('pages/kriteria/index.php')) ?>">Data Kriteria</a>
            <a href="<?= e(base_url('pages/aspek/index.php')) ?>">Data Aspek</a>
        <?php endif; ?>
        <?php if (in_array($role, ['admin', 'pembina'], true)): ?>
            <a href="<?= e(base_url('pages/penilaian/index.php')) ?>">Penilaian</a>
        <?php endif; ?>
        <?php if ($role === 'admin'): ?>
            <a href="<?= e(base_url('pages/perhitungan/index.php')) ?>">Perhitungan WP</a>
        <?php endif; ?>
        <a href="<?= e(base_url('pages/hasil-seleksi/index.php')) ?>">Hasil Seleksi</a>
        <a href="<?= e(base_url('logout.php')) ?>" class="logout">Logout</a>
    </nav>
</aside>
