<?php require_once __DIR__ . '/../includes/conexao.php';
$busca = trim($_GET['busca'] ?? '');
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 7;
$filtro = "%$busca%";
$total = $pdo->prepare("SELECT COUNT(*) FROM continentes WHERE nome LIKE ?");
$total->execute([$filtro]);
$totalPaginas = max(1, (int)ceil($total->fetchColumn() / $porPagina));
$pagina = min($pagina, $totalPaginas);
$offset = ($pagina - 1) * $porPagina;
$stmt = $pdo->prepare("SELECT * FROM continentes WHERE nome LIKE ? ORDER BY nome LIMIT $porPagina OFFSET $offset");
$stmt->execute([$filtro]);
$rows = $stmt->fetchAll(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Continentes</title>
</head>

<body><?php include '../includes/menu.php'; ?><main class="container page">
        <div class="table-box">
            <div class="table-toolbar">
                <div class="page-head"><h1><i class="fa-solid fa-earth-africa" aria-hidden="true"></i> Continentes</h1></div>
                <div class="page-actions">
                    <form class="search-bar"><input name="busca" placeholder="Pesquisar por nome..." value="<?= htmlspecialchars($busca) ?>"><button class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Pesquisar</button></form>
                    <a class="btn btn-primary" href="cadastrar.php"><i class="fa-solid fa-plus" aria-hidden="true"></i> Cadastrar</a>
                </div>
            </div>
            <div class="table-scroll">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>População</th>
                        <th>Área (km²)</th>
                        <th>Total de países</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody><?php foreach ($rows as $row): ?><tr>
                            <td><?= htmlspecialchars($row['nome']) ?></td>
                            <td><?= htmlspecialchars($row['populacao']) ?></td>
                            <td><?= htmlspecialchars($row['area']) ?></td>
                            <td><?= htmlspecialchars($row['total_paises']) ?></td>
                            <td class="actions"><a class="btn btn-warning" href="editar.php?id=<?= $row['id'] ?>" aria-label="Editar continente" title="Editar continente"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a><a class="btn btn-danger delete-link" href="excluir.php?id=<?= $row['id'] ?>" aria-label="Excluir continente" title="Excluir continente"><i class="fa-solid fa-trash" aria-hidden="true"></i></a></td>
                        </tr><?php endforeach; ?><?php if (!$rows): ?><tr>
                            <td colspan="5">Nenhum registro encontrado.</td>
                        </tr><?php endif; ?></tbody>
            </table>
            </div>
        <?php if ($totalPaginas > 1): ?><nav class="pagination" aria-label="Paginação de continentes">
            <a class="pagination-button" href="?pagina=<?= max(1, $pagina - 1) ?>&busca=<?= urlencode($busca) ?>" aria-label="Página anterior" aria-disabled="<?= $pagina === 1 ? 'true' : 'false' ?>"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></a>
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?><a class="pagination-button <?= $i === $pagina ? 'active' : '' ?>" href="?pagina=<?= $i ?>&busca=<?= urlencode($busca) ?>" <?= $i === $pagina ? 'aria-current="page"' : '' ?>><?= $i ?></a><?php endfor; ?>
            <a class="pagination-button" href="?pagina=<?= min($totalPaginas, $pagina + 1) ?>&busca=<?= urlencode($busca) ?>" aria-label="Próxima página" aria-disabled="<?= $pagina === $totalPaginas ? 'true' : 'false' ?>"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></a>
        </nav><?php endif; ?>
        </div>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>

</html>