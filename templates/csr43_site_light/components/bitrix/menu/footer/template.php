<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$items = is_array($arResult ?? null) ? $arResult : [];
?>
<ul class="footer-menu__list list-unstyled">
    <?php foreach ($items as $item): ?>
        <?php
        if (!is_array($item) || (int)($item['DEPTH_LEVEL'] ?? 1) !== 1) {
            continue;
        }

        $text = site_plain_text($item['TEXT'] ?? '');
        $link = site_url($item['LINK'] ?? null, '');
        if ($text === '') {
            continue;
        }
        ?>
        <li class="footer-menu__item mb-2">
            <?php if ($link !== ''): ?>
                <a href="<?=htmlspecialcharsbx($link)?>" class="footer-menu__link small" <?php if (!empty($item['SELECTED'])): ?>aria-current="page"<?php endif; ?>>
                    <?=htmlspecialcharsbx($text)?>
                </a>
            <?php else: ?>
                <span class="footer-text-muted small"><?=htmlspecialcharsbx($text)?></span>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
