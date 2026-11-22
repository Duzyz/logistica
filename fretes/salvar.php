<?php
include '../conexao.php';

$id = $_POST['id'];
$data_frete = $_POST['data_frete'];
$origem_id = $_POST['origem_id'];
$destino_id = $_POST['destino_id'];
$transportador_id = $_POST['transportador_id'];
$valor = $_POST['valor'];
$observacao = $_POST['observacao'];

// origem e destino não podem ser iguais
if ($origem_id == $destino_id) {
    header("Location: form.php?msg=" . urlencode("Origem e destino não podem ser iguais."));
    exit;
}

if ($id == "") {
    // frete novo
    $stmt = $pdo->prepare("INSERT INTO fretes (data_frete, origem_id, destino_id, transportador_id, valor, observacao) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$data_frete, $origem_id, $destino_id, $transportador_id, $valor, $observacao]);
    $id = $pdo->lastInsertId();
} else {
    // edição: atualiza o frete e apaga os produtos antigos
    $stmt = $pdo->prepare("UPDATE fretes SET data_frete = ?, origem_id = ?, destino_id = ?, transportador_id = ?, valor = ?, observacao = ? WHERE id = ?");
    $stmt->execute([$data_frete, $origem_id, $destino_id, $transportador_id, $valor, $observacao, $id]);

    $stmt = $pdo->prepare("DELETE FROM frete_produtos WHERE frete_id = ?");
    $stmt->execute([$id]);
}

// grava cada produto do frete
$produtos = $_POST['produto_id'];
$quantidades = $_POST['quantidade'];

for ($i = 0; $i < count($produtos); $i++) {
    $stmt = $pdo->prepare("INSERT INTO frete_produtos (frete_id, produto_id, quantidade) VALUES (?, ?, ?)");
    $stmt->execute([$id, $produtos[$i], $quantidades[$i]]);
}

header("Location: listar.php?msg=" . urlencode("Frete salvo com sucesso!"));
?>
