<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!Loader::includeModule('iblock')) {
    ShowError('Не удалось подключить модуль инфоблоков.');
    return;
}

$iblockType = trim((string)get_info('files_iblock_type', ''));
$iblockId = max(0, (int)get_info('files_iblock_id', 0));
$sectionKey = site_string($arParams['SECTION_ID'] ?? '');
$sectionId = preg_match('/^section_([1-9][0-9]*)$/D', $sectionKey, $sectionMatch) === 1
    ? (int)$sectionMatch[1]
    : 0;
$newsCount = min(1000, max(1, (int)($arParams['NEWS_COUNT'] ?? 100)));
$displayChildSections = ($arParams['DISPLAY_CHILD_SECTIONS'] ?? 'N') === 'Y';
$cacheType = (string)($arParams['CACHE_TYPE'] ?? 'A');
if (!in_array($cacheType, ['A', 'Y', 'N'], true)) {
    $cacheType = 'A';
}

$iblock = $iblockType !== '' && $iblockId > 0
    ? CIBlock::GetList([], ['ID' => $iblockId, 'TYPE' => $iblockType, 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y'])->Fetch()
    : false;

if (!$iblock) {
    ShowError('Инфоблок для компонента «Список файлов» не настроен или недоступен.');
    return;
}

$section = $sectionId > 0
    ? CIBlockSection::GetList([], ['ID' => $sectionId, 'IBLOCK_ID' => $iblockId, 'CHECK_PERMISSIONS' => 'Y', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'], false, ['ID'])->Fetch()
    : false;

if (!$section) {
    ShowError('В компоненте «Список файлов» не выбран раздел или выбран раздел другого инфоблока.');
    return;
}

$allowedSortFields = ['ID', 'NAME', 'SORT', 'ACTIVE_FROM', 'DATE_CREATE', 'TIMESTAMP_X'];
$sortBy1 = strtoupper(trim((string)($arParams['SORT_BY1'] ?? 'SORT')));
$sortBy2 = strtoupper(trim((string)($arParams['SORT_BY2'] ?? 'ID')));
$sortBy1 = in_array($sortBy1, $allowedSortFields, true) ? $sortBy1 : 'SORT';
$sortBy2 = in_array($sortBy2, $allowedSortFields, true) ? $sortBy2 : 'ID';
$sortOrder1 = strtoupper((string)($arParams['SORT_ORDER1'] ?? 'ASC')) === 'DESC' ? 'DESC' : 'ASC';
$sortOrder2 = strtoupper((string)($arParams['SORT_ORDER2'] ?? 'ASC')) === 'DESC' ? 'DESC' : 'ASC';

$sectionIds = [$sectionId];
if ($displayChildSections) {
    $sectionIds = [];
    $sectionResult = CIBlockSection::GetList(
        ['SORT' => 'ASC', 'NAME' => 'DESC'],
        [
            'IBLOCK_ID' => $iblockId,
            'SECTION_ID' => $sectionId,
            'ACTIVE' => 'Y',
            'GLOBAL_ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
        ],
        false,
        ['ID']
    );

    while ($childSection = $sectionResult->Fetch()) {
        $childSectionId = max(0, (int)($childSection['ID'] ?? 0));
        if ($childSectionId > 0) {
            $sectionIds[] = $childSectionId;
        }
    }
}

foreach ($sectionIds as $currentSectionId) {
    $APPLICATION->IncludeComponent(
        'bitrix:news.list',
        'files',
        [
        'IBLOCK_TYPE' => $iblockType,
        'IBLOCK_ID' => (string)$iblockId,
        'NEWS_COUNT' => (string)$newsCount,
        'SORT_BY1' => $sortBy1,
        'SORT_ORDER1' => $sortOrder1,
        'SORT_BY2' => $sortBy2,
        'SORT_ORDER2' => $sortOrder2,
        'FIELD_CODE' => ['ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'TIMESTAMP_X'],
        'PROPERTY_CODE' => ['FILES'],
        'PARENT_SECTION' => (string)$currentSectionId,
        'SECTION_ID' => (string)$currentSectionId,
        'INCLUDE_SUBSECTIONS' => ($arParams['INCLUDE_SUBSECTIONS'] ?? 'N') === 'Y' ? 'Y' : 'N',
        'CHECK_DATES' => 'Y',
        'SHOW_DATE' => ($arParams['SHOW_DATE'] ?? 'N') === 'Y' ? 'Y' : 'N',
        'DATE_FIELD' => 'TIMESTAMP_X',
        'SHOW_SECTION_NAME' => ($arParams['SHOW_SECTION_NAME'] ?? 'N') === 'Y' ? 'Y' : 'N',
        'COLLAPSE_SECTION' => ($arParams['COLLAPSE_SECTION'] ?? 'N') === 'Y' ? 'Y' : 'N',
        'SHOW_IMAGE_IMMEDIATELY' => ($arParams['SHOW_IMAGE_IMMEDIATELY'] ?? 'N') === 'Y' ? 'Y' : 'N',
        'CACHE_TYPE' => $cacheType,
        'CACHE_TIME' => max(0, (int)($arParams['CACHE_TIME'] ?? 36000000)),
        'CACHE_FILTER' => 'N',
        'CACHE_GROUPS' => 'Y',
        'SET_TITLE' => 'N',
        'SET_BROWSER_TITLE' => 'N',
        'SET_META_KEYWORDS' => 'N',
        'SET_META_DESCRIPTION' => 'N',
        'SET_LAST_MODIFIED' => 'N',
        'ADD_SECTIONS_CHAIN' => 'N',
        'ADD_ELEMENT_CHAIN' => 'N',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'N',
        'SET_STATUS_404' => 'N',
        ],
        $this
    );
}
