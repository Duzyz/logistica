<?php
include '../conexao.php';
$titulo = "Fretes";
include '../menu.php';

$sql = "SELECT f.*, o.apelido AS origem, d.apelido AS destino, t.nome AS transportador
        FROM fretes f
        JOIN enderecos o ON o.id = f.origem_id
        JOIN enderecos d ON d.id = f.destino_id
        JOIN transportadores t ON t.id = f.transportador_id
        ORDER BY f.data_frete DESC, f.id DESC";
$fretes = $pdo->query($sql)->fetchAll();
?>

<h1>Fretes</h1>
<a href="form.php" class="btn btn-primary mb-3">Novo frete</a>

<table class="table table-striped bg-white">
  <tr>
    <th>ID</th><th>Data</th><th>Origem</th><th>Destino</th><th>Transportador</th><th>Valor</th><th>Ações</th>
  </tr>
  <?php foreach ($fretes as $f) { ?>
  <tr>
    <td><?php echo $f['id']; ?></td>
    <td><?php echo date('d/m/Y', strtotime($f['data_frete'])); ?></td>
    <td><?php echo h($f['origem']); ?></td>
    <td><?php echo h($f['destino']); ?></td>
    <td><?php echo h($f['transportador']); ?></td>
    <td>R$ <?php echo number_format($f['valor'], 2, ',', '.'); ?></td>
    <td>
      <a href="ver.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-info">Ver</a>
      <a href="form.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
      <a href="excluir.php?id=<?php echo $f['id']; ?>" class="btn btn-sm btn-danger"
         onclick="return confirm('Deseja excluir?')">Excluir</a>
    </td>
  </tr>
  <?php } ?>
</table>

<?php include '../rodape.php'; ?>
