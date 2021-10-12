<?php
// global
include "../global.php";

// from config.php or page.php?
if ($_POST['p'] == 'config') {
    // auth dev
    if (!$_SESSION['logged']['dev']) {
        header("Location: ../login.php?dev=1");
        exit;
    }
    $fn_data = $fn_conf;
    $fn_append = '-conf';
}
unset($_POST['p']);

// auth
if (!$_SESSION['logged']) {
    header("Location: ../login.php");
    exit;
}

// clean data, create $data_array from $_POST
$data_array = cleanData($_POST);
//$data_array = $_POST;
//echo '<pre>';print_r($data_array);exit;
//================================
// save new data
//================================
$data_array_old = json_decode(file_get_contents("../" . $fn_data), true);
$data_array = array_merge($data_array_old, $data_array);
$data = json_encode($data_array, true);
//echo $data; exit;
if (!is_writeable("../$fn_data")) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'Desculpe, não foi possível salvar as alterações.';
} else {
    $_SESSION['cb']['type'] = 'info';
    $_SESSION['cb']['text'] = 'Alterações efetuadas com sucesso em ' . date("d/m") . ' às ' . date("H:i:s");
}
file_put_contents("../$fn_data", $data);

//================================
// save old data (log)
//================================
$fn_log = '../data/logs/data/' . time() . '-' . $_SESSION['logged']['token'] . $fn_append . '.json';
$content = array();
$content['auth_token'] = $_SESSION['logged']['token'];
$content['old'] = $data_array_old;
$content['new'] = $data;
if ($content['old'] == $content['new']) {
    goto ignore_log;
}
$content = json_encode($content, true);
file_put_contents($fn_log, $content);
if (!is_writeable($fn_log)) {
    $_SESSION['cb']['type'] = 'warning';
    $_SESSION['cb']['text'] = 'Não foi possível criar um backup. Verifique as permissões do arquivo.';
}
ignore_log:
header("Location: {$_SERVER['HTTP_REFERER']}");
