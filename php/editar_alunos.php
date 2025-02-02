<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtendo os dados do formulário
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    // Atualizando o banco de dados
    $sql = "UPDATE aluno SET aluno_nome = ?, aluno_cpf = ?, aluno_endereco = ?, aluno_telefone = ?, aluno_email = ? WHERE aluno_cod = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param('sssssi', $nome, $cpf, $endereco, $telefone, $email, $id);
    
    if ($stmt->execute()) {
        // Redireciona após a atualização bem-sucedida
        header('Location: gerenciaralunos.php');
        exit();
    } else {
        echo 'Erro ao atualizar aluno.';
    }
}
?>
