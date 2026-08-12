<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<?php foreach ($arResult["ITEMS"] as $direction): ?>
	<div class="col-md-4 col-sm-6">
		<a href="<?=$direction["PROPERTIES"]["LINK"]["VALUE"]?>" class="text-decoration-none" title="Ознакомиться с направление деятельности: <?=$direction['PREVIEW_PICTURE']['ALT']??$direction['NAME']?>">
    	<div class="direction-card">
        	<div class="direction-icon">
            	<img src="<?=$direction['PREVIEW_PICTURE']['SRC']?>" alt="<?=$direction['PREVIEW_PICTURE']['ALT']??$direction['NAME']?>" loading="lazy">
            </div>
            <h4 class="direction-title"><?=$direction['NAME']?></h4>
        </div>
		</a>
    </div>
<?php endforeach; ?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
