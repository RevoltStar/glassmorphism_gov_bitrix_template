<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$items = is_array($arResult ?? null) ? $arResult : [];
?>
<div class="social-menu d-flex flex-wrap gap-2">
    <?php foreach ($items as $item): ?>
        <?php
        if (!is_array($item)) {
            continue;
        }

        $link = site_url($item['LINK'] ?? null, '', ['http', 'https'], false);
        $imageUrl = site_template_image_url($item['PARAMS']['IMAGE'] ?? null);
        $text = site_plain_text($item['TEXT'] ?? '');
        if ($link === '' || $imageUrl === '' || $text === '') {
            continue;
        }
        ?>
        <a href="<?=htmlspecialcharsbx($link)?>"
           class="social-menu__link"
           target="_blank"
           rel="noopener noreferrer"
           aria-label="<?=htmlspecialcharsbx($text)?>"
           title="<?=htmlspecialcharsbx($text)?>">
            <img src="<?=htmlspecialcharsbx($imageUrl)?>" class="social-menu__icon" alt="" aria-hidden="true">
        </a>
    <?php endforeach; ?>
</div>
