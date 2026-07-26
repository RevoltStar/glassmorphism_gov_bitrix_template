<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'bitrix:news.detail',
    'detail',
    [
        // 1. Фиксированные параметры.
        'CHECK_DATES' => 'Y',
        'SET_TITLE' => 'Y',
        // Canonical уже устанавливается централизованно в header.php.
        'SET_CANONICAL_URL' => 'N',
        'SET_BROWSER_TITLE' => 'Y',
        'BROWSER_TITLE' => '-',
        'SET_META_KEYWORDS' => 'Y',
        'META_KEYWORDS' => '-',
        'SET_META_DESCRIPTION' => 'Y',
        'META_DESCRIPTION' => '-',
        'SET_LAST_MODIFIED' => 'Y',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
        'ADD_SECTIONS_CHAIN' => 'Y',
        'ADD_ELEMENT_CHAIN' => 'Y',
        'USE_PERMISSIONS' => 'N',
        'STRICT_SECTION_CHECK' => 'Y',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '3600',
        'CACHE_GROUPS' => 'Y',
        'SET_STATUS_404' => 'Y',
        'SHOW_404' => 'Y',
        'AJAX_MODE' => 'N',

        // 2. Параметры, которые определяет администратор.
        'IBLOCK_TYPE' => 'content',
        'IBLOCK_ID' => '8',
        'ELEMENT_CODE' => 'news-item-code',
        'FIELD_CODE' => [
            'ID',
            'NAME',
            'PREVIEW_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_TEXT',
            'DETAIL_PICTURE',
            'DATE_ACTIVE_FROM',
            'SHOW_COUNTER',
        ],
        'PROPERTY_CODE' => [
            'DESCRIPTION',
            'additional_files',
            'category',
        ],
        'DETAIL_URL' => '/news/#ELEMENT_CODE#/',
        'ACTIVE_DATE_FORMAT' => 'd.m.Y',
        'FILE_404' => '/404.php',

        // 3. Не оказывает эффекта, поскольку выбран ELEMENT_CODE.
        'ELEMENT_ID' => '',

        // INCLUDE_IBLOCK_INTO_CHAIN выключен.
        'IBLOCK_URL' => '/news/',

        // Локальный шаблон detail выводит поля самостоятельно и эти флаги
        // стандартного шаблона не читает.
        'DISPLAY_DATE' => 'Y',
        'DISPLAY_NAME' => 'Y',
        'DISPLAY_PICTURE' => 'Y',
        'DISPLAY_PREVIEW_TEXT' => 'Y',

        // Локальный шаблон не выводит штатный блок «Поделиться».
        'USE_SHARE' => 'N',
        'SHARE_HIDE' => 'Y',
        'SHARE_TEMPLATE' => '',
        'SHARE_HANDLERS' => [],
        'SHARE_SHORTEN_URL_LOGIN' => '',
        'SHARE_SHORTEN_URL_KEY' => '',

        // USE_PERMISSIONS выключен.
        'GROUP_PERMISSIONS' => [],

        // Локальный шаблон не выводит NAV_STRING детального компонента.
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'N',
        'PAGER_TITLE' => 'Страница',
        'PAGER_TEMPLATE' => 'csr43',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'PAGER_BASE_LINK' => '',
        'PAGER_PARAMS_NAME' => 'arrPager',

        // При SHOW_404 = Y показывается FILE_404.
        'MESSAGE_404' => '',

        // AJAX_MODE выключен.
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'Y',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',

        // Не является параметром штатного news.detail и компонентом
        // не обрабатывается.
        'DETAIL_FIELD_CODE' => [],
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
