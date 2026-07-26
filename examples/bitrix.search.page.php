<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'bitrix:search.page',
    '.default',
    [
        // 1. Фиксированные параметры.
        'RESTART' => 'Y',
        'NO_WORD_LOGIC' => 'N',
        'CHECK_DATES' => 'Y',
        'USE_TITLE_RANK' => 'Y',
        'DEFAULT_SORT' => 'rank',
        'USE_LANGUAGE_GUESS' => 'N',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '3600',
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'PAGER_TITLE' => 'Результаты поиска',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_TEMPLATE' => 'csr43',
        'AJAX_MODE' => 'N',

        // 2. Параметры, которые определяет администратор.
        'FILTER_NAME' => '',
        'arrFILTER' => ['iblock_content'],
        'arrFILTER_iblock_content' => ['all'],
        'SHOW_WHERE' => 'N',
        'arrWHERE' => ['iblock_content'],
        'SHOW_WHEN' => 'N',
        'PAGE_RESULT_COUNT' => '15',

        // Параметры локального шаблона.
        'SHOW_ORDER_BY' => 'Y',
        'SHOW_ITEM_DATE_CHANGE' => 'Y',
        'SHOW_ITEM_TAGS' => 'N',

        // 3. Локальный шаблон не выводит штатную поисковую подсказку.
        'USE_SUGGEST' => 'N',

        // Локальный шаблон не выводит рейтинг.
        'SHOW_RATING' => 'N',
        'RATING_TYPE' => '',
        'PATH_TO_USER_PROFILE' => '',

        // Локальный шаблон не выводит облако тегов.
        'SHOW_TAGS_CLOUD' => 'N',
        'TAGS_INHERIT' => 'N',
        'TAGS_SORT' => 'NAME',
        'TAGS_PAGE_ELEMENTS' => '150',
        'TAGS_PERIOD' => '30',
        'TAGS_URL_SEARCH' => '/search/',
        'FONT_MAX' => '50',
        'FONT_MIN' => '10',
        'COLOR_NEW' => '000000',
        'COLOR_OLD' => 'C8C8C8',
        'PERIOD_NEW_TAGS' => '',
        'SHOW_CHAIN' => 'N',
        'COLOR_TYPE' => 'Y',
        'WIDTH' => '100%',

        // AJAX_MODE выключен.
        'AJAX_OPTION_SHADOW' => 'N',
        'AJAX_OPTION_JUMP' => 'N',
        'AJAX_OPTION_STYLE' => 'Y',
        'AJAX_OPTION_HISTORY' => 'N',
        'AJAX_OPTION_ADDITIONAL' => '',
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
