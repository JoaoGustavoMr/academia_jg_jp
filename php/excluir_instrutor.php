<?php
require_once 'conexao.php';

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];

    // Primeiro, remover a referência ao instrutor na tabela 'usuarios'
    $sql_atualizar_usuarios = "UPDATE usuarios SET instrutor_cod = NULL WHERE instrutor_cod = ?";
    $stmt_usuarios = $conexao->prepare($sql_atualizar_usuarios);
    $stmt_usuarios->bind_param('i', $id);

    if ($stmt_usuarios->execute()) {
        // Excluir os registros na tabela 'aula' que têm referência ao instrutor
        $sql_excluir_aula = "DELETE FROM aula WHERE fk_instrutor_cod = ?";
        $stmt_aula = $conexao->prepare($sql_excluir_aula);
        $stmt_aula->bind_param('i', $id);

        if ($stmt_aula->execute()) {
            // Excluir o instrutor da tabela 'instrutor'
            $sql_excluir_instrutor = "DELETE FROM instrutor WHERE instrutor_cod = ?";
            $stmt_instrutor = $conexao->prepare($sql_excluir_instrutor);
            $stmt_instrutor->bind_param('i', $id);

            if ($stmt_instrutor->execute()) {
                // Redireciona após a exclusão bem-sucedida
                header('Location: gerenciarinstrutores.php');
                exit();
            } else {
                echo "Erro ao excluir o instrutor da tabela 'instrutor'.";
            }
        } else {
            echo "Erro ao excluir os registros da tabela 'aula'.";
        }
    } else {
        echo "Erro ao remover a referência ao instrutor na tabela 'usuarios'.";
    }
}
?>
