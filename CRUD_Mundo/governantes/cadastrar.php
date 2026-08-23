<?php require_once __DIR__ . '/../includes/conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST;
    $stmt = $pdo->prepare("INSERT INTO governantes (nome, partido_politico, data_nascimento, idade, data_inicio_mandato, data_final_mandato) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$data['nome'], $data['partido_politico'], $data['data_nascimento'], $data['idade'], $data['data_inicio_mandato'], $data['data_final_mandato']]);
    header('Location: listar.php');
    exit;
} ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Governantes</title>
</head>

<body><?php include '../includes/menu.php'; ?><main class="container page">
        <div class="page-head">
            <h1><i class="fa-solid fa-plus" aria-hidden="true"></i> Cadastrar Governantes</h1>
            <a class="btn btn-secondary" href="listar.php">← Voltar</a>
        </div>
        <form class="form-box grid-form" method="post">
            <div class="field"><label>Nome</label><input type="text" name="nome" value="<?= htmlspecialchars($row["nome"] ?? "") ?>" required></div>
            <div class="field"><label>Partido político</label><input type="text" name="partido_politico" value="<?= htmlspecialchars($row["partido_politico"] ?? "") ?>" required></div>
            <div class="field"><label>Data de nascimento</label><input type="date" name="data_nascimento" value="<?= htmlspecialchars($row["data_nascimento"] ?? "") ?>" required></div>
            <div class="field"><label>Idade</label><input type="number" name="idade" value="<?= htmlspecialchars($row["idade"] ?? "") ?>" required></div>
            <div class="field"><label>Início do mandato</label><input type="date" name="data_inicio_mandato" value="<?= htmlspecialchars($row["data_inicio_mandato"] ?? "") ?>" required></div>
            <div class="field"><label>Final do mandato</label><input type="date" name="data_final_mandato" value="<?= htmlspecialchars($row["data_final_mandato"] ?? "") ?>" required></div>
            <div class="field full form-actions"><button class="btn btn-primary">Salvar</button><a class="btn btn-secondary" href="listar.php">Cancelar</a></div>
        </form>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>

</html>