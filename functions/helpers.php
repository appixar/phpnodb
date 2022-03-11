
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

function phpNoDB($pages)
{
    $return = array();
    // Pages
    foreach ($pages as $pageName => $page) {
        if (is_array($page)) {
            // Sections
            foreach ($page as $sectionName => $section) {
                if ($sectionName == 'id' and !is_array($section)) $return[$section] = $page['value'];
                if (is_array($section)) {
                    // Elements
                    foreach ($section as $elementName => $element) {
                        // Single Element
                        if ($elementName == 'id' and !is_array($element)) $return[$element] = $section['value'];
                        if (is_array($element)) {
                            foreach ($element as $itemName => $item) {
                                if ($itemName == 'id' and !is_array($item)) $return[$item] = $element['value'];
                            }
                        }
                        // List Element
                        if (is_numeric($elementName) and is_array($element)) {
                            foreach ($element as $itemName => $item) {
                                if (is_array($item)) {
                                    foreach ($item as $propName => $prop) {
                                        if ($propName == 'id') $return[$prop][$elementName] = $item['value'];
                                    }
                                }
                                
                            }
                        }
                    }
                }
            }
        }
    }
    $return = array_merge($pages, $return);
    return $return;
}

function cleanData($array)
{

    $data_array = array();
    $tags = '<p><a><u><i><div><span><strong><em><br><script><h1><h2><h3><h4><h5><ul><li><noscript><img><small><main><nav><footer><iframe>';
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
                                        //$data_array[$k0][$k1][$k2][$k3][$k4] = strip_tags($v4, $tags);
                                        $data_array[$k0][$k1][$k2][$k3][$k4] = $v4;
                                    }
                                } else {
                                    //$data_array[$k0][$k1][$k2][$k3] = strip_tags($v3, $tags);
                                    $data_array[$k0][$k1][$k2][$k3] = $v3;
                                }
                            }
                        }
                        // Dont have key
                        else {
                            //$data_array[$k0][$k1][$k2] = strip_tags($v2, $tags);
                            $data_array[$k0][$k1][$k2] = $v2;
                        }
                    }
                } else {
                    //$data_array[$k0][$k1] = strip_tags($v1, $tags);
                    $data_array[$k0][$k1] = $v1;
                }
            }
        } else {
            //$data_array[$k0] = strip_tags($v0, $tags);
            $data_array[$k0] = $v0;
        }
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
