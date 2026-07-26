<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'bitrix:catalog.section.list',
    'structure',
    [
        // 1. Фиксированные параметры.
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '36000000',
        'CACHE_GROUPS' => 'Y',
        'ADD_SECTIONS_CHAIN' => 'N',
        'COUNT_ELEMENTS' => 'N',
        'HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS' => 'N',

        // 2. Параметры, которые определяет администратор.
        'IBLOCK_TYPE' => 'content',
        'IBLOCK_ID' => '8',
        'SECTION_ID' => '0',
        'SECTION_CODE' => '',
        'TOP_DEPTH' => '2',
        'SECTION_FIELDS' => ['ID', 'NAME', 'PICTURE'],
        'SECTION_USER_FIELDS' => [],
        'FILTER_NAME' => '',
        'SECTION_URL' => '',
        'CUSTOM_SECTION_SORT' => [
            'SORT' => 'ASC',
            'NAME' => 'ASC',
        ],

        // 3. Не оказывают эффекта при COUNT_ELEMENTS = N.
        'COUNT_ELEMENTS_FILTER' => 'CNT_ACTIVE',
        'ADDITIONAL_COUNT_ELEMENTS_FILTER' => '',

        // Не используются локальным шаблоном structure.
        'VIEW_MODE' => 'LINE',
        'SHOW_PARENT_NAME' => 'Y',
        'HIDE_SECTION_NAME' => 'N',
        'SECTIONS_OFFSET_MODE' => 'N',
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
