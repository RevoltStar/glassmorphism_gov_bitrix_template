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

<?if(!empty($arResult["ITEMS"])):?>
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <? $link = $arItem['PROPERTIES']['LINK']['VALUE'];?>
        <div class="row mb-4">
            <div class="usefull-resource-banner <?=$arParams['TYPE']??''?>">
                <a href="<?=$link?>" target="_blank" class="usefull-resource-banner-link <?=$arParams['TYPE']??''?>">
                <img
                    class="usefull-resource-banner-img <?=$arParams['TYPE']??''?>"
                    border="0"
                    src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                    alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                    title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                    loading="lazy"
                    />
                </a>
            </div>
        </div>
    <?endforeach;?>
<?else:?>
<div class="row justify-content-center align-items-center">
        <div class="col-12"  style="max-width: 250px;">
            <div class="alert alert-light border text-center py-4" role="alert">
                <div class="mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-info-circle text-muted" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                </div>
                <h5 class="alert-heading mb-3">Информационные материалы отсутствуют</h5>
                <p class="mb-3">В настоящее время нет активных информационных баннеров.</p>
                <p class="mb-0 text-muted small">Актуальная информация будет размещена в ближайшее время</p>
            </div>
        </div>
    </div>
<?endif;?>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
