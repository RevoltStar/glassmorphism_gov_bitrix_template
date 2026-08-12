<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

$arResult['ORG_STRUCTURE'] = [
    'tree' => [],
    'section_edit_action' => '',
    'section_delete_action' => '',
];

$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
$showEmployees = ($arParams['SHOW_ELEMENTS'] ?? 'N') === 'Y';
$sourceSections = is_array($arResult['SECTIONS'] ?? null) ? $arResult['SECTIONS'] : [];

if ($iblockId === 0 || $sourceSections === []) {
    return;
}

$sections = [];
$sectionIds = [];

foreach ($sourceSections as $sourceSection) {
    if (!is_array($sourceSection)) {
        continue;
    }

    $sectionId = max(0, (int)($sourceSection['ID'] ?? 0));
    if ($sectionId === 0 || isset($sections[$sectionId])) {
        continue;
    }

    $parentId = max(0, (int)($sourceSection['IBLOCK_SECTION_ID'] ?? 0));
    $depth = max(0, (int)($sourceSection['DEPTH_LEVEL'] ?? 0));
    $sections[$sectionId] = [
        'id' => $sectionId,
        'parent_id' => $parentId,
        'depth' => $depth,
        'name' => site_plain_text($sourceSection['~NAME'] ?? $sourceSection['NAME'] ?? ''),
        'edit_link' => site_string($sourceSection['EDIT_LINK'] ?? ''),
        'delete_link' => site_string($sourceSection['DELETE_LINK'] ?? ''),
        'employees' => [],
        'children' => [],
    ];
    $sectionIds[] = $sectionId;
}

if ($sections === []) {
    return;
}

$iblockAvailable = Loader::includeModule('iblock');
if ($iblockAvailable) {
    $arResult['ORG_STRUCTURE']['section_edit_action'] = site_string(
        CIBlock::GetArrayByID($iblockId, 'SECTION_EDIT')
    );
    $arResult['ORG_STRUCTURE']['section_delete_action'] = site_string(
        CIBlock::GetArrayByID($iblockId, 'SECTION_DELETE')
    );
}

