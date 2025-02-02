<?php
require_once 'conexao.php';

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];

    // Excluir o instrutor da tabela 'usuarios' primeiro
    $sql_excluir_usuarios = "DELETE FROM usuarios WHERE instrutor_cod = ?";
    $stmt_usuarios = $conexao->prepare($sql_excluir_usuarios);
    $stmt_usuarios->bind_param('i', $id);
    $stmt_usuarios->execute(); // Exclui os registros dependentes da tabela usuarios

    // Agora excluir o instrutor da tabela 'instrutor'
    $sql_excluir_instrutor = "DELETE FROM instrutor WHERE instrutor_cod = ?";
    $stmt_instrutor = $conexao->prepare($sql_excluir_instrutor);
    $stmt_instrutor->bind_param('i', $id);

    if ($stmt_instrutor->execute()) {
        // Redireciona após a exclusão bem-sucedida
        header('Location: gerenciarinstrutores.php');
        exit();
    } else {
        echo "Erro ao excluir o instrutor.";
    }
}
?>
