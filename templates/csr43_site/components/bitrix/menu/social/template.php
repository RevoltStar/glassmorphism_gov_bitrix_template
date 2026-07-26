<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$items = is_array($arResult ?? null) ? $arResult : [];
?>
<div class="social-icons d-flex gap-2">
    <?php foreach ($items as $item): ?>
        <?php
        if (!is_array($item)) {
            continue;
        }

        $link = site_url($item['LINK'] ?? null);
        $imageUrl = site_template_image_url($item['PARAMS']['IMAGE'] ?? null);
        $text = site_string($item['TEXT'] ?? '');
        if ($link === '#' || $imageUrl === '') {
            continue;
        }

        $accessibleText = $text !== '' ? $text : 'Социальная сеть';
        $linkTitle = "Перейти в сообщество в социальной сети '{$accessibleText}'";
        ?>
        <a href="<?=htmlspecialcharsbx($link)?>"
           class="social-link text-decoration-none"
           target="_blank"
           rel="noopener noreferrer"
           title="<?=htmlspecialcharsbx($linkTitle)?>">
            <img class="social-icon"
                 src="<?=htmlspecialcharsbx($imageUrl)?>"
                 alt=""
                 aria-hidden="true">
            <span class="social-link__label"><?=htmlspecialcharsbx($accessibleText)?></span>
        </a>
    <?php endforeach; ?>
</div>
