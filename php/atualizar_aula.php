<?php
include_once('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nova_data = $_POST['nova_data'];

    $sql = "UPDATE aula SET aula_data = ? WHERE aula_cod = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("si", $nova_data, $id);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false]);
    }

    $stmt->close();
    $conexao->close();
}
?>
