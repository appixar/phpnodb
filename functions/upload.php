<?php
function upload($options = array())
{
    // upload options
    if (!$options) {
        $options = array(
            "name" => 'file',
            "size" => 2, // mb
            "dir" => __DIR__ . 'upload/'
        );
    }
    $error = false;

    if (empty($_FILES)) {
        $error = "Arquivo não selecionado.";
        goto jump;
    }

    $image = $_FILES[$options['name']];

    if ($image['error'] !== 0) {
        if ($image['error'] === 1) {
            $error = 'Tamanho máximo da imagem excedido.';
            goto jump;
        }
        $error = 'Erro de upload (ini).';
        goto jump;
    }

    if (!file_exists($image['tmp_name'])) {
        $error = 'Imagem não encontrada no servidor.';
        goto jump;
    }

    $maxFileSize = $options['size'] * 10e6; // = 2 000 000 bytes = 2MB
    if ($image['size'] > $maxFileSize) {
        $error = 'Tamanho máximo da imagem excedido (2mb).';
        goto jump;
    }

    $imageData = getimagesize($image['tmp_name']);
    if (!$imageData) {
        $error = 'Imagem inválida.';
        goto jump;
    }

    $mimeType = $imageData['mime'];
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mimeType, $allowedMimeTypes)) {
        $error = 'Formatos permitidos: PNG, JPG ou GIF.';
        goto jump;
    }

    // file ext
    $ext = end(explode('.', $image['name']));

    // new file name
    $random_lenght = 6;
    $random_str = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($random_lenght / strlen($x)))), 1, $random_lenght);
    $fn = $random_str . '.' . $ext;
    $path = $options['dir'] . '/' . $fn;

    // save file
    $isUploaded = move_uploaded_file($_FILES[$options['name']]["tmp_name"], $path);

    if ($isUploaded) return array('success' => $fn);
    else $error = 'Não foi possível mover o arquivo.';

    jump:
    if ($error) return array('error' => $error);
    else return false;
}
