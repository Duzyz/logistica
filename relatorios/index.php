<?php
require __DIR__ . '/../includes/funcoes.php';
$pdo = db();
$data = $_GET['data'] ?? date('Y-m-d');

$st = $pdo->prepare("SELECT f.id, f.data_frete, o.apelido AS origem, d.apelido AS destino, t.nome AS transportador,
    t.placa, f.valor_frete, p.nome AS produto, fp.quantidade, p.peso_kg
    FROM fretes f JOIN enderecos o ON o.id=f.origem_id JOIN enderecos d ON d.id=f.destino_id
    JOIN transportadores t ON t.id=f.transportador_id
    JOIN frete_produtos fp ON fp.frete_id=f.id JOIN produtos p ON p.id=fp.produto_id
    WHERE f.data_frete = ? ORDER BY f.id, p.nome");
$st->execute([$data]);
$linhas = $st->fetchAll();

// Download do relatório em CSV (abre no Excel)
if (isset($_GET['baixar'])) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="relatorio_fretes_' . $data . '.csv"');
    echo "\xEF\xBB\xBF"; // BOM para o Excel entender os acentos
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Frete', 'Data', 'Origem', 'Destino', 'Transportador', 'Placa', 'Valor do frete', 'Produto', 'Quantidade', 'Peso total (kg)'], ';');
    $totalFrete = 0; $fretesVistos = [];
    foreach ($linhas as $l) {
        if (!isset($fretesVistos[$l['id']])) { $fretesVistos[$l['id']] = true; $totalFrete += $l['valor_frete']; }
        fputcsv($out, [$l['id'], dataBr($l['data_frete']), $l['origem'], $l['destino'], $l['transportador'], $l['placa'],
            number_format($l['valor_frete'], 2, ',', '.'), $l['produto'], $l['quantidade'],
            number_format($l['peso_kg'] * $l['quantidade'], 2, ',', '.')], ';');
    }
    fputcsv($out, ['', '', '', '', '', 'TOTAL DOS FRETES', number_format($totalFrete, 2, ',', '.'), '', '', ''], ';');
    fclose($out);
    exit;
}

$titulo = 'Relatórios';
require __DIR__ . '/../includes/header.php'; ?>
<h1 class="titulo">Relatório de fretes por dia</h1>
<div class="card mb-3"><div class="card-body">
  <form method="get" class="row g-2 align-items-end">
    <div class="col-md-4"><label class="form-label">Escolha o dia</label>
      <input type="date" name="data" class="form-control" value="<?= e($data) ?>"></div>
    <div class="col-auto"><button class="btn btn-primary"><i class="bi bi-search"></i> Ver</button></div>
    <?php if ($linhas): ?>
    <div class="col-auto"><button name="baixar" value="1" class="btn btn-success"><i class="bi bi-download"></i> Baixar relatório (CSV)</button></div>
    <?php endif; ?>
  </form>
</div></div>
<div class="card"><div class="table-responsive">
  <table class="table table-hover mb-0">
    <thead><tr><th>Frete</th><th>Origem</th><th>Destino</th><th>Transportador</th><th>Produto</th><th>Qtd.</th><th>Valor do frete</th></tr></thead>
    <tbody>
    <?php $vistos = []; foreach ($linhas as $l): ?>
      <tr><td>#<?= $l['id'] ?></td><td><?= e($l['origem']) ?></td><td><?= e($l['destino']) ?></td>
        <td><?= e($l['transportador']) ?></td><td><?= e($l['produto']) ?></td><td><?= $l['quantidade'] ?></td>
        <td><?php if (!isset($vistos[$l['id']])) { echo moeda($l['valor_frete']); $vistos[$l['id']] = true; } ?></td></tr>
    <?php endforeach; if (!$linhas): ?>
      <tr><td colspan="7" class="text-center text-muted py-4">Nenhum frete em <?= dataBr($data) ?>.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
