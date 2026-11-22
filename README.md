# Gerenciador Logístico

Sistema feito em PHP para controlar fretes. Dá para cadastrar produtos, endereços (origem e destino)
e transportadores. Depois é possível criar um frete, e um transportador pode levar vários produtos.

## Tecnologias usadas
- PHP
- MySQL (XAMPP)
- HTML, CSS e JavaScript
- Bootstrap 5
- Git e GitHub

## O que o sistema faz
- Cadastrar, editar, listar e excluir produtos
- Cadastrar, editar, listar e excluir endereços
- Cadastrar, editar, listar e excluir transportadores
- Criar fretes com vários produtos (os campos de produto são adicionados com JavaScript)
- Ver os detalhes de cada frete
- Baixar o relatório dos fretes de um dia

## Pastas do projeto
```
logistica/
├── index.php            página inicial
├── conexao.php          conexão com o banco
├── menu.php             topo e menu das páginas
├── rodape.php           final das páginas
├── banco.sql            criação do banco de dados
├── css/                 estilos
├── js/                  JavaScript
├── produtos/            listar, form, salvar, excluir
├── enderecos/           listar, form, salvar, excluir
├── transportadores/     listar, form, salvar, excluir
├── fretes/              listar, form, salvar, excluir, ver
├── relatorios/          relatório e download
└── docs/                esboços das telas (Figma)
```

## Como rodar
1. Instale o XAMPP e ligue o **Apache** e o **MySQL**.
2. Copie a pasta do projeto para `C:\xampp\htdocs\logistica`.
3. Abra `http://localhost/phpmyadmin`, vá em **Importar** e escolha o arquivo `banco.sql`.
4. Acesse `http://localhost/logistica/`.

## Autor
Gabriel
