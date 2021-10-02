<form action='pages/page.post.php' method='post' enctype='multipart/form-data'>

    <input type='hidden' name='target' value='conf' />

    <div class="accordion mb-4">

        <div class="accordion-item">
            <h2 class="accordion-header" id="heading_0">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_0" aria-expanded="true" aria-controls="collapse_0">
                    Definições
                </button>
            </h2>
            <div id="collapse_0" class="accordion-collapse collapse show" aria-labelledby="heading_0">
                <div class="accordion-body">
                    <?php
                    // sub foreach to get data
                    foreach ($conf as $k_ => $v_) {
                        //=====================================
                        // set $label, $type, $value
                        // ... and save others in hidden
                        //=====================================
                        $label = $k_;
                        $type = $v_['type'];
                        $value = $v_['value'];
                        $id = $v_['id'];
                        // save others in hidden
                        foreach ($v_ as $k__ => $v__) {
                            if ($k__ != "value") {
                                echo "<input type='hidden' name='{$k_}[{$k__}]' value='$v__' />";
                            }
                        }
                        // copy & popover (tooltip)
                        $popover = '';
                        if ($_SESSION['logged']['dev']) {
                            if (!$id) $copy = '$nodb' . "['{$k_}']";
                            else $copy = '$nodb' . "['$id']";
                            $popover = '<input type="text" size="' . strlen($copy) . '" readonly="true" class="copy text-secondary" value="' . $copy . '" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-content="Copiado" />';
                        }
                    ?>
                        <div class="col-12 mb-4">
                            <?php
                            //=====================================
                            // textarea
                            //=====================================
                            if ($type == "textarea") {
                            ?>
                                <label class='mb-3'><?= $label ?></label>
                                <?= $popover ?>
                                <textarea name='<?= $k_ ?>[value]' class='textarea'><?= $value ?></textarea>
                            <?php
                            }
                            //=====================================
                            // checkbox
                            //=====================================
                            elseif ($type == "checkbox") {
                                if ($value == "true") $checked = "checked";
                                else $checked = "";
                            ?>
                                <input name='<?= $k_ ?>[value]' type="hidden" value="false" />
                                <label><input name='<?= $k_ ?>[value]' type="checkbox" value="true" <?= $checked ?> /> <?= $label ?></label>
                            <?php
                            }
                            //=====================================
                            // image
                            //=====================================
                            elseif ($type == "image") {
                                $r = rand(111111, 999999);
                            ?>
                                <input type='hidden' name='<?= $k_ ?>[value]' value='<?= $value ?>'>
                                <label class='mb-3'><?= $label ?></label>
                                <?= $popover ?>
                                <br />
                                <a href='<?= $value ?>' target='_blank'><img src='<?= $value ?>' class='mb-3' style='max-width:256px;max-height:128px;' /></a><br />
                                <input class='form-control' type='file' accept='image/'>
                            <?php
                            }
                            //=====================================
                            // text
                            //=====================================
                            else {
                            ?>
                                <label class="form-label"><?= $label ?></label>
                                <?= $popover ?>
                                <input name='<?= $k_ ?>[value]' type="text" class="form-control" placeholder="<?= $k_ ?>" value="<?= $value ?>">
                            <?php } ?>
                        </div>
                    <?php
                    } // sub foreach to get data
                    ?>

                </div>
            </div>
        </div>



    </div>


    <button class="w-100 btn btn-primary btn-lg mb-4" type="submit">Salvar alterações</button>

</form>

<script>
    $(document).ready(function() {
        $('.copy').click(function() {
            $(this).select();
            document.execCommand("copy");
            document.getSelection().removeAllRanges();
        });
    });
</script>