<?php
// global
include "global.php";

// dev?
if ($_GET['dev']) {
    $_title = "Identifique-se (Dev)";
    $_h1 = "Hello, master.";
    $_submit = "Entrar";
    $_pass = "Senha do desenvolvedor";
    $_link_cli = "<p class='mt-3'><a href='./login.php' class='btn btn-secondary btn-sm'>... ou entrar como cliente</a></p>";
    $_url = "";
}
// client?
else {
    $_title = "Identifique-se";
    $_h1 = "Identifique-se";
    $_submit = "Entrar";
    $_pass = "Digite a sua senha";
    $_url = "?dev=1";
    $_link_cli = "";
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="J. W. Balaniuc">
    <meta http-equiv="Pragma" content="no-cache">
    <title>[NoDB] <?= $conf['sys_name'] ?> · Login</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/custom.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/img/logo-ico32.png" />
    <meta name="theme-color" content="#7952b3">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;500&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>

    <!-- Custom styles for this template -->
    <link href="assets/css/jw.css?<?= time() ?>" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">
</head>

<body class="text-center" cz-shortcut-listen="true">

    <main class="form-signin">
        <form action='pages/login.post.php' method='post' class='mb-5'>
            <img id="logo" class="mb-3" src="assets/img/logo.png" alt="" width="100">

            <?php
            //================================
            // verificar permissões de arquivo
            //================================
            if (!is_writeable($fn_pass) or !is_writeable($fn_data)) {
            ?>
                <h1 class="h3 mb-3 fw-normal">Ops!</h1>
                <div style="margin-top:20px" class="cb alert alert-danger" role="alert">
                    Verifique as permissões de escrita:<br />
                    <strong>chmod -R 777 ./data</strong>
                </div>
                <a href='login.php' class="w-100 btn btn-lg btn-primary">Tentar novamente</a>
            <?php
            }
            //================================
            // senha dev criada
            //================================
            elseif ($_GET['done'] == 1) {
            ?>
                <h1 class="h3 mb-3 fw-normal">Sucesso!</h1>
                <p>Agora vamos definir uma senha aleatória para o seu cliente.</p>
                <a href='pages/pass-reset.post.php' class="w-100 btn btn-lg btn-primary">Gerar senha do cliente</a>
            <?php
            }
            //================================
            // senha cliente criada
            //================================
            elseif ($_GET['done'] == 2) {
            ?>
                <h1 class="h3 mb-3 fw-normal">Pronto!</h1>
                <p>Nova senha temporária para o seu cliente:</p>
                <h3 class="mb-4 text-primary"><?= $_SESSION['change_password_cli'] ?></h3>

                <a href='login.php' class="w-100 mb-2 btn btn-md btn-primary"><i class='fa fa-user'></i> &nbsp; Entrar como cliente</a>
                <a href='login.php?dev=1' class="w-100 btn btn-md btn-secondary"><i class='fa fa-lock'></i> &nbsp; Entrar como desenvolvedor</a>
            <?php
            }
            //================================
            // existe senha... autenticar
            //================================
            elseif ($password_dev) {

                if ($_GET['dev']) echo "<input type='hidden' name='dev' value='1' />";

            ?>
                <h1 class="h3 mb-3 fw-normal"><?= $_h1 ?></h1>
                <?php cb(); ?>
                <div class="form-floating">
                    <input required name='password' type="password" class="form-control" placeholder="Senha">
                    <label><?= $_pass ?></label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit"><?= $_submit ?></button>
                <?= $_link_cli ?>

            <?php
            }
            //================================
            // criar primeira senha
            //================================
            else {
            ?>
                <h1 class="h3 mb-3 fw-normal">Definir senha master</h1>
                <div class="mb-3">
                    <small class="text-muted">Esta será a senha do <strong>desenvolvedor</strong>. O cliente receberá uma senha diferente.</small>
                    <div class="cb mt-3 alert alert-info alert-sm" role="alert"><i class="fa fa-info-circle"></i></a> &nbsp; A senha deve conter no mínimo 6 caracteres, maiúsculo, minúsculo e símbolo.</div>
                </div>
                <?php cb(); ?>
                <input type='hidden' name='first' value='1' />
                <div class="form-floating">
                    <input name='pass0' type="password" class="form-control" placeholder="Senha">
                    <label>Nova senha</label>
                </div>
                <div class="form-floating">
                    <input name='pass1' type="password" class="form-control" placeholder="Senha">
                    <label>Digite novamente</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Salvar</button>
            <?php
            }
            ?>
        </form>

        <?php include 'pages/_partials/footer.php' ?>

    </main>

    <script src="assets/js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            clicks = 0;
            $("#logo").click(function() {
                clicks++;
                if (clicks === 7) window.location.href = 'login.php<?= $_url ?>';
            });
        });
    </script>



</body>

</html>