<?php
session_start();
require_once 'conexao.php';

$sql_aluno = 'SELECT * FROM aluno';
$resultado_aluno = $conexao->query($sql_aluno);

if (isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql_editar = "UPDATE aluno SET aluno_nome = ?, aluno_cpf = ?, aluno_endereco = ?, aluno_telefone = ?, aluno_email = ? WHERE aluno_cod = ?";
    $stmt = $conexao->prepare($sql_editar);
    $stmt->bind_param('sssssi', $nome, $cpf, $endereco, $telefone, $email, $id);
    
    if ($stmt->execute()) {
        echo "<script>window.location.href = window.location.href;</script>";
    } 
}

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];

    // Excluir os registros relacionados na tabela 'usuarios' antes de excluir o aluno
    $sql_excluir_usuarios = "DELETE FROM usuarios WHERE aluno_cod = ?";
    $stmt_usuarios = $conexao->prepare($sql_excluir_usuarios);
    $stmt_usuarios->bind_param('i', $id);
    $stmt_usuarios->execute();
    
    // Agora podemos excluir o aluno
    $sql_excluir = "DELETE FROM aluno WHERE aluno_cod = ?";
    $stmt = $conexao->prepare($sql_excluir);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo "<script>window.location.reload();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/gerenciaralunos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <title>Gerenciar Alunos</title>
</head>
<body>
<?php
if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}

// Obtendo o ID do usuário logado
$id_usuario = $_SESSION['id_sessao']; // Certifique-se de que esse valor está sendo armazenado corretamente na sessão

// Verifica se o usuário é um aluno ou um instrutor
$sql_verifica = "SELECT aluno_cod FROM aluno WHERE aluno_email = '{$_SESSION['email_sessao']}'";
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
        <div id="container">
            <div class="tabela-container">
                <div class="tabela">
                    <h3>Alunos Cadastrados</h3>
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

    <!-- Modal Editar -->
    <div id="modal-editar" class="modal">
        <div class="modal-content">
            <span class="close" id="fechar-editar">&times;</span>
            <h3>Editar Aluno</h3>
            <form method="POST">
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

    <!-- Modal Excluir -->
    <div id="modal-excluir" class="modal">
        <div class="modal-content">
            <span class="close" id="fechar-excluir">&times;</span>
            <h3>Tem certeza que deseja excluir este aluno?</h3>
            <form method="GET">
                <input type="hidden" name="excluir" id="id-excluir">
                <button type="submit">Sim, excluir</button>
                <button type="button" id="cancelar-excluir">Cancelar</button>
            </form>
        </div>
    </div>

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

        // Excluir aluno
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
    </script>
</body>
</html>
