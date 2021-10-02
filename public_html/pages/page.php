<form action='pages/page.post.php' method='post' enctype='multipart/form-data'>

    <input type='hidden' name='target' value='data' />

    <div class="accordion mb-4">

        <?php
        // page names
        $count = 0;
        foreach ($data as $key => $val) {
            $count++;
            if ($count != $p) goto ignore;

            // pages -> data
            $x = 0;
            foreach ($val as $k => $v) {
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

                            // sub foreach to get data
                            foreach ($v as $k_ => $v_) {
                                //=====================================
                                // set $label, $type, $value
                                // ... and save others in hidden
                                //=====================================
                                $label = $k_;
                                $id = '';
                                if (is_array($v_)) {
                                    $type = $v_['type'];
                                    $value = $v_['value'];
                                    $id = $v_['id'];
                                    // save others in hidden
                                    foreach ($v_ as $k__ => $v__) {
                                        if ($k__ != "value") {
                                            echo "<input type='hidden' name='{$key}[{$k}][{$k_}][{$k__}]' value='$v__' />";
                                        }
                                    }
                                } else {
                                    $type = "";
                                    $value = $v_;
                                }
                                // copy & popover (tooltip)
                                $popover = '';
                                if ($_SESSION['logged']['dev']) {
                                    if (!$id) $copy = '$nodb' . "['{$key}']['{$k}']['{$k_}']";
                                    else $copy = '$nodb' . "['$id']";
                                    $popover = '<input type="text" readonly="true" class="copy text-secondary" value="' . $copy . '" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-content="Copiado" />';
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
                                        <textarea name='<?= $key ?>[<?= $k ?>][<?= $k_ ?>][value]' class='textarea'><?= $value ?></textarea>
                                    <?php
                                    }
                                    //=====================================
                                    // checkbox
                                    //=====================================
                                    elseif ($type == "checkbox") {
                                        if ($value == "true") $checked = "checked";
                                        else $checked = "";
                                    ?>
                                        <input name='<?= $key ?>[<?= $k ?>][<?= $k_ ?>][value]' type="hidden" value="false" />
                                        <label><input name='<?= $key ?>[<?= $k ?>][<?= $k_ ?>][value]' type="checkbox" value="true" <?= $checked ?> /> <?= $label ?></label>
                                    <?php
                                    }
                                    //=====================================
                                    // text
                                    //=====================================
                                    else {
                                    ?>
                                        <label class="form-label"><?= $label ?></label>
                                        <?= $popover ?>
                                        <input name='<?= $key ?>[<?= $k ?>][<?= $k_ ?>]' type="text" class="form-control" placeholder="<?= $k_ ?>" value="<?= $value ?>">
                                    <?php } ?>
                                </div>
                            <?php
                            } // sub foreach to get data
                            ?>

                        </div>
                    </div>
                </div>
        <?php
            } // foreach (pages -> data)
            ignore:
        } // foreach  (pages names)
        ?>


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