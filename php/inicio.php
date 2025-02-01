<?php
include_once('conexao.php');
session_start();

if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/inicio.css">
    <script src="../js/inicio.js"></script>
    <title>Início</title>
</head>
<body>
    <header>
        <nav>
            <a href="inicio.php"><img id="logo-acad" src="../img/logo-sem-fundo.png" alt=""></a>
            <ul>
                <li><a href="inicio.php">Início</a></li>
                <li><a href="aulas.php">Minhas aulas</a></li>
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
            <h1>Bem-vindo à Alpha Gym - Onde sua evolução começa!</h1>
            <p>Na Alpha Gym, acreditamos que cada treino é um passo rumo à sua melhor versão. Com estrutura moderna, equipamentos de ponta e uma equipe dedicada, oferecemos o ambiente ideal para você superar desafios e alcançar seus objetivos. Seja para ganhar força, definir o corpo ou melhorar sua saúde, aqui você encontra tudo o que precisa.
            <br><br>Seu limite é apenas o começo. <b>Treine na Alpha Gym!</b></p>
        </div>
        <img id="img-acad" src="../img/academia.webp" alt="">
    </main>
    <footer>
        <h2>Desenvolvido por:</h2>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo Mota Ramos</a>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro da Cunha Machado</a>
    </footer>
</body>
</html>