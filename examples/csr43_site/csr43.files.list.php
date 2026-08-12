<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'csr43:files.list',
    '.default',
    [
        // 1. Фиксированные параметры.
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '36000000',
        'CACHE_GROUPS' => 'Y',

        // 2. Параметры, которые определяет администратор.
        // Значение выбирается из списка и имеет обязательный префикс section_.
        'SECTION_ID' => 'section_12',
        'INCLUDE_SUBSECTIONS' => 'N',
        'DISPLAY_CHILD_SECTIONS' => 'N',
        'NEWS_COUNT' => '100',
        'SORT_BY1' => 'SORT',
        'SORT_ORDER1' => 'ASC',
        'SORT_BY2' => 'ID',
        'SORT_ORDER2' => 'ASC',
        'SHOW_SECTION_NAME' => 'Y',
        'COLLAPSE_SECTION' => 'N',
        'SHOW_DATE' => 'Y',
        'SHOW_IMAGE_IMMEDIATELY' => 'N',

        // 3. Параметров без эффекта в этой конфигурации нет.
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
