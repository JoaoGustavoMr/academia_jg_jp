<?php
include_once('conexao.php');
session_start();

if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_sessao'];

$sql_teste = "SELECT id_usuario FROM usuarios WHERE email = '{$_SESSION['email_sessao']}'";
$resultado_teste = $conexao->query($sql_teste);

if ($resultado_teste->num_rows > 0) {
    $sql_testando = "SELECT a.aula_data, a.aula_tipo, a.aula_cod,
                i.instrutor_nome AS instrutor_nome, 
                al.aluno_nome AS aluno_nome
        FROM aula a
        JOIN instrutor i ON a.fk_instrutor_cod = i.instrutor_cod
        JOIN aluno al ON a.fk_aluno_cod = al.aluno_cod
        WHERE a.fk_aluno_cod = '$id_usuario'
        ORDER BY a.aula_data ASC";
}

$resultado_testando = $conexao->query($sql_testando);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/aulas.css">
    <script src="../js/aulas.js"></script>
    <title>Minhas aulas</title>
</head>
<body>
    <header>
        <nav>
            <a href="inicio.php"><img id="logo-acad" src="../img/logo-sem-fundo.png" alt=""></a>
            <ul>
                <li><a href="inicio.php">Início</a></li>
                <li><a href="">Minhas aulas</a></li>
                <li><a href="">Instrutores</a></li>
                <li><a href="">Alunos</a></li>
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
                        <a href="">
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
            <?php if ($resultado_testando->num_rows > 0) {
                            while ($linha = $resultado_testando->fetch_assoc()) {
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
    </main>
    <footer>
        <h2>Desenvolvido por:</h2>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo Mota Ramos</a>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro da Cunha Machado</a>
    </footer>
</body>
</html>