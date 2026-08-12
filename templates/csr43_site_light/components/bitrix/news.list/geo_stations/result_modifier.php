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
$propertyValue = static function (array $properties, string $code): string {
    $property = is_array($properties[$code] ?? null) ? $properties[$code] : [];
    return site_plain_text($property['~VALUE'] ?? $property['VALUE'] ?? '');
};

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $name = site_plain_text($item['~NAME'] ?? $item['NAME'] ?? '');
    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $linkText = $propertyValue($properties, 'LINK');

    $view['items'][] = [
        'name' => $name,
        'type' => $propertyValue($properties, 'TYPE'),
        'name_point' => $propertyValue($properties, 'NAME_POINT'),
        'coord_system' => $propertyValue($properties, 'COORD_SYSTEM'),
        'measuring_device' => $propertyValue($properties, 'MEASURING_DEVICE'),
        'serial_number' => $propertyValue($properties, 'SERIAL_NUMBER'),
        'link_text' => $linkText,
        'url' => site_url($linkText, ''),
    ];
}

$arResult['GEO_STATIONS'] = $view;
