<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

// Группируем элементы по разделам
$groupedItems = [];
foreach($arResult["ITEMS"] as $arItem) {
    $sectionId = $arItem["IBLOCK_SECTION_ID"];
    $groupedItems[$sectionId][] = $arItem;
}
?>
<div id="full-menu" class="header__modal-menu d-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="modal-menu">
                    <div class="text-end">
                        <button id="modal-menu-btn" class="btn btn-danger">X</button>
                    </div>
                    <div class="modal-meu__items block-padding">
                        <?php
                        // Выводим разделы в порядке сортировки
                        foreach($arResult["SECTIONS_SORTED"] as $sectionId => $sort):
                            if(!isset($groupedItems[$sectionId])) continue;
                            
                            $sectionName = $arResult["SECTIONS"][$sectionId];
                            $items = $groupedItems[$sectionId];
                        ?>
                            <div class="modal-menu__item content__divide-b">
                                <h4><?= $sectionName ?></h4>
                                <ul>
                                    <?php foreach($items as $arItem): ?>
                                        <?
                                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                                        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                                        ?>
                                        <li id="<?=$this->GetEditAreaId($arItem['ID']);?>" style="display: flex; align-items: center; gap: 8px;">
                                            <i class="fs-2 bi <?=$arItem["PROPERTIES"]["ICON"]["VALUE"]?>" style="flex-shrink: 0; margin-top: 2px;" aria-hidden="true"></i>
                                            <a target="_blank" href="<?=$arItem["PROPERTIES"]["LINK"]["VALUE"]?>">
                                                <?= $arItem["NAME"] ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>