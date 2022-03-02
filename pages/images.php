<?php
$total = intval(count(scandir('data/upload/')) - 2);
$_disabled = "";
if ($conf['sys_up_maxcount'] <= $total) {
    $_disabled = "disabled";
    $_SESSION['cb']['type'] = 'warning';
    $_SESSION['cb']['text'] = 'O limite de imagens foi atingido.';
    cb();
}
?>

<div class="accordion mb-4">

    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Galeria de imagens (<?= $total ?>)
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">

                <p class="text-center">
                    <a id="upload_btn" href="#" class="edit edit-off btn btn-primary btn-md <?= $_disabled ?>">
                        <i class="fa fa-upload"></i> &nbsp; Enviar imagem
                    </a>
                </p>

                <form id="upload_form" action="pages/images.post.php" method="post" enctype="multipart/form-data">
                    <input id="upload_input" name='file' type="file" accept="image/*" hidden />
                </form>

                <?php include "pages/_partials/images.list.php"; ?>

            </div>
        </div>
    </div>
</div>
