<?php
// global
include "../global.php";

// auth
if (!$_SESSION['logged']) {
    header("Location: ../login.php");
    exit;
}

// cli ?
$fn_target = $fn_pass;
$append_dev = "";
$correct_pass = $password;
// dev ?
if ($_SESSION['logged']['dev']) {
    $fn_target = $fn_pass_dev;
    $append_dev = "_dev";
    $correct_pass = $password_dev;
}

// change password ?
$p0 = $_POST['pass0'];
$p1 = $_POST['pass1'];
$p2 = $_POST['pass2'];
unset($_POST['pass0']);
unset($_POST['pass1']);
unset($_POST['pass2']);

//================================
// change pass
//================================
if ($p1 !== $p2) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'As senhas não coincidem. Por favor, digite cuidadosamente.';
} elseif (!password_verify($p0, $correct_pass)) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'Senha incorreta.';
} elseif (!validatepass($p1)) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'A senha deve conter no mínimo 6 caracteres, maiúsculo, minúsculo, e símbolo.';
} elseif (!is_writeable("../$fn_pass")) {
    $_SESSION['cb']['type'] = 'danger';
    $_SESSION['cb']['text'] = 'Não foi possível alterar a senha. Verifique as permissões de escrita para o arquivo <strong>data-password.php</strong>';
} else {
    $_SESSION['cb']['type'] = 'info';
    $_SESSION['cb']['text'] = 'Senha alterada com sucesso.';
    $new_password = password_hash($p1, PASSWORD_DEFAULT);
    $_SESSION['logged']['password'] = $p1;
    file_put_contents("../$fn_target", '<?php $password' . $append_dev . " = '" . $new_password . "';");
    header("Location: ../?p=cb");
    exit;
}
header("Location: {$_SERVER['HTTP_REFERER']}");
