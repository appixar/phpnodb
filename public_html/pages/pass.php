<?php
// strings
$_who = "do cliente";
if ($_SESSION['logged']['dev']) {
    $_who = "do desenvolvedor";
}
?>

<form action='pages/pass.post.php' method='post'>
    <div class="accordion mb-4">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Alterar senha <?= $_who ?>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="row mb-4">
                        <div class="col-sm-12 col-md-4 col-lg-4 mb-1">
                            <label class="form-label">Senha atual</label>
                            <input name="pass0" type="password" class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 mb-1">
                            <label class="form-label">Nova senha</label>
                            <input name="pass1" type="password" class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4 mb-1">
                            <label class="form-label">Repetir</label>
                            <input name="pass2" type="password" class="form-control">
                        </div>
                    </div>
                    <strong>Guarde a senha em um local seguro.</strong>
                    <!--Em caso de perda, a única forma de alterá-la é alterando o arquivo <code><?= $fn_pass ?></code> no servidor.--> A senha deve conter no mínimo 6 caracteres, letras e números e símbolo.
                </div>
            </div>
        </div>
    </div>


    <button class="w-100 btn btn-primary btn-lg mb-4" type="submit">Salvar alterações</button>

</form>