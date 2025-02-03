<?php
include_once('conexao.php');
session_start();

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo_usuario'];

$response = [
    "usuario_id" => $usuario_id,
    "nome" => $_SESSION['nome'],
    "tipo_usuario" => $tipo_usuario,
];

if ($tipo_usuario == "Instrutor") {
    $response["especialidade"] = $_SESSION['especialidade'];

    // Buscar todos os alunos
    $sql = "SELECT usuario_id AS id, nome FROM usuarios WHERE tipo = 'Aluno'";
    $result = $conexao->query($sql);
    $response["alunos"] = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Buscar todos os instrutores
    $sql = "SELECT usuario_id AS id, nome, especialidade FROM usuarios WHERE tipo = 'instrutor'";
    $result = $conexao->query($sql);
    $response["instrutores"] = $result->fetch_all(MYSQLI_ASSOC);
}

echo json_encode($response);
?>
