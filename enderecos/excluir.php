<?php
include '../conexao.php';

try {
    $stmt = $pdo->prepare("DELETE FROM enderecos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $msg = "Endereço excluído!";
} catch (PDOException $erro) {
    $msg = "Não foi possível excluir: o endereço está sendo usado em um frete.";
}

header("Location: listar.php?msg=" . urlencode($msg));
?>
