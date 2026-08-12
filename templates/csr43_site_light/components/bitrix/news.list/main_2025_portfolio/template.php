<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** /** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
?>

<div class="container">
    <div class="row px-2">
        <?php foreach ($arResult["ITEMS"] as $arItem): ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            
            // Обработка картинки
            $arImage = false;
            if ($arItem["PREVIEW_PICTURE"]) {
                if (is_array($arItem["PREVIEW_PICTURE"])) {
                    $arImage = $arItem["PREVIEW_PICTURE"];
                } else {
                    $arImage = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);
                }
            } elseif ($arItem["DETAIL_PICTURE"]) {
                if (is_array($arItem["DETAIL_PICTURE"])) {
                    $arImage = $arItem["DETAIL_PICTURE"];
                } else {
                    $arImage = CFile::GetFileArray($arItem["DETAIL_PICTURE"]);
                }
            }
            
            // Расчет времени анимации
            $transitionDuration = "2.5s";
            if ($arImage) {
                $height = $arImage["HEIGHT"];
                $width = $arImage["WIDTH"];
                if ($width > 0) {
                    $transitionDuration = number_format($height / $width * 2.5, 2) . "s";
                }
            }
            
            // Получаем ссылку из свойства
            $link = "";
            if (isset($arItem["PROPERTIES"]["LINK"]["VALUE"])) {
                $link = $arItem["PROPERTIES"]["LINK"]["VALUE"];
            } elseif (isset($arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"])) {
                $link = $arItem["DISPLAY_PROPERTIES"]["LINK"]["VALUE"];
            }
            ?>
            
            <div class="col-xl-4 branding photo port" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <div class="item-box">
                    <?php if ($link): ?>
                        <a href="<?= $link ?>">
                    <?php endif; ?>
                    
                    <?php if ($arImage): ?>
                        <img 
                            class="item-container img-fluid" 
                            src="<?= $arImage["SRC"] ?>" 
                            alt="<?= $arItem["NAME"] ?>"
                            width="<?= $arImage["WIDTH"] ?>"
                            height="<?= $arImage["HEIGHT"] ?>"
                            style="transition: all <?= $transitionDuration ?> ease-out 0s;"
							loading="lazy"
                        >
                    <?php else: ?>
                        <!-- Заглушка если нет изображения -->
                        <div class="no-image" style="width: 100%; height: 274px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                            Нет изображения
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($link): ?>
                        </a>
                    <?php endif; ?>
                </div>
                <h3><?= $arItem["NAME"] ?></h3>
                <?php if ($arItem["PREVIEW_TEXT"]): ?>
                    <div class="preview-text"><?= $arItem["PREVIEW_TEXT"] ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>