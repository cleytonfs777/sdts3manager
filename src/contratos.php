<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Contratos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white min-h-screen">
        <div class="text-center text-xl font-bold p-6 border-b border-gray-700">Gestor</div>
        <nav class="p-4 space-y-2">
            <a href="index.php" class="block px-4 py-2 rounded hover:bg-gray-700">🏠 Início</a>
            <a href="contratos.php" class="block px-4 py-2 rounded bg-gray-800">📄 Contratos</a>
            <span class="block px-4 py-2 text-gray-500">📁 Controle de SEI</span>
            <span class="block px-4 py-2 text-gray-500">💰 Controle Financeiro</span>
            <a href="logout.php" class="block px-4 py-2 mt-8 text-red-400 hover:bg-gray-800">🚪 Sair</a>
        </nav>
    </div>

    <!-- Main content -->
    <div class="flex-1 p-10 overflow-auto">
        <h1 class="text-2xl font-bold mb-6">📄 Gestão de Contratos</h1>

        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 mb-6">
            <a href="contrato_novo.php" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">+ Novo Contrato</a>

            <input type="text" id="filtro" placeholder="Buscar..." class="p-2 border rounded flex-1">

            <select id="filtroSituacao" class="p-2 border rounded">
                <option value="">Todas Situações</option>
                <option value="Contratado">Contratado</option>
                <option value="Em Contratação">Em Contratação</option>
                <option value="Encerrado">Encerrado</option>
                <option value="Suspenso">Suspenso</option>
            </select>

            <select id="filtroVigencia" class="p-2 border rounded">
                <option value="">Todas Vigências</option>
                <option value="Vigente">Vigente</option>
                <option value="Expirado">Expirado</option>
            </select>

            <input type="date" id="dataInicio" class="p-2 border rounded" placeholder="Início">
            <input type="date" id="dataFim" class="p-2 border rounded" placeholder="Fim">

            <button id="exportarCSV" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Exportar CSV</button>
        </div>

        <!-- Tabela -->
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 font-semibold">
                    <tr>
                        <th class="px-6 py-3 text-left">Nº</th>
                        <th class="px-6 py-3 text-left">Objeto</th>
                        <th class="px-6 py-3 text-left">Razão Social</th>
                        <th class="px-6 py-3 text-left">Final</th>
                        <th class="px-6 py-3 text-left">Situação</th>
                        <th class="px-6 py-3 text-left">Vigência</th>
                        <th class="px-6 py-3 text-left">Arquivo</th>
                        <th class="px-6 py-3 text-left">PDF</th>
                        <th class="px-6 py-3 text-left">Editar</th>
                    </tr>
                </thead>
                <tbody id="tabelaContratos" class="divide-y divide-gray-200">
                    <!-- Conteúdo gerado dinamicamente -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de alerta vencimento -->
<div id="modalAlerta" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg max-w-md p-6 space-y-4">
        <h2 class="text-xl font-bold text-red-600">⚠️ Atenção</h2>
        <pre id="mensagemModal" class="whitespace-pre-wrap text-gray-800"></pre>
        <div class="text-right">
            <button id="fecharModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Fechar</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const filtro = document.getElementById('filtro');
    const filtroSituacao = document.getElementById('filtroSituacao');
    const filtroVigencia = document.getElementById('filtroVigencia');
    const dataInicio = document.getElementById('dataInicio');
    const dataFim = document.getElementById('dataFim');
    const tabelaContratos = document.getElementById('tabelaContratos');
    const exportarCSV = document.getElementById('exportarCSV');
    const modalAlerta = document.getElementById('modalAlerta');
    const mensagemModal = document.getElementById('mensagemModal');
    const fecharModal = document.getElementById('fecharModal');

    let contratos = [];

    async function carregarContratos() {
        const res = await fetch('./contratos_lista.php');
        contratos = await res.json();
        atualizarTabela();
        verificarVencimentos();
    }

    function atualizarTabela() {
        const filtroTexto = filtro.value.toLowerCase();
        const inicio = dataInicio.value ? new Date(dataInicio.value) : null;
        const fim = dataFim.value ? new Date(dataFim.value) : null;

        const contratosFiltrados = contratos.filter(c => {
            const textoMatch = c.objeto.toLowerCase().includes(filtroTexto) || c.detalhamento.toLowerCase().includes(filtroTexto);
            const dataC = new Date(c.data_final);
            const dataMatch = (!inicio || dataC >= inicio) && (!fim || dataC <= fim);
            const situacaoMatch = !filtroSituacao.value || c.situacao === filtroSituacao.value;
            const vigenciaMatch = !filtroVigencia.value || c.vigencia === filtroVigencia.value;

            return textoMatch && dataMatch && situacaoMatch && vigenciaMatch;
        });

        tabelaContratos.innerHTML = contratosFiltrados.map(c => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">${c.nr_contrato}</td>
                <td class="px-6 py-4">${c.objeto}</td>
                <td class="px-6 py-4">${c.detalhamento}</td>
                <td class="px-6 py-4">${c.data_final}</td>
                <td class="px-6 py-4">${c.situacao}</td>
                <td class="px-6 py-4">
                    <span class="${c.vigencia === 'Vigente' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'}">
                        ${c.vigencia}
                    </span>
                </td>
                <td class="px-6 py-4">
                    ${c.arquivo ? `<a href="${c.arquivo}" target="_blank" class="text-blue-600 underline">Download</a>` : '<span class="text-gray-400">Nenhum</span>'}
                </td>
                <td class="px-6 py-4">
                    <a href="contrato_pdf.php?id=${c.id}" target="_blank" class="text-indigo-600 underline text-sm">PDF</a>
                </td>
                <td class="px-6 py-4">
                    <a href="contrato_editar.php?id=${c.id}" class="text-green-600 underline text-sm">Editar</a>
                </td>
            </tr>
        `).join('');
    }

    function exportarParaCSV() {
        const linhas = [
            ['Nº', 'Objeto', 'Razão Social', 'Data Final', 'Situação', 'Vigência'],
            ...contratos.map(c => [
                c.nr_contrato,
                c.objeto,
                c.detalhamento,
                c.data_final,
                c.situacao,
                c.vigencia
            ])
        ];

        const csvContent = "data:text/csv;charset=utf-8," + linhas.map(e => e.join(",")).join("\n");
        const link = document.createElement("a");
        link.setAttribute("href", encodeURI(csvContent));
        link.setAttribute("download", "contratos_exportados.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    async function verificarVencimentos() {
        const res = await fetch('./verificar_vencimentos.php');
        const msg = await res.text();
        if (msg.trim()) {
            mensagemModal.textContent = msg;
            modalAlerta.classList.remove('hidden');
        }
    }

    filtro.addEventListener('input', atualizarTabela);
    filtroSituacao.addEventListener('change', atualizarTabela);
    filtroVigencia.addEventListener('change', atualizarTabela);
    dataInicio.addEventListener('change', atualizarTabela);
    dataFim.addEventListener('change', atualizarTabela);
    exportarCSV.addEventListener('click', exportarParaCSV);
    fecharModal.addEventListener('click', () => modalAlerta.classList.add('hidden'));

    await carregarContratos();
});
</script>

</body>
</html>
