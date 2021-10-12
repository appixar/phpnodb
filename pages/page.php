<?php
// page data
$level = $p;
// conf data
if ($p == 'config') {
    $level = 0;
    $data = $conf;
}
?>

<form action='pages/page.post.php' method='post' enctype='multipart/form-data'>

    <input type='hidden' name='p' value='<?= $p ?>' />

    <div class="accordion mb-4">

        <?php
        //print_r($data);
        $json = new arrayEditor($data, $level);
        echo $json->html;
        ?>


    </div>

    <button class="w-100 btn btn-primary btn-lg mb-5" type="submit">Salvar alterações</button>

</form>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Selecionar imagem</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $modal = true;
                include "pages/_partials/images.list.php";
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <a href='./?p=images' class="btn btn-primary"><i class='fa fa-pencil'> &nbsp; </i>Editar galeria</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.copy').click(function() {
            $(this).select();
            document.execCommand("copy");
            document.getSelection().removeAllRanges();
        });
        //========================================
        // CREATE NEW ITEM
        //========================================
        $(document).on("click", ".multi-item-btn", function(e) {
            e.preventDefault();
            // get data
            var item_id = $(this).attr('multi-item-id');
            var $last = '.card[multi-item-id=' + item_id + ']:last';
            // clone item
            $($last).clone().appendTo(".multi-item-area[multi-item-id=" + item_id + "]");
            // fix image fields
            var rand = Math.floor(Math.random() * (99999 - 1)) + 1;
            $($last + ' .image-form').attr('data-image-rand', rand);
            $($last + ' .image-form img').attr('src', ''); //assets/img/0.png
            // clear form values
            $($last + ' *:not([type=hidden])').each(function() {
                try {
                    var name = $(this).attr('name');
                    $(this).attr('name', name);
                    //
                    $(this).val('');
                } catch (e) {}
            });
            $($last + ' textarea').each(function() {
                $(this).html('');
            });
            // refresh names, array_id, item_id, etc
            refreshArray(item_id);
        });
        //========================================
        // REFRESH ITEMS ARRAY ID (UPDATE SORT)
        //========================================
        function refreshArray(item_id) {
            $('#item_' + item_id + ' .card').each(function(i) {
                //i = i + 1;
                // get old array id
                var array_id = $(this).attr('multi-array-id');
                var array_id_new = i;
                // update card id
                $(this).attr('id', 'card_multi_' + item_id + '_' + array_id_new);
                // update array id
                $(this).attr('multi-array-id', array_id_new);
                // update items
                $(this).find('.multi-item-id-text').html(i);
                $(this).find('*').each(function() {
                    try {
                        var name = $(this).attr('name');
                        name = name.replace("[" + array_id + "]", "[" + array_id_new + "]");
                        $(this).attr('name', name);
                    } catch (e) {}
                });
                // update copy
                var copy = $(this).find('.copy').val();
                copy = copy.replace("['" + array_id + "']", "['" + array_id_new + "']");
                $(this).find('.copy').val(copy);
            });
        }

        //========================================
        // UP/DOWN/DEL ITEMS
        //========================================
        $(document).on("click", ".multi-item-up", function(e) {
            e.preventDefault();
            var $card = $(this).closest('.card');
            var item_id = $card.attr('multi-item-id');
            var array_id = $card.attr('multi-array-id');
            var array_id_before = parseInt(array_id) - 1;
            var el_before = "#card_multi_" + item_id + "_" + array_id_before;
            if ($(el_before).length) {
                $card.remove().clone().insertBefore(el_before);
                setTimeout(function() {
                    refreshArray(3);
                }, 300);
            }
        });
        $(document).on("click", ".multi-item-down", function(e) {
            e.preventDefault();
            var $card = $(this).closest('.card');
            var item_id = $card.attr('multi-item-id');
            var array_id = $card.attr('multi-array-id');
            var array_id_after = parseInt(array_id) + 1;
            var el_after = "#card_multi_" + item_id + "_" + array_id_after;
            if ($(el_after).length) {
                $card.remove().clone().insertAfter(el_after);
                setTimeout(function() {
                    refreshArray(item_id);
                }, 300);
            }
        });
        $(document).on("click", ".multi-item-del", function(e) {
            e.preventDefault();
            var $card = $(this).closest('.card');
            var item_id = $card.attr('multi-item-id');
            var array_id = $card.attr('multi-array-id');
            if (confirm('Tem certeza que deseja remover o item ' + array_id + '?')) {
                $card.fadeOut('fast', function() {
                    $(this).remove();
                    setTimeout(function() {
                        refreshArray(item_id);
                    }, 300);
                });

            }

        });
    });
</script>