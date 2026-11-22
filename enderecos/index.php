<?php
require __DIR__ . '/../includes/funcoes.php';
$pdo = db();
$pagina = 'enderecos/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['acao'] ?? '') === 'excluir') {
        try {
            $pdo->prepare("DELETE FROM enderecos WHERE id = ?")->execute([(int)$_POST['id']]);
            flash('success', 'Endereço excluído.');
        } catch (PDOException $e) {
            flash('danger', 'Não dá para excluir: o endereço já está em um frete.');
        }
        redirect($pagina);
    }
    $id = (int)($_POST['id'] ?? 0);
    $campos = ['apelido', 'cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'uf'];
    $dados = [];
    foreach ($campos as $c) $dados[] = trim($_POST[$c] ?? '');
    $dados[7] = strtoupper($dados[7]);
    if ($dados[0] === '' || $dados[2] === '' || $dados[6] === '' || $dados[7] === '') {
        flash('danger', 'Preencha apelido, logradouro, cidade e UF.');
        redirect($pagina . '?acao=form' . ($id ? "&id=$id" : ''));
    }
    if ($id) {
        $dados[] = $id;
        $pdo->prepare("UPDATE enderecos SET apelido=?, cep=?, logradouro=?, numero=?, complemento=?, bairro=?, cidade=?, uf=? WHERE id=?")->execute($dados);
    } else {
        $pdo->prepare("INSERT INTO enderecos (apelido, cep, logradouro, numero, complemento, bairro, cidade, uf) VALUES (?,?,?,?,?,?,?,?)")->execute($dados);
    }
    flash('success', 'Endereço salvo com sucesso!');
    redirect($pagina);
}

$titulo = 'Endereços';
require __DIR__ . '/../includes/header.php';

if (($_GET['acao'] ?? '') === 'form'):
    $en = ['id' => '', 'apelido' => '', 'cep' => '', 'logradouro' => '', 'numero' => '', 'complemento' => '', 'bairro' => '', 'cidade' => '', 'uf' => ''];
    if (!empty($_GET['id'])) {
        $st = $pdo->prepare("SELECT * FROM enderecos WHERE id = ?");
        $st->execute([(int)$_GET['id']]);
        $en = $st->fetch() ?: $en;
    } ?>
  <h1 class="titulo"><?= $en['id'] ? 'Editar' : 'Novo' ?> endereço</h1>
  <div class="card"><div class="card-body">
    <form method="post" class="row g-3">
      <input type="hidden" name="id" value="<?= e($en['id']) ?>">
      <div class="col-md-6"><label class="form-label">Apelido * (ex.: Depósito Central)</label>
        <input name="apelido" class="form-control" required value="<?= e($en['apelido']) ?>"></div>
      <div class="col-md-3"><label class="form-label">CEP</label>
        <input name="cep" class="form-control" maxlength="9" value="<?= e($en['cep']) ?>"></div>
      <div class="col-md-9"><label class="form-label">Logradouro *</label>
        <input name="logradouro" class="form-control" required value="<?= e($en['logradouro']) ?>"></div>
      <div class="col-md-3"><label class="form-label">Número</label>
        <input name="numero" class="form-control" value="<?= e($en['numero']) ?>"></div>
      <div class="col-md-4"><label class="form-label">Complemento</label>
        <input name="complemento" class="form-control" value="<?= e($en['complemento']) ?>"></div>
      <div class="col-md-4"><label class="form-label">Bairro</label>
        <input name="bairro" class="form-control" value="<?= e($en['bairro']) ?>"></div>
      <div class="col-md-4"><label class="form-label">Cidade *</label>
        <input name="cidade" class="form-control" required value="<?= e($en['cidade']) ?>"></div>
      <div class="col-md-2"><label class="form-label">UF *</label>
        <input name="uf" class="form-control" maxlength="2" required value="<?= e($en['uf']) ?>"></div>
      <div class="col-12">
        <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar</button>
        <a href="<?= url($pagina) ?>" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div></div>
<?php else:
    $lista = $pdo->query("SELECT * FROM enderecos ORDER BY id DESC")->fetchAll(); ?>
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="titulo">Endereços (origem e destino)</h1>
    <a href="<?= url($pagina) ?>?acao=form" class="btn btn-primary mb-3"><i class="bi bi-plus-lg"></i> Novo endereço</a>
  </div>
  <div class="card"><div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead><tr><th>ID</th><th>Apelido</th><th>Endereço</th><th>CEP</th><th class="text-end">Ações</th></tr></thead>
      <tbody>
      <?php foreach ($lista as $en): ?>
        <tr>
          <td><?= $en['id'] ?></td><td><?= e($en['apelido']) ?></td>
          <td><?= e(enderecoTexto($en)) ?></td><td><?= e($en['cep']) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="<?= url($pagina) ?>?acao=form&id=<?= $en['id'] ?>"><i class="bi bi-pencil"></i></a>
            <form method="post" class="d-inline form-excluir">
              <input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= $en['id'] ?>">
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      <?php endforeach; if (!$lista): ?>
        <tr><td colspan="5" class="text-center text-muted py-4">Nenhum endereço cadastrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div></div>
<?php endif; require __DIR__ . '/../includes/footer.php'; ?>
