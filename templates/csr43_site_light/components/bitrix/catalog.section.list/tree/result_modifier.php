<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$sourceSections = is_array($arResult['SECTIONS'] ?? null) ? $arResult['SECTIONS'] : [];
$showCount = ($arParams['COUNT_ELEMENTS'] ?? 'N') === 'Y';
$selectedSectionId = max(0, (int)($arParams['SECTION_ID'] ?? 0));
if ($selectedSectionId === 0) {
    $selectedSectionId = max(0, (int)($_REQUEST['SECTION_ID'] ?? 0));
}

$view = [
    'nodes' => [],
    'selected_id' => $selectedSectionId,
    'selected_title' => '',
    'section_edit_action' => '',
    'section_delete_action' => '',
];
$arResult['SECTION_TREE'] = $view;

if ($sourceSections === []) {
    return;
}

$nodes = [];
$orderedIds = [];
$hasParentField = false;
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));

foreach ($sourceSections as $sourceSection) {
    if (!is_array($sourceSection)) {
        continue;
    }

    $sectionId = max(0, (int)($sourceSection['ID'] ?? 0));
    if ($sectionId === 0 || isset($nodes[$sectionId])) {
        continue;
    }

    $hasParentField = $hasParentField || array_key_exists('IBLOCK_SECTION_ID', $sourceSection);
    $sectionIblockId = max(0, (int)($sourceSection['IBLOCK_ID'] ?? 0));
    if ($iblockId === 0 && $sectionIblockId > 0) {
        $iblockId = $sectionIblockId;
    }

    $count = max(0, (int)($sourceSection['ELEMENT_CNT'] ?? 0));
    $name = site_plain_text($sourceSection['~NAME'] ?? $sourceSection['NAME'] ?? '');
    $nodes[$sectionId] = [
        'id' => $sectionId,
        'parent_id' => max(0, (int)($sourceSection['IBLOCK_SECTION_ID'] ?? 0)),
        'depth' => max(0, (int)($sourceSection['DEPTH_LEVEL'] ?? 0)),
        'name' => $name,
        'url' => site_url($sourceSection['SECTION_PAGE_URL'] ?? null, ''),
        'count' => $count,
        'show_count' => $showCount && $count > 0,
        'is_current' => $sectionId === $selectedSectionId,
        'edit_link' => site_string($sourceSection['EDIT_LINK'] ?? ''),
        'delete_link' => site_string($sourceSection['DELETE_LINK'] ?? ''),
        'children' => [],
    ];
    $orderedIds[] = $sectionId;

    if ($sectionId === $selectedSectionId) {
        $view['selected_title'] = $name;
    }
}

if ($nodes === []) {
    return;
}

if ($iblockId > 0 && Loader::includeModule('iblock')) {
    $view['section_edit_action'] = site_string(CIBlock::GetArrayByID($iblockId, 'SECTION_EDIT'));
    $view['section_delete_action'] = site_string(CIBlock::GetArrayByID($iblockId, 'SECTION_DELETE'));
}

$childrenByParentId = [];
if ($hasParentField) {
    foreach ($orderedIds as $sectionId) {
        $parentId = $nodes[$sectionId]['parent_id'];
        if ($parentId === $sectionId || !isset($nodes[$parentId])) {
            $parentId = 0;
        }
        $childrenByParentId[$parentId][] = $sectionId;
    }
} else {
    $depthStack = [];
    foreach ($orderedIds as $sectionId) {
        $depth = $nodes[$sectionId]['depth'];
        foreach (array_keys($depthStack) as $stackDepth) {
            if ($stackDepth >= $depth) {
                unset($depthStack[$stackDepth]);
            }
        }

        $parentId = 0;
        if ($depth > 0) {
            for ($parentDepth = $depth - 1; $parentDepth >= 0; $parentDepth--) {
                if (isset($depthStack[$parentDepth])) {
                    $parentId = $depthStack[$parentDepth];
                    break;
                }
            }
        }

        $childrenByParentId[$parentId][] = $sectionId;
        $depthStack[$depth] = $sectionId;
    }
}

$visited = [];
$buildTree = static function (int $parentId, array $path = []) use (&$buildTree, &$visited, $childrenByParentId, $nodes): array {
    $tree = [];
    foreach ($childrenByParentId[$parentId] ?? [] as $sectionId) {
        if (isset($path[$sectionId]) || isset($visited[$sectionId])) {
            continue;
        }

        $visited[$sectionId] = true;
        $childPath = $path;
        $childPath[$sectionId] = true;
        $node = $nodes[$sectionId];
        $node['children'] = $buildTree($sectionId, $childPath);
        $tree[] = $node;
    }

    return $tree;
};

$tree = $buildTree(0);
foreach ($orderedIds as $sectionId) {
    if (isset($visited[$sectionId])) {
        continue;
    }

    $visited[$sectionId] = true;
    $node = $nodes[$sectionId];
    $node['children'] = $buildTree($sectionId, [$sectionId => true]);
    $tree[] = $node;
}

$view['nodes'] = $tree;
$arResult['SECTION_TREE'] = $view;
