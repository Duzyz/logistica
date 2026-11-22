<?php
include '../conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$cpf_cnpj = $_POST['cpf_cnpj'];
$telefone = $_POST['telefone'];
$veiculo = $_POST['veiculo'];
$placa = $_POST['placa'];

if ($id == "") {
    $stmt = $pdo->prepare("INSERT INTO transportadores (nome, cpf_cnpj, telefone, veiculo, placa) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $cpf_cnpj, $telefone, $veiculo, $placa]);
} else {
    $stmt = $pdo->prepare("UPDATE transportadores SET nome = ?, cpf_cnpj = ?, telefone = ?, veiculo = ?, placa = ? WHERE id = ?");
    $stmt->execute([$nome, $cpf_cnpj, $telefone, $veiculo, $placa, $id]);
}

header("Location: listar.php?msg=" . urlencode("Transportador salvo com sucesso!"));
?>
