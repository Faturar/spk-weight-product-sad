<?php
function require_fields(array $data, array $fields): array
{
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
    if ($errors) {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . e($error) . '</li>';
        }
        echo '</ul></div>';
    }
}
