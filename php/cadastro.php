<?php
include_once('conexao.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_cadastro = $_POST['aluno_nome'];
    $email_cadastro = $_POST['aluno_email'];
    $senha_cadastro = $_POST['aluno_senha'];
    $telefone_cadastro = $_POST['aluno_telefone'];
    $cpf_cadastro = $_POST['aluno_cpf'];
    $endereco_cadastro = $_POST['aluno_endereco'];

    $sql1 = "INSERT INTO aluno (aluno_nome, aluno_email, aluno_senha, aluno_telefone, aluno_cpf, aluno_endereco) 
             VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql1);
    $stmt->bind_param('ssssss', $nome_cadastro, $email_cadastro, $senha_cadastro, $telefone_cadastro, $cpf_cadastro, $endereco_cadastro);
    $stmt->execute();

    $aluno_cod = $conexao->insert_id;

    $sql2 = "INSERT INTO usuarios (email, senha, aluno_cod) 
             VALUES (?, ?, ?)";
    $stmt2 = $conexao->prepare($sql2);
    $stmt2->bind_param('sss', $email_cadastro, $senha_cadastro, $aluno_cod);
    $stmt2->execute();

    if ($stmt->affected_rows > 0 && $stmt2->affected_rows > 0) {
        $cadastro_sucesso = true;
    } else {
        $cadastro_sucesso = false;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadastro.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Cadastro</title>
</head>
<body>
    <main>
        <form action="" method="POST" id="cadastro">
            <a href="javascript:window.history.back()" class="back-link">← Voltar</a>
            
            <img src="../img/logo-sem-fundo.png" alt="Logo">
            <h1>Cadastre-Se</h1>

            <label for="nome">Nome</label>
            <input type="text" name="aluno_nome" id="nome" required placeholder="Nome completo" autocomplete="name">

            <label for="email">Email</label>
            <input type="email" name="aluno_email" id="email" required placeholder="Email" autocomplete="email">

            <label for="senha">Senha</label>
            <input type="password" name="aluno_senha" id="senha" required placeholder="Senha" autocomplete="new-password">

            <label for="telefone">Telefone</label>
            <input type="tel" name="aluno_telefone" id="telefone" required placeholder="Telefone" autocomplete="tel">

            <label for="cpf">CPF</label>
            <input type="text" name="aluno_cpf" id="cpf" required placeholder="CPF" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" title="Formato: 000.000.000-00">

            <label for="endereco">Endereço</label>
            <input type="text" name="aluno_endereco" id="endereco" required placeholder="Endereço" autocomplete="address-line1">

            <button type="submit" id="login-button">Cadastrar</button>
        </form>
    </main>
    <script>
        <?php if (isset($cadastro_sucesso) && $cadastro_sucesso): ?>
            Swal.fire({
                icon: 'success',
                title: 'Usuário cadastrado com sucesso!',
                text: 'Você será redirecionado para a página de login.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php";
                }
            });
        <?php elseif (isset($cadastro_sucesso) && !$cadastro_sucesso): ?>
            Swal.fire({
                icon: 'error',
                title: 'Erro no cadastro!',
                text: 'Ocorreu um erro ao cadastrar o usuário. Tente novamente.',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
</body>
</html>
