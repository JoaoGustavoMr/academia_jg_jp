<?php
session_start();
require_once 'conexao.php';

$sql_instrutor = 'SELECT * FROM instrutor';
$resultado_instrutor = $conexao->query($sql_instrutor);

if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $especialidade = $_POST['especialidade'];

    $sql_editar = "UPDATE instrutor SET instrutor_nome = ?, instrutor_especialidade = ? WHERE instrutor_cod = ?";
    $stmt = $conexao->prepare($sql_editar);
    $stmt->bind_param('ssi', $nome, $especialidade, $id);
    
    if ($stmt->execute()) {
        echo "<script>window.location.href = window.location.href;</script>";
    } 
}

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];

    $sql_excluir_usuarios = "DELETE FROM usuarios WHERE instrutor_cod = ?";
    $stmt_usuarios = $conexao->prepare($sql_excluir_usuarios);
    $stmt_usuarios->bind_param('i', $id);
    $stmt_usuarios->execute();

    $sql_excluir = "DELETE FROM instrutor WHERE instrutor_cod = ?";
    $stmt = $conexao->prepare($sql_excluir);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<script>
                // Após a execução bem-sucedida, recarrega a página
                window.location.reload();
              </script>";
    }
}

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

$resultado_aulas = $conexao->query($sql_aulas);
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
            <h1>Conheça Nossos Instrutores - <br> A Equipe Que Vai Transformar Seu Treino!</h1>
            <p>Na Alpha Gym, contamos com uma equipe de instrutores altamente qualificados e apaixonados por ajudar você a alcançar seus objetivos. Com experiência em diversas modalidades, nossos profissionais estão prontos para oferecer treinos personalizados, dicas especializadas e todo o suporte necessário para sua evolução.   
            <br><br>Independente do seu nível, aqui você treina com os melhores. <b>Venha conhecer nossos instrutores e evoluir com a Alpha Gym!</b></p>
     </div>
        <div id="container">
            <div class="tabela-container">
                <div class="tabela">
                    <h3>Instrutores Cadastrados</h3>
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
                                    echo "<td>
                                            <button class='editar-btn' data-id='" . $linha['instrutor_cod'] . "' data-nome='" . htmlspecialchars($linha['instrutor_nome']) . "' data-especialidade='" . htmlspecialchars($linha['instrutor_especialidade']) . "'>Editar</button>
                                            <button class='excluir-btn' data-id='" . $linha['instrutor_cod'] . "'>Excluir</button>
                                          </td>";
                                    echo "</tr>";
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
<div id="modal-editar" class="modal">
    <div class="modal-content">
        <span class="close" id="fechar-editar">&times;</span>
        <h3>Editar Instrutor</h3>
        <form method="POST">
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


    <div id="modal-excluir" class="modal">
        <div class="modal-content">
            <span class="close" id="fechar-excluir">&times;</span>
            <h3>Tem certeza que deseja excluir este instrutor?</h3>
            <form method="GET">
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
</body>
</html>
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

        document.getElementById('fechar-editar').addEventListener('click', () => {
            document.getElementById('modal-editar').style.display = 'none';
        });

        document.querySelectorAll('.excluir-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = e.target.getAttribute('data-id');
                
                document.getElementById('id-excluir').value = id;
                
                document.getElementById('modal-excluir').style.display = 'block';
            });
        });

        document.getElementById('fechar-excluir').addEventListener('click', () => {
            document.getElementById('modal-excluir').style.display = 'none';
        });

        document.getElementById('cancelar-excluir').addEventListener('click', () => {
            document.getElementById('modal-excluir').style.display = 'none';
        });

        window.onclick = (event) => {
            if (event.target == document.getElementById('modal-editar')) {
                document.getElementById('modal-editar').style.display = 'none';
            }
            if (event.target == document.getElementById('modal-excluir')) {
                document.getElementById('modal-excluir').style.display = 'none';
            }
        }
    </script>
