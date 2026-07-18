<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
$arResult['SECTIONS'] = [];

if ($iblockId === 0) {
    return;
}

$sections = [];
$sectionResult = CIBlockSection::GetList(
    ['LEFT_MARGIN' => 'ASC'],
    [
        'IBLOCK_ID' => $iblockId,
        'SECTION_ID' => false,
        'ACTIVE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
    ],
    false,
    ['ID', 'NAME', 'CODE', 'UF_*']
);

while ($section = $sectionResult->Fetch()) {
    if (!is_array($section)) {
        continue;
    }

    $sectionId = max(0, (int)($section['ID'] ?? 0));
    if ($sectionId === 0) {
        continue;
    }

    $section['ID'] = $sectionId;
    $section['ELEMENTS'] = [];
    $sections[$sectionId] = $section;
}

if ($sections === []) {
    return;
}

$elementResult = CIBlockElement::GetList(
    ['SORT' => 'ASC', 'NAME' => 'ASC'],
    [
        'IBLOCK_ID' => $iblockId,
        'SECTION_ID' => array_keys($sections),
        'ACTIVE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
    ],
    false,
    false,
    [
        'ID',
        'NAME',
        'IBLOCK_SECTION_ID',
        'PREVIEW_TEXT',
        'PREVIEW_PICTURE',
        'DETAIL_PAGE_URL',
        'PROPERTY_PHONE',
        'PROPERTY_ADDRESS',
        'PROPERTY_EMAIL',
    ]
);

while ($element = $elementResult->GetNextElement()) {
    $fields = $element->GetFields();
    $properties = $element->GetProperties();
    if (!is_array($fields) || !is_array($properties)) {
        continue;
    }

    foreach (['PHONE', 'ADDRESS', 'EMAIL'] as $code) {
        $properties[$code]['VALUE'] = implode(
            ', ',
            site_string_list($properties[$code]['VALUE'] ?? [])
        );
    }

    $fields['PROPERTIES'] = $properties;
    $sectionId = max(0, (int)($fields['IBLOCK_SECTION_ID'] ?? 0));
    if (isset($sections[$sectionId])) {
        $sections[$sectionId]['ELEMENTS'][] = $fields;
    }
}

$arResult['SECTIONS'] = $sections;
