<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'bitrix:menu',
    'top',
    [
        // 1. Фиксированные параметры.
        'USE_EXT' => 'N',
        'DELAY' => 'N',
        'ALLOW_MULTI_SELECT' => 'N',
        'MENU_CACHE_TYPE' => 'A',
        'MENU_CACHE_TIME' => '3600',
        'MENU_CACHE_USE_GROUPS' => 'Y',
        'CACHE_SELECTED_ITEMS' => 'N',
        'MENU_CACHE_USE_USERS' => 'N',

        // 2. Параметры, которые определяет администратор.
        'ROOT_MENU_TYPE' => 'top',
        'MAX_LEVEL' => '2',
        'CHILD_MENU_TYPE' => 'left',
        'MENU_CACHE_GET_VARS' => [],

        // 3. Параметров без эффекта в этой конфигурации нет.
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
