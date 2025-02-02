<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['excluir'];

    // Primeiro, exclui os registros da tabela usuarios que dependem do aluno
    $sql_usuarios = "DELETE FROM usuarios WHERE aluno_cod = ?";
    $stmt_usuarios = $conexao->prepare($sql_usuarios);
    $stmt_usuarios->bind_param('i', $id);
    
    // Se a exclusão na tabela usuarios for bem-sucedida, então exclui o aluno
    if ($stmt_usuarios->execute()) {
        // Excluindo o aluno da tabela aluno
        $sql_aluno = "DELETE FROM aluno WHERE aluno_cod = ?";
        $stmt_aluno = $conexao->prepare($sql_aluno);
        $stmt_aluno->bind_param('i', $id);
        
        if ($stmt_aluno->execute()) {
            // Redireciona após a exclusão bem-sucedida
            header('Location: gerenciaralunos.php');
            exit();
        } else {
            echo 'Erro ao excluir o aluno.';
        }
    } else {
        echo 'Erro ao excluir os registros dependentes.';
    }
}
?>

