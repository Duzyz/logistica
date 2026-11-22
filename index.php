<?php
include 'conexao.php';
$titulo = "Início";
include 'menu.php';

// conta quantos registros existem em cada tabela
$total_produtos = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$total_enderecos = $pdo->query("SELECT COUNT(*) FROM enderecos")->fetchColumn();
$total_transportadores = $pdo->query("SELECT COUNT(*) FROM transportadores")->fetchColumn();
$total_fretes = $pdo->query("SELECT COUNT(*) FROM fretes")->fetchColumn();
?>

<h1>Gerenciador Logístico</h1>

<div class="row">
  <div class="col-md-3">
    <div class="card text-bg-primary mb-3"><div class="card-body">
      <h5>Produtos</h5><h2><?php echo $total_produtos; ?></h2>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-success mb-3"><div class="card-body">
      <h5>Endereços</h5><h2><?php echo $total_enderecos; ?></h2>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-warning mb-3"><div class="card-body">
      <h5>Transportadores</h5><h2><?php echo $total_transportadores; ?></h2>
    </div></div>
  </div>
  <div class="col-md-3">
    <div class="card text-bg-danger mb-3"><div class="card-body">
      <h5>Fretes</h5><h2><?php echo $total_fretes; ?></h2>
    </div></div>
  </div>
</div>

<div class="card"><div class="card-body">
  <h5>Passo a passo</h5>
  <p>1. Cadastre os produtos.<br>
     2. Cadastre os endereços de origem e destino.<br>
     3. Cadastre o transportador.<br>
     4. Crie o frete escolhendo vários produtos.<br>
     5. Baixe o relatório dos fretes do dia.</p>
</div></div>

<?php include 'rodape.php'; ?>
