<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!empty($arResult["ITEMS"])):?>
<?$galleryId = 'information-' . $this->randString();?>
<div class="banner-grid">
    <?foreach($arResult["ITEMS"] as $key => $banner):
        $imageSrc = $banner['PREVIEW_PICTURE']['SRC'] ?? SITE_TEMPLATE_PATH."/images/no-photo.png";
        $detailSrc = $banner['DETAIL_PICTURE']['SRC'] ?? $imageSrc;
        $link = $banner['PROPERTIES']['LINK']['VALUE'] ?: "#";
        $name = $banner["NAME"];
        $desc = strip_tags($banner["PREVIEW_TEXT"] ?? "");
        $caption = $name . ($desc ? " - " . $desc : "");
    ?>
    <div class="gallery-media banner-card">
        <?// Кнопка увеличения (FancyBox её обрабатывает) ?>
        <a href="<?=htmlspecialcharsbx($detailSrc)?>" class="gallery-expand-button me-2 mt-2"
           data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
           data-gallery-caption="<?=htmlspecialcharsbx($caption)?>" data-type="image"
           aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $caption)?>">
            <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
        </a>

        <?// Основная ссылка карточки ?>
        <a href="<?=htmlspecialcharsbx($link)?>" class="banner-card__link" aria-label="<?=htmlspecialcharsbx($name)?>">
            <div class="banner-card__image">
                <img src="<?=htmlspecialcharsbx($imageSrc)?>"
                     alt="<?=htmlspecialcharsbx($name)?>"
                     loading="lazy"
                     class="banner-card__img">
                <div class="banner-card__overlay">
                    <h3 class="banner-card__title"><?=htmlspecialcharsbx($name)?></h3>
                    <?if($desc):?>
                        <p class="banner-card__desc"><?=htmlspecialcharsbx($desc)?></p>
                    <?endif;?><!--
                    <span class="banner-card__hint">
                        Подробнее <i class="bi bi-arrow-right-short"></i>
                    </span>-->
                </div>
            </div>
        </a>
    </div>
    <?endforeach;?>
</div>
<?endif;?>
