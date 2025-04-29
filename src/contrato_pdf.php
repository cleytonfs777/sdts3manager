<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'conexao.php';

$id = $_GET['id'] ?? null;
if (!$id) die("ID inválido");

$contrato = $conn->query("SELECT * FROM contratos WHERE id = $id")->fetch_assoc();

$mpdf = new \Mpdf\Mpdf();
$html = "
<h1>Contrato nº {$contrato['nr_contrato']}</h1>
<p><strong>Objeto:</strong> {$contrato['objeto']}</p>
<p><strong>Razão Social:</strong> {$contrato['detalhamento']}</p>
<p><strong>Período:</strong> {$contrato['data_inicio']} a {$contrato['data_final']}</p>
";

$mpdf->WriteHTML($html);
$mpdf->Output("Contrato-{$contrato['nr_contrato']}.pdf", 'I');
