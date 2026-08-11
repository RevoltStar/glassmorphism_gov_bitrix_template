<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

$arComponentDescription = [
    'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_NAME'),
    'DESCRIPTION' => Loc::getMessage('CSR43_NEWS_CATEGORIES_DESCRIPTION'),
    'SORT' => 20,
    'COMPLEX' => 'N',
    'PATH' => [
        'ID' => 'csr43',
        'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_PATH_NAME'),
        'SORT' => 20,
    ],
];