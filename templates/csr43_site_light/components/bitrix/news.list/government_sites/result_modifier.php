<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
$itemsBySection = [];
$sectionIds = [];

foreach (is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $id = max(0, (int)($item['ID'] ?? 0));
    $sectionId = max(0, (int)($item['IBLOCK_SECTION_ID'] ?? 0));
    $name = site_plain_text($item['NAME'] ?? '');
    if ($id <= 0 || $sectionId <= 0 || $name === '') {
        continue;
    }

    $properties = is_array($item['PROPERTIES'] ?? null) ? $item['PROPERTIES'] : [];
    $linkProperty = is_array($properties['LINK'] ?? null) ? $properties['LINK'] : [];
    $iconProperty = is_array($properties['ICON'] ?? null) ? $properties['ICON'] : [];
    $url = site_url($linkProperty['VALUE'] ?? null, '');

    $itemsBySection[$sectionId][] = [
        'id' => $id,
        'name' => $name,
        'url' => $url,
        'is_external' => $url !== '' && site_is_external_http_url($url),
        'icon' => site_css_classes($iconProperty['VALUE'] ?? '', 'bi-building'),
        'edit_link' => site_string($item['EDIT_LINK'] ?? ''),
        'delete_link' => site_string($item['DELETE_LINK'] ?? ''),
    ];
    $sectionIds[$sectionId] = $sectionId;
}

$groups = [];
$elementEditAction = '';
$elementDeleteAction = '';

if ($iblockId > 0 && $sectionIds !== [] && Loader::includeModule('iblock')) {
    $sectionResult = CIBlockSection::GetList(
        ['SORT' => 'ASC', 'NAME' => 'ASC', 'ID' => 'ASC'],
        [
            'IBLOCK_ID' => $iblockId,
            'ID' => array_values($sectionIds),
            'ACTIVE' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
        ],
        false,
        ['ID', 'NAME', 'SORT']
    );

    while ($section = $sectionResult->Fetch()) {
        $sectionId = max(0, (int)($section['ID'] ?? 0));
        $name = site_plain_text($section['NAME'] ?? '');
        if ($sectionId <= 0 || $name === '' || !isset($itemsBySection[$sectionId])) {
            continue;
        }

        $groups[] = [
            'id' => $sectionId,
            'name' => $name,
            'items' => $itemsBySection[$sectionId],
        ];
    }

    $elementEditAction = site_string(CIBlock::GetArrayByID($iblockId, 'ELEMENT_EDIT'));
    $elementDeleteAction = site_string(CIBlock::GetArrayByID($iblockId, 'ELEMENT_DELETE'));
}

$arResult['GOVERNMENT_SITES'] = [
    'groups' => $groups,
    'element_edit_action' => $elementEditAction,
    'element_delete_action' => $elementDeleteAction,
];
