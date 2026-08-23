<?php require_once '../includes/conexao.php';
$paises = $pdo->query("SELECT id,nome FROM paises ORDER BY nome")->fetchAll();
$governantes = $pdo->query("SELECT id,nome FROM governantes ORDER BY nome")->fetchAll();
$row = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $s = $pdo->prepare("INSERT INTO cidades(nome,pais_id,populacao,area,clima,governante_id,data_fundacao) VALUES(?,?,?,?,?,?,?)");
    $s->execute([$_POST['nome'], $_POST['pais_id'], $_POST['populacao'], $_POST['area'], $_POST['clima'], $_POST['governante_id'] ?: null, $_POST['data_fundacao'] ?: null]);
    header('Location:listar.php');
    exit;
} ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Cidades</title>
</head>

<body><?php include '../includes/menu.php'; ?><main class="container page">
        <div class="page-head">
            <h1><i class="fa-solid fa-plus" aria-hidden="true"></i> Cadastrar Cidade</h1>
            <a class="btn btn-secondary" href="listar.php">← Voltar</a>
        </div>
        <form method="post" class="form-box grid-form">
            <div class="field"><label>Nome</label><input name="nome" value="<?= htmlspecialchars($row['nome'] ?? '') ?>" required></div>
            <div class="field"><label>País</label><select name="pais_id" required><?php foreach ($paises as $x): ?><option value="<?= $x['id'] ?>" <?= isset($row['pais_id']) && $row['pais_id'] == $x['id'] ? 'selected' : '' ?>><?= htmlspecialchars($x['nome']) ?></option><?php endforeach; ?></select></div>
            <div class="field"><label>População</label><input type="number" min="0" name="populacao" value="<?= htmlspecialchars($row['populacao'] ?? 0) ?>" required></div>
            <div class="field"><label>Área (km²)</label><input type="number" step="0.01" min="0" name="area" value="<?= htmlspecialchars($row['area'] ?? 0) ?>" required></div>
            <div class="field"><label>Clima</label><input name="clima" value="<?= htmlspecialchars($row['clima'] ?? '') ?>"></div>
            <div class="field"><label>Governante</label><select name="governante_id">
                    <option value="">Nenhum</option><?php foreach ($governantes as $x): ?><option value="<?= $x['id'] ?>" <?= isset($row['governante_id']) && $row['governante_id'] == $x['id'] ? 'selected' : '' ?>><?= htmlspecialchars($x['nome']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="field"><label>Data de fundação</label><input type="date" name="data_fundacao" value="<?= htmlspecialchars($row['data_fundacao'] ?? '') ?>"></div>
            <div class="field full form-actions"><button class="btn btn-primary">Salvar</button><a class="btn btn-secondary" href="listar.php">Cancelar</a></div>
        </form>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>

</html>