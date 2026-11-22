<?php
include '../conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$peso = $_POST['peso'];
$valor = $_POST['valor'];

if ($id == "") {
    // cadastro novo
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, peso, valor) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nome, $descricao, $peso, $valor]);
} else {
    // edição
    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, peso = ?, valor = ? WHERE id = ?");
    $stmt->execute([$nome, $descricao, $peso, $valor, $id]);
}

header("Location: listar.php?msg=" . urlencode("Produto salvo com sucesso!"));
?>
