<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = $arResult['ITEMS'] ?? [];
if (!is_array($items) || $items === []) {
    return;
}

$fallbackDetailPageUrl = site_url(
    $arParams['FALLBACK_DETAIL_PAGE_URL'] ?? null,
    '/activity/'
);

$items[] = [
    'NAME' => 'Посмотреть все направления деятельности',
    'PREVIEW_TEXT' => '',
    'DETAIL_PAGE_URL' => $fallbackDetailPageUrl,
    'PROPERTIES' => ['ICON' => ['VALUE' => 'fa fa-arrow-right']],
];
?>
<div class="row g-4">
    <?php foreach ($items as $direction): ?>
        <?php
        if (!is_array($direction)) {
            continue;
        }

        $icon = site_css_classes(
            $direction['PROPERTIES']['ICON']['VALUE'] ?? null
        );
        $name = site_string($direction['~NAME'] ?? $direction['NAME'] ?? '');
        $preview = site_plain_text(
            $direction['~PREVIEW_TEXT'] ?? $direction['PREVIEW_TEXT'] ?? ''
        );
        $link = site_url(
            $direction['PROPERTIES']['LINK']['VALUE']
                ?? $direction['DETAIL_PAGE_URL']
                ?? '#'
        );
        ?>
        <div class="col-md-3 col-sm-6">
            <div class="directions-card text-center">
                <?php if ($icon !== ''): ?>
                    <div class="directions-card__icon mx-auto">
                        <i class="<?=htmlspecialcharsbx($icon)?>" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>
                <h5 class="directions-card__title fw-bold"><?=htmlspecialcharsbx($name)?></h5>
                <?php if ($preview !== ''): ?>
                    <p class="directions-card__description small"><?=htmlspecialcharsbx($preview)?></p>
                <?php endif; ?>
                <a href="<?=htmlspecialcharsbx($link)?>"
                   class="directions-card__link text-decoration-none small">Подробнее <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
