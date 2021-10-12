<div class="flex">

    <?php

    $dir = 'data/upload/';
    $files = scan_dir($dir);

    for ($i = 0; $i < count($files); $i++) {
        $fn = $files[$i];
        if ($fn == "." or $fn == "..") goto jump;
        $path = "{$dir}{$files[$i]}";
        $size = filesize_formatted($path);
        if (!$modal) {
            $href = $path;
            $param = 'target="_blank"';
        } else {
            $href = '#';
            $param = 'class="image-add"';
        }
    ?>
        <div class='item mb-4'>
            <a href='<?= $href ?>' data-fn='<?= $path ?>' <?= $param ?>>
                <img src='<?= $path ?>' class='mb-2' />
            </a>
            <br />

            <?php
            // Gallery editor only
            if (!$modal) {
            ?>
                <div class='mmb-4'>
                    <p><span class='badge rounded-pill bg-light text-dark'><?= $size ?></span></p>
                    <!--<a href='#' class='btn btn-sm btn-info'><i class='fa fa-copy'></i></a>-->
                    <a href='#' class='image-del btn btn-sm btn-secondary' data-fn='{$files[$i]}'><i class='fa fa-times'></i></a>
                </div>
            <?php
            } // gallery
            ?>
        </div>

    <?php
        jump:
    } // for 
    ?>

</div>

<script>
    $(document).ready(function() {
        $("#upload_btn").click(function(e) {
            e.preventDefault();
            $("#upload_input").click();
        });
        $("#upload_input").change(function() {
            if ($(this)[0].files.length > 3) {
                alert("Você pode enviar no máximo 3 imagens de uma vez.");
            } else {
                $("#upload_form").submit();
            }
        });
        $('.image-del').click(function(e) {
            e.preventDefault();
            var fn = $(this).attr('data-fn');
            if (confirm('Tem certeza que deseja remover esta imagem?')) {
                window.location.href = 'pages/images.post.php?del=' + fn;
            }
        });
        //========================================
        // BTN 'SELECIONAR IMAGEM' (OPEN MODAL)
        //========================================
        image_id = ''; // RAND
        $(document).on("click", ".image-btn-modal", function(e) {
            e.preventDefault();
            image_id = $(this).closest('.image-form').attr('data-image-rand');
        });
        //========================================
        // IMAGE CLICK (ON MODAL)
        //========================================
        $('.image-add').click(function(e) {
            e.preventDefault();
            var fn = $(this).attr('data-fn');
            console.log(fn);
            $el = $('.image-form[data-image-rand=' + image_id + ']');
            console.log('.image-form[data-image-rand=' + image_id + ']');
            //$el.hide();
            $el.find('.image-input').val(fn);
            $el.find('img').attr('src', fn);
            $el.find('a.image-link').attr('href', fn);
            $('#exampleModal').modal('toggle');
        });
        //========================================
        // IMAGE CLICK (ON MODAL)
        //========================================
        $(document).on("click", ".image-btn-del", function(e) {
            e.preventDefault();
            image_id = $(this).closest('.image-form').attr('data-image-rand');
            if (confirm('Tem certeza que deseja limpar este campo?')) {
                $el = $('.image-form[data-image-rand=' + image_id + ']');
                $el.find('.image-input').val('');
                $el.find('img').attr('src', '');
                $el.find('a.image-link').attr('href', '#');
            }
        });
    });
</script>