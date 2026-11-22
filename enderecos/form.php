<?php
include '../conexao.php';
$titulo = "Endereço";
include '../menu.php';

$id = "";
$apelido = "";
$cep = "";
$rua = "";
$numero = "";
$bairro = "";
$cidade = "";
$uf = "";

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM enderecos WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $e = $stmt->fetch();
    $id = $e['id'];
    $apelido = $e['apelido'];
    $cep = $e['cep'];
    $rua = $e['rua'];
    $numero = $e['numero'];
    $bairro = $e['bairro'];
    $cidade = $e['cidade'];
    $uf = $e['uf'];
}
?>

<h1>Cadastro de endereço</h1>

<div class="card"><div class="card-body">
  <form action="salvar.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label>Apelido (ex: Depósito Central)</label>
    <input type="text" name="apelido" class="form-control mb-3" required value="<?php echo h($apelido); ?>">

    <label>CEP</label>
    <input type="text" name="cep" class="form-control mb-3" maxlength="9" value="<?php echo h($cep); ?>">

    <label>Rua</label>
    <input type="text" name="rua" class="form-control mb-3" required value="<?php echo h($rua); ?>">

    <label>Número</label>
    <input type="text" name="numero" class="form-control mb-3" value="<?php echo h($numero); ?>">

    <label>Bairro</label>
    <input type="text" name="bairro" class="form-control mb-3" value="<?php echo h($bairro); ?>">

    <label>Cidade</label>
    <input type="text" name="cidade" class="form-control mb-3" required value="<?php echo h($cidade); ?>">

    <label>UF</label>
    <input type="text" name="uf" class="form-control mb-3" maxlength="2" required value="<?php echo h($uf); ?>">

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="listar.php" class="btn btn-secondary">Voltar</a>
  </form>
</div></div>

<?php include '../rodape.php'; ?>
