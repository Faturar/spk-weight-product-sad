<?php
// Session dipakai untuk menyimpan status login dan pesan sementara/flash.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function base_url(string $path = ''): string
{
    static $base = null;

    if ($base === null) {
        // Menentukan folder dasar project secara otomatis agar link tetap benar di Laragon/subfolder.
        $documentRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
        $projectRoot = realpath(dirname(__DIR__));

        if ($documentRoot && $projectRoot) {
            $documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');
            $projectRoot = str_replace('\\', '/', $projectRoot);

            if (stripos($projectRoot, $documentRoot) === 0) {
                $base = trim(substr($projectRoot, strlen($documentRoot)), '/');
                $base = $base === '' ? '' : '/' . $base;
            }
        }

        if ($base === null) {
            $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
            $folder = '/' . basename(dirname(__DIR__));
            $pos = strpos($script, $folder . '/');
            $base = $pos === false ? '' : substr($script, 0, $pos + strlen($folder));
        }
    }

    return $base . ($path ? '/' . ltrim($path, '/') : '');
}

function e($value): string
{
    // Helper untuk mencegah XSS saat menampilkan data dari database atau input user.
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    // Mengalihkan halaman memakai base_url agar path konsisten dari folder mana pun.
    header('Location: ' . base_url($path));
    exit;
}

function require_login(): void
{
    // Semua halaman internal wajib login. Jika belum login, user diarahkan ke login.php.
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
    // Mengecek hak akses berdasarkan role, misalnya admin atau pembina.
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
    // Pesan disimpan di session agar bisa tampil setelah proses redirect.
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function show_flash(): void
{
    // Flash message hanya ditampilkan satu kali, lalu dihapus dari session.
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($flash['type']) . '">' . e($flash['message']) . '</div>';
    }
}

function report_header(string $title): void
{
    // Header khusus halaman cetak laporan.
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>' . e($title) . '</title><link rel="stylesheet" href="' . e(base_url('assets/css/style.css')) . '"></head><body class="print-page">';
    echo '<div class="report"><div class="report-kop"><h2>MTs Nurul Falah Areman</h2><p>Jl. Menpor Palsigunung No.89 RT 1 / RW.7 Tugu, Kec. Cimanggis, Kota Depok, Jawa Barat 16451</p></div><h3>' . e($title) . '</h3>';
}

function report_footer(): void
{
    // Footer laporan sekaligus memanggil window.print() agar dialog cetak otomatis muncul.
    $tanggal = date('d-m-Y');
    echo '<div class="signature"><p>Kota Depok, ' . e($tanggal) . '</p><p>Mengetahui,</p><p>Pembina</p><br><br><p>________________________</p></div></div><script>window.print();</script></body></html>';
}
