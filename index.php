<?php
// CB
include "global.php";

// MODAL?
if (@$_GET['modal']) $_SESSION['modal'] = true;
elseif (@$_GET['no_modal']) unset($_SESSION['modal']);

// FIND KEY
if (@$_GET['key']) {
    $key = $_GET['key'];
    $pageNumber = 1;
    foreach ($data as $pageName => $page) {
        $sectionNumber = 1;
        foreach ($page as $sectionName => $section) {
            foreach ($section as $element) {
                // Single Element
                if ($element['id'] === $key) {
                    header("Location: ./?p=$pageNumber&section=$sectionNumber&element=$key#goto_$key");
                    exit;
                }
                // List Element
                foreach ($element as $itemName => $item) {
                    if ($item['id'] === $key) {
                        header("Location: ./?p=$pageNumber&section=$sectionNumber&element=$key&noScroll=1#goto_$key");
                        exit;
                    }
                }
            }
            $sectionNumber++;
        }
        $pageNumber++;
    }
    echo $key;
    //print_r($data);
    exit;
}

// GET CURRENT PAGE
$p = $_GET['p'];
if (!$p) $p = 1;
if (is_numeric($p) or $p == "config") $fn_include = "page";
$pages = array("json", "history", "log", "pass", "pass-reset", "cb", "theme", "images");
if (in_array($p, $pages)) $fn_include = $p;

// AUTH
if (!$_SESSION['logged']) {
    header("Location: login.php");
    exit;
}
// CB IS THE ONLY PAGE WITHOUT VERIFY PASS INTEGRITY (BUGFIX AFTER CHANGE PASS)
if ($p != "cb") {
    // CURRENT PASS DEV
    if ($_SESSION['logged']['dev'] and (!$password_dev or !password_verify($_SESSION['logged']['password'], $password_dev))) {
        header("Location: pages/logout.php");
        exit;
    }
    // CURRENT PASS CLI
    if (!$_SESSION['logged']['dev'] and (!$password or !password_verify($_SESSION['logged']['password'], $password))) {
        //echo 1; exit;
        header("Location: pages/logout.php");
        exit;
    }
}

// GET CURRENT PAGE TITLE
$page = '';
$x = 0;
foreach ($data_full as $k => $v) {
    $x++;
    if ((!$p and $x == 1) or $p == $x) $page = $k;
}
if (!$page) $page = ucfirst($p);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="J. W. Balaniuc">
    <title>[NoDB] <?= $conf['sys_name'] ?> ?? <?= $page ?></title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/img/logo-ico32.png" />
    <meta name="theme-color" content="#7952b3">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;500&display=swap">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/custom.css" rel="stylesheet">
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <!-- Jquery & Forms -->
    <script src="assets/js/jquery.min.js"></script>
    <link href="assets/css/form-validation.css" rel="stylesheet">
    <script src="assets/js/form-validation.js"></script>

    <?php if (is_numeric($p) or $p == "config") { ?>
        <!-- Trumbowyg -->
        <link href="assets/css/trumbowyg.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.1/trumbowyg.min.js"></script>
    <?php } ?>

    <?php if ($p == 'json' or $p == 'theme') { ?>
        <!-- Code Highlight -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/styles/default.min.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.2.0/highlight.min.js"></script>
        <script>
            hljs.highlightAll();
        </script>
    <?php } ?>

    <!-- Jw -->
    <link href="assets/css/jw.css?<?= time() ?>" rel="stylesheet">
</head>

