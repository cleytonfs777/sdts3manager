<?php
$host = 'db'; // nome do serviço MySQL no docker-compose
$usuario = 'usersdts3';
$senha = 'passsdts3';
$banco = 'contratos_db';

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
}
?>
