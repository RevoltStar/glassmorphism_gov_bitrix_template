<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Защита от отсутствия ID инфоблока
if (empty($arParams["IBLOCK_ID"])) {
    $arResult["SECTIONS"] = [];
    return;
}

// Кэширование запросов на 24 часа (если компонент не кэширует сам)
$cacheTtl = 86400;
$cacheId = "org_structure_" . $arParams["IBLOCK_ID"];
$cacheDir = "/org_structure";
$obCache = new CPHPCache();

if ($obCache->InitCache($cacheTtl, $cacheId, $cacheDir)) {
    $vars = $obCache->GetVars();
    $arResult["SECTIONS"] = $vars["sections"];
} else {
    $obCache->StartDataCache();

    // 1. Корневые разделы
    $sections = [];
    $res = CIBlockSection::GetList(
        ["LEFT_MARGIN" => "ASC"], // сортировка по дереву (корневые + их вложенность, если нужна)
        [
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "SECTION_ID" => false,
            "ACTIVE" => "Y"
        ],
        false,
        ["ID", "NAME", "CODE", "UF_*"] // добавьте нужные пользовательские поля
    );
    while ($section = $res->Fetch()) {
        $section["ELEMENTS"] = [];
        $sections[$section["ID"]] = $section;
    }

    if (!empty($sections)) {
        // 2. Элементы, привязанные к этим разделам
        $resEl = CIBlockElement::GetList(
            ["SORT" => "ASC", "NAME" => "ASC"], // сортировка внутри отдела
            [
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "SECTION_ID" => array_keys($sections),
                "ACTIVE" => "Y"
            ],
            false,
            false,
            [
                "ID", "NAME", "IBLOCK_SECTION_ID", "PREVIEW_TEXT", "PREVIEW_PICTURE",
                "DETAIL_PAGE_URL",
                "PROPERTY_PHONE",   // унифицируем коды: PHONE
                "PROPERTY_ADDRESS", // унифицируем коды: ADDRESS
                "PROPERTY_EMAIL"    // если нужно
            ]
        );

        while ($el = $resEl->GetNextElement()) {
            $fields = $el->GetFields();
            $props = $el->GetProperties();

            // Приведение множественных свойств к строке
            foreach (["PHONE", "ADDRESS", "EMAIL"] as $code) {
                if (!empty($props[$code]["VALUE"])) {
                    if (is_array($props[$code]["VALUE"])) {
                        $props[$code]["VALUE"] = implode(", ", $props[$code]["VALUE"]);
                    }
                } else {
                    $props[$code] = ["VALUE" => null];
                }
            }

            $fields["PROPERTIES"] = $props;
            $sectionId = $fields["IBLOCK_SECTION_ID"];
            if (isset($sections[$sectionId])) {
                $sections[$sectionId]["ELEMENTS"][] = $fields;
            }
        }
    }

    $arResult["SECTIONS"] = $sections;

    $obCache->EndDataCache(["sections" => $sections]);
}

?>