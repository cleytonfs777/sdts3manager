<?php
// Conecta ao banco de dados
include 'conexao.php';

// Consulta todos os contratos
$result = $conn->query("SELECT * FROM contratos");

// Prepara array de resposta
$contratos = [];

// Itera sobre os resultados
while ($row = $result->fetch_assoc()) {
    // Garante que o campo 'situacao' existe e está preenchido
    $row['situacao'] = $row['situacao'] ?? "Não informado";

    // Calcula automaticamente se o contrato está vigente ou expirado
    $dataFinal = strtotime($row['data_final']);
    $hoje = time();
    $row['vigencia'] = ($dataFinal >= $hoje) ? "Vigente" : "Expirado";

    // Adiciona o contrato ao array final
    $contratos[] = $row;
}

// Define o cabeçalho de resposta como JSON
header('Content-Type: application/json');

// Retorna os contratos como JSON
echo json_encode($contratos);
