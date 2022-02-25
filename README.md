# phpNoDB Light CMS

```
// Obter dados de phpNoDB
$data = json_decode(file_get_contents('./phpnodb/data/public/data.json'), true);

// Inserir dados na página
<title><?= $data['titulo'] ?><title>
```