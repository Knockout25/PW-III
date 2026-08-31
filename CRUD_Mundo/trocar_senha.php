<?php
require_once __DIR__ . '/includes/conexao.php';

if (empty($_SESSION['usuario_id'])) {
    header('Location: /CRUD_Mundo/login.php');
    exit;
}

$usuario = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id LIMIT 1');
$usuario->execute([':id' => $_SESSION['usuario_id']]);
$usuario = $usuario->fetch();

if (!$usuario) {
    session_destroy();
    header('Location: /CRUD_Mundo/login.php');
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAtual = $_POST['senha_atual'] ?? '';
    $novaSenha = $_POST['nova_senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    if ($senhaAtual === '' || $novaSenha === '' || $confirmarSenha === '') {
        $erro = 'Preencha todos os campos da senha.';
    } elseif (strlen($novaSenha) < 6) {
        $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
    } elseif ($novaSenha !== $confirmarSenha) {
        $erro = 'A confirmação da nova senha não confere.';
    } elseif (!password_verify($senhaAtual, $usuario['senha'])) {
        $erro = 'A senha atual informada está incorreta.';
    } else {
        $novoHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $pdo->prepare('UPDATE usuarios SET senha = :senha, primeiro_acesso = 0, tentativas = 0, bloqueado = 0, bloqueado_em = NULL, updated_at = NOW() WHERE id = :id')
            ->execute([
                ':senha' => $novoHash,
                ':id' => $usuario['id'],
            ]);

        $pdo->prepare('INSERT INTO logs (usuario_id, acao, descricao) VALUES (:usuario_id, :acao, :descricao)')
            ->execute([
                ':usuario_id' => $usuario['id'],
                ':acao' => 'troca_senha',
                ':descricao' => 'Senha alterada com sucesso.',
            ]);

        $_SESSION['usuario_primeiro_acesso'] = 0;
        $sucesso = 'Senha alterada com sucesso!';
        $usuario['primeiro_acesso'] = 0;

        if (empty($_GET['from_login'])) {
            header('Location: /CRUD_Mundo/index.php?status=senha_atualizada');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trocar senha | CRUD Mundo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <h1>Trocar senha</h1>

            <?php if ($erro): ?>
                <div class="alert error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="alert success"><?= htmlspecialchars($sucesso) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <label>
                    Senha atual
                    <input type="password" name="senha_atual" placeholder="Digite a senha atual" required>
                </label>

                <label>
                    Nova senha
                    <input type="password" name="nova_senha" placeholder="Digite a nova senha" required>
                </label>

                <label>
                    Confirmar nova senha
                    <input type="password" name="confirmar_senha" placeholder="Confirme a nova senha" required>
                </label>

                <button type="submit" class="btn btn-primary">Salvar nova senha</button>
            </form>

            <p class="form-help">Você deve trocar a senha no primeiro acesso ao sistema.</p>
        </section>
    </main>
</body>
</html>
