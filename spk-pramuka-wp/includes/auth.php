<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_url(string $path = ''): string
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $pos = strpos($script, '/spk-pramuka-wp/');
    $base = $pos === false ? '' : substr($script, 0, $pos + strlen('/spk-pramuka-wp'));
    return $base . ($path ? '/' . ltrim($path, '/') : '');
}

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function require_login(): void
{
    if (empty($_SESSION['user'])) {
        redirect('login.php');
    }
}

function user_role(): string
{
    return $_SESSION['user']['role'] ?? '';
}

function can_manage_master(): bool
{
    return user_role() === 'admin';
}

function can_input_nilai(): bool
{
    return in_array(user_role(), ['admin', 'pembina'], true);
}

function require_roles(array $roles): void
{
    require_login();
    if (!in_array(user_role(), $roles, true)) {
        http_response_code(403);
        include __DIR__ . '/header.php';
        echo '<div class="card"><h2>Akses ditolak</h2><p>Anda tidak memiliki izin membuka halaman ini.</p></div>';
        include __DIR__ . '/footer.php';
        exit;
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function show_flash(): void
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
    }
}

function report_header(string $title): void
{
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>' . e($title) . '</title><link rel="stylesheet" href="' . e(base_url('assets/css/style.css')) . '"></head><body class="print-page">';
    echo '<div class="report"><div class="report-kop"><h2>MTs Nurul Falah Areman</h2><p>Jl. Menpor Palsigunung No.89 RT 1 / RW.7 Tugu, Kec. Cimanggis, Kota Depok, Jawa Barat 16451</p></div><h3>' . e($title) . '</h3>';
}

function report_footer(): void
{
    $tanggal = date('d-m-Y');
    echo '<div class="signature"><p>Kota Depok, ' . e($tanggal) . '</p><p>Mengetahui,</p><p>Pembina</p><br><br><p>________________________</p></div></div><script>window.print();</script></body></html>';
}
