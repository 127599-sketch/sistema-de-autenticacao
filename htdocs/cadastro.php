<?php
require_once 'conecta.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_usuario = trim($_POST['email_usuario'] ?? '');
    $senha_usuario = $_POST['senha_usuario'] ?? '';

    if (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um endereço de e-mail válido.';
    } elseif (strlen($senha_usuario) < 8) {
        $erro = 'A senha deve ter pelo menos 8 caracteres.';
    } else {
        // A senha é armazenada como hash, nunca em texto puro.
        $senha_hash = password_hash($senha_usuario, PASSWORD_DEFAULT);

        // A tabela utilizada neste projeto se chama usuarios.
        $sql = "INSERT INTO usuarios (email_usuario, senha_usuario)
                VALUES (:email_usuario, :senha_usuario)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email_usuario', $email_usuario, PDO::PARAM_STR);
        $stmt->bindParam(':senha_usuario', $senha_hash, PDO::PARAM_STR);

        try {
            $stmt->execute();
            header('Location: index.php?cadastro=sucesso');
            exit();
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar usuário: " . $e->getMessage());
            $erro = 'Não foi possível concluir o cadastro. Verifique se o e-mail já está cadastrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex flex-column justify-content-center align-items-center vh-100">
    <form method="POST" action="" class="w-100" style="max-width: 400px;">
        <h1 class="h3 mb-3 fw-normal text-center">Cadastre-se</h1>
        <?php if ($erro): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <div class="form-floating mb-3">
            <input type="email" name="email_usuario" class="form-control" placeholder="E-mail de usuário" required>
            <label>E-mail de usuário</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" name="senha_usuario" class="form-control" placeholder="Senha" minlength="8" required>
            <label>Senha (mínimo de 8 caracteres)</label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Cadastrar</button>
        <a class="w-100 btn btn-lg btn-secondary mt-3" href="index.php">Voltar ao login</a>
    </form>
</div>
</body>
</html>
