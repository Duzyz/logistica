<?php
require __DIR__ . '/../includes/funcoes.php';
$pdo = db();
$pagina = 'transportadores/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['acao'] ?? '') === 'excluir') {
        try {
            $pdo->prepare("DELETE FROM transportadores WHERE id = ?")->execute([(int)$_POST['id']]);
            flash('success', 'Transportador excluído.');
        } catch (PDOException $e) {
            flash('danger', 'Não dá para excluir: o transportador já está em um frete.');
        }
        redirect($pagina);
    }
    $id = (int)($_POST['id'] ?? 0);
    $dados = [];
    foreach (['nome', 'cpf_cnpj', 'telefone', 'tipo_veiculo', 'placa'] as $c) $dados[] = trim($_POST[$c] ?? '');
    $dados[4] = strtoupper($dados[4]);
    if ($dados[0] === '') {
        flash('danger', 'Informe o nome do transportador.');
        redirect($pagina . '?acao=form' . ($id ? "&id=$id" : ''));
    }
    if ($id) {
        $dados[] = $id;
        $pdo->prepare("UPDATE transportadores SET nome=?, cpf_cnpj=?, telefone=?, tipo_veiculo=?, placa=? WHERE id=?")->execute($dados);
    } else {
        $pdo->prepare("INSERT INTO transportadores (nome, cpf_cnpj, telefone, tipo_veiculo, placa) VALUES (?,?,?,?,?)")->execute($dados);
    }
    flash('success', 'Transportador salvo com sucesso!');
    redirect($pagina);
}

$titulo = 'Transportadores';
require __DIR__ . '/../includes/header.php';

if (($_GET['acao'] ?? '') === 'form'):
    $t = ['id' => '', 'nome' => '', 'cpf_cnpj' => '', 'telefone' => '', 'tipo_veiculo' => '', 'placa' => ''];
    if (!empty($_GET['id'])) {
        $st = $pdo->prepare("SELECT * FROM transportadores WHERE id = ?");
        $st->execute([(int)$_GET['id']]);
        $t = $st->fetch() ?: $t;
    } ?>
  <h1 class="titulo"><?= $t['id'] ? 'Editar' : 'Novo' ?> transportador</h1>
  <div class="card"><div class="card-body">
    <form method="post" class="row g-3">
      <input type="hidden" name="id" value="<?= e($t['id']) ?>">
      <div class="col-md-8"><label class="form-label">Nome / Razão social *</label>
        <input name="nome" class="form-control" required value="<?= e($t['nome']) ?>"></div>
      <div class="col-md-4"><label class="form-label">CPF ou CNPJ</label>
        <input name="cpf_cnpj" class="form-control" maxlength="18" value="<?= e($t['cpf_cnpj']) ?>"></div>
      <div class="col-md-4"><label class="form-label">Telefone</label>
        <input name="telefone" class="form-control" value="<?= e($t['telefone']) ?>"></div>
      <div class="col-md-4"><label class="form-label">Tipo de veículo</label>
        <select name="tipo_veiculo" class="form-select">
          <?php foreach (['', 'Moto', 'Carro', 'Van', 'Caminhão leve', 'Caminhão pesado'] as $v): ?>
            <option value="<?= e($v) ?>" <?= $t['tipo_veiculo'] === $v ? 'selected' : '' ?>><?= $v ?: 'Selecione' ?></option>
          <?php endforeach; ?>
        </select></div>
      <div class="col-md-4"><label class="form-label">Placa</label>
        <input name="placa" class="form-control" maxlength="10" value="<?= e($t['placa']) ?>"></div>
      <div class="col-12">
        <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar</button>
        <a href="<?= url($pagina) ?>" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div></div>
<?php else:
    $lista = $pdo->query("SELECT * FROM transportadores ORDER BY id DESC")->fetchAll(); ?>
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="titulo">Transportadores</h1>
    <a href="<?= url($pagina) ?>?acao=form" class="btn btn-primary mb-3"><i class="bi bi-plus-lg"></i> Novo transportador</a>
  </div>
  <div class="card"><div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead><tr><th>ID</th><th>Nome</th><th>CPF/CNPJ</th><th>Telefone</th><th>Veículo</th><th>Placa</th><th class="text-end">Ações</th></tr></thead>
      <tbody>
      <?php foreach ($lista as $t): ?>
        <tr>
          <td><?= $t['id'] ?></td><td><?= e($t['nome']) ?></td><td><?= e($t['cpf_cnpj']) ?></td>
          <td><?= e($t['telefone']) ?></td><td><?= e($t['tipo_veiculo']) ?></td><td><?= e($t['placa']) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="<?= url($pagina) ?>?acao=form&id=<?= $t['id'] ?>"><i class="bi bi-pencil"></i></a>
            <form method="post" class="d-inline form-excluir">
              <input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= $t['id'] ?>">
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      <?php endforeach; if (!$lista): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">Nenhum transportador cadastrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div></div>
<?php endif; require __DIR__ . '/../includes/footer.php'; ?>
