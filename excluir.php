<?php
require_once 'src/conexao.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM vagas WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    die("Erro ao excluir: " . $e->getMessage());
}