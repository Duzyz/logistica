<?php
include '../conexao.php';
$titulo = "Transportadores";
include '../menu.php';

$transportadores = $pdo->query("SELECT * FROM transportadores ORDER BY id DESC")->fetchAll();
?>

<h1>Transportadores</h1>
<a href="form.php" class="btn btn-primary mb-3">Novo transportador</a>

<table class="table table-striped bg-white">
  <tr>
    <th>ID</th><th>Nome</th><th>CPF/CNPJ</th><th>Telefone</th><th>Veículo</th><th>Placa</th><th>Ações</th>
  </tr>
  <?php foreach ($transportadores as $t) { ?>
  <tr>
    <td><?php echo $t['id']; ?></td>
    <td><?php echo h($t['nome']); ?></td>
    <td><?php echo h($t['cpf_cnpj']); ?></td>
    <td><?php echo h($t['telefone']); ?></td>
    <td><?php echo h($t['veiculo']); ?></td>
    <td><?php echo h($t['placa']); ?></td>
    <td>
      <a href="form.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
      <a href="excluir.php?id=<?php echo $t['id']; ?>" class="btn btn-sm btn-danger"
         onclick="return confirm('Deseja excluir?')">Excluir</a>
    </td>
  </tr>
  <?php } ?>
</table>

<?php include '../rodape.php'; ?>
