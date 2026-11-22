<?php
require_once 'conecta.php';

if (!isset($_SESSION['email_usuario'])) {
    header('Location: index.php');
    exit();
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nova_senha = $_POST['nova_senha'] ?? '';
    $email_usuario = $_SESSION['email_usuario'];

    if (strlen($nova_senha) < 8) {
        $erro = 'A nova senha deve ter pelo menos 8 caracteres.';
    } else {
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios
                SET senha_usuario = :nova_senha
                WHERE email_usuario = :email_usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nova_senha', $nova_senha_hash, PDO::PARAM_STR);
        $stmt->bindParam(':email_usuario', $email_usuario, PDO::PARAM_STR);

        try {
            $stmt->execute();
            header('Location: inicio.php?senha=alterada');
            exit();
        } catch (PDOException $e) {
            error_log("Erro ao alterar senha: " . $e->getMessage());
            $erro = 'Não foi possível alterar a senha.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
    <form method="POST" action="" class="w-100" style="max-width: 400px;">
        <h1 class="h3 mb-3 fw-normal text-center">Alterar Senha</h1>
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <div class="form-floating mb-3">
            <input type="password" name="nova_senha" class="form-control" placeholder="Nova senha" minlength="8" required>
            <label>Nova senha (mínimo de 8 caracteres)</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Alterar senha</button>
        <a class="w-100 btn btn-lg btn-secondary mt-3" href="inicio.php">Cancelar</a>
    </form>
</div>
</body>
</html>
