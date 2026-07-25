<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arTemplateParameters = is_array($arTemplateParameters ?? null)
    ? $arTemplateParameters
    : [];

$arTemplateParameters['SHOW_ORDER_BY'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('CSR43_SEARCH_PARAM_SHOW_ORDER_BY'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
];
$arTemplateParameters['SHOW_ITEM_DATE_CHANGE'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('CSR43_SEARCH_PARAM_SHOW_ITEM_DATE_CHANGE'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y',
];
$arTemplateParameters['SHOW_ITEM_TAGS'] = [
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('CSR43_SEARCH_PARAM_SHOW_ITEM_TAGS'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N',
];
