<?php
require __DIR__ . '/../includes/funcoes.php';
$pdo = db();
$pagina = 'fretes/index.php';
$acao = $_GET['acao'] ?? 'listar';

// ----- Salvar / excluir -----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['acao'] ?? '') === 'excluir') {
        $pdo->prepare("DELETE FROM fretes WHERE id = ?")->execute([(int)$_POST['id']]);
        flash('success', 'Frete excluído.');
        redirect($pagina);
    }
    $id = (int)($_POST['id'] ?? 0);
    $volta = $pagina . '?acao=form' . ($id ? "&id=$id" : '');

    // junta produtos e quantidades enviados pelo formulário
    $itens = [];
    foreach (($_POST['produto_id'] ?? []) as $i => $pid) {
        $qtd = (int)($_POST['quantidade'][$i] ?? 0);
        if ((int)$pid > 0 && $qtd > 0) $itens[(int)$pid] = ($itens[(int)$pid] ?? 0) + $qtd;
    }
    if (!$itens) { flash('danger', 'Adicione pelo menos um produto.'); redirect($volta); }
    if ($_POST['origem_id'] === $_POST['destino_id']) { flash('danger', 'Origem e destino não podem ser iguais.'); redirect($volta); }
    if (!$_POST['origem_id'] || !$_POST['destino_id'] || !$_POST['transportador_id'] || !$_POST['data_frete']) {
        flash('danger', 'Preencha data, origem, destino e transportador.'); redirect($volta);
    }

    $dados = [$_POST['data_frete'], (int)$_POST['origem_id'], (int)$_POST['destino_id'],
              (int)$_POST['transportador_id'], numero($_POST['valor_frete']), trim($_POST['observacoes'])];
    $pdo->beginTransaction();
    if ($id) {
        $dados[] = $id;
        $pdo->prepare("UPDATE fretes SET data_frete=?, origem_id=?, destino_id=?, transportador_id=?, valor_frete=?, observacoes=? WHERE id=?")->execute($dados);
        $pdo->prepare("DELETE FROM frete_produtos WHERE frete_id = ?")->execute([$id]);
    } else {
        $pdo->prepare("INSERT INTO fretes (data_frete, origem_id, destino_id, transportador_id, valor_frete, observacoes) VALUES (?,?,?,?,?,?)")->execute($dados);
        $id = (int)$pdo->lastInsertId();
    }
    $ins = $pdo->prepare("INSERT INTO frete_produtos (frete_id, produto_id, quantidade) VALUES (?,?,?)");
    foreach ($itens as $pid => $qtd) $ins->execute([$id, $pid, $qtd]);
    $pdo->commit();
    flash('success', "Frete #$id salvo com sucesso!");
    redirect($pagina);
}

$titulo = 'Fretes';

