<?php
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
