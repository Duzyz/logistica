<?php
include '../conexao.php';

$id = $_POST['id'];
$apelido = $_POST['apelido'];
$cep = $_POST['cep'];
$rua = $_POST['rua'];
$numero = $_POST['numero'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$uf = strtoupper($_POST['uf']);

if ($id == "") {
    $stmt = $pdo->prepare("INSERT INTO enderecos (apelido, cep, rua, numero, bairro, cidade, uf) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$apelido, $cep, $rua, $numero, $bairro, $cidade, $uf]);
} else {
    $stmt = $pdo->prepare("UPDATE enderecos SET apelido = ?, cep = ?, rua = ?, numero = ?, bairro = ?, cidade = ?, uf = ? WHERE id = ?");
    $stmt->execute([$apelido, $cep, $rua, $numero, $bairro, $cidade, $uf, $id]);
}

header("Location: listar.php?msg=" . urlencode("Endereço salvo com sucesso!"));
?>
