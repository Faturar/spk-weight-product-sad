<?php
require_once __DIR__ . '/includes/auth.php';
require_roles(['admin']);
redirect('pages/dashboard.php');
