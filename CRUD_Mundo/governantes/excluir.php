<?php require_once __DIR__ . '/../includes/conexao.php';
$id = (int)($_GET['id'] ?? 0);
try {
    $stmt = $pdo->prepare("DELETE FROM governantes WHERE id=?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    echo '<script>alert(' . json_encode('Não foi possível excluir: existem registros relacionados.') . '); history.back();</script>';
    exit;
}
header('Location: listar.php');
exit;
