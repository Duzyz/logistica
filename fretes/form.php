<?php
include '../conexao.php';
$titulo = "Frete";
include '../menu.php';

// busca os dados para preencher os campos de escolha (select)
$enderecos = $pdo->query("SELECT * FROM enderecos ORDER BY apelido")->fetchAll();
$transportadores = $pdo->query("SELECT * FROM transportadores ORDER BY nome")->fetchAll();
$produtos = $pdo->query("SELECT * FROM produtos ORDER BY nome")->fetchAll();

// valores vazios (frete novo)
$id = "";
$data_frete = date('Y-m-d');
$origem_id = "";
$destino_id = "";
$transportador_id = "";
$valor = "";
$observacao = "";
$itens = array();

// se veio id, busca o frete e os produtos dele
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM fretes WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $f = $stmt->fetch();
    $id = $f['id'];
    $data_frete = $f['data_frete'];
    $origem_id = $f['origem_id'];
    $destino_id = $f['destino_id'];
    $transportador_id = $f['transportador_id'];
    $valor = $f['valor'];
    $observacao = $f['observacao'];

    $stmt = $pdo->prepare("SELECT * FROM frete_produtos WHERE frete_id = ?");
    $stmt->execute([$id]);
    $itens = $stmt->fetchAll();
}
?>

<h1>Cadastro de frete</h1>

<form action="salvar.php" method="post">
  <input type="hidden" name="id" value="<?php echo $id; ?>">

  <div class="card mb-3"><div class="card-body">
    <div class="row">
      <div class="col-md-4 mb-3">
        <label>Data</label>
        <input type="date" name="data_frete" class="form-control" required value="<?php echo $data_frete; ?>">
      </div>
      <div class="col-md-5 mb-3">
        <label>Transportador</label>
        <select name="transportador_id" class="form-select" required>
          <option value="">Selecione</option>
          <?php foreach ($transportadores as $t) { ?>
            <option value="<?php echo $t['id']; ?>" <?php if ($t['id'] == $transportador_id) echo "selected"; ?>>
              <?php echo h($t['nome']); ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-3 mb-3">
        <label>Valor do frete (R$)</label>
        <input type="number" step="0.01" name="valor" class="form-control" value="<?php echo $valor; ?>">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label>Origem</label>
        <select name="origem_id" class="form-select" required>
          <option value="">Selecione</option>
          <?php foreach ($enderecos as $e) { ?>
            <option value="<?php echo $e['id']; ?>" <?php if ($e['id'] == $origem_id) echo "selected"; ?>>
              <?php echo h($e['apelido']); ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-6 mb-3">
        <label>Destino</label>
        <select name="destino_id" class="form-select" required>
          <option value="">Selecione</option>
          <?php foreach ($enderecos as $e) { ?>
            <option value="<?php echo $e['id']; ?>" <?php if ($e['id'] == $destino_id) echo "selected"; ?>>
              <?php echo h($e['apelido']); ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>

    <label>Observação</label>
    <input type="text" name="observacao" class="form-control" value="<?php echo h($observacao); ?>">
  </div></div>

  <div class="card mb-3"><div class="card-body">
    <h5>Produtos do frete</h5>

    <!-- aqui o JavaScript adiciona as linhas de produto -->
    <div id="produtos">
      <?php foreach ($itens as $item) { ?>
      <div class="row mb-2 linha-produto">
        <div class="col-md-8">
          <select name="produto_id[]" class="form-select">
            <?php foreach ($produtos as $p) { ?>
              <option value="<?php echo $p['id']; ?>" <?php if ($p['id'] == $item['produto_id']) echo "selected"; ?>>
                <?php echo h($p['nome']); ?>
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" name="quantidade[]" class="form-control" min="1" value="<?php echo $item['quantidade']; ?>">
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger" onclick="removerProduto(this)">Remover</button>
        </div>
      </div>
      <?php } ?>
    </div>

    <button type="button" class="btn btn-success" onclick="adicionarProduto()">+ Adicionar produto</button>
  </div></div>

  <!-- modelo de linha que o JavaScript copia (template não aparece na tela) -->
  <template id="modelo">
    <div class="row mb-2 linha-produto">
      <div class="col-md-8">
        <select name="produto_id[]" class="form-select">
          <?php foreach ($produtos as $p) { ?>
            <option value="<?php echo $p['id']; ?>"><?php echo h($p['nome']); ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-2">
        <input type="number" name="quantidade[]" class="form-control" min="1" value="1">
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-danger" onclick="removerProduto(this)">Remover</button>
      </div>
    </div>
  </template>

  <button type="submit" class="btn btn-primary">Salvar frete</button>
  <a href="listar.php" class="btn btn-secondary">Voltar</a>
</form>

<script src="/logistica/js/frete.js"></script>

<?php include '../rodape.php'; ?>
