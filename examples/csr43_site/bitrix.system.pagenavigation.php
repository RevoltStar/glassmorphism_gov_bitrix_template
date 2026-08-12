<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/*
 * bitrix:system.pagenavigation не размещается самостоятельным
 * IncludeComponent(). Его вызывает родительский компонент после выполнения
 * выборки. Ниже — минимальный корректный пример подключения шаблона csr43.
 */
$APPLICATION->IncludeComponent(
    'bitrix:news.list',
    'news',
    [
        // 1. Фиксированные параметры выбора нашей пагинации.
        'DISPLAY_TOP_PAGER' => 'N',
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'PAGER_TEMPLATE' => 'csr43',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_SHOW_ALL' => 'N',

        // Обязательные фиксированные настройки безопасной выборки.
        'CHECK_DATES' => 'Y',
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '3600',
        'CACHE_GROUPS' => 'Y',

        // 2. Параметры, которые определяет администратор.
        'IBLOCK_TYPE' => 'content',
        'IBLOCK_ID' => '8',
        'NEWS_COUNT' => '12',
        'PAGER_TITLE' => 'Новости',

        // 3. Параметров без эффекта в этой сокращённой конфигурации нет.
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
