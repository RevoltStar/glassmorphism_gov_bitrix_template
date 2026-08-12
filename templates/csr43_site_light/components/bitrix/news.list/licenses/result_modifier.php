<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$iblockAvailable = Loader::includeModule('iblock');
$fileCache = [];
$view = ['items' => []];

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
    $imageProperty = is_array($properties['IMG'] ?? null) ? $properties['IMG'] : [];
    $fileIds = is_array($imageProperty['VALUE'] ?? null)
        ? $imageProperty['VALUE']
        : [$imageProperty['VALUE'] ?? null];
    $images = [];

    foreach ($fileIds as $fileId) {
        $fileId = max(0, (int)$fileId);
        if ($fileId <= 0) {
            continue;
        }

        if (!$iblockAvailable) {
            continue;
        }

        if (!array_key_exists($fileId, $fileCache)) {
            $fileCache[$fileId] = CFile::GetFileArray($fileId) ?: null;
        }

        $file = is_array($fileCache[$fileId]) ? $fileCache[$fileId] : [];
        $url = site_url($file['SRC'] ?? null, '');
        if ($url === '') {
            continue;
        }

        $description = site_plain_text($file['DESCRIPTION'] ?? '');
        $images[] = [
            'url' => $url,
            'caption' => $description !== '' ? $description : $name,
        ];
    }

    $view['items'][] = [
        'id' => $id,
        'name' => $name,
        'images' => $images,
    ];
}

$arResult['LICENSES'] = $view;
