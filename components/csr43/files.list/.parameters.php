<?php

use Bitrix\Main\Loader;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$sectionOptions = ['' => '— Выберите раздел —'];

if (Loader::includeModule('iblock')) {
    $iblockType = trim((string)get_info('files_iblock_type', ''));
    $iblockId = max(0, (int)get_info('files_iblock_id', 0));
    $iblock = $iblockType !== '' && $iblockId > 0
        ? CIBlock::GetList([], ['ID' => $iblockId, 'TYPE' => $iblockType, 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y'])->Fetch()
        : false;

    if ($iblock) {
        $sectionResult = CIBlockSection::GetList(
            ['LEFT_MARGIN' => 'ASC'],
            ['IBLOCK_ID' => $iblockId, 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'Y'],
            false,
            ['ID', 'NAME', 'DEPTH_LEVEL']
        );

        while ($section = $sectionResult->Fetch()) {
            $depth = max(0, (int)$section['DEPTH_LEVEL'] - 1);
            $prefix = $depth > 0 ? str_repeat('·  ', $depth) : '';
            $sectionOptions['section_' . (int)$section['ID']] = $prefix . trim(strip_tags((string)$section['NAME']));
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
        'SECTION_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Раздел инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $sectionOptions,
            'DEFAULT' => '',
            'ADDITIONAL_VALUES' => 'N',
        ],
        'INCLUDE_SUBSECTIONS' => [
            'PARENT' => 'BASE',
            'NAME' => 'Включать материалы из подразделов',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'DISPLAY_CHILD_SECTIONS' => [
            'PARENT' => 'BASE',
            'NAME' => 'Выводить каждый непосредственный подраздел отдельным списком',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'NEWS_COUNT' => [
            'PARENT' => 'BASE',
            'NAME' => 'Максимальное количество элементов',
            'TYPE' => 'STRING',
            'DEFAULT' => '100',
        ],
        'SORT_BY1' => [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => 'Поле первой сортировки',
            'TYPE' => 'LIST',
            'VALUES' => ['SORT' => 'Сортировка', 'NAME' => 'Название', 'DATE_CREATE' => 'Дата создания', 'TIMESTAMP_X' => 'Дата изменения', 'ID' => 'ID'],
            'DEFAULT' => 'SORT',
        ],
        'SORT_ORDER1' => [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => 'Направление первой сортировки',
            'TYPE' => 'LIST',
            'VALUES' => ['ASC' => 'По возрастанию', 'DESC' => 'По убыванию'],
            'DEFAULT' => 'ASC',
        ],
        'SORT_BY2' => [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => 'Поле второй сортировки',
            'TYPE' => 'LIST',
            'VALUES' => ['ID' => 'ID', 'NAME' => 'Название', 'SORT' => 'Сортировка', 'DATE_CREATE' => 'Дата создания', 'TIMESTAMP_X' => 'Дата изменения'],
            'DEFAULT' => 'ID',
        ],
        'SORT_ORDER2' => [
            'PARENT' => 'DATA_SOURCE',
            'NAME' => 'Направление второй сортировки',
            'TYPE' => 'LIST',
            'VALUES' => ['ASC' => 'По возрастанию', 'DESC' => 'По убыванию'],
            'DEFAULT' => 'ASC',
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
    ],
];
