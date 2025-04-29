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
    <title>Dashboard - Gestor de Contratos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-900 text-white min-h-screen">
        <div class="text-center text-xl font-bold p-6 border-b border-gray-700">Gestor</div>
        <nav class="p-4 space-y-2">
            <a href="index.php" class="block px-4 py-2 rounded bg-gray-800">🏠 Início</a>
            <a href="contratos.php" class="block px-4 py-2 rounded hover:bg-gray-700">📄 Contratos</a>
            <span class="block px-4 py-2 text-gray-500">📁 Controle de SEI</span>
            <span class="block px-4 py-2 text-gray-500">💰 Controle Financeiro</span>
            <a href="logout.php" class="block px-4 py-2 mt-8 text-red-400 hover:bg-gray-800">🚪 Sair</a>
        </nav>
    </div>

    <!-- Main content -->
    <div class="flex-1 p-10 overflow-auto">
        <h1 class="text-2xl font-bold mb-8">📊 Visão Geral</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-white rounded-lg shadow text-center">
                <h2 id="total" class="text-4xl font-bold text-blue-700">0</h2>
                <p class="text-gray-600 mt-2">Total de Contratos</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow text-center">
                <h2 id="vigentes" class="text-4xl font-bold text-green-600">0</h2>
                <p class="text-gray-600 mt-2">Vigentes</p>
            </div>
            <div class="p-6 bg-white rounded-lg shadow text-center">
                <h2 id="expirados" class="text-4xl font-bold text-red-600">0</h2>
                <p class="text-gray-600 mt-2">Expirados</p>
            </div>
        </div>

        <!-- Área de boas-vindas -->
        <div class="mt-10 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">👋 Olá, <?= $_SESSION['usuario']['nome'] ?? 'Usuário' ?>!</h2>
            <p class="text-gray-600">
                Bem-vindo ao sistema de gestão de contratos. Use o menu lateral para navegar entre os módulos.
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const totalElement = document.getElementById('total');
    const vigentesElement = document.getElementById('vigentes');
    const expiradosElement = document.getElementById('expirados');

    try {
        console.log("🚀 Carregando dados...");
        const res = await fetch('contratos_lista.php');
        if (!res.ok) {
            throw new Error('Erro ao buscar contratos');
        }
        const contratos = await res.json();

        const total = contratos.length;
        const vigentes = contratos.filter(c => c.vigencia === 'Vigente').length;
        const expirados = contratos.filter(c => c.vigencia === 'Expirado').length;

        totalElement.textContent = total;
        vigentesElement.textContent = vigentes;
        expiradosElement.textContent = expirados;
    } catch (error) {
        console.error('❌ Erro ao carregar os dados:', error.message);
    }
});
</script>

</body>
</html>
