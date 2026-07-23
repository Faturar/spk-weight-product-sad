<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

// Jika user sudah login, tidak perlu membuka halaman login lagi.
if (!empty($_SESSION['user'])) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data login dari form, lalu validasi agar username dan password tidak kosong.
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        // Prepared statement dipakai untuk mencari user dan menghindari SQL injection.
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Jika password cocok, data user disimpan ke session sebagai tanda sudah login.
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'nama' => $user['nama'],
                'username' => $user['username'],
                'role' => $user['role'],
            ];
            redirect('index.php');
        }
        $error = 'Username atau password salah.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SPK Pramuka WP</title>
    <link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>">
</head>

<body class="login-page">
    <form class="login-card" method="post">
        <img src="<?= e(base_url('logo.png')) ?>" alt="Logo SPK Pramuka WP" class="login-logo">
        <h1>SPK Pramuka WP</h1>
        <p>MTs Nurul Falah Areman</p>
        <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required autofocus>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</body>

</html>
