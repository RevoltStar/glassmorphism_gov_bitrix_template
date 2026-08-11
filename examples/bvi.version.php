<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$APPLICATION->IncludeComponent(
    'csr43:bvi.version',
    '.default',
    [
        // 1. У компонента нет параметров: пустой массив фиксирован.

        // 2. Параметров, определяемых администратором, нет.

        // 3. Любой переданный параметр не окажет эффекта:
        // component.php только подключает шаблон.
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);
