# phpNoDB

phpNoDB é o CMS para sites PHP sem banco de dados. As informações são gerenciadas através de um painel adminstrativo simples e intuitivo, e os dados dinâmicos do site ficam armazenadas no sistema de arquivos em formato JSON.

## Pré-requisitos

- PHP 7.2+

## Instalação

```
cd site_directory/
git clone https://github.com/appixar/phpnodb.git
```
> **IMPORTANTE**: É aconselhável alterar o nome do diretório /phpnodb para um nome secreto que só você e o seu cliente conhecem.

## Acessando painel administrativo

Após instalado, basta acessar pelo navegador: **seusite.com/phpnodb**

Você deverá criar uma senha para uso do Desenvolvedor, e outra para uso do Cliente.

A senha do Desenvolvedor possui privilégios para alterar a estrutura dos dados JSON.

## Estrutura do site com JavaScript

- **site_directory/index.php**
```
<!-- Estrutura do site original -->
<html>
<head>
    // Mostrar dados na página
    <title x-data='titulo'><title>
</head>
<body>
    <p>Olá, visitante!</p>
    <p>Total de lucros gerados no último mês: <span x-data='lucro'></span></p>
    ...
    <script src='phpnodb/phpnodb.js'></script>
```

## Estrutura do site com PHP

- **site_directory/index.php**
```
<?php
// Incluir dados de phpNoDB
include "phpnodb/global.php"; // Agora array $data contém todos os dados dinâmicos do site
?>

<!-- Estrutura do site original -->
<html>
<head>
    // Mostrar dados na página
    <title><?= $data['titulo'] ?><title>
</head>
<body>
    <p>Olá, visitante!</p>
    <p>Total de lucros gerados no último mês: <?= $data['lucro'] ?></p>
    ...
```

## Configuração dos dados dinâmicos
- **site_directory/phpnodb/data/public/data.json**
```
#--------------------------------------------------------------
# Exemplo:
#--------------------------------------------------------------
{
    "Home": {
        "Dados gerais": {
            "Título da Página": {
                "id": "titulo",
                "type": "text",
                "value": "<strong>teste</strong>"
            },
            "Quem somos?": "Uma família feliz :)"
        }
    }
}
#--------------------------------------------------------------
# Formatos gerados para exibição no site:
#
#   $data['titulo']
#     Possui id, logo pode ser acessado através do atalho.
#
#   $data['Home']['Dados gerais']['Quem somos?']
#     Não possui id, deve ser acessado pelo caminho completo.
#--------------------------------------------------------------
```