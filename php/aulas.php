<?php
include_once('conexao.php');
session_start();

if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_sessao']; 
$tipo_usuario = $_SESSION['tipo_usuario']; 

$resultado_testando = null;

if ($tipo_usuario == 'Aluno') {
    $sql_testando = "SELECT a.aula_data, a.aula_tipo, a.aula_cod,
                            i.instrutor_nome, 
                            al.aluno_nome
                    FROM aula a
                    LEFT JOIN instrutor i ON a.fk_instrutor_cod = i.instrutor_cod
                    LEFT JOIN aluno al ON a.fk_aluno_cod = al.aluno_cod
                    WHERE a.fk_aluno_cod = (SELECT aluno_cod FROM aluno WHERE fk_usuario_id = ?)
                    ORDER BY a.aula_data ASC";
} else if ($tipo_usuario == 'Instrutor') {
    $sql_testando = "SELECT a.aula_data, a.aula_tipo, a.aula_cod,
                            i.instrutor_nome, 
                            al.aluno_nome
                    FROM aula a
                    LEFT JOIN instrutor i ON a.fk_instrutor_cod = i.instrutor_cod
                    LEFT JOIN aluno al ON a.fk_aluno_cod = al.aluno_cod
                    WHERE a.fk_instrutor_cod = (SELECT instrutor_cod FROM instrutor WHERE fk_usuario_id = ?)
                    ORDER BY a.aula_data ASC";
}

if (isset($sql_testando)) {

    $stmt = $conexao->prepare($sql_testando);
    
    if ($stmt === false) {
        echo "Erro ao preparar a consulta SQL.";
        exit();
    }

    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();

    $resultado_testando = $stmt->get_result();
} else {
    echo "Erro na definição da consulta.";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/aulas.css">
    <script src="../js/aulas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Minhas aulas</title>
</head>
<body>
<header>
    <nav>
        <a href="inicio.php"><img id="logo-acad" src="../img/logo-sem-fundo.png" alt=""></a>
        <ul>
            <li><a href="inicio.php">Início</a></li>
            <li><a href="aulas.php">Minhas aulas</a></li>
            <li><a href="gerenciarinstrutores.php">Instrutores</a></li>
            <li><a href="gerenciaralunos.php">Alunos</a></li>
        </ul>

        <?php if (isset($_SESSION['email_sessao'])): ?>
            <div id="perfil-logout">
                <a href="perfil.php">
                    <div id="perfil">
                        <img id="icon-perfil" src="../img/user-vector.png" alt="">
                        <h6><?= $_SESSION['email_sessao'] ?></h6>
                        <h6><?= $_SESSION['tipo_usuario'] ?></h6>
                    </div>
                </a>
                <a href="#" onclick="confirmarSaida();">
                    <div id="logout">
                        <img id="icon-logout" src="../img/logout.png" alt="">
                        <h6>Sair</h6>
                    </div>
                </a>
            </div>
        <?php else: ?>
            <a id="entrar" href="index.php">Entrar</a>
        <?php endif; ?>
    </nav>
</header>
    <main>
        <div id="textos">
            <h1>Minhas aulas</h1>
            <p>Aqui você encontra todas as suas aulas, organizadas para acompanhar seu progresso e atingir seus objetivos. Treine com foco, dedicação e transforme seu desempenho!</p>
        </div>
        <div id="aulas">
    <?php 
    // Verifica se há aulas para exibir
    if ($resultado_testando && $resultado_testando->num_rows > 0) {
        while ($linha = $resultado_testando->fetch_assoc()) { ?>
            <div class='aula'>
                <ul>
                    <li><b>Data: </b> <?= $linha['aula_data'] ?> </li>
                    <li><b>Instrutor: </b> <?= $linha['instrutor_nome'] ?> </li>
                    <li><b>Aluno: </b> <?= $linha['aluno_nome'] ?> </li>
                    <li><b>Tipo da aula: </b> <?= $linha['aula_tipo'] ?> </li>

                    <?php if ($tipo_usuario == "Instrutor") { ?>
                        <li id='botoes'>
                            <button class='editar' data-id='<?= $linha['aula_cod'] ?>'
                                    data-aulaData='<?= $linha['aula_data'] ?>'
                                    data-aulaTipo='<?= $linha['aula_tipo'] ?>'
                                    data-instrutorNome='<?= $linha['instrutor_nome'] ?>'
                                    data-alunoNome='<?= $linha['aluno_nome'] ?>'>Editar</button> 
                            <button class='excluir' data-id='<?= $linha['aula_cod'] ?>'>Excluir</button>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } 
       } else { ?>
          <p id="nenhuma-aula">
            <?php if ($tipo_usuario == "Aluno") { ?>
                Você ainda não está cadastrado em nenhuma aula. Aguarde um instrutor adicioná-lo!
            <?php } else { ?>
                Você ainda não cadastrou nenhuma aula. Comece agora mesmo clicando no botão abaixo!
            <?php } ?>
            </p>
         <?php } ?>

   <!-- O botão de adicionar aula aparecerá sempre para instrutores -->
         <?php if ($tipo_usuario == "Instrutor") { ?>
         <br>
        <button id="adicionar-aula">Adicionar Aula</button>
        <?php } ?>
    
    </main>
    <footer>
        <h2>Desenvolvido por:</h2>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo Mota Ramos</a>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro da Cunha Machado</a>
    </footer>

<!-- Modal de Adicionar Aula -->
<div id="modal-aula" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Adicionar Nova Aula</h2>
        <form id="form-aula" action="processar_adicionar_aula.php" method="POST">
            <label for="data">Data da Aula:</label>
            <input type="date" id="data" name="data" required>

            <?php
                // Buscar a especialidade do instrutor logado
                $sql_especialidade = "SELECT instrutor_especialidade FROM instrutor WHERE fk_usuario_id = ?";
                $stmt_especialidade = $conexao->prepare($sql_especialidade);
                $stmt_especialidade->bind_param("i", $id_usuario);
                $stmt_especialidade->execute();
                $resultado_especialidade = $stmt_especialidade->get_result();
                $especialidade = $resultado_especialidade->fetch_assoc()['instrutor_especialidade'] ?? 'Não definida';
                ?>

                <label for="tipo">Tipo da Aula:</label>
                <input type="text" id="tipo" name="tipo" value="<?= htmlspecialchars($especialidade) ?>" readonly>

            <label for="aluno">Aluno:</label>
            <select id="aluno" name="aluno" required>
                <option value="">Selecione um aluno</option>
                <?php
                $sql_alunos = "SELECT aluno_cod, aluno_nome FROM aluno";
                $resultado_alunos = $conexao->query($sql_alunos);
                while ($aluno = $resultado_alunos->fetch_assoc()) {
                    echo "<option value='{$aluno['aluno_cod']}'>{$aluno['aluno_nome']}</option>";
                }
                ?>
            </select>
            <input type="hidden" name="instrutor" value="<?= $id_usuario ?>"> 

            <button type="submit">Salvar Aula</button>
        </form>
    </div>
</div>
<script>
    var modal = document.getElementById("modal-aula");
    var btn = document.getElementById("adicionar-aula");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    function confirmarSaida() {
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Você quer sair de sua conta?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sair',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona para o logout
                    window.location.href = 'logout.php';
                }
            });
        }
</script>
</body>
</html>
