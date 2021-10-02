<?php
session_start();

$fn_conf = 'data/public/config.json';
$fn_data = 'data/public/data.json';
$fn_pass = 'data/private/password_cli.php';
$fn_pass_dev = 'data/private/password_dev.php';

include __DIR__ . '/' . $fn_pass;
include __DIR__ . '/' . $fn_pass_dev;

// GET CONFIG
$conf = json_decode(file_get_contents($fn_conf), true); // php array
$_SESSION['current_conf'] = file_get_contents($fn_conf); // json

// GET DATA
$data = json_decode(file_get_contents($fn_data), true); // php array
$_SESSION['current_data'] = file_get_contents($fn_data); // json

// GLOBAL FUNCTIONS

function validatepass($password)
{
    // Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 6) {
        return false;
    }
    return true;
}

function cb($type = '', $text = '')
{
    global $_SESSION;
    if ($text == '') {
        $text = $_SESSION['cb']['text'];
        $type = $_SESSION['cb']['type'];
    }
    if ($type == "warning") $ico = "fa fa-exclamation-triangle";
    if ($type == "danger") $ico = "fa fa-times";
    if ($type == "info") $ico = "fa fa-info-circle";
    if ($text) {
        echo '<div class="cb mt-2 alert alert-' . $type . '" role="alert"><i class="' . $ico . '"></i></a> &nbsp;' . $text . '</div>';
    }
    unset($_SESSION['cb']);
}

function randomStr($length = 10)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

function getIp()
{
    // CLOUDFLARE PROXY
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"]; // SOMETIMES RETURN IPV6
    }
    // OTHER VERIFICATIONS...
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}
