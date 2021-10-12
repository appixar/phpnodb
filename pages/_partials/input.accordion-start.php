<?php
if ($this->html_item_id == 1) {
    $show = 'show';
    $collapsed = '';
} else {
    $show = '';
    $collapsed = 'collapsed';
}
?>

<div class="accordion-item" id="item_<?= $this->html_item_id ?>">
    <h2 class="accordion-header" id="heading_<?= $this->html_item_id ?>">
        <button class="accordion-button <?= $collapsed ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?= $this->html_item_id ?>" aria-expanded="true" aria-controls="collapse_<?= $this->html_item_id ?>">
            <?= $k ?>
        </button>
    </h2>
    <div id="collapse_<?= $this->html_item_id ?>" class="accordion-collapse collapse <?= $show ?>" aria-labelledby="heading_<?= $this->html_item_id ?>">
        <div class="accordion-body">