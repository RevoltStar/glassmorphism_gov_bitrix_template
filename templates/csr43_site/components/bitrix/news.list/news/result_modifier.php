<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];

$arResult["NEWS_CATEGORIES"] = array();

if (($arParams["SHOW_CATEGORY_FILTER"] ?? "N") !== "Y") {
    return;
}

$categoryResult = CIBlockPropertyEnum::GetList(
    array("SORT" => "ASC", "VALUE" => "ASC"),
    array(
        "IBLOCK_ID" => max(0, (int)($arParams["IBLOCK_ID"] ?? 0)),
        "CODE" => "category",
    )
);

while (is_object($categoryResult) && ($category = $categoryResult->Fetch())) {
    if (!is_array($category)) {
        continue;
    }

    $arResult["NEWS_CATEGORIES"][] = array(
        "ID" => max(0, (int)($category["ID"] ?? 0)),
        "VALUE" => site_string($category["VALUE"] ?? ''),
        "XML_ID" => site_string($category["XML_ID"] ?? ''),
    );
}
