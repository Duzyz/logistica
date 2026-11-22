// Adiciona uma nova linha de produto no formulário do frete
function adicionarProduto() {
  var modelo = document.getElementById("modelo");
  var novaLinha = modelo.content.cloneNode(true);
  document.getElementById("produtos").appendChild(novaLinha);
}

// Remove a linha do produto (mantém pelo menos uma)
function removerProduto(botao) {
  var linhas = document.getElementsByClassName("linha-produto");
  if (linhas.length > 1) {
    botao.closest(".linha-produto").remove();
  } else {
    alert("O frete precisa ter pelo menos um produto.");
  }
}

// Se o frete for novo, já começa com uma linha de produto
if (document.getElementById("produtos").children.length == 0) {
  adicionarProduto();
}
