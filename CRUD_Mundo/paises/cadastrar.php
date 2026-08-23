<?php require_once '../includes/conexao.php';
$continentes = $pdo->query("SELECT id,nome FROM continentes ORDER BY nome")->fetchAll();
$governantes = $pdo->query("SELECT id,nome FROM governantes ORDER BY nome")->fetchAll();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $s = $pdo->prepare("INSERT INTO paises(nome,continente_id,area,idioma,governante_id,clima,regime_politico,moeda) VALUES(?,?,?,?,?,?,?,?)");
    $s->execute([$_POST['nome'], $_POST['continente_id'], $_POST['area'], $_POST['idioma'], $_POST['governante_id'] ?: null, $_POST['clima'], $_POST['regime_politico'], $_POST['moeda']]);
    header('Location:listar.php');
    exit;
}
$row = []; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Países</title>
</head>

<body><?php include '../includes/menu.php'; ?><main class="container page">
        <div class="page-head">
            <h1><i class="fa-solid fa-plus" aria-hidden="true"></i> Cadastrar País</h1>            <a class="btn btn-secondary" href="listar.php">← Voltar</a>        </div>
        <div class="alert" role="note">
            A população do país será calculada automaticamente pela soma das populações das cidades cadastradas nele.
        </div>
        <form method="post" class="form-box grid-form">
            <div class="field"><label>Nome</label><input name="nome" value="<?= htmlspecialchars($row['nome'] ?? '') ?>" required></div>
            <div class="field"><label>Continente</label><select name="continente_id" required><?php foreach ($continentes as $x): ?><option value="<?= $x['id'] ?>" <?= isset($row['continente_id']) && $row['continente_id'] == $x['id'] ? 'selected' : '' ?>><?= htmlspecialchars($x['nome']) ?></option><?php endforeach; ?></select></div>
            <div class="field"><label>Área (km²)</label><input type="number" step="0.01" min="0" name="area" value="<?= htmlspecialchars($row['area'] ?? 0) ?>" required></div>
            <div class="field"><label>Idioma</label><input name="idioma" value="<?= htmlspecialchars($row['idioma'] ?? '') ?>"></div>
            <div class="field"><label>Governante</label><select name="governante_id">
                    <option value="">Nenhum</option><?php foreach ($governantes as $x): ?><option value="<?= $x['id'] ?>" <?= isset($row['governante_id']) && $row['governante_id'] == $x['id'] ? 'selected' : '' ?>><?= htmlspecialchars($x['nome']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="field"><label>Clima</label><input name="clima" value="<?= htmlspecialchars($row['clima'] ?? '') ?>"></div>
            <div class="field"><label>Regime político</label><input name="regime_politico" value="<?= htmlspecialchars($row['regime_politico'] ?? '') ?>"></div>
            <div class="field"><label>Moeda</label><input name="moeda" value="<?= htmlspecialchars($row['moeda'] ?? '') ?>"></div>
            <div class="field full form-actions"><button class="btn btn-primary">Salvar</button><a class="btn btn-secondary" href="listar.php">Cancelar</a></div>
        </form>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>

</html>