if ($showEmployees && $iblockAvailable) {
    $elements = [];
    $elementIds = [];
    $elementSections = [];
    $elementResult = CIBlockElement::GetList(
        ['SORT' => 'ASC', 'ID' => 'ASC'],
        [
            'IBLOCK_ID' => $iblockId,
            'SECTION_ID' => $sectionIds,
            'INCLUDE_SUBSECTIONS' => 'N',
            'ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
        ],
        false,
        false,
        ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'SORT']
    );

    while ($element = $elementResult->GetNext()) {
        if (!is_array($element)) {
            continue;
        }

        $elementId = max(0, (int)($element['ID'] ?? 0));
        if ($elementId === 0 || isset($elements[$elementId])) {
            continue;
        }

        $elements[$elementId] = $element;
        $elementIds[] = $elementId;
        $elementSections[$elementId] = [];
    }

    $propertiesByElementId = array_fill_keys($elementIds, []);
    if ($elementIds !== []) {
        CIBlockElement::GetPropertyValuesArray(
            $propertiesByElementId,
            $iblockId,
            ['ID' => $elementIds],
            ['CODE' => ['STATUS', 'PHONE', 'CABINET', 'ADDRESS']]
        );

        $groupResult = CIBlockElement::GetElementGroups($elementIds, true, ['ID', 'IBLOCK_ELEMENT_ID']);
        while ($group = $groupResult->Fetch()) {
            if (!is_array($group)) {
                continue;
            }

            $elementId = max(0, (int)($group['IBLOCK_ELEMENT_ID'] ?? 0));
            $sectionId = max(0, (int)($group['ID'] ?? 0));
            if (isset($elementSections[$elementId], $sections[$sectionId])) {
                $elementSections[$elementId][$sectionId] = true;
            }
        }
    }

    $phonePrefix = site_plain_text($arParams['PHONE'] ?? '');

    foreach ($elements as $elementId => $element) {
        $properties = is_array($propertiesByElementId[$elementId] ?? null)
            ? $propertiesByElementId[$elementId]
            : [];
        $statusProperty = is_array($properties['STATUS'] ?? null) ? $properties['STATUS'] : [];
        $status = site_string($statusProperty['VALUE_XML_ID'] ?? '');

        // Legacy contract: elements without STATUS are not displayed.
        if ($status === '') {
            continue;
        }

        $phoneProperty = is_array($properties['PHONE'] ?? null) ? $properties['PHONE'] : [];
        $cabinetProperty = is_array($properties['CABINET'] ?? null) ? $properties['CABINET'] : [];
        $addressProperty = is_array($properties['ADDRESS'] ?? null) ? $properties['ADDRESS'] : [];
        $phone = site_plain_text($phoneProperty['VALUE'] ?? '');
        $phoneDialValue = preg_replace('/(?!^\+)[^0-9]|(?<=.)\+/u', '', $phonePrefix . $phone);
        $phoneUrl = is_string($phoneDialValue) && $phoneDialValue !== ''
            ? site_url('tel:' . $phoneDialValue, '', ['tel'], false)
            : '';
        $name = site_plain_text($element['~NAME'] ?? $element['NAME'] ?? '');
        $position = site_plain_text($element['~PREVIEW_TEXT'] ?? $element['PREVIEW_TEXT'] ?? '');
        if ($position === '') {
            $position = $name;
        }

        $isVacant = $status === 'vacant';
        $imageUrl = '';
        $pictureId = max(0, (int)($element['PREVIEW_PICTURE'] ?? 0));
        if (!$isVacant && $pictureId > 0) {
            $imagePath = CFile::GetPath($pictureId);
            $imageUrl = site_url(is_string($imagePath) ? $imagePath : null, '');
        }

        $employee = [
            'id' => $elementId,
            'name' => $name,
            'position' => $position,
            'status' => $status,
            'is_vacant' => $isVacant,
            'is_acting' => $status === 'acting_director',
            'phone' => $phone,
            'phone_url' => $phoneUrl,
            'cabinet' => site_plain_text($cabinetProperty['VALUE'] ?? ''),
            'address' => site_plain_text($addressProperty['VALUE_ENUM'] ?? $addressProperty['VALUE'] ?? ''),
            'image_url' => $imageUrl,
        ];

        $assignedSectionIds = array_keys($elementSections[$elementId] ?? []);
        if ($assignedSectionIds === []) {
            $primarySectionId = max(0, (int)($element['IBLOCK_SECTION_ID'] ?? 0));
            $assignedSectionIds = isset($sections[$primarySectionId]) ? [$primarySectionId] : [];
        }

        foreach ($assignedSectionIds as $sectionId) {
            $sections[$sectionId]['employees'][] = $employee;
        }
    }
}

$childrenByParentId = [];
foreach ($sections as $sectionId => $section) {
    $parentId = isset($sections[$section['parent_id']]) && $section['parent_id'] !== $sectionId
        ? $section['parent_id']
        : 0;
    $childrenByParentId[$parentId][] = $sectionId;
}

$visited = [];
$buildTree = static function (int $parentId, array $path = []) use (&$buildTree, &$visited, $childrenByParentId, $sections): array {
    $tree = [];
    foreach ($childrenByParentId[$parentId] ?? [] as $sectionId) {
        if (isset($path[$sectionId]) || isset($visited[$sectionId])) {
            continue;
        }

        $visited[$sectionId] = true;
        $childPath = $path;
        $childPath[$sectionId] = true;
        $section = $sections[$sectionId];
        $section['children'] = $buildTree($sectionId, $childPath);
        $tree[] = $section;
    }

    return $tree;
};

$tree = $buildTree(0);
foreach (array_keys($sections) as $sectionId) {
    if (isset($visited[$sectionId])) {
        continue;
    }

    $section = $sections[$sectionId];
    $visited[$sectionId] = true;
    $section['children'] = $buildTree($sectionId, [$sectionId => true]);
    $tree[] = $section;
}

$arResult['ORG_STRUCTURE']['tree'] = $tree;
