<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$showCounter = !empty($arResult['SHOW_COUNTER']);
$view = [
    'items' => [],
    'show_counter' => $showCounter,
    'pager_html' => site_safe_pagination_html($arResult['NAV_STRING'] ?? ''),
    'show_top_pager' => ($arParams['DISPLAY_TOP_PAGER'] ?? 'N') === 'Y',
    'show_bottom_pager' => ($arParams['DISPLAY_BOTTOM_PAGER'] ?? 'N') === 'Y',
    'search_url' => site_url('/search/', ''),
];

$showName = ($arParams['DISPLAY_NAME'] ?? 'Y') === 'Y';
$showPicture = ($arParams['DISPLAY_PICTURE'] ?? 'Y') === 'Y';
$showPreview = ($arParams['DISPLAY_PREVIEW_TEXT'] ?? 'Y') === 'Y';
$items = is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [];

foreach ($items as $item) {
    if (!is_array($item)) {
        continue;
    }

    $name = site_plain_text($item['~NAME'] ?? $item['NAME'] ?? '');
    $previewSource = site_string($item['~PREVIEW_TEXT'] ?? $item['PREVIEW_TEXT'] ?? '');
    $preview = html_entity_decode(strip_tags($previewSource), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $preview = trim((string)preg_replace('/\s+/u', ' ', $preview));
    if (mb_strlen($preview) > 150) {
        $preview = rtrim(mb_substr($preview, 0, 150)) . '…';
    }

    $date = site_plain_text($item['DISPLAY_ACTIVE_FROM'] ?? '');
    if ($date === '') {
        $dateTimestamp = strtotime(site_string($item['ACTIVE_FROM_X'] ?? $item['ACTIVE_FROM'] ?? ''));
        if ($dateTimestamp !== false) {
            $date = date('d.m.Y', $dateTimestamp);
        }
    }

    $image = null;
    $picture = is_array($item['PREVIEW_PICTURE'] ?? null) ? $item['PREVIEW_PICTURE'] : [];
    $imageUrl = site_url($picture['SRC'] ?? null, '');
    if ($showPicture && $imageUrl !== '') {
        $imageAlt = site_plain_text($picture['ALT'] ?? '');
        $image = ['url' => $imageUrl, 'alt' => $imageAlt !== '' ? $imageAlt : $name];
    }

    $categories = [];
    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $categoryProperty = is_array($properties['category'] ?? null) ? $properties['category'] : [];
    $names = $categoryProperty['VALUE_ENUM'] ?? $categoryProperty['VALUE'] ?? [];
    $names = is_array($names) ? $names : [$names];
    $xmlIds = $categoryProperty['VALUE_XML_ID'] ?? [];
    $xmlIds = is_array($xmlIds) ? $xmlIds : [$xmlIds];
    foreach ($names as $index => $categoryName) {
        $categoryName = site_plain_text($categoryName);
        $xmlId = site_string($xmlIds[$index] ?? '');
        $categoryUrl = $xmlId !== ''
            ? site_url('/news/category/' . rawurlencode($xmlId) . '/', '')
            : '';
        if ($categoryName !== '' && $categoryUrl !== '') {
            $categories[] = ['name' => $categoryName, 'url' => $categoryUrl];
        }
    }

    $view['items'][] = [
        'id' => max(0, (int)($item['ID'] ?? 0)),
        'name' => $name,
        'show_name' => $showName,
        'detail_url' => site_url($item['DETAIL_PAGE_URL'] ?? null, ''),
        'date' => $date,
        'counter' => max(0, (int)($item['SHOW_COUNTER'] ?? 0)),
        'preview_text' => $showPreview ? $preview : '',
        'image' => $image,
        'categories' => $categories,
    ];
}

$arResult['NEWS_LIST'] = $view;
