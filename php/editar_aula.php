<?php
include_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aula_id = $_POST['aula_id'];
    $data = $_POST['data'];
    $tipo = $_POST['tipo'];
    $instrutor = $_POST['instrutor'];
    $aluno = $_POST['aluno'];

    // Buscar IDs do instrutor e aluno pelo nome
    $sql_instrutor = "SELECT instrutor_cod FROM instrutor WHERE instrutor_nome = ?";
    $sql_aluno = "SELECT aluno_cod FROM aluno WHERE aluno_nome = ?";

    $stmt_instrutor = $conexao->prepare($sql_instrutor);
    $stmt_instrutor->bind_param("s", $instrutor);
    $stmt_instrutor->execute();
    $result_instrutor = $stmt_instrutor->get_result();
    $instrutor_cod = $result_instrutor->fetch_assoc()['instrutor_cod'];

    $stmt_aluno = $conexao->prepare($sql_aluno);
    $stmt_aluno->bind_param("s", $aluno);
    $stmt_aluno->execute();
    $result_aluno = $stmt_aluno->get_result();
    $aluno_cod = $result_aluno->fetch_assoc()['aluno_cod'];

    if (!$instrutor_cod || !$aluno_cod) {
        echo json_encode(["success" => false, "message" => "Instrutor ou aluno não encontrados."]);
        exit();
    }

    // Atualizar aula no banco de dados
    $sql_update = "UPDATE aula SET aula_data = ?, aula_tipo = ?, fk_instrutor_cod = ?, fk_aluno_cod = ? WHERE aula_cod = ?";
    $stmt = $conexao->prepare($sql_update);
    $stmt->bind_param("ssiii", $data, $tipo, $instrutor_cod, $aluno_cod, $aula_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Erro ao atualizar a aula."]);
    }

    $stmt->close();
    $stmt_instrutor->close();
    $stmt_aluno->close();
    $conexao->close();
}
?>
