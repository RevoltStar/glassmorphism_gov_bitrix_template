<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);

$arResult = is_array($arResult ?? null)
    ? $arResult
    : [];

$categories = is_array($arResult['ITEMS'] ?? null)
    ? $arResult['ITEMS']
    : [];

if ($categories === []) {
    return;
}

$allUrl = site_url($arResult['ALL_URL'] ?? null);

if (!is_string($allUrl) || $allUrl === '') {
    return;
}

$allIsActive = ($arResult['ALL_IS_ACTIVE'] ?? false) === true;
?>

<nav
    class="news-category-filter mb-4"
    aria-label="<?=htmlspecialcharsbx(
        Loc::getMessage('CSR43_NEWS_CATEGORIES_ARIA_LABEL')
    )?>"
>
    <a
        href="<?=htmlspecialcharsbx($allUrl)?>"
        class="news-category-filter__item<?=$allIsActive ? ' is-active' : ''?>"
        <?=$allIsActive ? 'aria-current="page"' : ''?>
    >
        <?=htmlspecialcharsbx(
            Loc::getMessage('CSR43_NEWS_CATEGORIES_ALL')
        )?>
    </a>

    <?php foreach ($categories as $category): ?>
        <?php
        if (!is_array($category)) {
            continue;
        }

        $categoryValue = site_string(
            $category['VALUE'] ?? ''
        );

        $categoryUrl = site_url(
            $category['URL'] ?? null
        );

        if (
            !is_string($categoryValue)
            || $categoryValue === ''
            || !is_string($categoryUrl)
            || $categoryUrl === ''
        ) {
            continue;
        }

        $isActive = ($category['IS_ACTIVE'] ?? false) === true;
        ?>

        <a
            href="<?=htmlspecialcharsbx($categoryUrl)?>"
            class="news-category-filter__item<?=$isActive ? ' is-active' : ''?>"
            <?=$isActive ? 'aria-current="page"' : ''?>
        >
            <?=htmlspecialcharsbx($categoryValue)?>
        </a>
    <?php endforeach; ?>
</nav>