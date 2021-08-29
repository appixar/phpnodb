<?php
session_start();

$fn_data = 'data/public/data.json';
$fn_pass = 'data/private/password.php';

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
    if ($text) {
        echo '<div style="margin-top:20px" class="cb alert alert-' . $type . '" role="alert">' . $text . '</div>';
    }
    unset($_SESSION['cb']);
}

function randomStr($length = 10)
{
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
}

function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
