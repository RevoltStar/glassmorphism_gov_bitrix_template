<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$iblockTypeOptions = ['' => '— Выберите тип инфоблока —'];
$iblockOptions = ['0' => '— Выберите инфоблок —'];
$sectionOptions = ['0' => '— Выберите раздел —'];

if (Loader::includeModule('iblock')) {
    $iblockTypeOptions += CIBlockParameters::GetIBlockTypes();

    $selectedIblockType = trim((string)($arCurrentValues['IBLOCK_TYPE'] ?? ''));
    $selectedIblockId = max(0, (int)($arCurrentValues['IBLOCK_ID'] ?? 0));

    if ($selectedIblockType !== '') {
        $iblockResult = CIBlock::GetList(
            ['SORT' => 'ASC', 'NAME' => 'ASC'],
            ['TYPE' => $selectedIblockType, 'ACTIVE' => 'Y']
        );

        while ($iblock = $iblockResult->Fetch()) {
            $iblockOptions[(string)$iblock['ID']] = sprintf('[%d] %s', $iblock['ID'], trim(strip_tags((string)$iblock['NAME'])));
        }
    }

    if ($selectedIblockId > 0) {
        $sectionResult = CIBlockSection::GetList(
            ['LEFT_MARGIN' => 'ASC'],
            ['IBLOCK_ID' => $selectedIblockId, 'ACTIVE' => 'Y'],
            false,
            ['ID', 'NAME', 'DEPTH_LEVEL']
        );

        while ($section = $sectionResult->Fetch()) {
            $depth = max(0, (int)$section['DEPTH_LEVEL'] - 1);
            $prefix = $depth > 0 ? str_repeat('·  ', $depth) : '';
            $sectionOptions[(string)$section['ID']] = $prefix . trim(strip_tags((string)$section['NAME']));
        }
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
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $iblockTypeOptions,
            'DEFAULT' => '',
            'REFRESH' => 'Y',
            'ADDITIONAL_VALUES' => 'N',
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $iblockOptions,
            'DEFAULT' => '0',
            'REFRESH' => 'Y',
            'ADDITIONAL_VALUES' => 'N',
        ],
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
            'NAME' => 'Включать ссылки из подразделов',
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
        'SHOW_UPDATE_DATE' => [
            'PARENT' => 'DISPLAY',
            'NAME' => 'Показывать дату обновления',
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
