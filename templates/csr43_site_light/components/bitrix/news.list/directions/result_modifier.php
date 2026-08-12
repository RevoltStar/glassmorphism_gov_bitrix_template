<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$view = [
    'items' => [],
    'pager_html' => site_safe_pagination_html($arResult['NAV_STRING'] ?? ''),
    'show_top_pager' => ($arParams['DISPLAY_TOP_PAGER'] ?? 'N') === 'Y',
    'show_bottom_pager' => ($arParams['DISPLAY_BOTTOM_PAGER'] ?? 'N') === 'Y',
];

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $id = max(0, (int)($item['ID'] ?? 0));
    $name = site_plain_text($item['~NAME'] ?? $item['NAME'] ?? '');
    if ($id <= 0 || $name === '') {
        continue;
    }

    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $linkProperty = is_array($properties['LINK'] ?? null) ? $properties['LINK'] : [];
    $picture = is_array($item['PREVIEW_PICTURE'] ?? null) ? $item['PREVIEW_PICTURE'] : [];
    $imageUrl = site_url($picture['SRC'] ?? null, '');
    $imageAlt = site_plain_text($picture['ALT'] ?? '');

    $view['items'][] = [
        'id' => $id,
        'name' => $name,
        'url' => site_url($linkProperty['VALUE'] ?? null, ''),
        'image' => $imageUrl !== '' ? [
            'url' => $imageUrl,
            'alt' => $imageAlt !== '' ? $imageAlt : $name,
        ] : null,
    ];
}

$arResult['DIRECTIONS_LIST'] = $view;
