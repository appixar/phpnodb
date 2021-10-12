<?php
$r = rand(100000, 999999);
?>

<div class="image-form col-12 mb-4" data-image-rand='<?= $r ?>'>
    <input class='image-input' type='hidden' name='<?= $name ?>' value='<?= $value ?>'>
    <label class='mb-3'><?= $label ?></label>
    <br />
    <a class='image-link' href='<?= $value ?>' target='_blank'>
        <img src='<?= $value ?>' class='mb-3' style='max-width:256px;max-height:128px;' />
    </a>
    <br />
    <a href='#' class="image-btn-modal btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class='fa fa-image'></i>&nbsp;
        Selecionar imagem
    </a>
    <a href='#' class="image-btn-del btn btn-outline-danger btn-sm">
        <i class='fa fa-eraser'></i>&nbsp;
        Limpar
    </a>
</div>