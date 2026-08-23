<?php require_once '../includes/conexao.php';
$id = (int)($_GET['id'] ?? 0);
$s = $pdo->prepare("DELETE FROM cidades WHERE id=?");
$s->execute([$id]);
header('Location:listar.php');
exit;
