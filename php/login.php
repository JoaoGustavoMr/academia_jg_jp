<?php
include_once('conexao.php');
session_start();

$login_erro = false;
$logado_sucesso = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email_login']) && !empty($_POST['senha_login'])) {
        
        $email_digitado = trim($_POST['email_login']);
        $senha_digitado = trim($_POST['senha_login']);

        // Busca o usuário pelo email
        $sql2 = "SELECT * FROM usuarios WHERE email = ?";
        $stmt2 = $conexao->prepare($sql2);

        if (!$stmt2) {
            die("Erro na preparação da query: " . $conexao->error);
        }

        $stmt2->bind_param("s", $email_digitado);
        
        if (!$stmt2->execute()) {
            die("Erro na execução da query: " . $stmt2->error);
        }

        $result2 = $stmt2->get_result();

        if ($result2->num_rows === 1) {
            $usuario_logado = $result2->fetch_assoc();

            // Verifica a senha
            if ($senha_digitado === $usuario_logado['senha']) {
                $_SESSION['email_sessao'] = $usuario_logado['email'];
                $_SESSION['id_sessao'] = $usuario_logado['id_usuario']; //isso tá correto, é reconhecido
                $_SESSION['tipo_usuario'] = $usuario_logado['tipo_usuario']; //também correto, exibiu aluno
                $_SESSION['id_tipo'] = "";
                // var_dump($usuario_logado['tipo_usuario']);
                var_dump($usuario_logado['id_usuario']);

                // $_SESSION['tipo_usuario'] = $usuario_logado['tipo_usuario'];  id tipo aqui?

                // Verifica se o usuário é instrutor ou aluno e armazena o ID correto
                if ($usuario_logado['tipo_usuario'] === 'Instrutor') {
                    $sql_instrutor = "SELECT instrutor_cod FROM instrutor WHERE fk_usuario_id = ?"; //selecionar id de instrutor de quem tem o id de usuario da conta logada
                    $stmt_instrutor = $conexao->prepare($sql_instrutor);
                    $stmt_instrutor->bind_param("i", $usuario_logado['id_usuario']);
                    $stmt_instrutor->execute();
                    $result_instrutor = $stmt_instrutor->get_result();

                    var_dump($result_instrutor->num_rows);
                    
                    if ($result_instrutor->num_rows === 1) { //nao ta entrando nesse if?
                        $dados_instrutor = $result_instrutor->fetch_assoc();
                        $_SESSION['id_tipo'] = $dados_instrutor['instrutor_cod']; // ID correto do instrutor
                    }
                } elseif ($usuario_logado['tipo_usuario'] === 'Aluno') {
                    $sql_aluno = "SELECT aluno_cod FROM aluno WHERE fk_usuario_id = ?";
                    $stmt_aluno = $conexao->prepare($sql_aluno);
                    $stmt_aluno->bind_param("i", $usuario_logado['id_usuario']);
                    $stmt_aluno->execute();
                    $result_aluno = $stmt_aluno->get_result();

                    var_dump($result_aluno->num_rows);
                    
                    if ($result_aluno->num_rows === 1) {  //nao ta entrando nesse if?
                        $dados_aluno = $result_aluno->fetch_assoc();
                        $_SESSION['id_tipo'] = $dados_aluno['aluno_cod']; // ID correto do aluno
                    }
                }

                $logado_sucesso = true;
                header('Location: inicio.php');
                exit();
            } else {
                $login_erro = true;
            }
        } else {
            $login_erro = true;
        }
    } else {
        $login_erro = true;
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
                text: 'Você será redirecionado para a página inicial.',
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
