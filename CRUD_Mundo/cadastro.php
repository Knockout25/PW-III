<?php
require_once __DIR__ . '/includes/conexao.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: /CRUD_Mundo/index.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $login = trim($_POST['login'] ?? '');

    if ($nome === '' || $login === '') {
        $erro = 'Preencha todos os campos.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE login = :login LIMIT 1');
        $stmt->execute([':login' => $login]);

        if ($stmt->fetch()) {
            $erro = 'Este usuário já está cadastrado.';
        } else {
            $hash = password_hash('123456', PASSWORD_DEFAULT);

            $insert = $pdo->prepare('INSERT INTO usuarios (nome, login, senha, primeiro_acesso, tentativas, bloqueado, bloqueado_em, ultimo_login, created_at, updated_at) VALUES (:nome, :login, :senha, 1, 0, 0, NULL, NULL, NOW(), NOW())');
            $insert->execute([
                ':nome' => $nome,
                ':login' => $login,
                ':senha' => $hash,
            ]);

            $usuarioId = (int)$pdo->lastInsertId();

            $pdo->prepare('INSERT INTO logs (usuario_id, acao, descricao) VALUES (:usuario_id, :acao, :descricao)')
                ->execute([
                    ':usuario_id' => $usuarioId,
                    ':acao' => 'cadastro',
                    ':descricao' => 'Novo usuário cadastrado no sistema.',
                ]);

            $sucesso = 'Cadastro realizado com sucesso! Faça login e altere sua senha no primeiro acesso.';
            $_POST = [];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro | CRUD Mundo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <h1><i class="fa-solid fa-user-plus"></i> Cadastro</h1>

            <?php if ($erro): ?>
                <div class="alert error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="alert success"><?= htmlspecialchars($sucesso) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <label>
                    Nome completo
                    <input type="text" name="nome" placeholder="Digite seu nome" value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                </label>

                <label>
                    Usuário
                    <input type="text" name="login" placeholder="Escolha um usuário" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>
                </label>

                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </form>

            <p class="form-help"><a href="login.php" style="color: #dbeafe; text-decoration: none;">Já tem conta? Entrar</a></p>
        </section>
    </main>
</body>
</html>
