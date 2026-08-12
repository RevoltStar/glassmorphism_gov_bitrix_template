<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'csr43:cookie.manager',
    '.default',
    [
        // 1. Фиксированные параметры.
        'CACHE_TYPE' => 'A',
        'CACHE_TIME' => '86400',
        'CHECK_TIMEOUT' => '3000',
        'EXPIRE_DAYS' => '365',
        'SHOW_SETTINGS' => 'Y',

        // 2. Параметры, которые определяет администратор.
        'MESSAGE' => 'Сайт использует файлы cookie для работы и аналитики.',
        'POLICY_URL' => '/doc/personal_data_processing_policies/',
        'POLICY_TEXT' => 'Политика обработки персональных данных',
        'PRESETS' => 'style4',

        // 3. Параметров без эффекта в этой конфигурации нет.
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
