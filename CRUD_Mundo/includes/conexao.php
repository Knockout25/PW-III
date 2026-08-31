<?php
session_start();

$host = 'localhost';
$db = 'bd_mundo';
$user = 'root';
$pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo '<script>alert(' . json_encode('Erro na conexão com o banco de dados.') . '); history.back();</script>';
    exit;
}

$paginaAtual = basename($_SERVER['PHP_SELF'] ?? '');
$paginasPublicas = ['login.php', 'cadastro.php', 'logout.php'];

if (!in_array($paginaAtual, $paginasPublicas, true)) {
    if (empty($_SESSION['usuario_id'])) {
        header('Location: /CRUD_Mundo/login.php');
        exit;
    }

    if (!empty($_SESSION['usuario_primeiro_acesso']) && $_SESSION['usuario_primeiro_acesso'] == 1 && $paginaAtual !== 'trocar_senha.php') {
        header('Location: /CRUD_Mundo/trocar_senha.php');
        exit;
    }
}
