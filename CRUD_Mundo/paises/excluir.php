<?php require_once '../includes/conexao.php';
$id = (int)($_GET['id'] ?? 0);
try {
    $s = $pdo->prepare("DELETE FROM paises WHERE id=?");
    $s->execute([$id]);
} catch (PDOException $e) {
    echo '<script>alert(' . json_encode('Não é possível excluir este país porque existem cidades associadas.') . '); history.back();</script>';
    exit;
}
header('Location:listar.php');
exit;
