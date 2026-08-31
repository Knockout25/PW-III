<?php require_once '../includes/conexao.php';
$busca = trim($_GET['busca'] ?? '');
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 10;
$filtro = "%$busca%";
$total = $pdo->prepare("SELECT COUNT(*) FROM logs l LEFT JOIN usuarios u ON u.id=l.usuario_id WHERE l.acao LIKE ? OR l.descricao LIKE ? OR u.nome LIKE ?");
$total->execute([$filtro, $filtro, $filtro]);
$totalPaginas = max(1, (int)ceil($total->fetchColumn() / $porPagina));
$pagina = min($pagina, $totalPaginas);
$offset = ($pagina - 1) * $porPagina;
$stmt = $pdo->prepare("SELECT l.*, u.nome FROM logs l LEFT JOIN usuarios u ON u.id=l.usuario_id WHERE l.acao LIKE ? OR l.descricao LIKE ? OR u.nome LIKE ? ORDER BY l.created_at DESC LIMIT $porPagina OFFSET $offset");
$stmt->execute([$filtro, $filtro, $filtro]);
$rows = $stmt->fetchAll(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Logs</title>
</head>

<body><?php include '../includes/menu.php'; ?><main class="container page">
        <div class="table-box">
            <div class="table-toolbar">
                <div class="page-head"><h1><i class="fa-solid fa-history" aria-hidden="true"></i> Registros de Atividade</h1></div>
                <div class="page-actions">
                    <form class="search-bar"><input name="busca" placeholder="Pesquisar por ação, descrição ou usuário..." value="<?= htmlspecialchars($busca) ?>"><button class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Pesquisar</button></form>
                </div>
            </div>
            <div class="table-scroll">
            <table class="table">
                <thead>
                    <tr>
                        <th>Data/Hora</th>
                        <th>Usuário</th>
                        <th>Ação</th>
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody><?php foreach ($rows as $r): ?><tr><td><?= htmlspecialchars(date('d/m/Y H:i:s', strtotime($r['created_at']))) ?></td><td><?= htmlspecialchars($r['nome'] ?? '(Sistema)') ?></td><td><span style="background: rgba(59, 130, 246, 0.2); padding: 4px 8px; border-radius: 6px; font-size: 0.85rem;"><?= htmlspecialchars($r['acao']) ?></span></td><td><?= htmlspecialchars($r['descricao']) ?></td>
                        </tr><?php endforeach; ?></tbody>
            </table>
            </div>
        <?php if ($totalPaginas > 1): ?><nav class="pagination" aria-label="Paginação de logs">
            <a class="pagination-button" href="?pagina=<?= max(1, $pagina - 1) ?>&busca=<?= urlencode($busca) ?>" aria-label="Página anterior" aria-disabled="<?= $pagina === 1 ? 'true' : 'false' ?>"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></a>
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?><a class="pagination-button <?= $i === $pagina ? 'active' : '' ?>" href="?pagina=<?= $i ?>&busca=<?= urlencode($busca) ?>" <?= $i === $pagina ? 'aria-current="page"' : '' ?>><?= $i ?></a><?php endfor; ?>
            <a class="pagination-button" href="?pagina=<?= min($totalPaginas, $pagina + 1) ?>&busca=<?= urlencode($busca) ?>" aria-label="Próxima página" aria-disabled="<?= $pagina === $totalPaginas ? 'true' : 'false' ?>"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></a>
        </nav><?php endif; ?>
        </div>
    </main>
    <script src="/CRUD_Mundo/js/script.js"></script>

</html>
