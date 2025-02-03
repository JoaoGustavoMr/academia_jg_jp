<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['excluir'];

    // Primeiro, exclui os registros na tabela 'aula' que dependem do aluno
    $sql_aula = "DELETE FROM aula WHERE fk_aluno_cod = ?";
    $stmt_aula = $conexao->prepare($sql_aula);
    $stmt_aula->bind_param('i', $id);

    if ($stmt_aula->execute()) {
        // Agora, exclui o registro na tabela 'usuarios' que faz referência ao aluno
        $sql_usuarios = "DELETE FROM usuarios WHERE aluno_cod = ?";
        $stmt_usuarios = $conexao->prepare($sql_usuarios);
        $stmt_usuarios->bind_param('i', $id);

        if ($stmt_usuarios->execute()) {
            // Agora, exclui o aluno da tabela 'aluno'
            $sql_aluno = "DELETE FROM aluno WHERE aluno_cod = ?";
            $stmt_aluno = $conexao->prepare($sql_aluno);
            $stmt_aluno->bind_param('i', $id);

            if ($stmt_aluno->execute()) {
                // Redireciona após a exclusão bem-sucedida
                header('Location: gerenciaralunos.php');
                exit();
            } else {
                echo 'Erro ao excluir o aluno na tabela aluno.';
            }
        } else {
            echo 'Erro ao excluir o registro na tabela usuarios.';
        }
    } else {
        echo 'Erro ao excluir os registros na tabela aula.';
    }
}
?>

