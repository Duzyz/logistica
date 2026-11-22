<?php
include '../conexao.php';

try {
    $stmt = $pdo->prepare("DELETE FROM transportadores WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $msg = "Transportador excluído!";
} catch (PDOException $erro) {
    $msg = "Não foi possível excluir: o transportador está sendo usado em um frete.";
}

header("Location: listar.php?msg=" . urlencode($msg));
?>
