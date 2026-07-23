<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$sections = $arResult['SECTIONS'] ?? [];
if (!is_array($sections)) {
    $sections = [];
}
$sections = array_values(array_filter($sections, 'is_array'));
?>
<div class="org-structure">
    <?php if ($sections === []): ?>
        <div class="csr43-glass-surface org-structure__empty" role="status">
            Нет данных о структуре
        </div>
    <?php else: ?>
    <?php foreach ($sections as $section): ?>
        <?php
        if (!is_array($section)) {
            continue;
        }
        $sectionName = site_string($section['~NAME'] ?? $section['NAME'] ?? '');
        $employees = is_array($section['ELEMENTS'] ?? null) ? $section['ELEMENTS'] : [];
        ?>
        <div class="csr43-glass-card csr43-glass-card--interactive org-department">
            <h2 class="org-department__title"><?=htmlspecialcharsbx($sectionName)?></h2>

            <?php if ($employees !== []): ?>
                <div class="employee-grid">
                    <?php foreach ($employees as $employee): ?>
                        <?php
                        if (!is_array($employee)) {
                            continue;
                        }

                        $employeeName = site_string($employee['~NAME'] ?? $employee['NAME'] ?? '');
                        $position = site_plain_text($employee['~PREVIEW_TEXT'] ?? $employee['PREVIEW_TEXT'] ?? '');
                        $phone = site_string($employee['PROPERTIES']['PHONE']['VALUE'] ?? '');
                        $address = site_string($employee['PROPERTIES']['ADDRESS']['VALUE'] ?? '');
                        $email = site_string($employee['PROPERTIES']['EMAIL']['VALUE'] ?? '');
                        $emailLink = site_url('mailto:' . $email, '', ['mailto'], false);
                        $pictureId = max(0, (int)($employee['PREVIEW_PICTURE'] ?? 0));
                        $photoSrc = '';
                        if ($pictureId > 0) {
                            $file = CFile::ResizeImageGet(
                                $pictureId,
                                ['width' => 120, 'height' => 120],
                                BX_RESIZE_IMAGE_EXACT,
                                true
                            );
                            if (is_array($file)) {
                                $photoSrc = site_url($file['src'] ?? null, '');
                            }
                        }
                        ?>
                        <div class="employee-card">
                            <div class="csr43-glass-card csr43-glass-card--interactive csr43-glass-card--stretch employee-card__inner">
                                <?php if ($photoSrc !== ''): ?>
                                    <div class="employee-card__photo-wrapper">
                                        <img src="<?=htmlspecialcharsbx($photoSrc)?>"
                                             alt="<?=htmlspecialcharsbx($employeeName)?>"
                                             class="employee-card__photo"
                                             loading="lazy">
                                    </div>
                                <?php else: ?>
                                    <div class="employee-card__photo-placeholder">
                                        <i class="fas fa-user-circle" aria-hidden="true"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="employee-card__info">
                                    <div class="employee-card__name"><?=htmlspecialcharsbx($employeeName)?></div>
                                    <?php if ($position !== ''): ?>
                                        <div class="employee-card__position"><?=htmlspecialcharsbx($position)?></div>
                                    <?php endif; ?>
                                    <?php if ($phone !== ''): ?>
                                        <div class="employee-card__contact">
                                            <i class="fas fa-phone-alt employee-card__contact-icon" aria-hidden="true"></i>
                                            <span><?=htmlspecialcharsbx($phone)?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($address !== ''): ?>
                                        <div class="employee-card__contact">
                                            <i class="fas fa-map-marker-alt employee-card__contact-icon" aria-hidden="true"></i>
                                            <span><?=htmlspecialcharsbx($address)?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($emailLink !== ''): ?>
                                        <div class="employee-card__contact employee-card__email">
                                            <i class="fas fa-envelope employee-card__contact-icon" aria-hidden="true"></i>
                                            <a href="<?=htmlspecialcharsbx($emailLink)?>"><?=htmlspecialcharsbx($email)?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="csr43-glass-surface org-department__empty">
                    <i class="fas fa-users-slash me-2" aria-hidden="true"></i> Нет сотрудников
                </p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
