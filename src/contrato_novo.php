<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $obj = $_POST['objeto'];
    $detalhamento = $_POST['detalhamento'];
    $el_item = $_POST['el_item'];
    $valor_anual_estimado = str_replace(',', '.', str_replace('R$', '', $_POST['valor_anual_estimado']));
    $valor_ppag = str_replace(',', '.', str_replace('R$', '', $_POST['valor_ppag']));
    $valor_empenhado = str_replace(',', '.', str_replace('R$', '', $_POST['valor_empenhado']));
    $nr_contrato = $_POST['nr_contrato'];
    $meses = $_POST['meses'];
    $servico_continuado = isset($_POST['servico_continuado']) ? 1 : 0;
    $anos_limite_contratual = $_POST['anos_limite_contratual'];
    $data_inicio = $_POST['data_inicio'];
    $data_final = $_POST['data_final'];
    $empenho = isset($_POST['empenho']) ? 1 : 0;
    $liquidacao = isset($_POST['liquidacao']) ? 1 : 0;
    $nr_termo_aditivo = $_POST['nr_termo_aditivo'];
    $quantidade = $_POST['quantidade'];
    $distribuicao = $_POST['distribuicao'];
    $processo_sei_sdts = $_POST['processo_sei_sdts'];
    $processo_sei_csm = $_POST['processo_sei_csm'];
    $status_aditamento = $_POST['status_aditamento'];
    $situacao_aditamento_ano_corrente = $_POST['situacao_aditamento_ano_corrente'];
    $razao_social = $_POST['razao_social'];
    $email = $_POST['email'];
    $responsavel = $_POST['responsavel'];
    $telefone = $_POST['telefone'];
    $situacao = $_POST['situacao'];
    $observacoes = $_POST['observacoes'];

    $stmt = $conn->prepare("
        INSERT INTO contratos 
        (objeto, detalhamento, el_item, valor_anual_estimado, valor_ppag, valor_empenhado, nr_contrato, meses, servico_continuado, anos_limite_contratual, data_inicio, data_final, empenho, liquidacao, nr_termo_aditivo, quantidade, distribuicao, processo_sei_sdts, processo_sei_csm, status_aditamento, situacao_aditamento_ano_corrente, razao_social, email, responsavel, telefone, situacao, observacoes)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssdddsiiiiissiidssssssssss",
        $obj,
        $detalhamento,
        $el_item,
        $valor_anual_estimado,
        $valor_ppag,
        $valor_empenhado,
        $nr_contrato,
        $meses,
        $servico_continuado,
        $anos_limite_contratual,
        $data_inicio,
        $data_final,
        $empenho,
        $liquidacao,
        $nr_termo_aditivo,
        $quantidade,
        $distribuicao,
        $processo_sei_sdts,
        $processo_sei_csm,
        $status_aditamento,
        $situacao_aditamento_ano_corrente,
        $razao_social,
        $email,
        $responsavel,
        $telefone,
        $situacao,
        $observacoes
    );
    $stmt->execute();
    header("Location: contratos.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Novo Contrato</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen font-sans">
<div class="flex h-full">
    <div class="w-64 bg-gray-900 text-white">
        <div class="text-center text-xl font-bold p-6 border-b border-gray-700">Gestor</div>
        <nav class="p-4 space-y-2">
            <a href="index.php" class="block px-4 py-2 rounded hover:bg-gray-700">🏠 Início</a>
            <a href="contratos.php" class="block px-4 py-2 rounded hover:bg-gray-700">📄 Contratos</a>
        </nav>
    </div>

    <div class="flex-1 p-10 overflow-auto">
        <h2 class="text-2xl font-bold mb-6">Adicionar Novo Contrato</h2>

        <form method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded space-y-6 max-w-4xl">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold">Objeto</label>
                    <input type="text" name="objeto" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Detalhamento / Razão Social</label>
                    <input type="text" name="detalhamento" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold">EL Item</label>
                    <input type="number" name="el_item" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Valor Anual Estimado</label>
                    <input type="text" name="valor_anual_estimado" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Valor PPAG</label>
                    <input type="text" name="valor_ppag" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Valor Empenhado</label>
                    <input type="text" name="valor_empenhado" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Nº Contrato</label>
                    <input type="text" name="nr_contrato" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Meses de Vigência</label>
                    <input type="number" name="meses" class="w-full border rounded p-2" required>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="servico_continuado" class="w-5 h-5">
                    <label class="block text-sm font-semibold">Serviço Continuado</label>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Anos Limite Contratual</label>
                    <input type="number" name="anos_limite_contratual" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Data Início</label>
                    <input type="date" name="data_inicio" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Data Final</label>
                    <input type="date" name="data_final" class="w-full border rounded p-2" required>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="empenho" class="w-5 h-5">
                    <label class="block text-sm font-semibold">Empenho</label>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="liquidacao" class="w-5 h-5">
                    <label class="block text-sm font-semibold">Liquidação</label>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Nº Termo Aditivo</label>
                    <input type="number" name="nr_termo_aditivo" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Quantidade</label>
                    <input type="text" name="quantidade" class="w-full border rounded p-2">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold">Distribuição</label>
                    <textarea name="distribuicao" class="w-full border rounded p-2" rows="2"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Processo SEI SDTS</label>
                    <input type="text" name="processo_sei_sdts" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Processo SEI CSM</label>
                    <input type="text" name="processo_sei_csm" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Status Aditamento</label>
                    <select name="status_aditamento" class="w-full border rounded p-2">
                        <option value="Contratado">Contratado</option>
                        <option value="Em contratação">Em contratação</option>
                        <option value="Em renovação">Em renovação</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Situação Aditamento no Ano</label>
                    <select name="situacao_aditamento_ano_corrente" class="w-full border rounded p-2">
                        <option value="Não se aplica">Não se aplica</option>
                        <option value="Não iniciado">Não iniciado</option>
                        <option value="Montagem do processo">Montagem do processo</option>
                        <option value="NFC">NFC</option>
                        <option value="Núcleo Jurídico">Núcleo Jurídico</option>
                        <option value="Ajustes finais">Ajustes finais</option>
                        <option value="Assinado e publicado">Assinado e publicado</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Razão Social</label>
                    <input type="text" name="razao_social" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Responsável</label>
                    <input type="text" name="responsavel" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm font-semibold">Telefone</label>
                    <input type="text" name="telefone" class="w-full border rounded p-2">
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold">Situação</label>
                    <select name="situacao" class="w-full border rounded p-2" required>
                        <option value="Contratado">Contratado</option>
                        <option value="Em Contratação">Em Contratação</option>
                        <option value="Encerrado">Encerrado</option>
                        <option value="Suspenso">Suspenso</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold">Observações</label>
                    <textarea name="observacoes" class="w-full border rounded p-2" rows="4"></textarea>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold">Arquivo (PDF)</label>
                    <input type="file" name="arquivo" class="block mt-1">
                </div>
            </div>

            <div class="flex justify-between pt-6">
                <a href="contratos.php" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-sm">Cancelar</a>
                <button type="submit" class="px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Salvar Contrato</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
