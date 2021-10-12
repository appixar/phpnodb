<?php
// global
include "../global.php";

//======================
// new password (client)
//======================
if (!$_SESSION['logged']['dev']) {
    header("Location: ../");
    exit;
}
$_SESSION['change_password_cli'] = rand(111111, 999999);
$hash = password_hash($_SESSION['change_password_cli'], PASSWORD_DEFAULT);
file_put_contents("../$fn_pass", '<?php $password = ' . "'$hash';");
header("Location: ../login.php?done=2");
