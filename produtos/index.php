<?php
require __DIR__ . '/../includes/funcoes.php';
$pdo = db();
$pagina = 'produtos/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['acao'] ?? '') === 'excluir') {
        try {
            $pdo->prepare("DELETE FROM produtos WHERE id = ?")->execute([(int)$_POST['id']]);
            flash('success', 'Produto excluído.');
        } catch (PDOException $e) {
            flash('danger', 'Não dá para excluir: o produto já está em um frete.');
        }
        redirect($pagina);
    }
    $id = (int)($_POST['id'] ?? 0);
    $dados = [trim($_POST['nome']), trim($_POST['descricao']), numero($_POST['peso_kg']), numero($_POST['valor'])];
    if ($dados[0] === '') {
        flash('danger', 'Informe o nome do produto.');
        redirect($pagina . '?acao=form' . ($id ? "&id=$id" : ''));
    }
    if ($id) {
        $dados[] = $id;
        $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, peso_kg=?, valor=? WHERE id=?")->execute($dados);
    } else {
        $pdo->prepare("INSERT INTO produtos (nome, descricao, peso_kg, valor) VALUES (?,?,?,?)")->execute($dados);
    }
    flash('success', 'Produto salvo com sucesso!');
    redirect($pagina);
}

$titulo = 'Produtos';
require __DIR__ . '/../includes/header.php';

if (($_GET['acao'] ?? '') === 'form'):
    $p = ['id' => '', 'nome' => '', 'descricao' => '', 'peso_kg' => '', 'valor' => ''];
    if (!empty($_GET['id'])) {
        $st = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
        $st->execute([(int)$_GET['id']]);
        $p = $st->fetch() ?: $p;
    } ?>
  <h1 class="titulo"><?= $p['id'] ? 'Editar' : 'Novo' ?> produto</h1>
  <div class="card"><div class="card-body">
    <form method="post" class="row g-3">
      <input type="hidden" name="id" value="<?= e($p['id']) ?>">
      <div class="col-md-8"><label class="form-label">Nome *</label>
        <input name="nome" class="form-control" required value="<?= e($p['nome']) ?>"></div>
      <div class="col-md-2"><label class="form-label">Peso (kg)</label>
        <input name="peso_kg" class="form-control" value="<?= e($p['peso_kg']) ?>"></div>
      <div class="col-md-2"><label class="form-label">Valor (R$)</label>
        <input name="valor" class="form-control" value="<?= e($p['valor']) ?>"></div>
      <div class="col-12"><label class="form-label">Descrição</label>
        <input name="descricao" class="form-control" value="<?= e($p['descricao']) ?>"></div>
      <div class="col-12">
        <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar</button>
        <a href="<?= url($pagina) ?>" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </div></div>
<?php else:
    $lista = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll(); ?>
  <div class="d-flex justify-content-between align-items-center">
    <h1 class="titulo">Produtos</h1>
    <a href="<?= url($pagina) ?>?acao=form" class="btn btn-primary mb-3"><i class="bi bi-plus-lg"></i> Novo produto</a>
  </div>
  <div class="card"><div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead><tr><th>ID</th><th>Nome</th><th>Descrição</th><th>Peso</th><th>Valor</th><th class="text-end">Ações</th></tr></thead>
      <tbody>
      <?php foreach ($lista as $p): ?>
        <tr>
          <td><?= $p['id'] ?></td><td><?= e($p['nome']) ?></td><td><?= e($p['descricao']) ?></td>
          <td><?= number_format($p['peso_kg'], 2, ',', '.') ?> kg</td><td><?= moeda($p['valor']) ?></td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="<?= url($pagina) ?>?acao=form&id=<?= $p['id'] ?>"><i class="bi bi-pencil"></i></a>
            <form method="post" class="d-inline form-excluir">
              <input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= $p['id'] ?>">
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
      <?php endforeach; if (!$lista): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">Nenhum produto cadastrado.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div></div>
<?php endif; require __DIR__ . '/../includes/footer.php'; ?>
