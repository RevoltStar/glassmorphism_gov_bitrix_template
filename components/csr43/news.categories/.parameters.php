<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loc::loadMessages(__FILE__);

$iblockOptions = [
    '0' => Loc::getMessage('CSR43_NEWS_CATEGORIES_IBLOCK_NOT_SELECTED'),
];

if (Loader::includeModule('iblock')) {
    $iblockResult = CIBlock::GetList(
        [
            'SORT' => 'ASC',
            'NAME' => 'ASC',
        ],
        [
            'ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
        ]
    );

    while ($iblock = $iblockResult->Fetch()) {
        if (!is_array($iblock)) {
            continue;
        }

        $iblockId = max(0, (int)($iblock['ID'] ?? 0));
        $iblockName = trim(strip_tags((string)($iblock['NAME'] ?? '')));

        if ($iblockId <= 0 || $iblockName === '') {
            continue;
        }

        $iblockOptions[(string)$iblockId] = sprintf(
            '[%d] %s',
            $iblockId,
            $iblockName
        );
    }
}

$arComponentParameters = [
    'GROUPS' => [
        'ROUTING' => [
            'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_GROUP_ROUTING'),
            'SORT' => 200,
        ],
    ],
    'PARAMETERS' => [
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_IBLOCK_ID'),
            'TYPE' => 'LIST',
            'VALUES' => $iblockOptions,
            'DEFAULT' => '0',
            'ADDITIONAL_VALUES' => 'Y',
        ],

        'BASE_URL' => [
            'PARENT' => 'ROUTING',
            'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_BASE_URL'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/news/',
        ],

        'CATEGORY_URL_TEMPLATE' => [
            'PARENT' => 'ROUTING',
            'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_CATEGORY_URL_TEMPLATE'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/news/category/#CODE#/',
        ],

        'CURRENT_CATEGORY_CODE' => [
            'PARENT' => 'ROUTING',
            'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_CURRENT_CATEGORY_CODE'),
            'TYPE' => 'STRING',
            'DEFAULT' => '',
        ],

        'CACHE_TIME' => [
            'DEFAULT' => 36000000,
        ],

        'CACHE_GROUPS' => [
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => Loc::getMessage('CSR43_NEWS_CATEGORIES_CACHE_GROUPS'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
    ],
];