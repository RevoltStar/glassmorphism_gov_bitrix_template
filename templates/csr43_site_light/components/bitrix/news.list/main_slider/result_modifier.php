<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$allowedObjectFits = ['contain', 'cover', 'fill', 'none', 'scale-down'];
$view = ['items' => []];

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $name = site_plain_text($item['~NAME'] ?? $item['NAME'] ?? '');
    if ($name === '') {
        continue;
    }

    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $linkProperty = is_array($properties['LINK'] ?? null) ? $properties['LINK'] : [];
    $objectFitProperty = is_array($properties['OBJECT_FIT'] ?? null) ? $properties['OBJECT_FIT'] : [];
    $objectFit = strtolower(trim(site_string($objectFitProperty['~VALUE'] ?? $objectFitProperty['VALUE'] ?? '')));
    $picture = is_array($item['PREVIEW_PICTURE'] ?? null) ? $item['PREVIEW_PICTURE'] : [];
    $imageUrl = site_url($picture['SRC'] ?? null, '');
    $imageAlt = site_plain_text($picture['ALT'] ?? '');
    $previewIsHtml = strtolower(site_string($item['PREVIEW_TEXT_TYPE'] ?? 'text')) === 'html';
    $rawPreview = site_string($item['~PREVIEW_TEXT'] ?? $item['PREVIEW_TEXT'] ?? '');

    $view['items'][] = [
        'name' => $name,
        'url' => site_url($linkProperty['~VALUE'] ?? $linkProperty['VALUE'] ?? null, ''),
        'image' => $imageUrl !== '' ? [
            'url' => $imageUrl,
            'alt' => $imageAlt !== '' ? $imageAlt : $name,
        ] : null,
        'preview_is_html' => $previewIsHtml,
        'preview_html' => $previewIsHtml ? site_safe_html($rawPreview) : '',
        'preview_text' => $previewIsHtml ? '' : site_plain_text($rawPreview),
        'object_fit' => in_array($objectFit, $allowedObjectFits, true) ? $objectFit : 'cover',
    ];
}

$arResult['MAIN_SLIDER'] = $view;
