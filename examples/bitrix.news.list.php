<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

// Администратор выбирает локальный шаблон под назначение списка.
$componentTemplate = 'news';

$APPLICATION->IncludeComponent(
    'bitrix:news.list',
    $componentTemplate,
    [
        // 1. Фиксированные параметры.
        'CHECK_DATES' => 'Y',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '3600',
        'CACHE_FILTER' => 'Y',
        'CACHE_GROUPS' => 'Y',
        'SET_TITLE' => 'N',
        'SET_BROWSER_TITLE' => 'N',
        'SET_META_KEYWORDS' => 'N',
        'SET_META_DESCRIPTION' => 'N',
        'SET_LAST_MODIFIED' => 'N',
        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
        'ADD_SECTIONS_CHAIN' => 'N',
        'HIDE_LINK_WHEN_NO_DETAIL' => 'Y',
        'PAGER_TEMPLATE' => 'csr43',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'SET_STATUS_404' => 'N',
        'SHOW_404' => 'N',
        'AJAX_MODE' => 'N',

        // 2. Параметры, которые определяет администратор.
        'IBLOCK_TYPE' => 'content',
        'IBLOCK_ID' => '8',
        'NEWS_COUNT' => '12',
        'SORT_BY1' => 'ACTIVE_FROM',
        'SORT_ORDER1' => 'DESC',
        'SORT_BY2' => 'SORT',
        'SORT_ORDER2' => 'ASC',
        'FILTER_NAME' => 'newsFilter',
        'FIELD_CODE' => [
            'ID',
            'NAME',
            'PREVIEW_TEXT',
            'PREVIEW_PICTURE',
            'DETAIL_PICTURE',
            'ACTIVE_FROM',
        ],
        'PROPERTY_CODE' => ['category'],
        'DETAIL_URL' => '/news/#ELEMENT_CODE#/',
        'ACTIVE_DATE_FORMAT' => 'd.m.Y',
        'PARENT_SECTION' => '',
        'PARENT_SECTION_CODE' => '',
        'INCLUDE_SUBSECTIONS' => 'Y',
        'DISPLAY_DATE' => 'Y',
        'DISPLAY_NAME' => 'Y',
        'DISPLAY_PICTURE' => 'Y',
        'DISPLAY_PREVIEW_TEXT' => 'Y',
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'PAGER_TITLE' => 'Новости',

        // Параметры локального шаблона news.
        'SHOW_CATEGORY_FILTER' => 'Y',
        'CATEGORY_CODE' => '',

        // 3. Пустое значение не ограничивает анонс на уровне компонента;
        // локальный шаблон сам обрезает текст.
        'PREVIEW_TRUNCATE_LEN' => '',

        // Обратная навигация выключена.
        'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',

        // Обработка базовой ссылки выключена.
        'PAGER_BASE_LINK' => '',
        'PAGER_PARAMS_NAME' => 'arrPager',

        // Обработка 404 выключена.
        'MESSAGE_404' => '',
        'FILE_404' => '/404.php',

        // AJAX_MODE выключен.
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'Y',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',

        // Пустой служебный массив не переопределяет SORT_BY1/SORT_BY2.
        'CUSTOM_ELEMENT_SORT' => [],

        // Параметры других локальных шаблонов news.list не читаются
        // выбранным шаблоном news.
        'FALLBACK_DETAIL_PAGE_URL' => '/activity/',
        'PROJECT_LIST' => '/activity/implemintation-of-regional-projects/',
        'SHOW_IMAGE_IMMEDIATELY' => 'N',
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
