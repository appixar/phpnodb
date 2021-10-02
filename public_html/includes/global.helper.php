
<?php
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

function getDataById($array)
{
    $return = array();
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            foreach ($v as $k_ => $v_) {
                if ($k_ == 'id' and !is_array($v_)) $return[$v_] = $v['value'];
                if (is_array($v_)) {
                    foreach ($v_ as $k__ => $v__) {
                        if ($k__ == 'id' and !is_array($v__)) $return[$v__] = $v_['value'];;
                    }
                }
            }
        }
    }
    return $return;
}
