<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php
$items = is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [];
$fallbackImage = site_template_image_url('image_not_found.svg');
if ($items !== []):
?>
<?php $galleryId = 'information-' . $this->randString();?>
<div class="banner-grid">
    <?php foreach($items as $key => $banner):
        if (!is_array($banner)) {
            continue;
        }
        $imageSrc = site_url($banner['PREVIEW_PICTURE']['SRC'] ?? null, $fallbackImage);
        $detailSrc = site_url($banner['DETAIL_PICTURE']['SRC'] ?? null, $imageSrc);
        $link = site_url($banner['PROPERTIES']['LINK']['VALUE'] ?? null);
        $name = site_string($banner['~NAME'] ?? $banner['NAME'] ?? '');
        $desc = site_plain_text($banner['~PREVIEW_TEXT'] ?? $banner['PREVIEW_TEXT'] ?? '');
        $caption = $name . ($desc ? " - " . $desc : "");
    ?>
    <div class="gallery-media banner-card">
        <?php // Кнопка увеличения (FancyBox её обрабатывает) ?>
        <a href="<?=htmlspecialcharsbx($detailSrc)?>" class="gallery-expand-button me-2 mt-2"
           data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
           data-gallery-caption="<?=htmlspecialcharsbx($caption)?>" data-type="image"
           aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $caption)?>">
            <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
        </a>

        <?php // Основная ссылка карточки ?>
        <a href="<?=htmlspecialcharsbx($link)?>" class="banner-card__link" aria-label="<?=htmlspecialcharsbx($name)?>">
            <div class="banner-card__image">
                <img src="<?=htmlspecialcharsbx($imageSrc)?>"
                     alt="<?=htmlspecialcharsbx($name)?>"
                     loading="lazy"
                     class="banner-card__img">
                <div class="banner-card__overlay">
                    <h3 class="banner-card__title"><?=htmlspecialcharsbx($name)?></h3>
                    <?php if($desc):?>
                        <p class="banner-card__desc"><?=htmlspecialcharsbx($desc)?></p>
                    <?php endif;?><!--
                    <span class="banner-card__hint">
                        Подробнее <i class="bi bi-arrow-right-short"></i>
                    </span>-->
                </div>
            </div>
        </a>
    </div>
    <?php endforeach;?>
</div>
<?php endif;?>
