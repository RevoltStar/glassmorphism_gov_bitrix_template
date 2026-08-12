<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['LICENSES'] ?? null) ? $arResult['LICENSES'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$galleryInstanceId = 'licenses-' . $this->randString();
?>
<?php if ($items !== []): ?>
    <div class="licenses-list">
        <?php foreach ($items as $item): ?>
            <?php
            if (!is_array($item)) {
                continue;
            }
            $id = max(0, (int)($item['id'] ?? 0));
            $name = site_string($item['name'] ?? '');
            $images = is_array($item['images'] ?? null) ? $item['images'] : [];
            $galleryId = $galleryInstanceId . '-' . $id;
            ?>
            <article class="csr43-light-card licenses-card">
                <h2 class="licenses-card__title"><?=htmlspecialcharsbx($name)?></h2>
                <?php if ($images !== []): ?>
                    <div class="licenses-card__grid">
                        <?php foreach ($images as $image): ?>
                            <?php
                            if (!is_array($image)) {
                                continue;
                            }
                            $url = site_url($image['url'] ?? null, '');
                            $caption = site_string($image['caption'] ?? $name);
                            if ($url === '') {
                                continue;
                            }
                            ?>
                            <a
                                class="licenses-card__image-link"
                                href="<?=htmlspecialcharsbx($url)?>"
                                data-gallery-item
                                data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                                data-gallery-caption="<?=htmlspecialcharsbx($caption)?>"
                                data-type="image"
                                aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $caption, GetMessage('CSR43_LIGHT_LICENSES_OPEN_IMAGE')))?>"
                            >
                                <img class="licenses-card__image" src="<?=htmlspecialcharsbx($url)?>" alt="<?=htmlspecialcharsbx($caption)?>" loading="lazy" decoding="async">
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
