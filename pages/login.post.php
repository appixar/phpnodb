<?php
// global
include "../includes/global.php";
// get $password
include "../$fn_pass";
//======================
// get browser data
//======================
require_once('../vendor/BrowserDetection.php');
$Browser = new foroco\BrowserDetection();
$useragent = $_SERVER['HTTP_USER_AGENT'];
$result = $Browser->getAll($useragent);
//
$log['token'] = randomStr(16);
$log['ip'] = getIp();
$log['date'] = date("Y-m-d H:i:s");
$log['ctime'] = time();
$log['os'] = $result['os_title'];
$log['browser'] = $result['browser_title'];
$log['device'] = $result['device_type'];

//======================
// new pass / first acc
//======================
if ($_POST['first'] and !$password) {
    $p0 = $_POST['pass0'];
    $p1 = $_POST['pass1'];

    if ($p0 !== $p1) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'As senhas não coincidem.';
    } elseif (!validatepass($p1)) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'Senha fraca.';
    } elseif (!is_writeable("../$fn_pass")) {
        $_SESSION['cb']['type'] = 'danger';
        $_SESSION['cb']['text'] = 'Não foi possível alterar a senha. Verifique as permissões de escrita.';
    } else {
        $_SESSION['cb']['type'] = 'info';
        $_SESSION['cb']['text'] = 'Senha criada com sucesso.';
        $new_password = hash('SHA512', $p0);
        file_put_contents("../$fn_pass", '<?php $password = "' . $new_password . '";');
        header("Location: ../login.php?done=1");
        exit;
    }
    header("Location: ../login.php");
    exit;
}

//======================
// auth
//======================
$p = hash('SHA512', $_POST['password']);
if ($p !== $password) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'Senha incorreta';
    // write error log
    $log['error_pass'] = $_POST['password'];
    $fn_error = '../data/logs/error/' . $log['ctime'] . '-' . $log['token'] . '.json';
    unset($log['ctime']);
    unset($log['token']);
    $content = json_encode($log, true);
    file_put_contents($fn_error, $content);
} else {
    // write auth log
    $fn_auth = '../data/logs/auth/' . $log['ctime'] . '-' . $log['token'] . '.json';
    $content = json_encode($log, true);
    file_put_contents($fn_auth, $content);
    if (!is_writeable($fn_auth)) {
        $_SESSION['cb']['type'] = 'warning';
        $_SESSION['cb']['text'] = 'Não foi possível registrar o acesso. Verifique as permissões do arquivo.';
    }
    $_SESSION['logged'] = $log;
}
header("Location: ../");
exit;
