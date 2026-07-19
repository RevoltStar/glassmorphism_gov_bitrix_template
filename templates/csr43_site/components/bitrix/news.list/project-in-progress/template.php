<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = $arResult['ITEMS'] ?? [];
if (!is_array($items) || $items === []) {
    return;
}

$projectListUrl = site_url(
    $arParams['PROJECT_LIST'] ?? null,
    '/activity/implemintation-of-regional-projects/'
);

$items[] = [
    'NAME' => 'Посмотреть все проекты',
    'PREVIEW_TEXT' => 'Нажмите, чтобы перейти',
    'PROPERTIES' => [
        'ICON' => ['VALUE' => 'fa fa-arrow-right'],
        'LINK' => ['VALUE' => $projectListUrl],
    ],
];
?>
<div class="row g-4">
    <?php foreach ($items as $project): ?>
        <?php
        if (!is_array($project)) {
            continue;
        }

        $link = site_url($project['PROPERTIES']['LINK']['VALUE'] ?? null);
        $icon = site_css_classes(
            $project['PROPERTIES']['ICON']['VALUE'] ?? null
        );
        $name = site_string($project['~NAME'] ?? $project['NAME'] ?? '');
        $preview = site_plain_text(
            $project['~PREVIEW_TEXT'] ?? $project['PREVIEW_TEXT'] ?? ''
        );
        $badges = site_string_list(
            $project['PROPERTIES']['BADGES']['VALUE'] ?? []
        );
        ?>
        <div class="col-md-6">
            <a href="<?=htmlspecialcharsbx($link)?>" class="text-decoration-none">
                <div class="glass-card d-flex">
                    <div class="me-3">
                        <?php if ($icon !== ''): ?>
                            <div class="activity-icon" style="width: 60px; height: 60px;">
                                <i class="<?=htmlspecialcharsbx($icon)?>" aria-hidden="true"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="w-100">
                        <h5 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialcharsbx($name)?></h5>
                        <?php if ($preview !== ''): ?>
                            <p style="color: #2c6b9e;"><?=htmlspecialcharsbx($preview)?></p>
                        <?php endif; ?>
                        <?php if ($badges !== []): ?>
                            <div class="gap-2 d-flex flex-wrap">
                                <?php foreach ($badges as $badge): ?>
                                    <span class="badge main-badge"><?=htmlspecialcharsbx($badge)?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