// ----- Formulário -----
if ($acao === 'form') {
    $f = ['id' => '', 'data_frete' => date('Y-m-d'), 'origem_id' => '', 'destino_id' => '', 'transportador_id' => '', 'valor_frete' => '', 'observacoes' => ''];
    $itensFrete = [];
    if (!empty($_GET['id'])) {
        $st = $pdo->prepare("SELECT * FROM fretes WHERE id = ?");
        $st->execute([(int)$_GET['id']]);
        $f = $st->fetch() ?: $f;
        $st = $pdo->prepare("SELECT produto_id, quantidade FROM frete_produtos WHERE frete_id = ?");
        $st->execute([(int)$_GET['id']]);
        $itensFrete = $st->fetchAll();
    }
    $enderecos = $pdo->query("SELECT * FROM enderecos ORDER BY apelido")->fetchAll();
    $transp = $pdo->query("SELECT * FROM transportadores ORDER BY nome")->fetchAll();
    $produtos = $pdo->query("SELECT * FROM produtos ORDER BY nome")->fetchAll();
    $scriptExtra = 'assets/js/frete.js';
    require __DIR__ . '/../includes/header.php';

    // pequena função para montar as opções dos selects
    $opcoes = function (array $lista, string $campo, $sel) {
        $h = '<option value="">Selecione</option>';
        foreach ($lista as $o) {
            $h .= '<option value="' . $o['id'] . '"' . ($o['id'] == $sel ? ' selected' : '') . '>' . e($o[$campo]) . '</option>';
        }
        return $h;
    };
    $linhaProduto = function ($sel, $qtd) use ($produtos, $opcoes) { ?>
      <div class="item-produto row g-2 align-items-end">
        <div class="col-md-8"><label class="form-label">Produto</label>
          <select name="produto_id[]" class="form-select"><?= $opcoes($produtos, 'nome', $sel) ?></select></div>
        <div class="col-6 col-md-2"><label class="form-label">Qtd.</label>
          <input type="number" min="1" name="quantidade[]" class="form-control" value="<?= e($qtd) ?>"></div>
        <div class="col-6 col-md-2"><button type="button" class="btn btn-outline-danger w-100 btn-remover"><i class="bi bi-trash"></i> Remover</button></div>
      </div>
    <?php }; ?>
    <h1 class="titulo"><?= $f['id'] ? 'Editar frete #' . $f['id'] : 'Novo frete' ?></h1>
    <?php if (!$produtos || count($enderecos) < 2 || !$transp): ?>
      <div class="alert alert-warning">Antes de criar um frete, cadastre pelo menos <b>1 produto</b>, <b>2 endereços</b> e <b>1 transportador</b>.</div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="id" value="<?= e($f['id']) ?>">
      <div class="card mb-3"><div class="card-body row g-3">
        <div class="col-md-3"><label class="form-label">Data *</label>
          <input type="date" name="data_frete" class="form-control" required value="<?= e($f['data_frete']) ?>"></div>
        <div class="col-md-5"><label class="form-label">Transportador *</label>
          <select name="transportador_id" class="form-select" required><?= $opcoes($transp, 'nome', $f['transportador_id']) ?></select></div>
        <div class="col-md-4"><label class="form-label">Valor do frete (R$)</label>
          <input name="valor_frete" class="form-control" value="<?= e($f['valor_frete']) ?>"></div>
        <div class="col-md-6"><label class="form-label">Origem *</label>
          <select name="origem_id" class="form-select" required><?= $opcoes($enderecos, 'apelido', $f['origem_id']) ?></select></div>
        <div class="col-md-6"><label class="form-label">Destino *</label>
          <select name="destino_id" class="form-select" required><?= $opcoes($enderecos, 'apelido', $f['destino_id']) ?></select></div>
        <div class="col-12"><label class="form-label">Observações</label>
          <input name="observacoes" class="form-control" value="<?= e($f['observacoes']) ?>"></div>
      </div></div>

      <div class="card mb-3"><div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Produtos do frete</h5>
          <button type="button" id="btn-add-produto" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i> Adicionar produto</button>
        </div>
        <div id="lista-produtos">
          <?php foreach ($itensFrete as $it) $linhaProduto($it['produto_id'], $it['quantidade']); ?>
        </div>
        <template id="modelo-produto"><?php $linhaProduto('', 1); ?></template>
      </div></div>

      <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Salvar frete</button>
      <a href="<?= url($pagina) ?>" class="btn btn-secondary">Cancelar</a>
    </form>
    <?php require __DIR__ . '/../includes/footer.php'; return;
}

