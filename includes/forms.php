<?php
function require_fields(array $data, array $fields): array
{
    // Validasi sederhana untuk memastikan field wajib tidak kosong.
    $errors = [];
    foreach ($fields as $field => $label) {
        if (trim((string) ($data[$field] ?? '')) === '') {
            $errors[] = $label . ' wajib diisi.';
        }
    }
    return $errors;
}

function print_errors(array $errors): void
{
    // Menampilkan semua pesan validasi dalam bentuk alert di halaman form.
    if ($errors) {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . e($error) . '</li>';
        }
        echo '</ul></div>';
    }
}
