<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['ORG_STRUCTURE'] ?? null) ? $arResult['ORG_STRUCTURE'] : [];
$tree = is_array($view['tree'] ?? null) ? $view['tree'] : [];
$editAction = site_string($view['section_edit_action'] ?? '');
$deleteAction = site_string($view['section_delete_action'] ?? '');
$galleryId = 'org-structure-' . $this->randString();
$employeeLimit = 4;

$renderEmployees = static function (
    array $employees,
    int $sectionId,
    string $instanceId,
    string $galleryId
) use ($employeeLimit): string {
    $hiddenCount = max(0, count($employees) - $employeeLimit);
    $hiddenId = $instanceId . '-employees-' . $sectionId;

    ob_start();
    ?>
    <div class="org-structure__employees">
        <div class="org-structure__employees-grid" id="<?=htmlspecialcharsbx($hiddenId)?>">
            <?php foreach ($employees as $index => $employee): ?>
                <?php
                if (!is_array($employee)) {
                    continue;
                }
                $name = site_string($employee['name'] ?? '');
                $position = site_string($employee['position'] ?? '');
                $phone = site_string($employee['phone'] ?? '');
                $phoneUrl = site_url($employee['phone_url'] ?? null, '', ['tel'], false);
                $cabinet = site_string($employee['cabinet'] ?? '');
                $address = site_string($employee['address'] ?? '');
                $imageUrl = site_url($employee['image_url'] ?? null, '');
                $isVacant = ($employee['is_vacant'] ?? false) === true;
                $isActing = ($employee['is_acting'] ?? false) === true;
                $isInitiallyHidden = $index >= $employeeLimit;
                ?>
                <article class="csr43-light-card csr43-light-card--interactive org-structure__employee"
                         <?php if ($isInitiallyHidden): ?>data-org-hidden-employee hidden<?php endif; ?>>
                    <?php if (!$isVacant && $imageUrl !== ''): ?>
                        <div class="org-structure__employee-photo gallery-media">
                            <a href="<?=htmlspecialcharsbx($imageUrl)?>"
                               class="org-structure__photo-link"
                               data-gallery-item
                               data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                               data-gallery-caption="<?=htmlspecialcharsbx($position)?>"
                               data-type="image"
                               aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $position, GetMessage('CSR43_LIGHT_ORG_ENLARGE')))?>">
                                <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
                            </a>
                            <img src="<?=htmlspecialcharsbx($imageUrl)?>"
                                 class="org-structure__employee-image"
                                 alt="<?=htmlspecialcharsbx($position)?>"
                                 loading="lazy"
                                 decoding="async">
                        </div>
                    <?php elseif (!$isVacant): ?>
                        <div class="csr43-light-icon org-structure__employee-placeholder" aria-hidden="true">
                            <i class="bi bi-person-circle" aria-hidden="true"></i>
                        </div>
                    <?php endif; ?>

                    <?php if ($isVacant): ?>
                        <div class="org-structure__vacancy">
                            <i class="bi bi-person-x org-structure__vacancy-icon" aria-hidden="true"></i>
                            <p class="org-structure__vacancy-label"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_ORG_VACANT'))?></p>
                            <?php if ($name !== ''): ?>
                                <p class="org-structure__employee-position"><?=htmlspecialcharsbx($name)?></p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="org-structure__employee-info">
                            <h3 class="org-structure__employee-name"><?=htmlspecialcharsbx($position)?></h3>
                            <?php if ($name !== ''): ?>
                                <p class="org-structure__employee-position">
                                    <?=htmlspecialcharsbx($name)?><?php if ($isActing): ?> <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_ORG_ACTING'))?><?php endif; ?>
                                </p>
                            <?php endif; ?>
                            <?php if ($phone !== '' || $cabinet !== '' || $address !== ''): ?>
                                <ul class="org-structure__contacts">
                                    <?php if ($phone !== ''): ?>
                                        <li class="org-structure__contact">
                                            <i class="bi bi-telephone" aria-hidden="true"></i>
                                            <?php if ($phoneUrl !== ''): ?>
                                                <a href="<?=htmlspecialcharsbx($phoneUrl)?>"><?=htmlspecialcharsbx($phone)?></a>
                                            <?php else: ?>
                                                <span><?=htmlspecialcharsbx($phone)?></span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($cabinet !== ''): ?>
                                        <li class="org-structure__contact">
                                            <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                            <span><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_ORG_CABINET'))?> <?=htmlspecialcharsbx($cabinet)?></span>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($address !== ''): ?>
                                        <li class="org-structure__contact">
                                            <i class="bi bi-map" aria-hidden="true"></i>
                                            <span><?=htmlspecialcharsbx($address)?></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($hiddenCount > 0): ?>
            <?php
            $collapsedLabel = str_replace('#COUNT#', (string)$hiddenCount, GetMessage('CSR43_LIGHT_ORG_MORE'));
            $expandedLabel = GetMessage('CSR43_LIGHT_ORG_HIDE');
            ?>
            <div class="org-structure__more">
                <button type="button"
                        class="org-structure__more-button"
                        aria-expanded="false"
                        aria-controls="<?=htmlspecialcharsbx($hiddenId)?>"
                        data-org-employees-toggle
                        data-org-target="<?=htmlspecialcharsbx($hiddenId)?>"
                        data-collapsed-label="<?=htmlspecialcharsbx($collapsedLabel)?>"
                        data-expanded-label="<?=htmlspecialcharsbx($expandedLabel)?>">
                    <span data-org-toggle-label><?=htmlspecialcharsbx($collapsedLabel)?></span>
                    <i class="bi bi-chevron-down org-structure__more-icon" aria-hidden="true"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return (string)ob_get_clean();
};

