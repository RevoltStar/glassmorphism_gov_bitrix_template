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
            <div class="glass-card text-center">
                <?php if ($icon !== ''): ?>
                    <div class="activity-icon mx-auto">
                        <i class="<?=htmlspecialcharsbx($icon)?>" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>
                <h5 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialcharsbx($name)?></h5>
                <?php if ($preview !== ''): ?>
                    <p class="small" style="color: #2c6b9e;"><?=htmlspecialcharsbx($preview)?></p>
                <?php endif; ?>
                <a href="<?=htmlspecialcharsbx($link)?>"
                   class="text-decoration-none small"
                   style="color: #2980b9;">Подробнее <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
