<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}

$id_usuario = $_SESSION['id_sessao']; 

$sql_instrutor = 'SELECT * FROM instrutor';
$resultado_instrutor = $conexao->query($sql_instrutor);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/gerenciarinstrutores.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <title>Instrutores</title>
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
                <a href="logout.php">
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
        <h1>Conheça Nossos Instrutores - <br> A Equipe Que Vai Transformar Seu Treino!</h1>
        <p>Na Alpha Gym, contamos com uma equipe de instrutores altamente qualificados e apaixonados por ajudar você a alcançar seus objetivos. Com experiência em diversas modalidades, nossos profissionais estão prontos para oferecer treinos personalizados, dicas especializadas e todo o suporte necessário para sua evolução.   
        <br><br>Independente do seu nível, aqui você treina com os melhores. <b>Venha conhecer nossos instrutores e evoluir com a Alpha Gym!<i class="bi bi-barbell"></i></b></p>
    </div>

    <div id="container">
        <h3 id="titulo_tabela">Instrutores Cadastrados</h3>
        <div class="tabela-container">
            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>Nome do Usuário</th>
                            <th>Especialidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado_instrutor->num_rows > 0) {
                            while ($linha = $resultado_instrutor->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($linha['instrutor_nome']) . "</td>";
                                echo "<td>" . htmlspecialchars($linha['instrutor_especialidade']) . "</td>";
                                if ($_SESSION['tipo_usuario'] == 'Instrutor') {
                                echo "<td>
                                        <button class='editar-btn' data-id='" . $linha['instrutor_cod'] . "' data-nome='" . htmlspecialchars($linha['instrutor_nome']) . "' data-especialidade='" . htmlspecialchars($linha['instrutor_especialidade']) . "'>Editar</button>
                                        <button class='excluir-btn' data-id='" . $linha['instrutor_cod'] . "'>Excluir</button>
                                      </td>";
                                } else {
                                        echo "<td>Sem permissão</td>";
                                   }
                            }
                        } else {
                            echo "<tr><td colspan='3'>Nenhum usuário encontrado.</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="container2">
    <div id="instrutores">
        <img src="../img/instrutor.jpg" alt="">
    </div>
    <div id="textos">
        <h1>O Guia Perfeito Para Sua Jornada Fitness!</h1>
        <p>Por trás de cada conquista existe um mentor dedicado. Nossos instrutores não apenas montam treinos, eles acompanham sua evolução, corrigem movimentos e te motivam a ir além.   
        <br><br>Com uma abordagem personalizada e focada nos seus objetivos, eles fazem toda a diferença na sua rotina. <b>Treine com quem entende e alcance resultados reais na Alpha Gym! <i class="bi bi-barbell"></i></b></p>
    </div> 
</div>

<div id="modal-editar" class="modal">
    <div class="modal-content">
        <span class="close" id="fechar-editar">&times;</span>
        <h3>Editar Instrutor</h3>
        <form method="POST" action="editar_instrutor.php">
            <input type="hidden" name="id" id="id-editar">
            <label for="nome-editar">Nome:</label>
            <input type="text" name="nome" id="nome-editar" required>
            
            <label for="especialidade-editar">Especialidade:</label>
            <select name="especialidade" id="especialidade-editar" required>
                <option value="Yoga">Yoga</option>
                <option value="Musculação">Musculação</option>
                <option value="Crossfit">Crossfit</option>
                <option value="Zumba">Zumba</option>
                <option value="Calistenia">Calistenia</option>
            </select>

            <button type="submit" name="editar">Salvar</button>
        </form>
    </div>
</div>

<footer>
    <h2>Desenvolvido por:</h2>
    <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo Mota Ramos</a>
    <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro da Cunha Machado</a>
</footer>

<script>
    document.querySelectorAll('.editar-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.target.getAttribute('data-id');
            const nome = e.target.getAttribute('data-nome');
            const especialidade = e.target.getAttribute('data-especialidade');
            
            document.getElementById('id-editar').value = id;
            document.getElementById('nome-editar').value = nome;
            document.getElementById('especialidade-editar').value = especialidade;
            
            document.getElementById('modal-editar').style.display = 'block';
        });
    });

    document.querySelectorAll('.excluir-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.target.getAttribute('data-id');
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não pode ser desfeita.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'excluir_instrutor.php?excluir=' + id;
                }
            });
        });
    });

    document.getElementById('fechar-editar').addEventListener('click', () => {
        document.getElementById('modal-editar').style.display = 'none';
    });

    window.onclick = (event) => {
        if (event.target == document.getElementById('modal-editar')) {
            document.getElementById('modal-editar').style.display = 'none';
        }
    }
</script>
</body>
</html>
