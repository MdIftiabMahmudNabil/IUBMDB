<?php
require_once __DIR__ . '/env.php';

try {
    $dsn = "pgsql:host=$host;port=5432;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
