<?php
include '../conexao.php';
$titulo = "Detalhes do frete";
include '../menu.php';

$id = $_GET['id'];

// dados do frete
$sql = "SELECT f.*, o.apelido AS origem, d.apelido AS destino, t.nome AS transportador, t.placa
        FROM fretes f
        JOIN enderecos o ON o.id = f.origem_id
        JOIN enderecos d ON d.id = f.destino_id
        JOIN transportadores t ON t.id = f.transportador_id
        WHERE f.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$f = $stmt->fetch();

// produtos do frete
$sql = "SELECT p.nome, p.peso, p.valor, fp.quantidade
        FROM frete_produtos fp
        JOIN produtos p ON p.id = fp.produto_id
        WHERE fp.frete_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$itens = $stmt->fetchAll();

$peso_total = 0;
$valor_carga = 0;
?>

<h1>Frete nº <?php echo $f['id']; ?></h1>

<div class="card mb-3"><div class="card-body">
  <p><b>Data:</b> <?php echo date('d/m/Y', strtotime($f['data_frete'])); ?></p>
  <p><b>Origem:</b> <?php echo h($f['origem']); ?></p>
  <p><b>Destino:</b> <?php echo h($f['destino']); ?></p>
  <p><b>Transportador:</b> <?php echo h($f['transportador']); ?> (placa <?php echo h($f['placa']); ?>)</p>
  <p><b>Valor do frete:</b> R$ <?php echo number_format($f['valor'], 2, ',', '.'); ?></p>
  <p><b>Observação:</b> <?php echo h($f['observacao']); ?></p>
</div></div>

<table class="table table-striped bg-white">
  <tr><th>Produto</th><th>Quantidade</th><th>Peso unit. (kg)</th><th>Valor unit.</th></tr>
  <?php foreach ($itens as $i) {
      $peso_total = $peso_total + ($i['peso'] * $i['quantidade']);
      $valor_carga = $valor_carga + ($i['valor'] * $i['quantidade']);
  ?>
  <tr>
    <td><?php echo h($i['nome']); ?></td>
    <td><?php echo $i['quantidade']; ?></td>
    <td><?php echo $i['peso']; ?></td>
    <td>R$ <?php echo number_format($i['valor'], 2, ',', '.'); ?></td>
  </tr>
  <?php } ?>
</table>

<p><b>Peso total:</b> <?php echo number_format($peso_total, 2, ',', '.'); ?> kg<br>
   <b>Valor da carga:</b> R$ <?php echo number_format($valor_carga, 2, ',', '.'); ?></p>

<a href="listar.php" class="btn btn-secondary">Voltar</a>

<?php include '../rodape.php'; ?>
