<?php
class arrayEditor
{
    // global variables
    public $data = array();
    private $filter_level = 0;
    private $html_item_id = 0;
    private $first_key = ''; // for $html field names = first_key[key2][key3]...

    // parentCount
    private $levelCount = 0;
    private $levelStop = false;

    public function __construct($array = array(), $filter_level = 0)
    {
        $this->data = $array;
        $this->filter_level = $filter_level;
        $this->levelCount($this->data);
    }
    //====================================================
    // JSON levels count. If parentCount == 3,
    // first key is ref. to a nav-link (pages data.json)
    //====================================================
    private function levelCount($array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v) and !$this->levelStop) {
                //echo "$k<br>";
                $this->levelCount++;
                $this->levelCount($v);
            } else $this->levelStop = true;
        }
    }
    public function build()
    {
        //====================================================
        // Find filtered level (single page)
        // If levels > 3 first key is nav-item (pages data.json)
        //====================================================
        if ($this->levelCount >= 3) {
            $count = 0;
            foreach ($this->data as $key => $val) {

                // Filter array level (single page)
                if ($this->filter_level > 0) {
                    $count++;
                    if ($count != $this->filter_level) goto ignoreLevel;
                    $this->first_key = $key;
                }
                $this->html_item_id = 0;
                $this->buildItems($val);
                ignoreLevel:
            }
        }
        //====================================================
        // No filtered levels
        //====================================================
        else {
            $this->html_item_id = 0;
            $this->buildItems($this->data);
        }
    }
    //====================================================
    // Build items
    //====================================================
    private function buildItems($array)
    {
        //====================================================
        // 1. Accordion title
        //====================================================
        foreach ($array as $k => $v) {

            // fix first key
            if ($this->first_key) $key = "[$k]";
            else $key = $k;

            $this->html_item_id++;

            // Accordion start
            $this->html .= <<<EOD
            <div class="accordion-item">
                    <h2 class="accordion-header" id="heading_{$this->html_item_id}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{$this->html_item_id}" aria-expanded="true" aria-controls="collapse_{$this->html_item_id}">
                            $k
                        </button>
                    </h2>
                    <div id="collapse_{$this->html_item_id}" class="accordion-collapse collapse sshow" aria-labelledby="heading_{$this->html_item_id}">
                        <div class="accordion-body">
EOD;
            //====================================================
            // 2. Field label
            //====================================================
            foreach ($v as $k_ => $v_) { //1

                //====================================================
                // 3. a) Field parameters
                //====================================================
                //
                $label = $k_;
                //echo "$label<br>";
                $value = $v_;
                //$id = '';
                $type = '';
                $input_text_value = '';
                $name = "{$this->first_key}{$key}[$k_]";
                //
                if (is_array($v_)) {
                    //print_r($v_);
                    $type = $v_['type'];
                    $value = $v_['value'];
                    //$id = $v_['id'];
                    $name .= "[value]";
                    // save others in hidden
                    foreach ($v_ as $k__ => $v__) {
                        if ($k__ != "value") $this->html .= "<input type='hidden' name='{$this->first_key}{$key}[{$k_}][{$k__}]' value='$v__' />";
                    }
                }

                //====================================================
                // 4. Build fields
                //====================================================
                //$this->inputText($label, $value, "{$this->first_key}{$key}[$k_]");

                //=====================================
                // image
                //=====================================
                if ($type == "textarea") {
                    $this->inputTextarea($label, $value, $name);
                }
                //=====================================
                // image
                //=====================================
                elseif ($type == "image") {
                    $this->inputImage($label, $value, $name);
                }
                //=====================================
                // text
                //=====================================
                else {
                    $this->inputText($label, $value, $name);
                }

                //====================================================
                // 3. b) Field direct value
                //====================================================

            }
            // Accordion end
            $this->html .= <<<EOD
            </div></div></div>
EOD;
        }
    }
    private function inputText($label, $value, $name)
    {
        $this->html .= <<<EOD
        <div class="col-12 mb-4">
        <label class="form-label">$label</label>
        <input name='$name' type="text" class="form-control" placeholder="$name" value="$value">
        </div>
EOD;
    }
    private function inputTextarea($label, $value, $name)
    {
        ob_start();
        include "_partials/input.textarea.php";
        $this->html .= ob_get_clean();
    }
    private function inputImage($label, $value, $name)
    {
        $r = rand(1000, 9999);
        $this->html .= <<<EOD
        <div class="col-12 mb-4">
        <input class='image_$r' type='hidden' name='$name' value='$value'>
        <label class='mb-3'>$label</label>
        <br />
        <a class='image_$r' href='$value' target='_blank'>
            <img src='$value' class='image_$r mb-3' style='max-width:256px;max-height:128px;' />
        </a>
        <br />
        <a href='#' class="btn btn-outline-secondary btn-md image-sel" data-id='image_$r' data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class='fa fa-image'></i> &nbsp;
            Selecionar imagem
        </a>
        </div>
EOD;
    }
}
$json = new arrayEditor($data, 1);
//$json = new arrayEditor($conf);
$json->build();
echo $json->html;

echo '<pre>';
//print_r($data);
exit;


// file = page
$target = 'data';
// file = config
if ($p == 'config') {
    $target = 'conf';
    $data = $conf;
}
?>

<form action='pages/page.post.php' method='post' enctype='multipart/form-data'>

    <input type='hidden' name='target' value='<?= $target ?>' />

    <div class="accordion mb-4">

        <?php
        // page names
        $count = 0;
        foreach ($data as $key => $val) {
            $count++;
            if ($p != 'config' and $count != $p) goto ignore;

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
                                $input_text_value = '';
                                if (is_array($v_)) {
                                    $type = $v_['type'];
                                    $value = $v_['value'];
                                    $id = $v_['id'];
                                    $input_text_value = '[value]';
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
                                    // image
                                    //=====================================
                                    elseif ($type == "image") {
                                        $r = rand(1000, 9999);
                                    ?>
                                        <input class='image_<?= $r ?>' type='hidden' name='<?= $key ?>[<?= $k ?>][<?= $k_ ?>][value]' value='<?= $value ?>'>
                                        <label class='mb-3'><?= $label ?></label>
                                        <?= $popover ?>
                                        <br />
                                        <a class='image_<?= $r ?>' href='<?= $value ?>' target='_blank'>
                                            <img src='<?= $value ?>' class='image_<?= $r ?> mb-3' style='max-width:256px;max-height:128px;' />
                                        </a>
                                        <br />
                                        <a href='#' class="btn btn-outline-secondary btn-md image-sel" data-id='image_<?= $r ?>' data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class='fa fa-image'></i> &nbsp;
                                            Selecionar imagem
                                        </a>
                                    <?php
                                    }
                                    //=====================================
                                    // text
                                    //=====================================
                                    else {
                                    ?>
                                        <label class="form-label"><?= $label ?></label>
                                        <?= $popover ?>
                                        <input name='<?= $key ?>[<?= $k ?>][<?= $k_ ?>]<?= $input_text_value ?>' type="text" class="form-control" placeholder="<?= $k_ ?>" value="<?= $value ?>">
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
    });
</script>