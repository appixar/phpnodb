<?php
// global
include "../global.php";
include "../functions/upload.php";

// auth
if (!$_SESSION['logged']) {
    header("Location: ../login.php");
    exit;
}
// remove file
if ($_GET['del']) {
    $fn = basename($_GET['del']);
    $path = '../data/upload/' . $fn;
    if (file_exists($path)) {
        unlink($path);
    }
}

// check files
if (!$_FILES) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
// max count files
$total = intval(count(scandir('../data/upload/')) - 2);
if ($conf_id['sys_up_maxcount'] <= $total) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'Limite de imagens excedido.';
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}

// upload options
$options = array(
    "name" => 'file',
    "size" => 2,
    "dir" => __DIR__ . '/../data/upload/'
);

// save file
$upload = upload($options);
if ($upload['success']) {
    $_SESSION['cb']['type'] = 'info';
    $_SESSION['cb']['text'] = 'Upload concluído em ' . date("d/m") . ' às ' . date("H:i:s");
} elseif ($upload['error']) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = $upload['error'];
} else {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'upload() fail';
}
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
