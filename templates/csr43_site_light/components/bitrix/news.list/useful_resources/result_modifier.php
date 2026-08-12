<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$typeClasses = [
    'horizontal' => 'useful-resources--horizontal',
    'vertical' => 'useful-resources--vertical',
];
$type = strtolower(trim(site_string($arParams['TYPE'] ?? '')));
$view = [
    'items' => [],
    'modifier_class' => $typeClasses[$type] ?? '',
    'pager_html' => site_safe_pagination_html($arResult['NAV_STRING'] ?? ''),
    'show_top_pager' => ($arParams['DISPLAY_TOP_PAGER'] ?? 'N') === 'Y',
    'show_bottom_pager' => ($arParams['DISPLAY_BOTTOM_PAGER'] ?? 'N') === 'Y',
];

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $name = site_plain_text($item['~NAME'] ?? $item['NAME'] ?? '');
    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $linkProperty = is_array($properties['LINK'] ?? null) ? $properties['LINK'] : [];
    $url = site_url($linkProperty['~VALUE'] ?? $linkProperty['VALUE'] ?? null, '');
    $picture = is_array($item['PREVIEW_PICTURE'] ?? null) ? $item['PREVIEW_PICTURE'] : [];
    $imageUrl = site_url($picture['SRC'] ?? null, '');
    $imageAlt = site_plain_text($picture['ALT'] ?? '');

    $view['items'][] = [
        'name' => $name,
        'url' => $url,
        'is_external' => $url !== '' && site_is_external_http_url($url),
        'image' => $imageUrl !== '' ? [
            'url' => $imageUrl,
            'alt' => $imageAlt !== '' ? $imageAlt : $name,
            'title' => site_plain_text($picture['TITLE'] ?? ''),
        ] : null,
    ];
}

$arResult['USEFUL_RESOURCES'] = $view;
