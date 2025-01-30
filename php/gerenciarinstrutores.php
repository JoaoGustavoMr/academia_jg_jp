<?php
session_start();
require_once 'conexao.php'; 

$sql_instrutor = 'SELECT * FROM instrutor';
$resultado_instrutor = $conexao->query($sql_instrutor);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/gerenciarinstrutores.css">
    <script src="../js/dashboard.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <title>Instrutores</title>
</head>
<body>
    <main>
        <div id="container">
            <div class="tabela-container">
                <div class="tabela">
                    <h3>Instrutores Cadastrados</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Nome do Usuário</th>
                                <th>Especialidade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($resultado_instrutor->num_rows > 0) {
                                while ($linha = $resultado_instrutor->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($linha['instrutor_nome']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['instrutor_especialidade']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='2'>Nenhum usuário encontrado.</td></tr>";
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
