<?php
include '../conexao.php';
$titulo = "Relatórios";
include '../menu.php';

// dia escolhido (se não escolher, usa hoje)
$data = date('Y-m-d');
if (isset($_GET['data'])) {
    $data = $_GET['data'];
}

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
?>

<h1>Relatório de fretes do dia</h1>

<form method="get" class="mb-3">
  <label>Escolha o dia:</label>
  <input type="date" name="data" value="<?php echo $data; ?>">
  <button type="submit" class="btn btn-primary btn-sm">Ver</button>
  <a href="baixar.php?data=<?php echo $data; ?>" class="btn btn-success btn-sm">Baixar relatório</a>
</form>

<table class="table table-striped bg-white">
  <tr>
    <th>Frete</th><th>Origem</th><th>Destino</th><th>Transportador</th><th>Produto</th><th>Qtd.</th>
  </tr>
  <?php foreach ($linhas as $l) { ?>
  <tr>
    <td><?php echo $l['id']; ?></td>
    <td><?php echo h($l['origem']); ?></td>
    <td><?php echo h($l['destino']); ?></td>
    <td><?php echo h($l['transportador']); ?></td>
    <td><?php echo h($l['produto']); ?></td>
    <td><?php echo $l['quantidade']; ?></td>
  </tr>
  <?php } ?>
</table>

<?php if (count($linhas) == 0) { ?>
  <p>Nenhum frete encontrado nesse dia.</p>
<?php } ?>

<?php include '../rodape.php'; ?>
