<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arResult["NEWS_CATEGORIES"] = array();

if (($arParams["SHOW_CATEGORY_FILTER"] ?? "N") !== "Y") {
    return;
}

$categoryResult = CIBlockPropertyEnum::GetList(
    array("SORT" => "ASC", "VALUE" => "ASC"),
    array(
        "IBLOCK_ID" => (int)$arParams["IBLOCK_ID"],
        "CODE" => "category",
    )
);

while ($category = $categoryResult->Fetch()) {
    $arResult["NEWS_CATEGORIES"][] = array(
        "ID" => (int)$category["ID"],
        "VALUE" => $category["VALUE"],
        "XML_ID" => $category["XML_ID"],
    );
}
