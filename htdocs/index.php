<?php
require_once 'conecta.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    // Busca pelo e-mail. A senha armazenada é um hash e deve ser verificada
    // com password_verify(), não comparada diretamente no SQL.
    $sql = "SELECT email_usuario, senha_usuario
            FROM usuarios
            WHERE email_usuario = :email_usuario
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email_usuario', $email_usuario, PDO::PARAM_STR);
    $stmt->execute();

    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha_usuario, $usuario['senha_usuario'])) {
        session_regenerate_id(true);
        $_SESSION['email_usuario'] = $usuario['email_usuario'];
        header('Location: inicio.php');
        exit();
    }

    $erro = 'Usuário ou senha incorretos.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
    <form method="POST" action="" class="w-100" style="max-width: 400px;">
        <h1 class="h3 mb-3 fw-normal text-center">Faça login</h1>
        <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'sucesso'): ?>
            <div class="alert alert-success">Cadastro realizado. Faça seu login.</div>
        <?php endif; ?>
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <div class="form-floating mb-3">
            <input type="email" name="email_usuario" class="form-control" placeholder="E-mail de usuário" required>
            <label>E-mail de usuário</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="senha_usuario" class="form-control" placeholder="Senha" required>
            <label>Senha</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>
        <p class="mt-3 text-center"><a href="cadastro.php">Cadastrar-se</a></p>
    </form>
</div>
</body>
</html>
