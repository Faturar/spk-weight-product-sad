<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
redirect('pages/dashboard.php');
