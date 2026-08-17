<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['POPULAR_SERVICES'] ?? null) ? $arResult['POPULAR_SERVICES'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$faceClasses = ['front', 'back', 'right', 'left', 'top', 'bottom'];
$faceIndexes = [0, 2, 1, 4, 3, 5];
?>
<?php if ($items !== []): ?>
    <div class="popular-services">
        <?php foreach ($items as $item): ?>
            <?php
            if (!is_array($item)) {
                continue;
            }

            $name = site_string($item['name'] ?? '');
            $url = site_url($item['url'] ?? null, '');
            $faces = is_array($item['faces'] ?? null) ? $item['faces'] : [];
            ?>
            <article class="popular-service" data-popular-service>
                <h2 class="popular-service__title"><?=htmlspecialcharsbx($name)?></h2>
                <div class="popular-service__layout">
                    <div class="popular-service__visual" aria-hidden="true">
                        <div class="popular-service-cube">
                            <div class="popular-service-cube__box" data-popular-service-cube>
                                <?php foreach ($faceClasses as $position => $faceClass): ?>
                                    <?php
                                    $face = is_array($faces[$faceIndexes[$position]] ?? null) ? $faces[$faceIndexes[$position]] : [];
                                    $imageUrl = site_url($face['url'] ?? null, '');
                                    $label = site_string($face['name'] ?? '');
                                    ?>
                                    <div class="popular-service-cube__face popular-service-cube__face--<?=$faceClass?>">
                                        <?php if ($imageUrl !== ''): ?><img class="popular-service-cube__image" src="<?=htmlspecialcharsbx($imageUrl)?>" alt="" loading="lazy" decoding="async"><?php endif; ?>
                                        <span class="popular-service-cube__label"><?=htmlspecialcharsbx($label)?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="popular-service__content">
                        <?php if (($item['preview_html'] ?? '') !== ''): ?><div class="popular-service__preview"><?=$item['preview_html']?></div><?php endif; ?>
                        <?php if ($url !== ''): ?>
                            <a class="popular-service__cta" href="<?=htmlspecialcharsbx($url)?>" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $name, GetMessage('CSR43_LIGHT_POPULAR_SERVICES_CTA_ARIA')))?>"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_POPULAR_SERVICES_CTA'))?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
