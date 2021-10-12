
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
    // Home
    foreach ($array as $k => $v) {
        if (is_array($v)) {
            // Dados gerais
            foreach ($v as $k_ => $v_) {
                if ($k_ == 'id' and !is_array($v_)) $return[$v_] = $v['value'];
                if (is_array($v_)) {
                    // Conteudo da Pagina
                    foreach ($v_ as $k__ => $v__) {
                        if ($k__ == 'id' and !is_array($v__)) $return[$v__] = $v_['value'];
                        if (is_array($v__)) {
                            // id
                            foreach ($v__ as $k___ => $v___) {
                                if ($k___ == 'id' and !is_array($v___)) $return[$v___] = $v__['value'];
                            }
                        }
                    }
                }
            }
        }
    }
    $return = array_merge($array, $return);
    return $return;
}

function cleanData($array)
{

    $data_array = array();
    $tags = '<p><a><div><span><strong><em>';
    // key = Home
    foreach ($array as $k0 => $v0) {
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
                                if (is_array($v3)) {
                                    foreach ($v3 as $k4 => $v4) {
                                        $data_array[$k0][$k1][$k2][$k3][$k4] = strip_tags($v4, $tags);
                                    }
                                } else $data_array[$k0][$k1][$k2][$k3] = strip_tags($v3, $tags);
                            }
                        }
                        // Dont have key
                        else $data_array[$k0][$k1][$k2] = strip_tags($v2, $tags);
                    }
                } else $data_array[$k0][$k1] = strip_tags($v1, $tags);
            }
        } else $data_array[$k0] = strip_tags($v0, $tags);
    }
    return $data_array;
}

// scandir() order by date
function scan_dir($dir)
{
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}

function filesize_formatted($path)
{
    $size = filesize($path);
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
}

// str_replace first element
function str_replace_first($from, $to, $content)
{
    $from = '/' . preg_quote($from, '/') . '/';

    return preg_replace($from, $to, $content, 1);
}
