<?php
require_once __DIR__ . '/includes/conexao.php';
$totalPaises = $pdo->query("SELECT COUNT(*) FROM paises")->fetchColumn();
$totalCidades = $pdo->query("SELECT COUNT(*) FROM cidades")->fetchColumn();
$totalContinentes = $pdo->query("SELECT COUNT(*) FROM continentes")->fetchColumn();
$totalGovernantes = $pdo->query("SELECT COUNT(*) FROM governantes")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Crud Mundo</title>
</head>

<body>
    <?php include 'includes/menu.php'; ?>
    <main class="container">
        <section class="hero">
            <h1><i class="fa-solid fa-earth-americas" aria-hidden="true"></i> CRUD Mundo</h1>
            <p>Gerencie países, cidades, continentes e governantes.</p>
        </section>
        <section class="cards">
            <a class="card" href="paises/listar.php"><span><i class="fa-solid fa-earth-americas" aria-hidden="true"></i></span><strong><?= $totalPaises ?></strong><small>Países</small></a>
            <a class="card" href="cidades/listar.php"><span><i class="fa-solid fa-city" aria-hidden="true"></i></span><strong><?= $totalCidades ?></strong><small>Cidades</small></a>
            <a class="card" href="continentes/listar.php"><span><i class="fa-solid fa-earth-africa" aria-hidden="true"></i></span><strong><?= $totalContinentes ?></strong><small>Continentes</small></a>
            <a class="card" href="governantes/listar.php"><span><i class="fa-solid fa-user-tie" aria-hidden="true"></i></span><strong><?= $totalGovernantes ?></strong><small>Governantes</small></a>
        </section>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>
</body>

</html>