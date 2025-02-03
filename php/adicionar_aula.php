<?php
include_once('conexao.php');
session_start();

$usuario_id = $_SESSION['usuario_id'];
$tipo_usuario = $_SESSION['tipo_usuario']; // "instrutor" ou "aluno"

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST['dataAula'];

    if ($tipo_usuario == "instrutor") {
        $instrutor_id = $usuario_id;
        $aluno_id = $_POST['aluno'];
        $tipo_aula = $_SESSION['especialidade']; // Pegando a especialidade do instrutor
    } else {
        $aluno_id = $usuario_id;
        $instrutor_id = $_POST['instrutor'];
        $tipo_aula = $_SESSION['especialidade_instrutor']; // Especialidade do instrutor escolhido
    }

    $sql = "INSERT INTO aula (aula_data, instrutor_id, aluno_id, tipo_aula) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("siis", $data, $instrutor_id, $aluno_id, $tipo_aula);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => true]);
    } else {
        echo json_encode(["sucesso" => false]);
    }

    $stmt->close();
    $conexao->close();
}
?>
