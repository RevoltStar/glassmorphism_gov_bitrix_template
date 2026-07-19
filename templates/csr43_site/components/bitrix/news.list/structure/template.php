<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$sections = $arResult['SECTIONS'] ?? [];
if (!is_array($sections)) {
    $sections = [];
}
?>
<div class="glass-org-structure">
    <?php foreach ($sections as $section): ?>
        <?php
        if (!is_array($section)) {
            continue;
        }
        $sectionName = site_string($section['~NAME'] ?? $section['NAME'] ?? '');
        $employees = is_array($section['ELEMENTS'] ?? null) ? $section['ELEMENTS'] : [];
        ?>
        <div class="glass-department">
            <h2 class="glass-department__title"><?=htmlspecialcharsbx($sectionName)?></h2>

            <?php if ($employees !== []): ?>
                <div class="glass-employees-grid">
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
                        <div class="glass-employee-card">
                            <div class="glass-employee-card__inner">
                                <?php if ($photoSrc !== ''): ?>
                                    <div class="glass-employee__photo-wrapper">
                                        <img src="<?=htmlspecialcharsbx($photoSrc)?>"
                                             alt="<?=htmlspecialcharsbx($employeeName)?>"
                                             class="glass-employee__photo"
                                             loading="lazy">
                                    </div>
                                <?php else: ?>
                                    <div class="glass-employee__photo-placeholder">
                                        <i class="fas fa-user-circle" aria-hidden="true"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="glass-employee__info">
                                    <div class="glass-employee__name"><?=htmlspecialcharsbx($employeeName)?></div>
                                    <?php if ($position !== ''): ?>
                                        <div class="glass-employee__position"><?=htmlspecialcharsbx($position)?></div>
                                    <?php endif; ?>
                                    <?php if ($phone !== ''): ?>
                                        <div class="glass-employee__contact">
                                            <i class="fas fa-phone-alt glass-contact-icon" aria-hidden="true"></i>
                                            <span><?=htmlspecialcharsbx($phone)?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($address !== ''): ?>
                                        <div class="glass-employee__contact">
                                            <i class="fas fa-map-marker-alt glass-contact-icon" aria-hidden="true"></i>
                                            <span><?=htmlspecialcharsbx($address)?></span>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($emailLink !== ''): ?>
                                        <div class="glass-employee__contact glass-employee__email">
                                            <i class="fas fa-envelope glass-contact-icon" aria-hidden="true"></i>
                                            <a href="<?=htmlspecialcharsbx($emailLink)?>"><?=htmlspecialcharsbx($email)?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="glass-department__empty">
                    <i class="fas fa-users-slash me-2" aria-hidden="true"></i> Нет сотрудников
                </p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
