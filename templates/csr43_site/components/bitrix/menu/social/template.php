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
        $image = site_string($item['PARAMS']['IMAGE'] ?? null, '');
        $text = site_string($item['TEXT'] ?? '');
        if ($link === '#' || $image === '') {
            continue;
        }
        ?>
        <a href="<?=htmlspecialcharsbx($link)?>"
           class="text-decoration-none"
           target="_blank"
           rel="noopener noreferrer">
            <img class="social-icon"
                 src="<?=SITE_TEMPLATE_PATH?>/images/<?=htmlspecialcharsbx($image)?>"
                 alt="Логотип <?=htmlspecialcharsbx($text)?>"
                 title="Перейти в сообщество в социальной сети '<?=htmlspecialcharsbx($text)?>'">
        </a>
    <?php endforeach; ?>
</div>
