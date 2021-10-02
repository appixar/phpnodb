<?php
// global
include "../global.php";

//echo '<pre>';print_r($_POST); exit;

// auth
if (!$_SESSION['logged']) {
    header("Location: ../login.php");
    exit;
}

// change password ?
$p0 = $_POST['pass0'];
$p1 = $_POST['pass1'];
$p2 = $_POST['pass2'];
unset($_POST['pass0']);
unset($_POST['pass1']);
unset($_POST['pass2']);

// clean data, create $data_array from $_POST
$data_array = $_POST;
$data_array = array();
$tags = '<p><a><div><span><strong><em>';
// key = Home
foreach ($_POST as $k0 => $v0) {
    $k0 = str_replace("_", " ", $k0); // fix spaces
    if (is_array($v0)) {
        // key = Dados gerais
        foreach ($v0 as $k1 => $v1) {
            if (is_array($v1)) {
                // key = Conteúdo da Página
                foreach ($v1 as $k2 => $v2) {
                    // key = Parâmetros
                    if (is_array($v2)) {
                        foreach ($v2 as $k3 => $v3) {
                            $data_array[$k0][$k1][$k2][$k3] = strip_tags($v3, $tags);
                        }
                    }
                    // Dont have key
                    else $data_array[$k0][$k1][$k2] = strip_tags($v2, $tags);
                }
            }
        }
    }
}
//print_r($data_array);exit;
//================================
// save new data
//================================
$data_array_old = json_decode(file_get_contents("../" . $fn_data), true);
$data_array = array_merge($data_array_old, $data_array);
$data = json_encode($data_array, true);
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
$fn_log = '../data/logs/data/' . time() . '-' . $_SESSION['logged']['token'] . '.json';
$content = array();
$content['auth'] = $_SESSION['logged']['ctime'] . '-' . $_SESSION['logged']['token'] . '.json';
$content['old'] = $_SESSION['current_data'];
$content['new'] = $data;
if ($content['old'] == $content['new']) {
    $content['old'] = null;
    $content['new'] = null;
}
//if ($newpass > 0) $content['new_pass'] = $new_password;
$content = json_encode($content, true);
file_put_contents($fn_log, $content);
if (!is_writeable($fn_log)) {
    $_SESSION['cb']['type'] = 'warning';
    $_SESSION['cb']['text'] = 'Não foi possível criar um backup. Verifique as permissões do arquivo.';
}

header("Location: {$_SERVER['HTTP_REFERER']}");
