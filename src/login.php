<?php
session_start();

// Se já está logado, redireciona
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit;
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/conexao.php';

    $email = $_POST['email'] ?? '';
    $senha = md5($_POST['senha'] ?? '');

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $_SESSION['usuario'] = $resultado->fetch_assoc();
        header("Location: index.php");
        exit;
    } else {
        $erro = "⚠️ E-mail ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login - Gestor de Contratos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 h-screen flex items-center justify-center">

    <form method="POST" class="bg-blue p-8 rounded-lg shadow-md w-full max-w-sm space-y-5">
        <h2 class="text-xl font-bold text-center text-gray-800"><img src="assets/img/logo.png" alt="" srcset=""></h2>

        <?php if (!empty($erro)): ?>
            <div class="text-red-600 text-sm text-center"><?= $erro ?></div>
        <?php endif; ?>

        <div>
            <label class="block text-sm font-medium mb-1">E-mail</label>
            <input type="email" name="email" required class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Senha</label>
            <input type="password" name="senha" required class="w-full p-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition">Entrar</button>

        <p class="text-center text-sm text-gray-500">Usuário padrão:<br><code>admin@teste.com / 123456</code></p>
    </form>

</body>
</html>
