<?php
include '../conexao.php';
$titulo = "Transportador";
include '../menu.php';

$id = "";
$nome = "";
$cpf_cnpj = "";
$telefone = "";
$veiculo = "";
$placa = "";

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM transportadores WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $t = $stmt->fetch();
    $id = $t['id'];
    $nome = $t['nome'];
    $cpf_cnpj = $t['cpf_cnpj'];
    $telefone = $t['telefone'];
    $veiculo = $t['veiculo'];
    $placa = $t['placa'];
}
?>

<h1>Cadastro de transportador</h1>

<div class="card"><div class="card-body">
  <form action="salvar.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label>Nome</label>
    <input type="text" name="nome" class="form-control mb-3" required value="<?php echo h($nome); ?>">

    <label>CPF ou CNPJ</label>
    <input type="text" name="cpf_cnpj" class="form-control mb-3" value="<?php echo h($cpf_cnpj); ?>">

    <label>Telefone</label>
    <input type="text" name="telefone" class="form-control mb-3" value="<?php echo h($telefone); ?>">

    <label>Veículo</label>
    <input type="text" name="veiculo" class="form-control mb-3" value="<?php echo h($veiculo); ?>">

    <label>Placa</label>
    <input type="text" name="placa" class="form-control mb-3" value="<?php echo h($placa); ?>">

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="listar.php" class="btn btn-secondary">Voltar</a>
  </form>
</div></div>

<?php include '../rodape.php'; ?>
