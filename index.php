<?php
// cb
include "includes/global.php";
// auth
if (!$_SESSION['logged']) {
    header("Location: login.php");
    exit;
}
//print_r($_SESSION);
// data
$data = json_decode(file_get_contents($fn_data), true); // php array
$_SESSION['current_data'] = file_get_contents($fn_data); // json
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="J. W. Balaniuc">
    <title>NoDB · Editor</title>

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
    <link href="assets/css/form-validation.css" rel="stylesheet">
</head>

<body class="bg-light" cz-shortcut-listen="true">

    <div class="container" style='margin-top:32px'>
        <main>
            <div class="mb-4 ppy-5 text-center">
                <img class="d-block mx-auto mb-2" src="assets/img/logo.png" alt="" width="100">
                <h2><span class='text-primary'>{</span> NoDB <span class='text-primary'>}</span></h2>
                <!--
                <p class="lead">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
                -->

                <?php
                cb();
                if (!is_writeable($fn_data)) {
                    cb('warning', "Verifique as permissões de escrita para o arquivo <strong>$fn_data</strong>");
                }

                ?>
            </div>

            <form action='pages/index.post.php' method='post'>
                <div class="accordion mb-4">

                    <?php
                    foreach ($data as $k => $v) {
                        $x++;
                    ?>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading_<?= $x ?>">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $x ?>" aria-expanded="true" aria-controls="collapse_<?= $x ?>">
                                    <?= $k ?>
                                </button>
                            </h2>
                            <div id="collapse_<?= $x ?>" class="accordion-collapse collapse <?php if ($x == 1) echo 'show'; ?>" aria-labelledby="heading_<?= $x ?>">
                                <div class="accordion-body">
                                    <?php
                                    foreach ($v as $k_ => $v_) {
                                    ?>
                                        <div class="col-12 mb-4">
                                            <?php
                                            //============================
                                            // checkbox
                                            //============================
                                            if ($v_ == "false" OR $v_ == "true") {
                                                if ($v_ == "true") { $checked = "checked"; }
                                                else { $checked = ""; }
                                            ?>
                                            <input name="<?= $k ?>[<?= $k_ ?>]" type="hidden" value="false" />
                                            <label><input name="<?= $k ?>[<?= $k_ ?>]" type="checkbox" value="true" <?= $checked ?> /> <?= $k_ ?></label>
                                            <?php
                                            }
                                            //============================
                                            // text
                                            //============================
                                            else {
                                            ?>
                                            <label class="form-label"><?= $k_ ?></label>
                                            <input name="<?= $k ?>[<?= $k_ ?>]" type="text" class="form-control" placeholder="<?= $k_ ?>" value="<?= $v_ ?>">
                                            <?php } ?>
                                        </div>
                                    <?php
                                    } // sub foreach
                                    ?>

                                </div>
                            </div>
                        </div>
                    <?php } // foreach 
                    ?>

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Alterar senha
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <div class="row mb-4">
                                    <div class="col-4">
                                        <label class="form-label">Senha atual</label>
                                        <input name="pass0" type="password" class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Nova senha</label>
                                        <input name="pass1" type="password" class="form-control">
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Repetir</label>
                                        <input name="pass2" type="password" class="form-control">
                                    </div>
                                </div>
                                <strong>Guarde a senha em um local seguro.</strong><!--Em caso de perda, a única forma de alterá-la é alterando o arquivo <code><?= $fn_pass ?></code> no servidor.--> A senha deve conter no mínimo 6 caracteres, letras e números e símbolo.
                            </div>
                        </div>
                    </div>
                </div>


                <button class="w-100 btn btn-primary btn-lg mb-4" type="submit">Salvar alterações</button>

            </form>

            <p class='text-center'>
                <a href='pages/logout.php' class="w-100">Logout</a>
            </p>


        </main>

        <?php include 'pages/_partials/footer.php' ?>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/form-validation.js"></script>

</body>

</html>