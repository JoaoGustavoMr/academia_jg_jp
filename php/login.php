<?php
include_once('conexao.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
</head>
<body>
    <main>
        <form action="" method="POST" id="login">
            <h1>Login</h1>
            <label for="email">Email</label>
            <input type="email" name="email_login" id="email" required placeholder="Email">
            <label for="senha">Senha</label>
            <input type="password" name="senha_login" id="senha" required placeholder="Senha">
            <p>Não tem uma conta?<a href="cadastro.php">Cadastrar</a></p>
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
                    window.location.href = "dashboard.php";
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