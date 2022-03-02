<?php
// global
include "../global.php";

//======================
// get browser data
//======================
require_once('../vendors/BrowserDetection.php');
$Browser = new foroco\BrowserDetection();
$useragent = $_SERVER['HTTP_USER_AGENT'];
$result = $Browser->getAll($useragent);
//
$log['token'] = randomStr(8);
$log['ip'] = getIp();
$log['date'] = date("Y-m-d H:i:s");
$log['ctime'] = time();
$log['os'] = $result['os_title'];
$log['browser'] = $result['browser_title'];
$log['device'] = $result['device_type'];
//
if ($_POST['dev']) $log['dev'] = '-dev';

//======================
// new password (dev)
//======================
if ($_POST['first'] and !$password_dev) {
    $p0 = $_POST['pass0'];
    $p1 = $_POST['pass1'];

    if ($p0 !== $p1) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'As senhas não coincidem.';
    } elseif (!validatepass($p1)) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'Senha fraca.';
    } elseif (!is_writeable("../$fn_pass_dev")) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'Não foi possível alterar a senha. Verifique as permissões de escrita.';
    } else {
        /*$_SESSION['cb']['type'] = 'info';
        $_SESSION['cb']['text'] = 'Senha criada com sucesso.';*/
        $_SESSION['logged']['dev'] = 1;
        $new_password = password_hash($p0, PASSWORD_DEFAULT);
        file_put_contents("../$fn_pass_dev", '<?php $password_dev = ' . "'" . $new_password . "';");
        header("Location: ../login.php?done=1");
        exit;
    }
    header("Location: ../login.php");
    exit;
}

//======================
// auth client
//======================
$p = $_POST['password'];

// set vars
if ($_POST['dev']) {
    $correct_pass = $password_dev;
} else {
    $correct_pass = $password;
}
// error
if (!password_verify($p, $correct_pass)) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'Senha incorreta.';
    // write error log
    $log['error_pass'] = $_POST['password'];
    $fn_error = '../data/logs/error/' . $log['ctime'] . '-' . $log['token'] . $log['dev'] . '.json';
    unset($log['ctime']);
    unset($log['token']);
    $content = json_encode($log, true);
    file_put_contents($fn_error, $content);
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}
// success
else {
    // write auth log
    $fn_auth = '../data/logs/auth/' .  $log['ctime'] . '-' . $log['token'] . $log['dev'] . '.json';
    $content = json_encode($log, true);
    file_put_contents($fn_auth, $content);
    if (!is_writeable($fn_auth)) {
        $_SESSION['cb']['type'] = 'warning';
        $_SESSION['cb']['text'] = 'Não foi possível registrar o acesso. Verifique as permissões do arquivo.';
    }
    $log['password'] = $p;
    $_SESSION['logged'] = $log;
}
header("Location: ../live.php");
exit;
