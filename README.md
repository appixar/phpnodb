# phpNoDB

phpNoDB é o CMS para sites PHP sem banco de dados. As informações ficam armazenadas no sistema de arquivos do site.

## Pré-requisitos

- PHP 7.2+

## Instalação

```
cd site_directory/
git clone https://github.com/appixar/phpnodb.git
```

## Estrutura do site com phpNoDB

```
<?php
// Obter dados de phpNoDB
$data = json_decode(file_get_contents('./phpnodb/data/public/data.json'), true);
?>

<!-- Estrutura do site original -->
<html>
<head>
    // Inserir dados na página
    <title><?= $data['titulo'] ?><title>
</head>
<body>
    <p>Olá, visitante!</p>
    <p>Total de lucros gerados no último mês: <?= $data['lucro'] ?></p>
    ...
```