<?php
include 'conexao.php';
$res = $conn->query("
    SELECT nr_contrato, DATEDIFF(data_final, CURDATE()) AS dias_restantes 
    FROM contratos 
    WHERE DATEDIFF(data_final, CURDATE()) BETWEEN 0 AND 10
");
$msg = "";
while ($row = $res->fetch_assoc()) {
    $msg .= "⚠️ Contrato nº {$row['nr_contrato']} vence em {$row['dias_restantes']} dias!\n";
}
echo $msg;
