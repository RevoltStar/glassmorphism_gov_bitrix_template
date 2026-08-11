<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arParams = is_array($arParams ?? null) ? $arParams : [];
$arResult = is_array($arResult ?? null) ? $arResult : [];
$arResult['ROUTE_GROUPS'] = [];

$showElements = ($arParams['SHOW_ELEMENTS'] ?? 'N') === 'Y';
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
$sections = is_array($arResult['SECTIONS'] ?? null)
    ? array_values(array_filter($arResult['SECTIONS'], 'is_array'))
    : [];

if (!$showElements || $iblockId <= 0 || $sections === []) {
    return;
}

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    return;
}

/*
 * Маршруты зависят от прав пользователя. Штатный кеш родительского компонента
 * безопасен при CACHE_GROUPS=Y; при отключённом разбиении по группам не кешируем
 * дополненный результат, чтобы не отдать его пользователю с другими правами.
 */
if (($arParams['CACHE_GROUPS'] ?? 'Y') !== 'Y') {
    $component = $this->__component ?? null;
    if (is_object($component) && method_exists($component, 'AbortResultCache')) {
        $component->AbortResultCache();
    }
}

$sectionsById = [];
$routesBySectionId = [];

foreach ($sections as $section) {
    $sectionId = max(0, (int)($section['ID'] ?? 0));
    if ($sectionId <= 0 || isset($sectionsById[$sectionId])) {
        continue;
    }

    $sectionsById[$sectionId] = $section;
    $routesBySectionId[$sectionId] = [];
}

$sectionIds = array_keys($sectionsById);
if ($sectionIds === []) {
    return;
}

$elementRows = [];
$elementResult = CIBlockElement::GetList(
    ['SORT' => 'ASC', 'NAME' => 'ASC', 'ID' => 'ASC'],
    [
        'IBLOCK_ID' => $iblockId,
        'SECTION_ID' => $sectionIds,
        'INCLUDE_SUBSECTIONS' => 'N',
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
        'MIN_PERMISSION' => 'R',
    ],
    false,
    false,
    ['ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'SORT']
);

while ($element = $elementResult->Fetch()) {
    if (!is_array($element)) {
        continue;
    }

    $elementId = max(0, (int)($element['ID'] ?? 0));
    if ($elementId <= 0 || isset($elementRows[$elementId])) {
        continue;
    }

    $elementRows[$elementId] = $element;
}

$elementIds = array_keys($elementRows);
if ($elementIds === []) {
    return;
}

/*
 * GetPropertyValuesArray получает свойства всех маршрутов массово и заменяет
 * прежний GetProperty внутри цикла по элементам.
 */
$propertiesByElementId = array_fill_keys($elementIds, []);
CIBlockElement::GetPropertyValuesArray(
    $propertiesByElementId,
    $iblockId,
    ['ID' => $elementIds],
    [
        'CODE' => [
            'ts_type',
            'link_yandex',
            'start_way',
            'end_way',
        ],
    ],
    ['GET_RAW_DATA' => 'Y']
);

/*
 * Один элемент может быть привязан к нескольким разделам. Массовая выборка
 * связей сохраняет прежнюю группировку без запроса для каждого раздела.
 */
$sectionIdsByElementId = [];
$groupResult = CIBlockElement::GetElementGroups(
    $elementIds,
    true,
    ['ID', 'IBLOCK_ELEMENT_ID']
);

while ($group = $groupResult->Fetch()) {
    if (!is_array($group)) {
        continue;
    }

    $elementId = max(0, (int)($group['IBLOCK_ELEMENT_ID'] ?? 0));
    $sectionId = max(0, (int)($group['ID'] ?? 0));
    if (
        !isset($elementRows[$elementId])
        || !isset($sectionsById[$sectionId])
    ) {
        continue;
    }

    $sectionIdsByElementId[$elementId][$sectionId] = true;
}

$allowedVehicleTypes = [
    'bus_big',
    'bus_medium',
    'bus_little',
    'trolleybus',
];

$getProperty = static function (array $properties, string $code): array {
    $property = $properties[$code] ?? $properties[strtoupper($code)] ?? [];

    return is_array($property) ? $property : [];
};

$getLastString = static function (mixed $value): string {
    while (is_array($value)) {
        if ($value === []) {
            return '';
        }

        $value = end($value);
    }

    return trim(site_string($value));
};

foreach ($elementRows as $elementId => $element) {
    $properties = is_array($propertiesByElementId[$elementId] ?? null)
        ? $propertiesByElementId[$elementId]
        : [];
    $vehicleTypeProperty = $getProperty($properties, 'ts_type');
    $vehicleType = $getLastString($vehicleTypeProperty['VALUE_XML_ID'] ?? '');
    if (!in_array($vehicleType, $allowedVehicleTypes, true)) {
        $vehicleType = '';
    }

    $mapUrl = site_url(
        $getLastString(
            $getProperty($properties, 'link_yandex')['VALUE'] ?? ''
        ),
        '',
        ['https'],
        false
    );
    if (preg_match('~^https://(?:www\.)?yandex\.ru/~i', $mapUrl) !== 1) {
        $mapUrl = '';
    }

    $route = [
        'ID' => $elementId,
        'NAME' => site_string($element['NAME'] ?? ''),
        'START' => $getLastString(
            $getProperty($properties, 'start_way')['VALUE'] ?? ''
        ),
        'END' => $getLastString(
            $getProperty($properties, 'end_way')['VALUE'] ?? ''
        ),
        'MAP_URL' => $mapUrl,
        'VEHICLE_TYPE' => $vehicleType,
    ];

    foreach (array_keys($sectionIdsByElementId[$elementId] ?? []) as $sectionId) {
        $routesBySectionId[$sectionId][] = $route;
    }
}

foreach ($sectionsById as $sectionId => $section) {
    $routes = $routesBySectionId[$sectionId] ?? [];
    if ($routes === []) {
        continue;
    }

    $arResult['ROUTE_GROUPS'][] = [
        'SECTION' => $section,
        'ROUTES' => $routes,
    ];
}
