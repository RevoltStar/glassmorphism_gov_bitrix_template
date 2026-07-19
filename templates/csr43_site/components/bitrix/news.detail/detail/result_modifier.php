<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$arResult['NAV_NEWS'] = [
    'PREV' => false,
    'NEXT' => false,
];

$currentNewsId = max(0, (int)($arResult['ID'] ?? 0));
$iblockId = max(0, (int)($arResult['IBLOCK_ID'] ?? $arParams['IBLOCK_ID'] ?? 0));

if ($currentNewsId === 0 || $iblockId === 0) {
    return;
}

$neighborResult = CIBlockElement::GetList(
    [
        'ACTIVE_FROM' => 'DESC',
        'SORT' => 'ASC',
        'ID' => 'DESC',
    ],
    [
        'IBLOCK_ID' => $iblockId,
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
    ],
    false,
    [
        'nPageSize' => 1,
        'nElementID' => $currentNewsId,
    ],
    [
        'ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'ACTIVE_FROM',
    ]
);

if (!is_object($neighborResult)) {
    return;
}

$detailUrl = site_string($arParams['DETAIL_URL'] ?? '');
if ($detailUrl !== '' && method_exists($neighborResult, 'SetUrlTemplates')) {
    $neighborResult->SetUrlTemplates($detailUrl);
}

$neighbors = [];
while ($neighbor = $neighborResult->GetNext()) {
    if (is_array($neighbor)) {
        $neighbors[] = $neighbor;
    }
}

$currentIndex = null;
foreach ($neighbors as $index => $neighbor) {
    if ((int)($neighbor['ID'] ?? 0) === $currentNewsId) {
        $currentIndex = $index;
        break;
    }
}

if ($currentIndex === null) {
    return;
}

$arResult['NAV_NEWS']['PREV'] = $neighbors[$currentIndex - 1] ?? false;
$arResult['NAV_NEWS']['NEXT'] = $neighbors[$currentIndex + 1] ?? false;
