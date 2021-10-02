<?php
session_start();
//
$dev = "";
if ($_SESSION['logged']['dev']) $dev = "?dev=1";
//
session_destroy();
header("Location: ../login.php$dev");
exit;
