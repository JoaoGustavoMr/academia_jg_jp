<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['email_sessao']) || $_SESSION['tipo_usuario'] != 'Instrutor') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['criar'])) {
    // Recebe os dados do formulário
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Insere o usuário na tabela 'usuarios'
    $sql_usuario = "INSERT INTO usuarios (email, senha, tipo_usuario) VALUES (?, ?, 'Instrutor')";
    $stmt_usuario = $conexao->prepare($sql_usuario);
    $stmt_usuario->bind_param("ss", $email, $senha);
    $stmt_usuario->execute();

    // Pega o código do usuário inserido (id_usuario)
    $usuario_cod = $conexao->insert_id;

    // Insere o instrutor na tabela 'instrutor', agora vinculando o 'fk_usuario_id'
    $sql_instrutor = "INSERT INTO instrutor (instrutor_nome, instrutor_especialidade, fk_usuario_id) VALUES (?, ?, ?)";
    $stmt_instrutor = $conexao->prepare($sql_instrutor);
    $stmt_instrutor->bind_param("ssi", $nome, $especialidade, $usuario_cod);
    $stmt_instrutor->execute();

    // Verifica se a inserção foi bem-sucedida
    if ($stmt_instrutor->affected_rows > 0 && $stmt_usuario->affected_rows > 0) {
        $cadastro_sucesso = true;
        // Redireciona para a página de gerenciar instrutores
        header('Location: gerenciarinstrutores.php');
        exit();
    } else {
        $cadastro_sucesso = false;
        echo "Erro ao criar instrutor: " . $stmt_instrutor->error . " - " . $stmt_usuario->error;
    }
}
?>
