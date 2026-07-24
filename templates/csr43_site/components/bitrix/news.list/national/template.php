<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = is_array($arResult['ITEMS'] ?? null)
    ? array_values(array_filter($arResult['ITEMS'], 'is_array'))
    : [];
if ($items === []) {
    ?>
    <div class="csr43-glass-surface rounded-4 p-4 text-center text-muted" role="status">
        <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
        Информация о национальных проектах временно недоступна.
    </div>
    <?php
    return;
}

$totalName = 'Общая готовность';
$totalProgress = 0;
$totalLink = '#';
?>
<div class="csr43-glass-card csr43-glass-card--interactive national-project-card">
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
                        <span class="national-project__name fw-bold"><?=htmlspecialcharsbx($name)?></span>
                        <div class="d-flex align-items-center gap-3">
                            <span class="national-project__progress"><?=htmlspecialcharsbx($progressText)?>%</span>
                            <?php if ($link !== '#'): ?>
                                <a href="<?=htmlspecialcharsbx($link)?>"
                                   class="national-project__more btn btn-sm btn-outline-primary"
                                   aria-label="Подробнее: <?=htmlspecialcharsbx($name)?>">
                                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($badges !== []): ?>
                        <div class="gap-2 d-flex flex-wrap">
                            <?php foreach ($badges as $badge): ?>
                                <span class="badge csr43-glass-badge csr43-glass-badge--block national-project__badge"><?=htmlspecialcharsbx($badge)?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php $totalProgressText = rtrim(rtrim(number_format($totalProgress, 2, '.', ''), '0'), '.'); ?>
        <div class="col-lg-6">
            <div class="text-center p-4">
                <i class="national-project__summary-icon fas fa-chart-line fa-4x mb-3" aria-hidden="true"></i>
                <h4 class="national-project__summary-title fw-bold"><?=htmlspecialcharsbx($totalName)?></h4>
                <p class="national-project__summary-progress display-3 fw-bold"><?=htmlspecialcharsbx($totalProgressText)?>%</p>
                <p class="national-project__summary-description">План на текущий год выполнен на <?=htmlspecialcharsbx($totalProgressText)?>%</p>
                <?php if ($totalLink !== '#'): ?>
                    <a href="<?=htmlspecialcharsbx($totalLink)?>" class="btn btn-primary rounded-pill px-4 py-2 mt-2">Подробнее о проектах</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