$renderSections = static function (
    array $sections,
    CBitrixComponentTemplate $componentTemplate,
    string $instanceId,
    string $galleryId,
    string $editAction,
    string $deleteAction
) use (&$renderSections, $renderEmployees): string {
    ob_start();
    ?>
    <ul class="org-structure__list">
        <?php foreach ($sections as $section): ?>
            <?php
            if (!is_array($section)) {
                continue;
            }
            $sectionId = max(0, (int)($section['id'] ?? 0));
            if ($sectionId === 0) {
                continue;
            }
            $name = site_string($section['name'] ?? '');
            $depth = max(0, (int)($section['depth'] ?? 0));
            $employees = is_array($section['employees'] ?? null) ? $section['employees'] : [];
            $children = is_array($section['children'] ?? null) ? $section['children'] : [];

            $componentTemplate->AddEditAction($sectionId, site_string($section['edit_link'] ?? ''), $editAction);
            $componentTemplate->AddDeleteAction(
                $sectionId,
                site_string($section['delete_link'] ?? ''),
                $deleteAction,
                ['CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')]
            );
            ?>
            <li class="org-structure__node" data-depth="<?=$depth?>">
                <section class="org-structure__section" id="<?=htmlspecialcharsbx($componentTemplate->GetEditAreaId($sectionId))?>">
                    <header class="csr43-light-surface org-structure__section-header">
                        <i class="bi bi-diagram-3 org-structure__section-icon" aria-hidden="true"></i>
                        <h2 class="org-structure__section-title"><?=htmlspecialcharsbx($name)?></h2>
                    </header>
                    <?php if ($employees !== []): ?>
                        <?=$renderEmployees($employees, $sectionId, $instanceId, $galleryId)?>
                    <?php endif; ?>
                    <?php if ($children !== []): ?>
                        <?=$renderSections($children, $componentTemplate, $instanceId, $galleryId, $editAction, $deleteAction)?>
                    <?php endif; ?>
                </section>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
    return (string)ob_get_clean();
};

$instanceId = 'org-structure-' . $this->randString();
?>
<?php if ($tree !== []): ?>
    <div class="org-structure container py-4"
         data-org-structure
         role="region"
         aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_ORG_LABEL'))?>">
        <?=$renderSections($tree, $this, $instanceId, $galleryId, $editAction, $deleteAction)?>
    </div>
<?php else: ?>
    <div class="csr43-light-surface org-structure__empty" role="status">
        <i class="bi bi-info-circle" aria-hidden="true"></i>
        <span><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_ORG_EMPTY'))?></span>
    </div>
<?php endif; ?>