<body class="bg-light" cz-shortcut-listen="true">

    <div class="container mt-2">
        <main>

            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <?php if (!@$_SESSION['modal']) { ?>
                        <a class="navbar-brand" href="./live.php">
                            <img src="assets/img/logo.png" alt="" width="48">
                            &nbsp; <?= $conf['sys_name'] ?> <span class='sm text-muted'> > <?= $page ?></span>
                        </a>
                    <?php } ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-0 mb-lg-0">
                            <?php
                            $x = 0;
                            foreach ($data_full as $k => $v) {
                                $x++;
                                $active = '';
                                if ((!$p and $x == 1) or $p == $x) $active = 'active';
                            ?>
                                <li class="nav-item pb-1">
                                    <a class="nav-link <?= $active ?>" aria-current="page" href="./?p=<?= $x ?>">
                                        <i class="sm fa fa-file-text-o"></i>&nbsp;<?= $k ?>
                                    </a>
                                </li>
                            <?php
                            }
                            ?>
                        </ul>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link <?= ($p == 'images' ? 'active' : '') ?>" href="./?p=images">
                                    <i class="fa fa-image"></i>
                                    <span class='sm'>&nbsp;Galeria</span>
                                </a>
                            </li>
                            <?php if ($_SESSION['logged']['dev']) { ?>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($p == 'json' ? 'active' : '') ?>" href="./?p=json">
                                        <i class="fa fa-code"></i>
                                        <span class='sm'>&nbsp;JSON Editor</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= ($p == 'config' ? 'active' : '') ?>" href="./?p=config">
                                        <i class="fa fa-cog"></i>
                                        <span class='sm'>&nbsp;Defini????es</span>
                                    </a>
                                </li>
                                <!--<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-cog"></i>
                                        <span class='sm'>&nbsp; Ajustes</span>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="./?p=theme">Apar??ncia</a></li>
                                        <li><a class="dropdown-item" href="./?p=config">Defini????es</a></li>
                                    </ul>
                                </li>
                                -->
                            <?php } ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <!--<p style='margin-bottom:0;font-size:24px;text-align:center'><i class="fa fa-address-book"></i></p>-->
                                    <i class="fa fa-user"></i>
                                    <span class='sm'>&nbsp;Minha conta</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="#">Hist??rico</a></li>
                                    <li><a class="dropdown-item" href="#">Log de acessos</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="./?p=pass">Alterar senha</a></li>

                                    <?php if ($_SESSION['logged']['dev']) { ?>
                                        <li><a class="dropdown-item" href="./?p=pass-reset">Resetar senha do cliente</a></li>
                                    <?php } ?>

                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="pages/logout.php">Logout</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>

            <?php
            cb();
            if (!is_writeable($fn_data)) {
                cb('warning', "Verifique as permiss??es de escrita para o arquivo <strong>$fn_data</strong>");
            }
            ?>

            <?php include "pages/$fn_include.php"; ?>

            <!--<p class='text-center mb-5'>
                <a href='pages/logout.php' class="w-100">Logout</a>
            </p>-->


        </main>

        <?php include 'pages/_partials/footer.php'; ?>

    </div>


    <script>
        // enable bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        // enable popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })
    </script>
    <?php if (is_numeric($p)) { ?>
        <script type="text/javascript">
            $('.textarea').trumbowyg({
                btns: [
                    ['strong', 'em', 'formatting'],
                    [
                        ['link'],
                        ['insertImage']
                    ],
                    ['justifyLeft', 'justifyCenter', 'justifyRight'], //, 'justifyFull'
                    ['viewHTML'],
                    ['fullscreen']
                ],
                autogrow: true,
                autogrowOnEnter: true
            });
        </script>
    <?php } ?>

    <script>
        <?php if (@!$_GET['noScroll']) { ?>
            $(function() {
                var hash = window.location.hash.substr(1);
                if (hash) {
                    setTimeout(function() {
                        try {
                            var top = $("#" + hash).offset().top;
                            $('html, body').animate({
                                scrollTop: eval(top - 10)
                            }, 500);
                        } catch (e) {}
                    }, 500);
                    setTimeout(function() {
                        $("#" + hash).find('input:not(.copy)').focus();
                        $("#" + hash).find('.trumbowyg-editor').focus();
                    }, 500);
                }
            });
        <?php } ?>
    </script>

</body>

</html>