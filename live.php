<?php
// CB
include "global.php";

// AUTH
if (!$_SESSION['logged']) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="J. W. Balaniuc">
    <title>[NoDB] <?= $conf['sys_name'] ?> · Live</title>

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/img/logo-ico32.png" />
    <meta name="theme-color" content="#7952b3">

    <!-- Jquery -->
    <link href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>

    <link href="assets/css/live.css?<?= time() ?>" rel="stylesheet">
</head>

<body>
    <iframe id='site' src='<?= $conf['sys_url'] ?>'></iframe>

    <div id="dialog" title="Carregando...">
        <iframe id='editor' src='index.php?modal=1'></iframe>
    </div>

    <script>
        $(function() {

            //========================================
            // JQUERY UI DIALOG
            //========================================
            $("#dialog").dialog({
                maxWidth: 1000,
                maxHeight: 1000,
                minWidth: 350,
                minHeight: 600,
                width: 440,
                height: 600,
                //modal: true,
                close: function() {
                    window.location.href = './?no_modal=1';
                },
                open: function() {
                    setTimeout(function() {
                        var btn = '<button type="button" class="jw-minimize ui-button ui-corner-all ui-widget ui-button-icon-only"><span class="ui-button-icon ui-icon ui-icon-minusthick"></span></button>';
                        $(btn).insertBefore('.ui-button');
                    }, 500);
                }
            });

            //========================================
            // MINIMIZE DIALOG BUTTON
            //========================================
            let maximized = true;
            $(document).on('click', '.jw-minimize', function() {
                if (maximized) {
                    $('#dialog').hide();
                    $('#site').contents().find('#phpnodb_css').remove();
                    $('#site').contents().find('html').removeClass('maximized');
                    maximized = false;
                } else {
                    $('#dialog').show();
                    $('#site').contents().find('html').addClass('maximized');
                    siteCss();
                    maximized = true;
                }
            });

            //========================================
            // IFRAME EDITOR
            //========================================
            $('#editor').on('load', function() {
                $(this).contents().find("button[type='submit']").click(function() {
                    setTimeout(function() {
                        var iframe = document.getElementById('site');
                        iframe.src = iframe.src;
                    }, 1000);
                });
            });

            //========================================
            // IFRAME SITE
            //========================================
            $('#site').on('load', function() {
                var site = $(this);
                site.contents().find('html').addClass('maximized');
                site.contents().find("*[x-data]").click(function(e) {
                    if (maximized) e.preventDefault();
                    var key = $(this).attr('x-data');
                    $('#editor').attr('src', 'index.php?modal=1&key=' + key);
                    site.contents().find("*[x-data]").removeClass('active');
                    $(this).addClass('active');
                });
                siteCss();
            });

            function siteCss() {
                $('#site').contents().find('head').append('<link id="phpnodb_css" rel="stylesheet" href="phpnodb/assets/css/live-site.css">');
            }

            //========================================
            // LIVE TIMER IFRAME LOOP
            //========================================
            title = '';

            function live() {
                var title_new = $("#editor").contents().find("title").text();
                if (title_new !== title) {
                    title = title_new;
                    title = title.split('[NoDB] ')[1];
                    $('.ui-dialog-title').html(title);
                }
                setTimeout(function() {
                    live();
                }, 500);
            }
            live();
        });
    </script>
</body>

</html>