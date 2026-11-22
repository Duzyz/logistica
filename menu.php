<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $titulo; ?> - Logística</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/logistica/css/estilo.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="/logistica/index.php">Logística</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/logistica/produtos/listar.php">Produtos</a></li>
        <li class="nav-item"><a class="nav-link" href="/logistica/enderecos/listar.php">Endereços</a></li>
        <li class="nav-item"><a class="nav-link" href="/logistica/transportadores/listar.php">Transportadores</a></li>
        <li class="nav-item"><a class="nav-link" href="/logistica/fretes/listar.php">Fretes</a></li>
        <li class="nav-item"><a class="nav-link" href="/logistica/relatorios/relatorio.php">Relatórios</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
<?php if (isset($_GET['msg'])) { ?>
  <div class="alert alert-info"><?php echo h($_GET['msg']); ?></div>
<?php } ?>
