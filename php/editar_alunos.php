<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];

    // Primeiro, buscamos os dados atuais do aluno
    $sql_select = "SELECT aluno_cpf, aluno_email FROM aluno WHERE aluno_cod = ?";
    $stmt_select = $conexao->prepare($sql_select);
    $stmt_select->bind_param('i', $id);
    $stmt_select->execute();
    $resultado = $stmt_select->get_result();

    if ($resultado->num_rows > 0) {
        $aluno = $resultado->fetch_assoc();
        $cpf = $aluno['aluno_cpf'];
        $email = $aluno['aluno_email'];

        // Agora atualizamos apenas os campos editáveis
        $sql_update = "UPDATE aluno SET aluno_nome = ?, aluno_endereco = ?, aluno_telefone = ? WHERE aluno_cod = ?";
        $stmt_update = $conexao->prepare($sql_update);
        $stmt_update->bind_param('sssi', $nome, $endereco, $telefone, $id);

        if ($stmt_update->execute()) {
            // Redireciona após a atualização bem-sucedida
            header('Location: gerenciaralunos.php');
            exit();
        } else {
            echo 'Erro ao atualizar aluno: ' . $stmt_update->error;
        }
    } else {
        echo 'Erro: Aluno não encontrado.';
    }
}
?>
