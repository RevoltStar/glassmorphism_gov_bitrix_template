<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!Loader::includeModule('iblock')) {
    ShowError('Не удалось подключить модуль инфоблоков.');
    return;
}

$sectionId = max(0, (int)($arParams['SECTION_ID'] ?? 0));
$newsCount = min(1000, max(1, (int)($arParams['NEWS_COUNT'] ?? 100)));
$cacheType = (string)($arParams['CACHE_TYPE'] ?? 'A');
if (!in_array($cacheType, ['A', 'Y', 'N'], true)) {
    $cacheType = 'A';
}

$section = $sectionId > 0
    ? CIBlockSection::GetList([], ['ID' => $sectionId, 'IBLOCK_ID' => 17, 'CHECK_PERMISSIONS' => 'Y'], false, ['ID'])->Fetch()
    : false;

if (!$section) {
    ShowError('В компоненте «Ссылки» не выбран раздел или выбран раздел другого инфоблока.');
    return;
}

$showUpdateDate = ($arParams['SHOW_UPDATE_DATE'] ?? 'N') === 'Y' ? 'Y' : 'N';

$APPLICATION->IncludeComponent(
    'bitrix:news.list',
    'links',
    [
        'IBLOCK_TYPE' => 'mintrans',
        'IBLOCK_ID' => '17',
        'NEWS_COUNT' => (string)$newsCount,
        'SORT_BY1' => 'SORT',
        'SORT_ORDER1' => 'ASC',
        'SORT_BY2' => 'ID',
        'SORT_ORDER2' => 'ASC',
        'FIELD_CODE' => ['ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'TIMESTAMP_X'],
        'PROPERTY_CODE' => ['LINK', 'ICON'],
        'PARENT_SECTION' => (string)$sectionId,
        'SECTION_ID' => (string)$sectionId,
        'INCLUDE_SUBSECTIONS' => ($arParams['INCLUDE_SUBSECTIONS'] ?? 'N') === 'Y' ? 'Y' : 'N',
        'CHECK_DATES' => 'Y',
        'SHOW_DATE' => $showUpdateDate,
        'SHOW_UPDATE_DATE' => $showUpdateDate,
        'DATE_FIELD' => 'TIMESTAMP_X',
        'SHOW_SECTION_NAME' => ($arParams['SHOW_SECTION_NAME'] ?? 'N') === 'Y' ? 'Y' : 'N',
        'CACHE_TYPE' => $cacheType,
        'CACHE_TIME' => max(0, (int)($arParams['CACHE_TIME'] ?? 36000000)),
        'CACHE_FILTER' => 'N',
        'CACHE_GROUPS' => ($arParams['CACHE_GROUPS'] ?? 'Y') === 'N' ? 'N' : 'Y',
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
