<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['MAIN_SLIDER'] ?? null) ? $arResult['MAIN_SLIDER'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$carouselId = 'main-slider-' . $this->randString();
?>
<?php if ($items !== []): ?>
    <div
        id="<?=htmlspecialcharsbx($carouselId)?>"
        class="carousel slide main-slider"
        data-main-slider
        data-bs-interval="5000"
        aria-live="polite"
        aria-atomic="true"
        tabindex="0"
        title="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_MAIN_SLIDER_PAUSE_HINT'))?>"
    >
        <?php if (count($items) > 1): ?>
            <div class="carousel-indicators main-slider__indicators">
                <?php foreach ($items as $index => $item): ?>
                    <button
                        type="button"
                        data-bs-target="#<?=htmlspecialcharsbx($carouselId)?>"
                        data-bs-slide-to="<?=$index?>"
                        class="main-slider__indicator<?=$index === 0 ? ' active' : ''?>"
                        <?=$index === 0 ? 'aria-current="true"' : ''?>
                        aria-label="<?=htmlspecialcharsbx(str_replace('#NUMBER#', (string)($index + 1), GetMessage('CSR43_LIGHT_MAIN_SLIDER_SLIDE_ARIA')))?>"
                    ></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="carousel-inner main-slider__inner">
            <?php foreach ($items as $index => $item): ?>
                <?php
                if (!is_array($item)) {
                    continue;
                }
                $name = site_string($item['name'] ?? '');
                $url = site_url($item['url'] ?? null, '');
                $image = is_array($item['image'] ?? null) ? $item['image'] : null;
                $imageUrl = $image !== null ? site_url($image['url'] ?? null, '') : '';
                $objectFit = site_string($item['object_fit'] ?? 'cover');
                $objectFitClass = in_array($objectFit, ['contain', 'cover', 'fill', 'none', 'scale-down'], true)
                    ? ' main-slider__image--' . $objectFit
                    : ' main-slider__image--cover';
                ?>
                <div class="carousel-item main-slider__item<?=$index === 0 ? ' active' : ''?>">
                    <div class="main-slider__content">
                        <div class="main-slider__media">
                            <?php if ($imageUrl !== ''): ?>
                                <img class="main-slider__image<?=$objectFitClass?>" src="<?=htmlspecialcharsbx($imageUrl)?>" alt="<?=htmlspecialcharsbx(site_string($image['alt'] ?? $name))?>" loading="lazy" decoding="async">
                            <?php else: ?>
                                <span class="main-slider__placeholder" aria-hidden="true"><i class="bi bi-image"></i></span>
                            <?php endif; ?>
                        </div>
                        <div class="main-slider__overlay">
                            <div class="main-slider__text">
                                <h2 class="main-slider__title"><?=htmlspecialcharsbx($name)?></h2>
                                <?php if (($item['preview_is_html'] ?? false) === true && ($item['preview_html'] ?? '') !== ''): ?>
                                    <div class="main-slider__description"><?=$item['preview_html']?></div>
                                <?php elseif (($item['preview_text'] ?? '') !== ''): ?>
                                    <p class="main-slider__description"><?=htmlspecialcharsbx(site_string($item['preview_text']))?></p>
                                <?php endif; ?>
                                <?php if ($url !== ''): ?><a href="<?=htmlspecialcharsbx($url)?>" class="btn main-slider__cta" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $name, GetMessage('CSR43_LIGHT_MAIN_SLIDER_MORE_ARIA')))?>"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_MAIN_SLIDER_MORE'))?></a><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($items) > 1): ?>
            <button class="carousel-control-prev main-slider__control" type="button" data-bs-target="#<?=htmlspecialcharsbx($carouselId)?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_MAIN_SLIDER_PREVIOUS'))?></span>
            </button>
            <button class="carousel-control-next main-slider__control" type="button" data-bs-target="#<?=htmlspecialcharsbx($carouselId)?>" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_MAIN_SLIDER_NEXT'))?></span>
            </button>
        <?php endif; ?>
    </div>
<?php endif; ?>
