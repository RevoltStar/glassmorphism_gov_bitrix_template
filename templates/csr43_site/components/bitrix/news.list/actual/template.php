<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = $arResult['ITEMS'] ?? [];
if (!is_array($items) || $items === []) {
    return;
}
$fallbackImage = site_template_image_url('image_not_found.svg');

?>
<div class="row g-4">
    <?php foreach ($items as $actual): ?>
        <?php
        if (!is_array($actual)) {
            continue;
        }

        $link = site_url($actual['PROPERTIES']['LINK']['VALUE'] ?? null);

        $pictureValue = $actual['PREVIEW_PICTURE'] ?? null;
        $pictureSrc = null;
        if (is_array($pictureValue)) {
            $pictureSrc = $pictureValue['SRC'] ?? null;
        } elseif (
            is_int($pictureValue)
            || (is_string($pictureValue) && ctype_digit($pictureValue))
        ) {
            $pictureId = (int)$pictureValue;
            if ($pictureId > 0) {
                $pictureSrc = CFile::GetPath($pictureId);
            }
        }

        $imageSrc = site_url($pictureSrc, $fallbackImage);

        $nameValue = $actual['~NAME'] ?? $actual['NAME'] ?? '';
        $name = is_scalar($nameValue) ? (string)$nameValue : '';

        $previewValue = $actual['~PREVIEW_TEXT']
            ?? $actual['PREVIEW_TEXT']
            ?? '';
        $previewText = is_scalar($previewValue)
            ? trim(strip_tags((string)$previewValue))
            : '';
        ?>
        <div class="col-md-4">
            <a href="<?=htmlspecialcharsbx($link)?>"
               class="text-decoration-none">
                <div class="banner-item">
                    <img class="banner-item__image"
                         src="<?=htmlspecialcharsbx($imageSrc)?>"
                         alt=""
                         loading="lazy"
                         aria-hidden="true">
                    <div class="banner-overlay">
                        <h5 class="fw-bold"><?=htmlspecialcharsbx($name)?></h5>
                        <?php if ($previewText !== ''): ?>
                            <p class="mb-0 small fw-semibold"><?=htmlspecialcharsbx($previewText)?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
