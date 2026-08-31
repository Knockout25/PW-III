<?php
require_once __DIR__ . '/includes/conexao.php';

$erro = '';
$sucesso = '';
$loginBloqueado = false;
$tempoRestante = 0;
$tempoBloqueioMinutos = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE login = :login LIMIT 1');
    $stmt->execute([':login' => $login]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $bloqueadoAte = null;
        if (!empty($usuario['bloqueado_em'])) {
            $bloqueadoAte = new DateTime($usuario['bloqueado_em']);
            $bloqueadoAte->modify('+' . $tempoBloqueioMinutos . ' minutes');
        }

        if ((int)$usuario['bloqueado'] === 1 && $bloqueadoAte && new DateTime() < $bloqueadoAte) {
            $loginBloqueado = true;
            $tempoRestante = max(0, $bloqueadoAte->getTimestamp() - time());
            $erro = 'Conta bloqueada por excesso de tentativas. Tente novamente em ' . gmdate('i:s', $tempoRestante) . '.';
        }
    }

    if (!$loginBloqueado) {
        if ($login === '' || $senha === '') {
            $erro = 'Informe o usuário e a senha.';
        } else {
            $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE login = :login LIMIT 1');
            $stmt->execute([':login' => $login]);
            $usuario = $stmt->fetch();

            if (!$usuario) {
                $erro = 'Usuário ou senha inválidos.';
            } else {
                $bloqueadoAte = null;
                if (!empty($usuario['bloqueado_em'])) {
                    $bloqueadoAte = new DateTime($usuario['bloqueado_em']);
                    $bloqueadoAte->modify('+' . $tempoBloqueioMinutos . ' minutes');
                }

                if ((int)$usuario['bloqueado'] === 1 && $bloqueadoAte && new DateTime() < $bloqueadoAte) {
                    $loginBloqueado = true;
                    $tempoRestante = max(0, $bloqueadoAte->getTimestamp() - time());
                    $erro = 'Conta bloqueada por excesso de tentativas. Tente novamente em ' . gmdate('i:s', $tempoRestante) . '.';
                } else {
                    if ((int)$usuario['bloqueado'] === 1) {
                        $pdo->prepare('UPDATE usuarios SET bloqueado = 0, bloqueado_em = NULL, tentativas = 0 WHERE id = :id')
                            ->execute([':id' => $usuario['id']]);
                        $usuario['bloqueado'] = 0;
                        $usuario['tentativas'] = 0;
                        $usuario['bloqueado_em'] = null;
                    }

                    if (password_verify($senha, $usuario['senha'])) {
                        $_SESSION['usuario_id'] = (int)$usuario['id'];
                        $_SESSION['usuario_nome'] = $usuario['nome'];
                        $_SESSION['usuario_login'] = $usuario['login'];
                        $_SESSION['usuario_primeiro_acesso'] = (int)$usuario['primeiro_acesso'];

                        $pdo->prepare('UPDATE usuarios SET tentativas = 0, bloqueado = 0, bloqueado_em = NULL, ultimo_login = NOW(), updated_at = NOW() WHERE id = :id')
                            ->execute([':id' => $usuario['id']]);

                        $pdo->prepare('INSERT INTO logs (usuario_id, acao, descricao) VALUES (:usuario_id, :acao, :descricao)')
                            ->execute([
                                ':usuario_id' => $usuario['id'],
                                ':acao' => 'login',
                                ':descricao' => 'Login realizado com sucesso.',
                            ]);

                        if ((int)$usuario['primeiro_acesso'] === 1) {
                            header('Location: /CRUD_Mundo/trocar_senha.php');
                            exit;
                        }

                        header('Location: /CRUD_Mundo/index.php');
                        exit;
                    }

                    $tentativas = (int)$usuario['tentativas'] + 1;

                    if ($tentativas >= 3) {
                        $pdo->prepare('UPDATE usuarios SET tentativas = 3, bloqueado = 1, bloqueado_em = NOW(), updated_at = NOW() WHERE id = :id')
                            ->execute([':id' => $usuario['id']]);

                        $pdo->prepare('INSERT INTO logs (usuario_id, acao, descricao) VALUES (:usuario_id, :acao, :descricao)')
                            ->execute([
                                ':usuario_id' => $usuario['id'],
                                ':acao' => 'bloqueio',
                                ':descricao' => 'Senha incorreta por 3 tentativas consecutivas. Usuário bloqueado por 1 minuto (teste).',
                            ]);

                        $loginBloqueado = true;
                        $tempoRestante = 60;
                        $erro = 'Senha incorreta. Essa foi a 3ª tentativa. Sua conta foi bloqueada por 1 minuto.';
                    } else {
                        $pdo->prepare('UPDATE usuarios SET tentativas = :tentativas, updated_at = NOW() WHERE id = :id')
                            ->execute([':tentativas' => $tentativas, ':id' => $usuario['id']]);

                        $restantes = 3 - $tentativas;
                        $erro = 'Senha incorreta. Restam ' . $restantes . ' tentativa(s).';
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CRUD Mundo</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <main class="auth-shell">
        <section class="auth-card">
            <h1>Login</h1>

            <?php if ($erro): ?>
                <div class="alert error"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <?php if ($loginBloqueado && $tempoRestante > 0): ?>
                <div id="contador-bloqueio" class="alert error">Tempo restante: <?= gmdate('i:s', $tempoRestante) ?></div>
            <?php endif; ?>

            <?php if ($sucesso): ?>
                <div class="alert success"><?= htmlspecialchars($sucesso) ?></div>
            <?php endif; ?>

            <form method="POST" class="auth-form" id="form-login">
                <label>
                    Usuário
                    <input type="text" name="login" placeholder="Digite seu usuário" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required <?= $loginBloqueado ? 'disabled' : '' ?>>
                </label>

                <label>
                    Senha
                    <input type="password" name="senha" placeholder="Digite sua senha" required <?= $loginBloqueado ? 'disabled' : '' ?>>
                </label>

                <button type="submit" class="btn btn-primary" <?= $loginBloqueado ? 'disabled' : '' ?>>Entrar</button>
            </form>

            <p class="form-help">Senha padrão para novos usuários: <strong>123456</strong></p>
            <p class="form-help"><a href="cadastro.php" style="color: #dbeafe; text-decoration: none;">Não tem conta? Cadastre-se</a></p>
        </section>
    </main>

    <script>
        const contador = document.getElementById('contador-bloqueio');
        const formLogin = document.getElementById('form-login');
        const campos = formLogin ? formLogin.querySelectorAll('input, button') : [];

        if (contador && formLogin) {
            let tempoRestante = <?= (int) $tempoRestante ?>;

            const atualizarContador = () => {
                const minutos = Math.floor(tempoRestante / 60);
                const segundos = tempoRestante % 60;
                contador.textContent = `Tempo restante: ${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;

                if (tempoRestante <= 0) {
                    contador.remove();
                    campos.forEach((campo) => campo.disabled = false);
                    formLogin.submit();
                    return;
                }

                tempoRestante--;
            };

            atualizarContador();
            setInterval(atualizarContador, 1000);
        }
    </script>
</body>
</html>
