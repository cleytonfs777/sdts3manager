<?php
include 'conexao.php';

$id = $_GET['id'] ?? null;
if (!$id) die("Contrato inválido");

$stmt = $conn->prepare("SELECT * FROM contratos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$dados = $stmt->get_result()->fetch_assoc();

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

    $arquivo = $dados['arquivo'];
    if ($_FILES['arquivo']['name']) {
        $arquivo = 'uploads/' . basename($_FILES['arquivo']['name']);
        move_uploaded_file($_FILES['arquivo']['tmp_name'], $arquivo);
    }

    $stmt = $conn->prepare("
        UPDATE contratos SET
            objeto=?, detalhamento=?, el_item=?, valor_anual_estimado=?, valor_ppag=?, valor_empenhado=?, nr_contrato=?, meses=?, servico_continuado=?, anos_limite_contratual=?, data_inicio=?, data_final=?, empenho=?, liquidacao=?, nr_termo_aditivo=?, quantidade=?, distribuicao=?, processo_sei_sdts=?, processo_sei_csm=?, status_aditamento=?, situacao_aditamento_ano_corrente=?, razao_social=?, email=?, responsavel=?, telefone=?, situacao=?, observacoes=?, arquivo=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "ssdddsiiiiissiidsssssssssssi",
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
        $observacoes,
        $arquivo,
        $id
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
    <title>Editar Contrato</title>
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
        <h2 class="text-2xl font-bold mb-6">Editar Contrato</h2>

    <form method="POST" enctype="multipart/form-data" class="bg-white shadow p-6 rounded space-y-4 max-w-4xl">
    <div class="grid grid-cols-2 gap-4">
        <input type="text" name="objeto" value="<?= $dados['objeto'] ?>" class="w-full border rounded p-2" required>
        <input type="text" name="detalhamento" value="<?= $dados['detalhamento'] ?>" class="w-full border rounded p-2" required>
        <input type="number" name="el_item" value="<?= $dados['el_item'] ?>" class="w-full border rounded p-2" required>
        <input type="text" name="valor_anual_estimado" value="<?= $dados['valor_anual_estimado'] ?>" class="w-full border rounded p-2" required>
        <input type="text" name="valor_ppag" value="<?= $dados['valor_ppag'] ?>" class="w-full border rounded p-2">
        <input type="text" name="valor_empenhado" value="<?= $dados['valor_empenhado'] ?>" class="w-full border rounded p-2">
        <input type="text" name="nr_contrato" value="<?= $dados['nr_contrato'] ?>" class="w-full border rounded p-2" required>
        <input type="number" name="meses" value="<?= $dados['meses'] ?>" class="w-full border rounded p-2" required>
        <label><input type="checkbox" name="servico_continuado" <?= $dados['servico_continuado'] ? 'checked' : '' ?>> Serviço Continuado</label>
        <input type="number" name="anos_limite_contratual" value="<?= $dados['anos_limite_contratual'] ?>" class="w-full border rounded p-2">

        <input type="date" name="data_inicio" value="<?= $dados['data_inicio'] ?>" class="w-full border rounded p-2" required>
        <input type="date" name="data_final" value="<?= $dados['data_final'] ?>" class="w-full border rounded p-2" required>
        
        <label><input type="checkbox" name="empenho" <?= $dados['empenho'] ? 'checked' : '' ?>> Empenho</label>
        <label><input type="checkbox" name="liquidacao" <?= $dados['liquidacao'] ? 'checked' : '' ?>> Liquidação</label>

        <input type="number" name="nr_termo_aditivo" value="<?= $dados['nr_termo_aditivo'] ?>" class="w-full border rounded p-2">
        <input type="text" name="quantidade" value="<?= $dados['quantidade'] ?>" class="w-full border rounded p-2">
        <textarea name="distribuicao" class="w-full border rounded p-2"><?= $dados['distribuicao'] ?></textarea>
        <input type="text" name="processo_sei_sdts" value="<?= $dados['processo_sei_sdts'] ?>" class="w-full border rounded p-2">
        <input type="text" name="processo_sei_csm" value="<?= $dados['processo_sei_csm'] ?>" class="w-full border rounded p-2">
        <select name="status_aditamento" class="w-full border rounded p-2">
            <option value="Contratado" <?= $dados['status_aditamento'] === 'Contratado' ? 'selected' : '' ?>>Contratado</option>
            <option value="Em contratação" <?= $dados['status_aditamento'] === 'Em contratação' ? 'selected' : '' ?>>Em contratação</option>
            <option value="Em renovação" <?= $dados['status_aditamento'] === 'Em renovação' ? 'selected' : '' ?>>Em renovação</option>
        </select>
        <select name="situacao_aditamento_ano_corrente" class="w-full border rounded p-2">
            <option value="Não se aplica" <?= $dados['situacao_aditamento_ano_corrente'] === 'Não se aplica' ? 'selected' : '' ?>>Não se aplica</option>
            <option value="Não iniciado" <?= $dados['situacao_aditamento_ano_corrente'] === 'Não iniciado' ? 'selected' : '' ?>>Não iniciado</option>
            <option value="Montagem do processo" <?= $dados['situacao_aditamento_ano_corrente'] === 'Montagem do processo' ? 'selected' : '' ?>>Montagem do processo</option>
            <option value="NFC" <?= $dados['situacao_aditamento_ano_corrente'] === 'NFC' ? 'selected' : '' ?>>NFC</option>
            <option value="Núcleo Jurídico" <?= $dados['situacao_aditamento_ano_corrente'] === 'Núcleo Jurídico' ? 'selected' : '' ?>>Núcleo Jurídico</option>
            <option value="Ajustes finais" <?= $dados['situacao_aditamento_ano_corrente'] === 'Ajustes finais' ? 'selected' : '' ?>>Ajustes finais</option>
            <option value="Assinado e publicado" <?= $dados['situacao_aditamento_ano_corrente'] === 'Assinado e publicado' ? 'selected' : '' ?>>Assinado e publicado</option>
        </select>
        <input type="text" name="razao_social" value="<?= $dados['razao_social'] ?>" class="w-full border rounded p-2" required>
        <input type="email" name="email" value="<?= $dados['email'] ?>" class="w-full border rounded p-2">
        <input type="text" name="responsavel" value="<?= $dados['responsavel'] ?>" class="w-full border rounded p-2">
        <input type="text" name="telefone" value="<?= $dados['telefone'] ?>" class="w-full border rounded p-2">
        
        <select name="situacao" class="w-full border rounded p-2">
            <option value="Contratado" <?= $dados['situacao'] === 'Contratado' ? 'selected' : '' ?>>Contratado</option>
            <option value="Em Contratação" <?= $dados['situacao'] === 'Em Contratação' ? 'selected' : '' ?>>Em Contratação</option>
            <option value="Encerrado" <?= $dados['situacao'] === 'Encerrado' ? 'selected' : '' ?>>Encerrado</option>
            <option value="Suspenso" <?= $dados['situacao'] === 'Suspenso' ? 'selected' : '' ?>>Suspenso</option>
        </select>

        <textarea name="observacoes" class="w-full border rounded p-2"><?= $dados['observacoes'] ?></textarea>
    </div>

    <div class="mt-4">
        <label class="block text-sm font-semibold">Arquivo Atual</label><br>
        <?php if (!empty($dados['arquivo'])): ?>
            <a href="<?= $dados['arquivo'] ?>" target="_blank" class="text-blue-600 underline"><?= basename($dados['arquivo']) ?></a>
        <?php else: ?>
            <span class="text-gray-500">Nenhum arquivo enviado</span>
        <?php endif; ?>
    </div>

    <div class="mt-2">
        <label class="block text-sm font-semibold">Alterar Arquivo</label>
        <input type="file" name="arquivo" class="block mt-1">
    </div>

    <div class="flex justify-between pt-6">
        <a href="contratos.php" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 text-sm">Cancelar</a>
        <button type="submit" class="px-6 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Salvar Alterações</button>
    </div>
</form>

    </div>
</div>
</body>
</html>
