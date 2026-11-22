<?php
include '../conexao.php';

$data = $_GET['data'];

$sql = "SELECT f.id, o.apelido AS origem, d.apelido AS destino, t.nome AS transportador,
               p.nome AS produto, fp.quantidade, f.valor
        FROM fretes f
        JOIN enderecos o ON o.id = f.origem_id
        JOIN enderecos d ON d.id = f.destino_id
        JOIN transportadores t ON t.id = f.transportador_id
        JOIN frete_produtos fp ON fp.frete_id = f.id
        JOIN produtos p ON p.id = fp.produto_id
        WHERE f.data_frete = ?
        ORDER BY f.id";
$stmt = $pdo->prepare($sql);
$stmt->execute([$data]);
$linhas = $stmt->fetchAll();

// avisa o navegador que é um arquivo para baixar
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=relatorio_fretes_" . $data . ".csv");

echo "\xEF\xBB\xBF"; // faz o Excel mostrar os acentos certo

// primeira linha (títulos das colunas)
echo "Frete;Origem;Destino;Transportador;Produto;Quantidade;Valor do frete\n";

// uma linha para cada produto
foreach ($linhas as $l) {
    echo $l['id'] . ";" . $l['origem'] . ";" . $l['destino'] . ";" . $l['transportador'] . ";"
       . $l['produto'] . ";" . $l['quantidade'] . ";" . number_format($l['valor'], 2, ',', '.') . "\n";
}
?>
