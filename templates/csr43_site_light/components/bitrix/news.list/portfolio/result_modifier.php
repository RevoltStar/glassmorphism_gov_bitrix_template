<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
if ($iblockId <= 0) {
    foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
        $iblockId = is_array($item) ? max(0, (int)($item['IBLOCK_ID'] ?? 0)) : 0;
        if ($iblockId > 0) {
            break;
        }
    }
}
$iblockAvailable = $iblockId > 0 && Loader::includeModule('iblock');
$fileCache = [];
$view = [
    'items' => [],
    'element_edit_action' => $iblockAvailable ? site_string(CIBlock::GetArrayByID($iblockId, 'ELEMENT_EDIT')) : '',
    'element_delete_action' => $iblockAvailable ? site_string(CIBlock::GetArrayByID($iblockId, 'ELEMENT_DELETE')) : '',
];

$resolveImage = static function (mixed $value) use (&$fileCache, $iblockAvailable): ?array {
    $file = is_array($value) ? $value : null;
    if ($file === null) {
        $fileId = max(0, (int)$value);
        if ($fileId <= 0 || !$iblockAvailable) {
            return null;
        }
        if (!array_key_exists($fileId, $fileCache)) {
            $fileCache[$fileId] = CFile::GetFileArray($fileId) ?: null;
        }
        $file = is_array($fileCache[$fileId]) ? $fileCache[$fileId] : null;
    }

    if ($file === null || ($url = site_url($file['SRC'] ?? null, '')) === '') {
        return null;
    }

    return [
        'url' => $url,
        'width' => max(0, (int)($file['WIDTH'] ?? 0)),
        'height' => max(0, (int)($file['HEIGHT'] ?? 0)),
    ];
};

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $id = max(0, (int)($item['ID'] ?? 0));
    $name = site_plain_text($item['~NAME'] ?? $item['NAME'] ?? '');
    if ($id <= 0 || $name === '') {
        continue;
    }

    $imageValue = !empty($item['PREVIEW_PICTURE']) ? $item['PREVIEW_PICTURE'] : ($item['DETAIL_PICTURE'] ?? null);
    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $displayProperties = is_array($item['DISPLAY_PROPERTIES'] ?? null) ? $item['DISPLAY_PROPERTIES'] : [];
    $linkProperty = is_array($properties['LINK'] ?? null)
        ? $properties['LINK']
        : (is_array($displayProperties['LINK'] ?? null) ? $displayProperties['LINK'] : []);
    $previewIsHtml = strtolower(site_string($item['PREVIEW_TEXT_TYPE'] ?? 'text')) === 'html';
    $rawPreview = site_string($item['~PREVIEW_TEXT'] ?? $item['PREVIEW_TEXT'] ?? '');

    $view['items'][] = [
        'id' => $id,
        'name' => $name,
        'url' => site_url($linkProperty['~VALUE'] ?? $linkProperty['VALUE'] ?? null, ''),
        'image' => $resolveImage($imageValue),
        'preview_is_html' => $previewIsHtml,
        'preview_html' => $previewIsHtml ? site_safe_html($rawPreview) : '',
        'preview_text' => $previewIsHtml ? '' : site_plain_text($rawPreview),
        'edit_link' => site_string($item['EDIT_LINK'] ?? ''),
        'delete_link' => site_string($item['DELETE_LINK'] ?? ''),
    ];
}

$arResult['PORTFOLIO'] = $view;
