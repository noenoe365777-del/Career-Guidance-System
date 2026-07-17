<?php
require_once 'vendor/autoload.php';
$pdo = \App\Config\Database::getConnection();
echo $pdo->query('SELECT VERSION()')->fetchColumn();