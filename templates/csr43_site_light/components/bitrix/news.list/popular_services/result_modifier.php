<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$fileCache = [];
$fallbackFaceName = site_plain_text(GetMessage('CSR43_LIGHT_POPULAR_SERVICES_FACE_FALLBACK'));
$fallbackFaceName = $fallbackFaceName !== '' ? $fallbackFaceName : 'Этап';
$view = ['items' => []];

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $id = max(0, (int)($item['ID'] ?? 0));
    $name = site_plain_text($item['NAME'] ?? '');
    if ($id <= 0 || $name === '') {
        continue;
    }

    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $linkProperty = is_array($properties['LINK'] ?? null) ? $properties['LINK'] : [];
    $imagesProperty = is_array($properties['IMAGES'] ?? null) ? $properties['IMAGES'] : [];
    $fileIds = [];

    foreach (is_array($imagesProperty['VALUE'] ?? null) ? $imagesProperty['VALUE'] : [$imagesProperty['VALUE'] ?? null] as $fileId) {
        $fileId = max(0, (int)$fileId);
        if ($fileId > 0) {
            $fileIds[] = $fileId;
        }
        if (count($fileIds) >= 6) {
            break;
        }
    }

    $faces = [];
    foreach ($fileIds as $fileId) {
        if (!array_key_exists($fileId, $fileCache)) {
            $fileCache[$fileId] = CFile::GetFileArray($fileId) ?: null;
        }

        $file = is_array($fileCache[$fileId]) ? $fileCache[$fileId] : [];
        $description = site_plain_text($file['DESCRIPTION'] ?? '');
        $faceName = $description !== '' ? site_plain_text(pathinfo($description, PATHINFO_FILENAME)) : '';
        $faces[] = [
            'name' => $faceName !== '' ? $faceName : $fallbackFaceName,
            'url' => site_url($file['SRC'] ?? null, ''),
        ];
    }

    while (count($faces) < 6) {
        $faces[] = [
            'name' => $fallbackFaceName,
            'url' => '',
        ];
    }

    $previewSource = site_string($item['~PREVIEW_TEXT'] ?? '');
    if ($previewSource === '') {
        $previewSource = site_string($item['PREVIEW_TEXT'] ?? '');
    }

    $view['items'][] = [
        'id' => $id,
        'name' => $name,
        'preview_html' => site_safe_html($previewSource),
        'url' => site_url($linkProperty['VALUE'] ?? null, ''),
        'faces' => $faces,
    ];
}

$arResult['POPULAR_SERVICES'] = $view;
