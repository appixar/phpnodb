<?php
// global
include "../includes/global.php";

//print_r($_POST); exit;

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

// clean data
foreach ($_POST as $k => $v) {
    //$_POST[$k] = strip_tags($v, '<p><a><div><span><strong><em>');
}

//================================
// save new data
//================================
$data = json_encode($_POST, true);
if (!is_writeable("../$fn_data")) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'Desculpe, não foi possível salvar as alterações.';
} else {
    $_SESSION['cb']['type'] = 'info';
    $_SESSION['cb']['text'] = 'Alterações efetuadas com sucesso em ' . date("d/m") . ' às ' . date("H:i:s");
}
file_put_contents("../$fn_data", $data);

//================================
// change pass
//================================
if ($p1) {
    $newpass = 0;
    include "../$fn_pass"; // get $password
    $new_password = hash('SHA512', $p1);
    $old_password = hash('SHA512', $p0);
    if ($p1 !== $p2) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'As senhas não coincidem. Por favor, digite cuidadosamente.';
    } elseif ($old_password !== $password) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'Senha incorreta.';
    } elseif (!validatepass($p1)) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'A senha deve conter no mínimo 6 caracteres, maiúsculo, minúsculo, e símbolo.';
    } elseif (!is_writeable("../$fn_pass")) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'Não foi possível alterar a senha. Verifique as permissões de escrita para o arquivo <strong>data-password.php</strong>';
    } else {
        $newpass = 1;
        file_put_contents("../$fn_pass", '<?php $password = "' . $new_password . '";');
    }
}


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
if ($newpass > 0) $content['new_pass'] = $new_password;
$content = json_encode($content, true);
file_put_contents($fn_log, $content);
if (!is_writeable($fn_log)) {
    $_SESSION['cb']['type'] = 'warning';
    $_SESSION['cb']['text'] = 'Não foi possível criar um backup. Verifique as permissões do arquivo.';
}

header("Location: ../");
