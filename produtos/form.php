<?php
include '../conexao.php';
$titulo = "Produto";
include '../menu.php';

// valores vazios (cadastro novo)
$id = "";
$nome = "";
$descricao = "";
$peso = "";
$valor = "";

// se veio um id na URL, busca o produto para editar
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $p = $stmt->fetch();
    $id = $p['id'];
    $nome = $p['nome'];
    $descricao = $p['descricao'];
    $peso = $p['peso'];
    $valor = $p['valor'];
}
?>

<h1>Cadastro de produto</h1>

<div class="card"><div class="card-body">
  <form action="salvar.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label>Nome</label>
    <input type="text" name="nome" class="form-control mb-3" required value="<?php echo h($nome); ?>">

    <label>Descrição</label>
    <input type="text" name="descricao" class="form-control mb-3" value="<?php echo h($descricao); ?>">

    <label>Peso (kg)</label>
    <input type="number" step="0.01" name="peso" class="form-control mb-3" value="<?php echo $peso; ?>">

    <label>Valor (R$)</label>
    <input type="number" step="0.01" name="valor" class="form-control mb-3" value="<?php echo $valor; ?>">

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="listar.php" class="btn btn-secondary">Voltar</a>
  </form>
</div></div>

<?php include '../rodape.php'; ?>
