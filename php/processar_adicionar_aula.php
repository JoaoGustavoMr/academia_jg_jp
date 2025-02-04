<?php
include_once('conexao.php');
session_start();

// Verifica se é um instrutor
if (!isset($_SESSION['id_sessao']) || $_SESSION['tipo_usuario'] != "Instrutor") {
    die("Acesso negado.");
}

// Pegando os dados do formulário
$data = $_POST['data'];
$tipo = $_POST['tipo'];
$aluno = $_POST['aluno'];
$instrutor = $_SESSION['id_sessao'];

// Buscar o código do instrutor
$sql_instrutor = "SELECT instrutor_cod FROM instrutor WHERE fk_usuario_id = ?";
$stmt_instrutor = $conexao->prepare($sql_instrutor);
$stmt_instrutor->bind_param("i", $instrutor);
$stmt_instrutor->execute();
$result_instrutor = $stmt_instrutor->get_result();
$row_instrutor = $result_instrutor->fetch_assoc();
$instrutor_cod = $row_instrutor['instrutor_cod'];

// Inserir a nova aula
$sql = "INSERT INTO aula (aula_data, aula_tipo, fk_instrutor_cod, fk_aluno_cod) VALUES (?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("ssii", $data, $tipo, $instrutor_cod, $aluno);

if ($stmt->execute()) {
    echo "<script>alert('Aula adicionada com sucesso!'); window.location.href='aulas.php';</script>";
} else {
    echo "<script>alert('Erro ao adicionar aula!'); window.location.href='aulas.php';</script>";
}
?>