// ----- Detalhes -----
if ($acao === 'ver') {
    $st = $pdo->prepare("SELECT f.*, o.apelido AS o_apelido, o.logradouro AS o_logradouro, o.numero AS o_numero, o.bairro AS o_bairro, o.cidade AS o_cidade, o.uf AS o_uf,
        d.apelido AS d_apelido, d.logradouro AS d_logradouro, d.numero AS d_numero, d.bairro AS d_bairro, d.cidade AS d_cidade, d.uf AS d_uf,
        t.nome AS t_nome, t.telefone AS t_tel, t.tipo_veiculo AS t_veic, t.placa AS t_placa
        FROM fretes f JOIN enderecos o ON o.id=f.origem_id JOIN enderecos d ON d.id=f.destino_id
        JOIN transportadores t ON t.id=f.transportador_id WHERE f.id = ?");
    $st->execute([(int)($_GET['id'] ?? 0)]);
    $f = $st->fetch();
    if (!$f) { flash('danger', 'Frete não encontrado.'); redirect($pagina); }
    $st = $pdo->prepare("SELECT p.nome, p.peso_kg, p.valor, fp.quantidade FROM frete_produtos fp JOIN produtos p ON p.id=fp.produto_id WHERE fp.frete_id = ?");
    $st->execute([$f['id']]);
    $itens = $st->fetchAll();
    $pesoTotal = $valorTotal = 0;
    require __DIR__ . '/../includes/header.php'; ?>
    <h1 class="titulo">Frete #<?= $f['id'] ?> — <?= dataBr($f['data_frete']) ?></h1>
    <div class="row g-3 mb-3">
      <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h6 class="text-muted">Origem</h6><b><?= e($f['o_apelido']) ?></b><br>
        <?= e("{$f['o_logradouro']}, {$f['o_numero']} - {$f['o_bairro']}, {$f['o_cidade']}/{$f['o_uf']}") ?></div></div></div>
      <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h6 class="text-muted">Destino</h6><b><?= e($f['d_apelido']) ?></b><br>
        <?= e("{$f['d_logradouro']}, {$f['d_numero']} - {$f['d_bairro']}, {$f['d_cidade']}/{$f['d_uf']}") ?></div></div></div>
      <div class="col-md-4"><div class="card h-100"><div class="card-body">
        <h6 class="text-muted">Transportador</h6><b><?= e($f['t_nome']) ?></b><br>
        <?= e($f['t_veic']) ?> <?= e($f['t_placa']) ?><br><?= e($f['t_tel']) ?></div></div></div>
    </div>
    <div class="card mb-3"><div class="table-responsive"><table class="table mb-0">
      <thead><tr><th>Produto</th><th>Qtd.</th><th>Peso unit.</th><th>Valor unit.</th><th>Subtotal</th></tr></thead>
      <tbody>
      <?php foreach ($itens as $i):
          $pesoTotal += $i['peso_kg'] * $i['quantidade'];
          $valorTotal += $i['valor'] * $i['quantidade']; ?>
        <tr><td><?= e($i['nome']) ?></td><td><?= $i['quantidade'] ?></td>
          <td><?= number_format($i['peso_kg'], 2, ',', '.') ?> kg</td><td><?= moeda($i['valor']) ?></td>
          <td><?= moeda($i['valor'] * $i['quantidade']) ?></td></tr>
      <?php endforeach; ?>
      </tbody></table></div></div>
    <div class="card mb-3"><div class="card-body">
      <b>Peso total:</b> <?= number_format($pesoTotal, 2, ',', '.') ?> kg &nbsp;|&nbsp;
      <b>Valor da carga:</b> <?= moeda($valorTotal) ?> &nbsp;|&nbsp;
      <b>Valor do frete:</b> <?= moeda($f['valor_frete']) ?>
      <?php if ($f['observacoes']): ?><br><b>Obs.:</b> <?= e($f['observacoes']) ?><?php endif; ?>
    </div></div>
    <a href="<?= url($pagina) ?>" class="btn btn-secondary">Voltar</a>
    <a href="<?= url($pagina) ?>?acao=form&id=<?= $f['id'] ?>" class="btn btn-primary">Editar</a>
    <?php require __DIR__ . '/../includes/footer.php'; return;
}

// ----- Lista -----
$lista = $pdo->query("SELECT f.*, o.apelido AS origem, d.apelido AS destino, t.nome AS transportador,
    (SELECT COUNT(*) FROM frete_produtos fp WHERE fp.frete_id = f.id) AS qtd_produtos
    FROM fretes f JOIN enderecos o ON o.id=f.origem_id JOIN enderecos d ON d.id=f.destino_id
    JOIN transportadores t ON t.id=f.transportador_id ORDER BY f.data_frete DESC, f.id DESC")->fetchAll();
require __DIR__ . '/../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center">
  <h1 class="titulo">Fretes</h1>
  <a href="<?= url($pagina) ?>?acao=form" class="btn btn-primary mb-3"><i class="bi bi-plus-lg"></i> Novo frete</a>
</div>
<div class="card"><div class="table-responsive">
  <table class="table table-hover mb-0 align-middle">
    <thead><tr><th>ID</th><th>Data</th><th>Origem</th><th>Destino</th><th>Transportador</th><th>Produtos</th><th>Valor</th><th class="text-end">Ações</th></tr></thead>
    <tbody>
    <?php foreach ($lista as $f): ?>
      <tr>
        <td><?= $f['id'] ?></td><td><?= dataBr($f['data_frete']) ?></td><td><?= e($f['origem']) ?></td>
        <td><?= e($f['destino']) ?></td><td><?= e($f['transportador']) ?></td>
        <td><?= $f['qtd_produtos'] ?></td><td><?= moeda($f['valor_frete']) ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-secondary" href="<?= url($pagina) ?>?acao=ver&id=<?= $f['id'] ?>"><i class="bi bi-eye"></i></a>
          <a class="btn btn-sm btn-outline-primary" href="<?= url($pagina) ?>?acao=form&id=<?= $f['id'] ?>"><i class="bi bi-pencil"></i></a>
          <form method="post" class="d-inline form-excluir">
            <input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= $f['id'] ?>">
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
          </form>
        </td>
      </tr>
    <?php endforeach; if (!$lista): ?>
      <tr><td colspan="8" class="text-center text-muted py-4">Nenhum frete cadastrado.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
