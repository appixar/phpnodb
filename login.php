<?php
// global
include "includes/global.php";
// get $password
include $fn_pass;
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="J. W. Balaniuc">
    <meta http-equiv="Pragma" content="no-cache">
    <title>NoDB · Identifique-se</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/custom.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="assets/img/logo-ico32.png" />
    <meta name="theme-color" content="#7952b3">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;500&display=swap" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="assets/css/jw.css" rel="stylesheet">
    <link href="assets/css/login.css" rel="stylesheet">
</head>

<body class="text-center" cz-shortcut-listen="true">

    <main class="form-signin">
        <form action='pages/login.post.php' method='post'>
            <img class="mb-3" src="assets/img/logo.png" alt="" width="100">

            <?php
            //================================
            // existe senha... autenticar
            //================================
            if ($password OR $_GET['done']) {
            ?>

                <h1 class="h3 mb-3 fw-normal">Identifique-se</h1>
                <?php cb(); ?>
                <div class="form-floating">
                    <input name='password' type="password" class="form-control" placeholder="Senha">
                    <label>Digite a sua senha</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Entrar</button>

            <?php
            }
            //================================
            // criar primeira senha
            //================================
            else {
            ?>
                <h1 class="h3 mb-3 fw-normal">Crie uma senha</h1>
                <?php cb(); ?>
                <div class="mb-3">
                    <small class="text-muted">A senha deve conter no mínimo 6 caracteres, maiúsculo, minúsculo e símbolo.</small>
                </div>
                <input type='hidden' name='first' value='1' />
                <div class="form-floating">
                    <input name='pass0' type="password" class="form-control" placeholder="Senha">
                    <label>Nova senha</label>
                </div>
                <div class="form-floating">
                    <input name='pass1' type="password" class="form-control" placeholder="Senha">
                    <label>Digite novamente</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Definir senha</button>
            <?php
            }
            ?>

            <?php include 'pages/_partials/footer.php' ?>
        </form>
    </main>





</body>

</html>