<?php
// Inicia a sessão e disponibiliza a conexão PDO para as páginas.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Substitua pelos dados fornecidos pelo seu serviço de hospedagem.
$host = "SEU_HOST";
$banco = "SEU_BANCO";
$usuario = "SEU_USUARIO";
$senha = "SUA_SENHA";

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$banco};charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // Não exiba detalhes técnicos da conexão para visitantes.
    error_log("Erro de conexão PDO: " . $e->getMessage());
    http_response_code(500);
    exit("Não foi possível conectar ao banco de dados. Verifique as configurações.");
}
