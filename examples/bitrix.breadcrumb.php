<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'bitrix:breadcrumb',
    'breadcrumb',
    [
        // 1. Фиксированные параметры.
        'START_FROM' => '0',
        'PATH' => '', // Пустое значение означает текущий путь.

        // 2. Параметры, которые определяет администратор.
        'SITE_ID' => 's1',

        // 3. Параметров без эффекта в этой конфигурации нет.
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
