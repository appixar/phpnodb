<?php
class arrayEditor
{
    // global variables
    public $data = array();
    public $html = ""; // builded content to return
    private $filter_level = 0;
    private $html_item_id = 0;
    private $first_key = ''; // for $html field names = first_key[key2][key3]...
    private $dynMulti = 0; // current field is a dynamic array multidim

    // levelCount()
    private $levelCount = 0;
    private $levelStop = false;

    public function __construct($array = array(), $filter_level = 0)
    {
        $this->data = $array;
        $this->filter_level = $filter_level;
        $this->levelCount($this->data);
        $this->build();
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
    private function buildItems($pages)
    {
        //====================================================
        // 1. Accordion title
        //====================================================
        foreach ($pages as $sectionName => $section) {

            // fix first key
            if ($this->first_key) {
                //echo 1; exit;
                $key = "[$sectionName]";
            } else $key = $sectionName;

            $this->html_item_id++;

            // Accordion start
            ob_start();
            include "pages/_partials/input.accordion-start.php";
            $this->html .= ob_get_clean();

            //====================================================
            // 2. Field label
            //====================================================
            foreach ($section as $elementName => $element) { // loop 1
                //====================================================
                // 3. Dynamic multidimensional fields (list)
                //====================================================
                if (is_numeric($elementName) and is_array($element)) {
                    $this->$dynMulti++;
                    $this->html .= "<div id='card_multi_{$this->html_item_id}_$elementName' class='card mb-3' multi-item-id='{$this->html_item_id}' multi-array-id='$elementName'>";
                    $this->html .= "<div class='card-header'>Item <span class='multi-item-id-text'>$elementName</span>";
                    $this->html .= "<div style='float:right'>";
                    $this->html .= "&nbsp;<a href='#' class='multi-item-down btn btn-secondary btn-sm'><i class='fa fa-arrow-down'></i></a>";
                    $this->html .= "&nbsp;<a href='#' class='multi-item-up btn btn-secondary btn-sm'><i class='fa fa-arrow-up'></i></a>";
                    $this->html .= "&nbsp;<a href='#' class='multi-item-del btn btn-danger btn-sm'><i class='fa fa-times'></i></a>";
                    $this->html .= "</div></div><div class='card-body'>";
                    foreach ($element as $itemName => $item) {
                        //====================================================
                        // 3. a) Field parameters
                        //====================================================
                        //$label = $itemName . " <span class='text-muted multi_item_id'>$elementName</span>";
                        $label = $itemName;
                        $value = $item;
                        $id = '';
                        $type = '';
                        $name = "{$this->first_key}{$key}[$elementName][$itemName]";
                        //
                        if (is_array($item)) {
                            $type = $item['type'];
                            $value = $item['value'];
                            $id = $item['id'];
                            $name .= "[value]";
                            foreach ($item as $itemName_ => $item_) { // loop 2
                                //====================================================
                                // 3. b) Simple parameters (id, value, type, etc)
                                // Save parameters in hidden
                                //====================================================
                                if ($itemName_ != "value") $this->html .= "<input type='hidden' name='{$this->first_key}{$key}[$elementName][{$itemName}][{$itemName_}]' value='$item_' />";
                            }
                        }
                        $array_id = $elementName;
                        $this->makeInput($type, $label, $value, $name, $id, $array_id);
                    } // foreach
                    $this->html .= "</div></div>";
                } // dyn multi
                //====================================================
                // 4. "Normal" field parameters
                //====================================================
                else {
                    $this->$dynMulti = 0;
                    //====================================================
                    // 4. a) Field parameters
                    //====================================================
                    $label = $elementName;
                    $value = $element;
                    $id = '';
                    $type = '';
                    $name = "{$this->first_key}{$key}[$elementName]";
                    //
                    if (is_array($element)) {
                        $type = $element['type'];
                        $value = $element['value'];
                        $id = $element['id'];
                        $name .= "[value]";

                        foreach ($element as $itemName => $item) { // loop 2
                            //====================================================
                            // 5. b) Simple parameters (id, value, type, etc)
                            // Save parameters in hidden
                            //====================================================
                            if ($itemName != "value") $this->html .= "<input type='hidden' name='{$this->first_key}{$key}[{$elementName}][{$itemName}]' value='$item' />";
                        }
                    }
                    $this->makeInput($type, $label, $value, $name, $id);
                }
            }
            // Dynamic multidimensional button clone
            if ($this->$dynMulti > 0) {
                $this->html .= "<div class='multi-item-area' multi-item-id='{$this->html_item_id}'></div>";
                $this->html .= "<a href='#' multi-item-id='{$this->html_item_id}' class='multi-item-btn btn btn-md btn-secondary mb-3'><i class='fa fa-plus'></i> Adicionar item</a>";
            }
            // Accordion end
            ob_start();
            include "pages/_partials/input.accordion-end.php";
            $this->html .= ob_get_clean();
        }
    }
    private function makeInput($type, $label, $value, $name, $id = false, $array_id = false)
    {
        // id area (auto scroll)
        $this->html .= "<div id='goto_$id'>";

        // copy & popover (tooltip)
        if ($_SESSION['logged']['dev']) {
            // fix first key
            $name_var = str_replace_first("[", "][", $name);
            $name_var = "[" . $name_var;
            // fix quotes
            $name_var = str_replace("[", "['", $name_var);
            $name_var = str_replace("]", "']", $name_var);
            $name_var = str_replace("''", $array_id, $name_var);
            // copy
            if (!$id) $copy = '<?= $data' . $name_var . ' ?>';
            else {
                if (is_numeric($array_id)) $copy = '<?= $data' . "['$id'][$array_id] ?>";
                else $copy = '<?= $data' . "['$id'] ?>";
            }
            $this->html .= '<input type="text" readonly="true" class="copy text-secondary" value="' . $copy . '" data-bs-toggle="popover" data-bs-trigger="focus" data-bs-content="Copiado" />';
        }
        //=====================================
        // checkbox
        //=====================================
        if ($type == "checkbox") $this->inputCheckbox($label, $value, $name);
        //=====================================
        // textarea
        //=====================================
        elseif ($type == "textarea") $this->inputTextarea($label, $value, $name);
        //=====================================
        // image
        //=====================================
        elseif ($type == "image") $this->inputImage($label, $value, $name);
        //=====================================
        // text
        //=====================================
        else $this->inputText($label, $value, $name);
        $this->html .= "</div>";
    }
    private function inputCheckbox($label, $value, $name)
    {
        // bool
        if ($value == "true") $checked = "checked";
        else $checked = "";
        // include
        ob_start();
        include "pages/_partials/input.checkbox.php";
        $this->html .= ob_get_clean();
    }
    private function inputText($label, $value, $name)
    {
        ob_start();
        include "pages/_partials/input.text.php";
        $this->html .= ob_get_clean();
    }
    private function inputTextarea($label, $value, $name)
    {
        ob_start();
        include "pages/_partials/input.textarea.php";
        $this->html .= ob_get_clean();
    }
    private function inputImage($label, $value, $name)
    {
        ob_start();
        include "pages/_partials/input.image.php";
        $this->html .= ob_get_clean();
    }
}
