<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$sectionOptions = ['0' => '— Выберите раздел —'];

if (Loader::includeModule('iblock')) {
    $sectionResult = CIBlockSection::GetList(
        ['LEFT_MARGIN' => 'ASC'],
        ['IBLOCK_ID' => 18],
        false,
        ['ID', 'NAME', 'DEPTH_LEVEL']
    );

    while ($section = $sectionResult->Fetch()) {
        $depth = max(0, (int)$section['DEPTH_LEVEL'] - 1);
        $prefix = $depth > 0 ? str_repeat('·  ', $depth) : '';
        $sectionOptions[(string)$section['ID']] = $prefix . trim(strip_tags((string)$section['NAME']));
    }
}

$arComponentParameters = [
    'GROUPS' => [
        'DISPLAY' => [
            'NAME' => 'Отображение',
            'SORT' => 200,
        ],
    ],
    'PARAMETERS' => [
        'SECTION_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Раздел инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $sectionOptions,
            'DEFAULT' => '0',
            'ADDITIONAL_VALUES' => 'N',
        ],
        'INCLUDE_SUBSECTIONS' => [
            'PARENT' => 'BASE',
            'NAME' => 'Включать материалы из подразделов',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'NEWS_COUNT' => [
            'PARENT' => 'BASE',
            'NAME' => 'Максимальное количество элементов',
            'TYPE' => 'STRING',
            'DEFAULT' => '100',
        ],
        'SHOW_SECTION_NAME' => [
            'PARENT' => 'DISPLAY',
            'NAME' => 'Показывать название и описание раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'COLLAPSE_SECTION' => [
            'PARENT' => 'DISPLAY',
            'NAME' => 'Сворачивать содержимое раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'SHOW_DATE' => [
            'PARENT' => 'DISPLAY',
            'NAME' => 'Показывать дату обновления',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'SHOW_IMAGE_IMMEDIATELY' => [
            'PARENT' => 'DISPLAY',
            'NAME' => 'Показывать изображения сразу',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'CACHE_TIME' => [
            'DEFAULT' => 36000000,
        ],
        'CACHE_GROUPS' => [
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Учитывать права доступа',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
    ],
];

