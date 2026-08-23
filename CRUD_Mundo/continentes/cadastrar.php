<?php require_once __DIR__ . '/../includes/conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $stmt = $pdo->prepare("INSERT INTO continentes (nome, area) VALUES (?,?)");
    $stmt->execute([$data['nome'], $data['area']]);
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
            <h1><i class="fa-solid fa-plus" aria-hidden="true"></i> Cadastrar Continentes</h1>
            <a class="btn btn-secondary" href="listar.php">← Voltar</a>
        </div>
        <div class="alert" role="note">
            A população do continente será calculada automaticamente pela soma das populações dos países cadastrados nele.
        </div>
        <form class="form-box grid-form" method="post">
            <div class="field"><label>Nome</label><input type="text" name="nome" value="<?= htmlspecialchars($row["nome"] ?? "") ?>" required></div>
            <div class="field"><label>Área (km²)</label><input type="number" name="area" value="<?= htmlspecialchars($row["area"] ?? "") ?>" required></div>
            <div class="field full form-actions"><button class="btn btn-primary">Salvar</button><a class="btn btn-secondary" href="listar.php">Cancelar</a></div>
        </form>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>

</html>