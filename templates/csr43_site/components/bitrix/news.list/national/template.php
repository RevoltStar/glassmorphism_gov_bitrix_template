<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = $arResult['ITEMS'] ?? [];
if (!is_array($items) || $items === []) {
    return;
}

$totalName = 'Общая готовность';
$totalProgress = 0;
$totalLink = '#';
?>
<div class="row align-items-center">
    <div class="col-lg-6">
        <?php foreach ($items as $national): ?>
            <?php
            if (!is_array($national)) {
                continue;
            }

            $name = site_string($national['~NAME'] ?? $national['NAME'] ?? '');
            $progressValue = filter_var(
                $national['PROPERTIES']['PROGRESS']['VALUE'] ?? null,
                FILTER_VALIDATE_FLOAT
            );
            $progress = $progressValue === false
                ? 0
                : min(100, max(0, (float)$progressValue));
            $progressText = rtrim(rtrim(number_format($progress, 2, '.', ''), '0'), '.');
            $link = site_url($national['PROPERTIES']['LINK']['VALUE'] ?? null);

            if ($name === $totalName) {
                $totalProgress = $progress;
                $totalLink = $link;
                continue;
            }

            $badges = site_string_list(
                $national['PROPERTIES']['BADGES']['VALUE'] ?? []
            );
            ?>
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2 align-items-center">
                    <span class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialcharsbx($name)?></span>
                    <div class="d-flex align-items-center gap-3">
                        <span style="color: #2980b9; font-weight: 600;"><?=htmlspecialcharsbx($progressText)?>%</span>
                        <?php if ($link !== '#'): ?>
                            <a href="<?=htmlspecialcharsbx($link)?>"
                               class="btn btn-sm btn-outline-primary"
                               style="border-radius: 20px; padding: 2px 12px; font-size: 12px;"
                               aria-label="Подробнее: <?=htmlspecialcharsbx($name)?>">
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($badges !== []): ?>
                    <div class="gap-2 d-flex flex-wrap">
                        <?php foreach ($badges as $badge): ?>
                            <span class="badge main-badge"><?=htmlspecialcharsbx($badge)?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php $totalProgressText = rtrim(rtrim(number_format($totalProgress, 2, '.', ''), '0'), '.'); ?>
    <div class="col-lg-6">
        <div class="text-center p-4">
            <i class="fas fa-chart-line fa-4x mb-3" aria-hidden="true" style="color: #3498db;"></i>
            <h4 class="fw-bold" style="color: #0a3144;"><?=htmlspecialcharsbx($totalName)?></h4>
            <p class="display-3 fw-bold" style="color: #2980b9;"><?=htmlspecialcharsbx($totalProgressText)?>%</p>
            <p style="color: #2c6b9e;">План на текущий год выполнен на <?=htmlspecialcharsbx($totalProgressText)?>%</p>
            <?php if ($totalLink !== '#'): ?>
                <a href="<?=htmlspecialcharsbx($totalLink)?>" class="btn btn-glass-blue mt-2">Подробнее о проектах</a>
            <?php endif; ?>
        </div>
    </div>
</div>
