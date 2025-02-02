<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}
$id_usuario = $_SESSION['id_sessao']; 

$sql_verifica = "SELECT aluno_cod FROM aluno WHERE aluno_email = '{$_SESSION['email_sessao']}'";
$resultado_verifica = $conexao->query($sql_verifica);

if ($resultado_verifica->num_rows > 0) {
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

// Consulta para obter os alunos
$sql_alunos = "SELECT * FROM aluno"; // Você pode adicionar condições aqui se necessário
$resultado_aluno = $conexao->query($sql_alunos);

// Executar consulta para as aulas
$resultado_aulas = $conexao->query($sql_aulas);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/gerenciaralunos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <title>Alunos</title>
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
        <h1>Nosso Time de Alunos<br></h1>
        <h2>A Força Que Move a Alpha Gym para um ambiente de sucesso e aprendizado!</h2>
    </div>
    <div id="container">
        <div class="tabela-container">
            <div class="tabela">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Endereço</th>
                            <th>Telefone</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado_aluno->num_rows > 0) {
                            while ($linha = $resultado_aluno->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($linha['aluno_nome']) . "</td>";
                                echo "<td>" . htmlspecialchars($linha['aluno_cpf']) . "</td>";
                                echo "<td>" . htmlspecialchars($linha['aluno_endereco']) . "</td>";
                                echo "<td>" . htmlspecialchars($linha['aluno_telefone']) . "</td>";
                                echo "<td>" . htmlspecialchars($linha['aluno_email']) . "</td>";
                                echo "<td>
                                        <button class='editar-btn' data-id='" . $linha['aluno_cod'] . "' data-nome='" . htmlspecialchars($linha['aluno_nome']) . "' data-cpf='" . htmlspecialchars($linha['aluno_cpf']) . "' data-endereco='" . htmlspecialchars($linha['aluno_endereco']) . "' data-telefone='" . htmlspecialchars($linha['aluno_telefone']) . "' data-email='" . htmlspecialchars($linha['aluno_email']) . "'>Editar</button>
                                        <button class='excluir-btn' data-id='" . $linha['aluno_cod'] . "'>Excluir</button>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>Nenhum aluno encontrado.</td></tr>";
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<div id="container2">
        <div id="instrutores">
            <img src="../img/alunos_acad.jpg" alt="">
        </div>
        <div id="textos">
    <h1>Transforme Sua Vida na Alpha Gym!</h1>
    <p>Na Alpha Gym, cada aluno é único. Com treinos personalizados e acompanhamento contínuo, ajudamos você a alcançar seus objetivos, seja para ganhar força, perder peso ou melhorar sua saúde. 
    <br><br>Venha descobrir seu potencial e evoluir com a gente, em um ambiente motivador e acolhedor. <b>Junte-se à nossa comunidade e alcance resultados reais! <i class="bi bi-person"></i></b></p>
</div>

<!-- Modal de Editar -->
<div id="modal-editar" class="modal">
    <div class="modal-content">
        <span class="close" id="fechar-editar">&times;</span>
        <h3>Editar Aluno</h3>
        <form method="POST" action="editar_alunos.php">
            <input type="hidden" name="id" id="id-editar">
            <label for="nome-editar">Nome:</label>
            <input type="text" name="nome" id="nome-editar" required>
            
            <label for="cpf-editar">CPF:</label>
            <input type="text" name="cpf" id="cpf-editar" required>
            
            <label for="endereco-editar">Endereço:</label>
            <input type="text" name="endereco" id="endereco-editar" required>
            
            <label for="telefone-editar">Telefone:</label>
            <input type="text" name="telefone" id="telefone-editar" required>
            
            <label for="email-editar">Email:</label>
            <input type="email" name="email" id="email-editar" required>

            <button type="submit" name="editar">Salvar</button>
        </form>
    </div>
</div>

<!-- Modal de Excluir -->
<div id="modal-excluir" class="modal">
    <div class="modal-content">
        <span class="close" id="fechar-excluir">&times;</span>
        <h3>Tem certeza que deseja excluir este aluno?</h3>
        <form method="GET" action="excluir_alunos.php">
            <input type="hidden" name="excluir" id="id-excluir">
            <button type="submit">Sim, excluir</button>
            <button type="button" id="cancelar-excluir">Cancelar</button>
        </form>
    </div>
</div>

<footer>
    <h2>Desenvolvido por:</h2>
    <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo Mota Ramos</a>
    <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro da Cunha Machado</a>
</footer>

<script>
    // Editar aluno
    document.querySelectorAll('.editar-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const id = e.target.getAttribute('data-id');
            const nome = e.target.getAttribute('data-nome');
            const cpf = e.target.getAttribute('data-cpf');
            const endereco = e.target.getAttribute('data-endereco');
            const telefone = e.target.getAttribute('data-telefone');
            const email = e.target.getAttribute('data-email');

            document.getElementById('id-editar').value = id;
            document.getElementById('nome-editar').value = nome;
            document.getElementById('cpf-editar').value = cpf;
            document.getElementById('endereco-editar').value = endereco;
            document.getElementById('telefone-editar').value = telefone;
            document.getElementById('email-editar').value = email;

            document.getElementById('modal-editar').style.display = 'block';
        });
    });

    document.getElementById('fechar-editar').addEventListener('click', () => {
        document.getElementById('modal-editar').style.display = 'none';
    });

    // Excluir aluno com SweetAlert2
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
                    // Redirecionar para a página de exclusão passando o ID do aluno
                    window.location.href = 'excluir_alunos.php?excluir=' + id;
                }
            });
        });
    });

    // Fechar o modal de exclusão
    document.getElementById('fechar-excluir').addEventListener('click', () => {
        document.getElementById('modal-excluir').style.display = 'none';
    });

    document.getElementById('cancelar-excluir').addEventListener('click', () => {
        document.getElementById('modal-excluir').style.display = 'none';
    });
</script>

</body>
</html>
