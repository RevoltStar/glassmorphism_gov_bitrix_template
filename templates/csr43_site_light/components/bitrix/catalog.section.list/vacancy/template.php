<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['VACANCIES'] ?? null) ? $arResult['VACANCIES'] : [];
$sections = is_array($view['sections'] ?? null) ? $view['sections'] : [];
$phone = site_string($view['phone'] ?? '');
$phoneUrl = site_url($view['phone_url'] ?? null, '', ['tel'], false);
$email = site_string($view['email'] ?? '');
$emailUrl = site_url($view['email_url'] ?? null, '', ['mailto'], false);

$detailDefinitions = [
    ['key' => 'responsibilities', 'label' => GetMessage('CSR43_LIGHT_VACANCY_RESPONSIBILITIES'), 'icon' => 'bi-list-task'],
    ['key' => 'requirements', 'label' => GetMessage('CSR43_LIGHT_VACANCY_REQUIREMENTS'), 'icon' => 'bi-check-circle'],
    ['key' => 'conditions', 'label' => GetMessage('CSR43_LIGHT_VACANCY_CONDITIONS'), 'icon' => 'bi-star'],
    ['key' => 'offers', 'label' => GetMessage('CSR43_LIGHT_VACANCY_OFFERS'), 'icon' => 'bi-gift'],
];
?>
<?php if ($sections !== []): ?>
    <div class="vacancy container py-4">
        <nav class="csr43-light-surface vacancy__navigation"
             aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_NAVIGATION'))?>">
            <ul class="vacancy__navigation-list">
                <?php foreach ($sections as $section): ?>
                    <?php
                    if (!is_array($section)) {
                        continue;
                    }
                    $sectionId = max(0, (int)($section['id'] ?? 0));
                    if ($sectionId === 0) {
                        continue;
                    }
                    $sectionName = site_string($section['name'] ?? '');
                    $anchorId = 'vacancy-section-' . $sectionId;
                    $countLabel = site_string($section['count_label'] ?? '');
                    $anchorUrl = site_url('#' . $anchorId, '');
                    ?>
                    <li class="vacancy__navigation-item">
                        <a href="<?=htmlspecialcharsbx($anchorUrl)?>"
                           class="vacancy__navigation-link"
                           title="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_NAVIGATION_LINK'))?>">
                            <span><?=htmlspecialcharsbx($sectionName)?></span>
                            <span class="badge csr43-light-badge vacancy__navigation-count"><?=htmlspecialcharsbx($countLabel)?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <?php foreach ($sections as $section): ?>
            <?php
            if (!is_array($section)) {
                continue;
            }
            $sectionId = max(0, (int)($section['id'] ?? 0));
            if ($sectionId === 0) {
                continue;
            }
            $sectionName = site_string($section['name'] ?? '');
            $anchorId = 'vacancy-section-' . $sectionId;
            $items = is_array($section['items'] ?? null) ? $section['items'] : [];
            ?>
            <section class="vacancy__section" id="<?=htmlspecialcharsbx($anchorId)?>">
                <h2 class="vacancy__section-title"><?=htmlspecialcharsbx($sectionName)?></h2>
                <div class="vacancy__list">
                    <?php foreach ($items as $item): ?>
                        <?php
                        if (!is_array($item)) {
                            continue;
                        }
                        $vacancyId = max(0, (int)($item['id'] ?? 0));
                        $name = site_string($item['name'] ?? '');
                        $salary = site_string($item['salary'] ?? '');
                        $address = site_string($item['address'] ?? '');
                        ?>
                        <article class="csr43-light-card vacancy__card">
                            <header class="vacancy__card-header">
                                <div class="vacancy__heading">
                                    <h3 class="vacancy__title"><?=htmlspecialcharsbx($name)?></h3>
                                    <p class="vacancy__department"><?=htmlspecialcharsbx($sectionName)?></p>
                                </div>
                                <span class="badge csr43-light-badge vacancy__badge">
                                    <i class="bi bi-person-x" aria-hidden="true"></i>
                                    <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_BADGE'))?>
                                </span>
                            </header>

                            <div class="vacancy__details">
                                <?php foreach ($detailDefinitions as $definition): ?>
                                    <?php
                                    $lines = is_array($item[$definition['key']] ?? null)
                                        ? $item[$definition['key']]
                                        : [];
                                    if ($lines === []) {
                                        continue;
                                    }
                                    ?>
                                    <section class="vacancy__detail">
                                        <h4 class="vacancy__detail-title">
                                            <i class="bi <?=htmlspecialcharsbx($definition['icon'])?>" aria-hidden="true"></i>
                                            <?=htmlspecialcharsbx($definition['label'])?>
                                        </h4>
                                        <ul class="vacancy__value-list">
                                            <?php foreach ($lines as $line): ?>
                                                <li><?=htmlspecialcharsbx(site_string($line))?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </section>
                                <?php endforeach; ?>

                                <?php if ($address !== ''): ?>
                                    <section class="vacancy__detail">
                                        <h4 class="vacancy__detail-title">
                                            <i class="bi bi-building" aria-hidden="true"></i>
                                            <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_ADDRESS'))?>
                                        </h4>
                                        <p class="vacancy__value"><?=htmlspecialcharsbx($address)?></p>
                                    </section>
                                <?php endif; ?>

                                <?php if ($salary !== ''): ?>
                                    <section class="vacancy__detail vacancy__detail--salary">
                                        <h4 class="vacancy__detail-title">
                                            <i class="bi bi-cash-stack" aria-hidden="true"></i>
                                            <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_SALARY'))?>
                                        </h4>
                                        <p class="vacancy__value"><?=htmlspecialcharsbx($salary)?> <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_CURRENCY'))?></p>
                                    </section>
                                <?php endif; ?>
                            </div>

                            <?php if ($phone !== '' || $email !== ''): ?>
                                <details class="vacancy__contacts">
                                    <summary class="vacancy__contacts-toggle">
                                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                                        <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_MORE'))?>
                                    </summary>
                                    <div class="vacancy__contacts-body" id="vacancy-contacts-<?=$vacancyId?>">
                                        <?php if ($phone !== ''): ?>
                                            <p class="vacancy__contact">
                                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                                <strong><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_PHONE'))?></strong>
                                                <?php if ($phoneUrl !== ''): ?>
                                                    <a href="<?=htmlspecialcharsbx($phoneUrl)?>"><?=htmlspecialcharsbx($phone)?></a>
                                                <?php else: ?>
                                                    <span><?=htmlspecialcharsbx($phone)?></span>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($email !== ''): ?>
                                            <p class="vacancy__contact">
                                                <i class="bi bi-envelope" aria-hidden="true"></i>
                                                <strong><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_EMAIL'))?></strong>
                                                <?php if ($emailUrl !== ''): ?>
                                                    <a href="<?=htmlspecialcharsbx($emailUrl)?>"><?=htmlspecialcharsbx($email)?></a>
                                                <?php else: ?>
                                                    <span><?=htmlspecialcharsbx($email)?></span>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </details>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="csr43-light-surface vacancy__empty" role="status">
        <i class="bi bi-info-circle" aria-hidden="true"></i>
        <span><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_VACANCY_EMPTY'))?></span>
    </div>
<?php endif; ?>
