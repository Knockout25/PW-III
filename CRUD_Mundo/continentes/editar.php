<?php require_once __DIR__ . '/../includes/conexao.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM continentes WHERE id=?");
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    echo '<script>alert(' . json_encode('Registro não encontrado.') . '); history.back();</script>';
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE continentes SET nome=?, area=? WHERE id=?");
    $stmt->execute([$_POST['nome'], $_POST['area'], $id]);
    header('Location: listar.php');
    exit;
} ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Continentes</title>
</head>

<body><?php include '../includes/menu.php'; ?><main class="container page">
        <div class="page-head">
            <h1><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Editar Continentes</h1>
            <a class="btn btn-secondary" href="listar.php">← Voltar</a>
        </div>
        <form class="form-box grid-form" method="post">
            <div class="field"><label>Nome</label><input type="text" name="nome" value="<?= htmlspecialchars($row["nome"] ?? "") ?>" required></div>
            <div class="field"><label>Área (km²)</label><input type="number" min="0" step="0.01" name="area" value="<?= htmlspecialchars($row["area"] ?? 0) ?>" required></div>
            <div class="field full form-actions"><button class="btn btn-primary">Atualizar</button><a class="btn btn-secondary" href="listar.php">Cancelar</a></div>
        </form>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>

</html>