<?php
require_once __DIR__ . '/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Pramuka WP</title>
    <link rel="stylesheet" href="<?= e(base_url('assets/css/style.css')) ?>">
</head>
<body>
<div class="app">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="main">
        <header class="topbar">
            <button class="menu-toggle" type="button" aria-label="Buka menu">&#9776;</button>
            <div>
                <h1>Sistem Pendukung Keputusan Pramuka Inti</h1>
                <p>Metode Weighted Product - MTs Nurul Falah Areman</p>
            </div>
            <div class="user-box">
                <strong><?= e($_SESSION['user']['nama'] ?? '') ?></strong>
                <small><?= e(str_replace('_', ' ', user_role())) ?></small>
            </div>
        </header>
        <section class="content">
            <?php show_flash(); ?>
