<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

global $USER;

$arParams = is_array($arParams ?? null) ? $arParams : [];
$arResult = [];

$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));

$currentCategoryCode = site_string(
    $arParams['CURRENT_CATEGORY_CODE'] ?? ''
);

if (!is_string($currentCategoryCode)) {
    $currentCategoryCode = '';
}

$currentCategoryCode = mb_substr($currentCategoryCode, 0, 255);

$baseUrlParam = site_string(
    $arParams['BASE_URL'] ?? '/news/'
);

if (!is_string($baseUrlParam) || $baseUrlParam === '') {
    $baseUrlParam = '/news/';
}

$baseUrl = site_url($baseUrlParam, '/news/');

if (!is_string($baseUrl) || $baseUrl === '') {
    $baseUrl = '/news/';
}

$categoryUrlTemplate = site_string(
    $arParams['CATEGORY_URL_TEMPLATE'] ?? '/news/category/#CODE#/'
);

if (
    !is_string($categoryUrlTemplate)
    || $categoryUrlTemplate === ''
    || !str_contains($categoryUrlTemplate, '#CODE#')
) {
    $categoryUrlTemplate = '/news/category/#CODE#/';
}

$cacheType = (string)($arParams['CACHE_TYPE'] ?? 'A');

if (!in_array($cacheType, ['A', 'Y', 'N'], true)) {
    $cacheType = 'A';
}

$cacheTime = max(
    0,
    (int)($arParams['CACHE_TIME'] ?? 36000000)
);

$cacheGroups = ($arParams['CACHE_GROUPS'] ?? 'Y') !== 'N';

$arParams['CACHE_TYPE'] = $cacheType;
$arParams['CACHE_TIME'] = $cacheTime;
$arParams['CACHE_GROUPS'] = $cacheGroups ? 'Y' : 'N';

if ($iblockId <= 0) {
    ShowError(
        Loc::getMessage('CSR43_NEWS_CATEGORIES_ERROR_IBLOCK')
    );
    return;
}

/*
 * Результат зависит от прав пользователя, поэтому при CACHE_GROUPS=Y
 * группы включаются в дополнительный идентификатор кеша.
 */
$userGroups = [];

if (
    isset($USER)
    && is_object($USER)
    && method_exists($USER, 'GetGroups')
) {
    $userGroups = array_map('intval', (array)$USER->GetGroups());
    sort($userGroups, SORT_NUMERIC);
}

$additionalCacheId = [
    'iblock_id' => $iblockId,
    'current_category' => $currentCategoryCode,
    'base_url' => $baseUrl,
    'category_url_template' => $categoryUrlTemplate,
    'groups' => $userGroups,
];

if ($this->StartResultCache($cacheTime, $additionalCacheId)) {
    if (!Loader::includeModule('iblock')) {
        $this->AbortResultCache();

        ShowError(
            Loc::getMessage('CSR43_NEWS_CATEGORIES_ERROR_MODULE')
        );

        return;
    }

    /*
     * CIBlockPropertyEnum сам по себе не проверяет доступ пользователя
     * к инфоблоку. Поэтому сначала убеждаемся, что инфоблок активен
     * и доступен текущему пользователю.
     */
    $iblock = CIBlock::GetList(
        [],
        [
            'ID' => $iblockId,
            'ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
        ]
    )->Fetch();

    if (!is_array($iblock)) {
        $this->AbortResultCache();

        ShowError(
            Loc::getMessage('CSR43_NEWS_CATEGORIES_ERROR_IBLOCK')
        );

        return;
    }

    $arResult = [
        'ALL_URL' => $baseUrl,
        'ALL_IS_ACTIVE' => $currentCategoryCode === '',
        'ITEMS' => [],
    ];

    /*
     * Поведение намеренно совпадает с текущим news/result_modifier.php:
     * берём все enum-значения свойства category, а не только рубрики,
     * в которых сейчас есть активные новости.
     */
    $categoryResult = CIBlockPropertyEnum::GetList(
        [
            'SORT' => 'ASC',
            'VALUE' => 'ASC',
        ],
        [
            'IBLOCK_ID' => $iblockId,
            'CODE' => 'category',
        ]
    );

    while (
        is_object($categoryResult)
        && ($category = $categoryResult->Fetch())
    ) {
        if (!is_array($category)) {
            continue;
        }

        $categoryId = max(
            0,
            (int)($category['ID'] ?? 0)
        );

        $categoryValue = site_string(
            $category['VALUE'] ?? ''
        );

        $categoryXmlId = site_string(
            $category['XML_ID'] ?? ''
        );

        if (
            $categoryId <= 0
            || !is_string($categoryValue)
            || $categoryValue === ''
            || !is_string($categoryXmlId)
            || $categoryXmlId === ''
        ) {
            continue;
        }

        $categoryUrl = str_replace(
            '#CODE#',
            rawurlencode($categoryXmlId),
            $categoryUrlTemplate
        );

        $categoryUrl = site_url($categoryUrl);

        if (!is_string($categoryUrl) || $categoryUrl === '') {
            continue;
        }

        $arResult['ITEMS'][] = [
            'ID' => $categoryId,
            'VALUE' => $categoryValue,
            'XML_ID' => $categoryXmlId,
            'URL' => $categoryUrl,
            'IS_ACTIVE' => $currentCategoryCode === $categoryXmlId,
        ];
    }

    $this->IncludeComponentTemplate();
}