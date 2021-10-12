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
    private function buildItems($array)
    {
        //====================================================
        // 1. Accordion title
        //====================================================
        foreach ($array as $k => $v) {

            // fix first key
            if ($this->first_key) {
                //echo 1; exit;
                $key = "[$k]";
            } else $key = $k;

            $this->html_item_id++;

            // Accordion start
            ob_start();
            include "pages/_partials/input.accordion-start.php";
            $this->html .= ob_get_clean();

            //====================================================
            // 2. Field label
            //====================================================
            foreach ($v as $k_ => $v_) { // loop 1
                //====================================================
                // 3. Dynamic multidimensional fields (list)
                //====================================================
                if (is_numeric($k_) and is_array($v_)) {
                    $this->$dynMulti++;
                    $this->html .= "<div id='card_multi_{$this->html_item_id}_$k_' class='card mb-3' multi-item-id='{$this->html_item_id}' multi-array-id='$k_'>";
                    $this->html .= "<div class='card-header'>Item <span class='multi-item-id-text'>$k_</span>";
                    $this->html .= "<div style='float:right'>";
                    $this->html .= "&nbsp;<a href='#' class='multi-item-down btn btn-secondary btn-sm'><i class='fa fa-arrow-down'></i></a>";
                    $this->html .= "&nbsp;<a href='#' class='multi-item-up btn btn-secondary btn-sm'><i class='fa fa-arrow-up'></i></a>";
                    $this->html .= "&nbsp;<a href='#' class='multi-item-del btn btn-danger btn-sm'><i class='fa fa-times'></i></a>";
                    $this->html .= "</div></div><div class='card-body'>";
                    foreach ($v_ as $k__ => $v__) {
                        //====================================================
                        // 3. a) Field parameters
                        //====================================================
                        //$label = $k__ . " <span class='text-muted multi_item_id'>$k_</span>";
                        $label = $k__;
                        $value = $v__;
                        $id = '';
                        $type = '';
                        $name = "{$this->first_key}{$key}[$k_][$k__]";
                        //
                        if (is_array($v__)) {
                            $type = $v__['type'];
                            $value = $v__['value'];
                            $id = $v__['id'];
                            $name .= "[value]";
                            foreach ($v__ as $k___ => $v___) { // loop 2
                                //====================================================
                                // 3. b) Simple parameters (id, value, type, etc)
                                // Save parameters in hidden
                                //====================================================
                                if ($k___ != "value") $this->html .= "<input type='hidden' name='{$this->first_key}{$key}[$k_][{$k__}][{$k___}]' value='$v___' />";
                            }
                        }
                        $array_id = $k_;
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
                    $label = $k_;
                    $value = $v_;
                    $id = '';
                    $type = '';
                    $name = "{$this->first_key}{$key}[$k_]";
                    //
                    if (is_array($v_)) {
                        $type = $v_['type'];
                        $value = $v_['value'];
                        $id = $v_['id'];
                        $name .= "[value]";

                        foreach ($v_ as $k__ => $v__) { // loop 2
                            //====================================================
                            // 5. b) Simple parameters (id, value, type, etc)
                            // Save parameters in hidden
                            //====================================================
                            if ($k__ != "value") $this->html .= "<input type='hidden' name='{$this->first_key}{$key}[{$k_}][{$k__}]' value='$v__' />";
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
        // copy & popover (tooltip)
        if ($_SESSION['logged']['dev']) {
            // fix first key
            $name_var = str_replace_first("[", "][", $name);
            $name_var = "[" . $name_var;
            // fix quotes
            $name_var = str_replace("[", "['", $name_var);
            $name_var = str_replace("]", "']", $name_var);
            $name_var = str_replace("''", $array_id, $name_var);
            //
            if (!$id) $copy = '$nodb' . $name_var;
            else $copy = '$nodb' . "['$id']";
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
