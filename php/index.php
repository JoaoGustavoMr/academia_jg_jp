<?php
include_once('conexao.php');
session_start();

if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}

// Obtendo o ID do usuário logado
$id_usuario = $_SESSION['id_sessao']; // Certifique-se de que esse valor está sendo armazenado corretamente na sessão

// Verifica se o usuário é um aluno ou um instrutor
$sql_verifica = "SELECT aluno_cod FROM aluno WHERE aluno_email = '{$_SESSION['email_sessao']}'"; //select id usuario from usuario onde email = email sessao, acho q tem q ser isso
$resultado_verifica = $conexao->query($sql_verifica);

if ($resultado_verifica->num_rows > 0) {
    // Usuário é um aluno
    $sql_aulas = "SELECT a.aula_data, a.aula_tipo, a.aula_cod,
                i.instrutor_nome AS instrutor_nome, 
                al.aluno_nome AS aluno_nome
        FROM aula a
        JOIN instrutor i ON a.fk_instrutor_cod = i.instrutor_cod
        JOIN aluno al ON a.fk_aluno_cod = al.aluno_cod
        WHERE a.fk_aluno_cod = '$id_usuario'
        ORDER BY a.aula_data ASC";
} else {
    // Usuário é um instrutor
    $sql_aulas = "SELECT a.aula_data, a.aula_tipo, a.aula_cod,
                i.instrutor_nome AS instrutor_nome, 
                al.aluno_nome AS aluno_nome
        FROM aula a
        JOIN instrutor i ON a.fk_instrutor_cod = i.instrutor_cod
        JOIN aluno al ON a.fk_aluno_cod = al.aluno_cod
        WHERE a.fk_instrutor_cod = '$id_usuario'
        ORDER BY a.aula_data ASC";
}

$resultado_aulas = $conexao->query($sql_aulas);
?>

<!DOCTYPE html>
<html lang="Pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="stylesheet" href="../css/index.css">
    <script src="../js/index.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</head>

<body>
    <header class="header">
        <div class="container logo-menu">
            <nav class="menu">
                <ul class="nav-list">
                    <li><a href="index.php"><img id="logo_gym"src="../img/logo-sem-fundo.png" alt=""></a></li>
                    <li><a href="index.php">Início</a></li>
                    <li><a href="gerenciaralunos.php">Aluno</a></li>
                    <li><a href="gerenciarinstrutores.php">Instrutor</a></li>

                    <?php if (isset($_SESSION['id_sessao'])): ?>
                        <div class="user-vector">
                            <a href="perfil.php">
                                <img id="logo-vector" src="../img/user-vector.png" alt="">
                                <p>
                                    <?= $_SESSION['email_sessao'] ?>
                                </p>
                            </a>
                        </div>
                        <li><img id="logout" src="../img/logout.png" alt="Logout" style="cursor: pointer;"></li>
                    <?php else: ?>
                        <li><a href="login.php">Entrar</a></li>
                    <?php endif; ?>
                    </a>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <section class="intro">
            <div class="container">
                <h2 class="animated animated-title">Bem-vindo à Alpha Gym – Onde sua evolução começa!</h2>
                <p class="animated animated-subtitle">Na Alpha Gym, acreditamos que cada treino é um passo rumo à sua melhor versão. Com estrutura moderna, equipamentos de ponta e uma equipe dedicada, oferecemos o ambiente ideal para você superar desafios e alcançar seus objetivos. Seja para ganhar força, definir o corpo ou melhorar sua saúde, aqui você encontra tudo o que precisa.
                <br>🚀 Seu limite é apenas o começo. Treine na Alpha Gym!</p>
            </div>
        </section>

        <section>
            <div id="container">
                <h1 id="title-gerenc">Suas aulas</h1>
                <div class="tabela-div">
                    <?php if ($resultado_aulas->num_rows > 0) {
                        while ($linha = $resultado_aulas->fetch_assoc()) {
                            echo "<div>";
                            echo "<ul>";
                            echo "<li>Data: " . $linha['aula_data'] . "</li>";
                            echo "<li>Instrutor: " . $linha['instrutor_nome'] . "</li>";
                            echo "<li>Aluno: " . $linha['aluno_nome'] . "</li>";
                            echo "<li>Tipo da aula: " . $linha['aula_tipo'] . "</li>";
                            echo "<li><button class='editar' data-id='" . $linha['aula_cod'] . "' 
                                  data-aulaData='" . $linha['aula_data'] . "' 
                                  data-aulaTipo='" . $linha['aula_tipo'] . "' 
                                  data-instrutorNome='" . $linha['instrutor_nome'] . "' 
                                  data-alunoNome='" . $linha['aluno_nome'] . "'>Editar</button> 
                                <button class='excluir' data-id='" . $linha['aula_cod'] . "'>Excluir</button></li>";
                            // echo "<li><button class='editar' data-id='" . $linha['id_usuario'] . "', userNome='" . $linha['nome_usuario'] . "', userSobrenome='" . $linha['sobrenome_usuario'] . "', userEmail='" . $linha['email_usuario'] . "'>Editar</button> <button class='excluir' data-id='" . $linha['id_usuario'] . "'>Excluir</button></li>";
                            echo "</ul>";
                            echo "</div>";
                        }
                    } ?>
                </div>
            </div>
        </section>
    </main>


    <footer class="footer">
        <a id="titulo" href="desenvolvedores.php" target="_blank">Desenvolvedores</a>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo Mota Ramos</a>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro da Cunha Machado</a>

        <div class="footer-bottom">
            <p>&copy; 2024 Alpha Gym. Todos os direitos reservados.</p>
        </div>
    </footer>
    <div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Editar Aula</h2>
        <form id="editForm">
            <input type="hidden" id="editAulaId">
            <label for="editData">Data:</label>
            <input type="date" id="editData" required>
            <label for="editTipo">Tipo da Aula:</label>
            <input type="text" id="editTipo" required>
            <label for="editInstrutor">Instrutor:</label>
            <input type="text" id="editInstrutor" required>
            <label for="editAluno">Aluno:</label>
            <input type="text" id="editAluno" required>
            <button type="submit">Salvar</button>
        </form>
    </div>
</div>

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        width: 50%;
        text-align: center;
    }

    .close {
        float: right;
        font-size: 28px;
        cursor: pointer;
    }
</style>

</body>

</html>