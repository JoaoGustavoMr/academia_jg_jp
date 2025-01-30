<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'db_academia';

$conexao = new mysqli($hostname, $username, $password, $database);

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}
?>

