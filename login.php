<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

if (!empty($_SESSION['user'])) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
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