<?php
// Dados de conexão (padrão do XAMPP)
$host = "localhost";
$banco = "logistica";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    die("Erro ao conectar no banco: " . $erro->getMessage());
}

// Função para mostrar texto na tela com segurança
function h($texto) {
    return htmlspecialchars((string)$texto);
}
?>
