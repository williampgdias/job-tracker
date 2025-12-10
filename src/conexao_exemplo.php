<?php

$host = '127.0.0.1';
$port = '3306';
$dbname = 'job_tracker';
$username = 'seu_usuario';
$password = 'sua_senha';

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}