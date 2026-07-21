<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = is_array($arResult ?? null) ? $arResult : [];
$menuItems = [];

foreach ($items as $item) {
    if (!is_array($item) || (int)($item['DEPTH_LEVEL'] ?? 1) !== 1) {
        continue;
    }

    $text = site_plain_text($item['TEXT'] ?? '');
    if ($text === '') {
        continue;
    }

    $menuItems[] = [
        'LINK' => site_url($item['LINK'] ?? null),
        'TEXT' => $text,
        'SELECTED' => !empty($item['SELECTED']),
    ];
}

if ($menuItems === []) {
    return;
}
?>
<ul class="footer-menu__list list-unstyled">
    <?php foreach ($menuItems as $item): ?>
        <li class="footer-menu__item mb-2">
            <a href="<?=htmlspecialcharsbx($item['LINK'])?>"
               class="footer-menu__link"
               <?php if ($item['SELECTED']): ?>aria-current="page"<?php endif; ?>>
                <i class="footer-menu__link-icon bi bi-chevron-right" aria-hidden="true"></i>
                <?=htmlspecialcharsbx($item['TEXT'])?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
