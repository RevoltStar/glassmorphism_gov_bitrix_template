<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) { die(); }

use Bitrix\Main\Loader;

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
$sectionId = max(0, (int)($arParams['SECTION_ID'] ?? $arParams['PARENT_SECTION'] ?? 0));
$showSection = ($arParams['SHOW_SECTION_NAME'] ?? 'N') === 'Y';
$showDate = ($arParams['SHOW_DATE'] ?? 'N') === 'Y';
$allowedDateFields = ['ACTIVE_FROM', 'DATE_CREATE', 'TIMESTAMP_X'];
$dateField = site_string($arParams['DATE_FIELD'] ?? 'TIMESTAMP_X');
$dateField = in_array($dateField, $allowedDateFields, true) ? $dateField : 'TIMESTAMP_X';
$view = [
    'section' => null,
    'collapse_section' => ($arParams['COLLAPSE_SECTION'] ?? 'N') === 'Y',
    'show_image_immediately' => ($arParams['SHOW_IMAGE_IMMEDIATELY'] ?? 'N') === 'Y',
    'items' => [],
    'pager_html' => site_safe_pagination_html($arResult['NAV_STRING'] ?? ''),
    'show_top_pager' => ($arParams['DISPLAY_TOP_PAGER'] ?? 'N') === 'Y',
    'show_bottom_pager' => ($arParams['DISPLAY_BOTTOM_PAGER'] ?? 'N') === 'Y',
];

if ($showSection && $iblockId > 0 && $sectionId > 0 && Loader::includeModule('iblock')) {
    $sectionResult = CIBlockSection::GetList([], ['ID' => $sectionId, 'IBLOCK_ID' => $iblockId, 'CHECK_PERMISSIONS' => 'Y'], false, ['ID', 'NAME', 'DESCRIPTION']);
    $section = $sectionResult->GetNext();
    if (is_array($section)) {
        $view['section'] = ['name' => site_plain_text($section['~NAME'] ?? $section['NAME'] ?? ''), 'description' => site_plain_text($section['~DESCRIPTION'] ?? $section['DESCRIPTION'] ?? '')];
    }
}

$cache = [];
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
$videoTypes = ['mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg'];
$iconMap = ['pdf'=>'bi-filetype-pdf','doc'=>'bi-filetype-doc','docx'=>'bi-filetype-docx','xls'=>'bi-filetype-xls','xlsx'=>'bi-filetype-xlsx','ppt'=>'bi-filetype-ppt','pptx'=>'bi-filetype-pptx','zip'=>'bi-file-earmark-zip','rar'=>'bi-file-earmark-zip','svg'=>'bi-filetype-svg','txt'=>'bi-filetype-txt','xml'=>'bi-filetype-xml','csv'=>'bi-filetype-csv'];

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) { continue; }
    $name = site_plain_text($item['~NAME'] ?? $item['NAME'] ?? '');
    $description = site_plain_text($item['~PREVIEW_TEXT'] ?? $item['PREVIEW_TEXT'] ?? $item['~DETAIL_TEXT'] ?? $item['DETAIL_TEXT'] ?? '');
    if (mb_strlen($description) > 150) { $description = rtrim(mb_substr($description, 0, 150)) . '…'; }
    $date = '';
    $timestamp = strtotime(site_string($item[$dateField] ?? ''));
    if ($showDate && $timestamp !== false) { $date = date('d.m.Y H:i', $timestamp); }
    $files = [];
    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $fileProperty = is_array($properties['FILES'] ?? null) ? $properties['FILES'] : [];
    foreach (site_string_list($fileProperty['VALUE'] ?? []) as $rawId) {
        $fileId = max(0, (int)$rawId);
        if ($fileId === 0) { continue; }
        if (!array_key_exists($fileId, $cache)) { $cache[$fileId] = CFile::GetFileArray($fileId); }
        $file = is_array($cache[$fileId]) ? $cache[$fileId] : [];
        $url = site_url($file['SRC'] ?? null, '');
        if ($url === '') { continue; }
        $filename = site_plain_text($file['ORIGINAL_NAME'] ?? $file['FILE_NAME'] ?? '');
        $extension = strtolower((string)pathinfo(site_string($file['FILE_NAME'] ?? $filename), PATHINFO_EXTENSION));
        $type = isset($videoTypes[$extension]) ? 'video' : (in_array($extension, $imageExtensions, true) ? 'image' : 'download');
        $caption = site_plain_text($file['DESCRIPTION'] ?? '');
        $fileSize = max(0, (int)($file['FILE_SIZE'] ?? 0));
        $files[] = [
            'id' => $fileId,
            'type' => $type,
            'url' => $url,
            'display_name' => $name !== '' ? $name : $filename,
            'filename' => $filename,
            'caption' => $caption !== '' ? $caption : $name,
            'extension' => $extension,
            'display_size' => $fileSize > 0 ? site_plain_text(CFile::FormatSize($fileSize)) : '',
            'width' => max(0, (int)($file['WIDTH'] ?? 0)),
            'height' => max(0, (int)($file['HEIGHT'] ?? 0)),
            'icon' => $iconMap[$extension] ?? 'bi-file-earmark',
            'mime' => $videoTypes[$extension] ?? '',
        ];
    }
    $view['items'][] = ['id'=>max(0,(int)($item['ID']??0)),'name'=>$name,'description'=>$description,'date'=>$date,'files'=>$files];
}
$arResult['FILE_LIST'] = $view;
