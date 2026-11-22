<?php
include '../conexao.php';

try {
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $msg = "Produto excluído!";
} catch (PDOException $erro) {
    // dá erro se o produto já estiver em algum frete
    $msg = "Não foi possível excluir: o produto está sendo usado em um frete.";
}

header("Location: listar.php?msg=" . urlencode($msg));
?>
