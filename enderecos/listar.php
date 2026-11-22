<?php
include '../conexao.php';
$titulo = "Endereços";
include '../menu.php';

$enderecos = $pdo->query("SELECT * FROM enderecos ORDER BY id DESC")->fetchAll();
?>

<h1>Endereços</h1>
<a href="form.php" class="btn btn-primary mb-3">Novo endereço</a>

<table class="table table-striped bg-white">
  <tr>
    <th>ID</th><th>Apelido</th><th>Endereço</th><th>CEP</th><th>Ações</th>
  </tr>
  <?php foreach ($enderecos as $e) { ?>
  <tr>
    <td><?php echo $e['id']; ?></td>
    <td><?php echo h($e['apelido']); ?></td>
    <td><?php echo h($e['rua'] . ", " . $e['numero'] . " - " . $e['bairro'] . ", " . $e['cidade'] . "/" . $e['uf']); ?></td>
    <td><?php echo h($e['cep']); ?></td>
    <td>
      <a href="form.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
      <a href="excluir.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-danger"
         onclick="return confirm('Deseja excluir?')">Excluir</a>
    </td>
  </tr>
  <?php } ?>
</table>

<?php include '../rodape.php'; ?>
