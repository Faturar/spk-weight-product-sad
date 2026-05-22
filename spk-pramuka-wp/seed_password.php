<?php
$passwords = ['admin123', 'pembina123', 'kepala123'];
foreach ($passwords as $password) {
    echo $password . ' => ' . password_hash($password, PASSWORD_DEFAULT) . PHP_EOL;
}
