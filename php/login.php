<?php
include_once('conexao.php');
session_start();

$login_erro = false;
$logado_sucesso = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email_login']) && isset($_POST['senha_login'])) {

        $email_digitado = $_POST['email_login'];
        $senha_digitado = $_POST['senha_login'];

        $sql2 = "SELECT * FROM usuarios WHERE aluno_email = ?";
        $stmt2 = $conexao->prepare($sql2);
        $stmt2->bind_param("s", $email_digitado);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows == 1) {
            $usuario_logado = $result2->fetch_assoc();

            if (password_verify($senha_digitado, $usuario_logado['aluno_senha'])) {
                $_SESSION['nome_sessao'] = $usuario_logado['aluno_nome'];
                $_SESSION['id_sessao'] = $usuario_logado['id_usuario'];
                $logado_sucesso = true;
                header('location: inicio.php');
                exit();
            } else {
                $login_erro = true;
            }
        } else {
            $login_erro = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
</head>
<body>
    <main>
        <form action="" method="POST" id="login">
            <img src="../img/logo-sem-fundo.png" alt="Logo">
            <h1>Entrar</h1>
            <label for="email">Email</label>
            <input type="email" name="email_login" id="email" required placeholder="Email">
            <label for="senha">Senha</label>
            <input type="password" name="senha_login" id="senha" required placeholder="Senha">
            <p>Não tem uma conta? <a href="cadastro.php">Cadastrar</a></p>
            <button type="submit" id="login-button">Entrar</button>
        </form>
    </main>
    <script>
        <?php if ($logado_sucesso): ?>
            Swal.fire({
                icon: 'success',
                title: 'Usuário logado com sucesso!',
                text: 'Você será redirecionado para a página de dashboard.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "index.php"; 
                }
            });
        <?php elseif ($login_erro): ?>
            Swal.fire({
                icon: 'error',
                title: 'Credenciais erradas',
                text: 'Verifique as informações e tente novamente.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php"; 
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
