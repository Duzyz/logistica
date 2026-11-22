<?php
include '../conexao.php';
$titulo = "Produtos";
include '../menu.php';

$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
?>

<h1>Produtos</h1>
<a href="form.php" class="btn btn-primary mb-3">Novo produto</a>

<table class="table table-striped bg-white">
  <tr>
    <th>ID</th><th>Nome</th><th>Descrição</th><th>Peso (kg)</th><th>Valor</th><th>Ações</th>
  </tr>
  <?php foreach ($produtos as $p) { ?>
  <tr>
    <td><?php echo $p['id']; ?></td>
    <td><?php echo h($p['nome']); ?></td>
    <td><?php echo h($p['descricao']); ?></td>
    <td><?php echo $p['peso']; ?></td>
    <td>R$ <?php echo number_format($p['valor'], 2, ',', '.'); ?></td>
    <td>
      <a href="form.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
      <a href="excluir.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger"
         onclick="return confirm('Deseja excluir?')">Excluir</a>
    </td>
  </tr>
  <?php } ?>
</table>

<?php include '../rodape.php'; ?>
