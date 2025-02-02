<?php
require_once 'conexao.php';

if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];

    $sql_editar = "UPDATE instrutor SET instrutor_nome = ?, instrutor_especialidade = ? WHERE instrutor_cod = ?";
    $stmt = $conexao->prepare($sql_editar);
    $stmt->bind_param('ssi', $nome, $especialidade, $id);

    if ($stmt->execute()) {
        // Redireciona de volta após a edição bem-sucedida
        header('Location: gerenciarinstrutores.php');
        exit();
    } else {
        echo "Erro ao editar o instrutor.";
    }
}
?>
