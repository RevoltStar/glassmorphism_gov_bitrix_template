<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arTemplate = [
    'NAME' => Loc::getMessage('CSR43_FOOTER_MENU_TEMPLATE_NAME'),
    'DESCRIPTION' => Loc::getMessage('CSR43_FOOTER_MENU_TEMPLATE_DESCRIPTION'),
];
