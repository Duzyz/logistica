-- Rode este arquivo no phpMyAdmin (aba "Importar" ou "SQL")
CREATE DATABASE IF NOT EXISTS logistica CHARACTER SET utf8 COLLATE utf8_general_ci;
USE logistica;

CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  descricao VARCHAR(200),
  peso DECIMAL(10,2),
  valor DECIMAL(10,2)
);

CREATE TABLE enderecos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  apelido VARCHAR(80) NOT NULL,
  cep VARCHAR(9),
  rua VARCHAR(150) NOT NULL,
  numero VARCHAR(10),
  bairro VARCHAR(80),
  cidade VARCHAR(80) NOT NULL,
  uf CHAR(2) NOT NULL
);

CREATE TABLE transportadores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  cpf_cnpj VARCHAR(18),
  telefone VARCHAR(20),
  veiculo VARCHAR(40),
  placa VARCHAR(10)
);

CREATE TABLE fretes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  data_frete DATE NOT NULL,
  origem_id INT NOT NULL,
  destino_id INT NOT NULL,
  transportador_id INT NOT NULL,
  valor DECIMAL(10,2),
  observacao VARCHAR(200),
  FOREIGN KEY (origem_id) REFERENCES enderecos(id),
  FOREIGN KEY (destino_id) REFERENCES enderecos(id),
  FOREIGN KEY (transportador_id) REFERENCES transportadores(id)
);

-- liga o frete com varios produtos (1 frete para muitos produtos)
CREATE TABLE frete_produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  frete_id INT NOT NULL,
  produto_id INT NOT NULL,
  quantidade INT NOT NULL,
  FOREIGN KEY (frete_id) REFERENCES fretes(id) ON DELETE CASCADE,
  FOREIGN KEY (produto_id) REFERENCES produtos(id)
);
