<?php
include '../conexao.php';

// os produtos do frete são apagados juntos (ON DELETE CASCADE)
$stmt = $pdo->prepare("DELETE FROM fretes WHERE id = ?");
$stmt->execute([$_GET['id']]);

header("Location: listar.php?msg=" . urlencode("Frete excluído!"));
?>
