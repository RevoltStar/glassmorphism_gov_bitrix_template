<?php
// Получаем разделы с сортировкой
$arSections = CIBlockSection::GetList(
    ["SORT" => "ASC", "NAME" => "ASC"], // Сортировка по SORT и NAME
    ["IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y"],
    false,
    ["NAME", "ID", "SORT"]
);

$arResult["SECTIONS"] = [];
$arResult["SECTIONS_SORTED"] = []; // Массив для сохранения порядка разделов

while($section = $arSections->Fetch()){
    $arResult["SECTIONS"][$section["ID"]] = $section["NAME"];
    $arResult["SECTIONS_SORTED"][$section["ID"]] = $section["SORT"];
}

// Сортируем разделы по полю SORT
asort($arResult["SECTIONS_SORTED"]);
?>