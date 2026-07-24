<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php
$items = $arResult['ITEMS'] ?? [];
$contactEmail = site_string(get_info('email', ''));
$contactPhone = site_string(get_info('phone', ''));
$contactEmailUrl = site_url(
    'mailto:' . $contactEmail,
    '',
    ['mailto'],
    false
);
$contactPhoneUrl = site_url(
    'tel:' . site_string(get_info('phone_e164', $contactPhone), $contactPhone),
    '',
    ['tel'],
    false
);
$hasContactEmail = $contactEmail !== '' && $contactEmailUrl !== '';
$hasContactPhone = $contactPhone !== '' && $contactPhoneUrl !== '';
$hasVacancyContacts = $hasContactEmail || $hasContactPhone;
if (is_array($items) && $items !== []):
?>
    <div class="vacancy-list">
        <?php foreach ($items as $vacancyIndex => $vacancy):
            if (!is_array($vacancy)) {
                continue;
            }

            $responsibilities = site_string_list($vacancy['PROPERTIES']['responsibilities']['VALUE'] ?? []);
            $requirements = site_string_list($vacancy['PROPERTIES']['requirements']['VALUE'] ?? []);
            $conditions = site_string_list($vacancy['PROPERTIES']['conditions']['VALUE'] ?? []);
            $offers = site_string_list($vacancy['PROPERTIES']['offer']['VALUE'] ?? []);
            $location = site_string($vacancy['PROPERTIES']['location']['VALUE'] ?? '');
            $salary = site_string($vacancy['PROPERTIES']['salary']['VALUE'] ?? '');
            $format = site_string($vacancy['PROPERTIES']['format']['VALUE'] ?? '');
            $name = site_string($vacancy['~NAME'] ?? $vacancy['NAME'] ?? '');
            $timestamp = MakeTimeStamp(site_string($vacancy['TIMESTAMP_X'] ?? ''));
            $publishedDate = $timestamp > 0 ? FormatDate('d.m.Y', $timestamp) : '';
            $vacancyId = max(0, (int)($vacancy['ID'] ?? 0));
            $contactsId = 'vacancy-contacts-'
                . ($vacancyId > 0 ? $vacancyId : (int)$vacancyIndex)
                . '-'
                . $this->randString(6);
        ?>
        <div class="vacancy-card">
            <div class="csr43-glass-card csr43-glass-card--interactive vacancy-card__inner">
                <!-- Заголовок вакансии -->
                <div class="vacancy-card__header">
                    <h3 class="vacancy-card__title"><?=htmlspecialcharsbx($name)?></h3>
                    <?php if($format):?>
                        <span class="badge csr43-glass-badge vacancy-card__badge">
                            <i class="fas fa-laptop-code me-1"></i> <?=htmlspecialcharsbx($format)?>
                        </span>
                    <?php endif;?>
                </div>

                <!-- Основная информация: две колонки (обязанности + условия) -->
                <div class="vacancy-card__row">
                    <div class="vacancy-card__column">
                        <div class="vacancy-card__section">
                            <h5 class="vacancy-card__section-heading">
                                <i class="fas fa-tasks"></i> Обязанности
                            </h5>
                            <?php if(!empty($responsibilities)):?>
                                <ul class="vacancy-card__list">
                                    <?php foreach($responsibilities as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="vacancy-card__empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="vacancy-card__column">
                        <div class="vacancy-card__section">
                            <h5 class="vacancy-card__section-heading">
                                <i class="fas fa-clock"></i> Условия
                            </h5>
                            <?php if(!empty($conditions)):?>
                                <ul class="vacancy-card__list">
                                    <?php foreach($conditions as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="vacancy-card__empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <!-- Вторая строка: требования + предложения -->
                <div class="vacancy-card__row">
                    <div class="vacancy-card__column">
                        <div class="vacancy-card__section">
                            <h5 class="vacancy-card__section-heading">
                                <i class="fas fa-clipboard-list"></i> Требования
                            </h5>
                            <?php if(!empty($requirements)):?>
                                <ul class="vacancy-card__list">
                                    <?php foreach($requirements as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="vacancy-card__empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="vacancy-card__column">
                        <div class="vacancy-card__section">
                            <h5 class="vacancy-card__section-heading">
                                <i class="fas fa-gift"></i> Мы предлагаем
                            </h5>
                            <?php if(!empty($offers)):?>
                                <ul class="vacancy-card__list">
                                    <?php foreach($offers as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="vacancy-card__empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <!-- Местоположение и зарплата -->
                <div class="vacancy-card__row vacancy-card__footer">
                    <div class="vacancy-card__column">
                        <div class="csr43-glass-surface vacancy-card__info vacancy-card__info--location">
                            <i class="fas fa-map-marker-alt vacancy-card__info-icon"></i>
                            <div>
                                <div class="vacancy-card__info-label">Место работы</div>
                                <div class="vacancy-card__info-value">
                                    <?=$location !== '' ? htmlspecialcharsbx($location) : 'Не указано'?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vacancy-card__column">
                        <div class="csr43-glass-surface vacancy-card__info vacancy-card__info--salary">
                            <i class="fas fa-ruble-sign vacancy-card__info-icon"></i>
                            <div>
                                <div class="vacancy-card__info-label">Зарплата</div>
                                <div class="vacancy-card__info-value vacancy-card__salary">
                                    <?=$salary !== '' ? htmlspecialcharsbx($salary) . ' ₽' : 'Не указана'?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Нижняя панель: дата + кнопка -->
                <div class="vacancy-card__action">
                    <span class="vacancy-card__date">
                        <?php if ($publishedDate !== ''): ?>
                            <i class="far fa-calendar-alt me-1"></i> Опубликовано: <?=htmlspecialcharsbx($publishedDate)?>
                        <?php endif; ?>
                    </span>
                    <?php if ($hasVacancyContacts): ?>
                        <button type="button"
                                class="csr43-glass-surface csr43-glass-card--interactive vacancy-card__button"
                                data-bs-toggle="collapse"
                                data-bs-target="#<?=htmlspecialcharsbx($contactsId)?>"
                                aria-expanded="false"
                                aria-controls="<?=htmlspecialcharsbx($contactsId)?>">
                            <i class="fas fa-paper-plane me-1" aria-hidden="true"></i>
                            <span class="vacancy-contact-toggle__label vacancy-contact-toggle__label--show">Откликнуться</span>
                            <span class="vacancy-contact-toggle__label vacancy-contact-toggle__label--hide">Скрыть контакты</span>
                        </button>
                    <?php endif; ?>
                </div>

                <?php if ($hasVacancyContacts): ?>
                    <div id="<?=htmlspecialcharsbx($contactsId)?>" class="collapse">
                        <section class="csr43-glass-surface vacancy-contact">
                            <h4 class="vacancy-contact__title">Контакты для отклика</h4>
                            <div class="vacancy-contact__items">
                                <?php if ($hasContactEmail): ?>
                                    <a class="vacancy-contact__item"
                                       href="<?=htmlspecialcharsbx($contactEmailUrl)?>">
                                        <i class="fas fa-envelope" aria-hidden="true"></i>
                                        <span><?=htmlspecialcharsbx($contactEmail)?></span>
                                    </a>
                                <?php endif; ?>
                                <?php if ($hasContactPhone): ?>
                                    <a class="vacancy-contact__item"
                                       href="<?=htmlspecialcharsbx($contactPhoneUrl)?>">
                                        <i class="fas fa-phone" aria-hidden="true"></i>
                                        <span><?=htmlspecialcharsbx($contactPhone)?></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <p class="vacancy-contact__hint">
                                При обращении укажите название вакансии:
                                «<?=htmlspecialcharsbx($name)?>».
                            </p>
                        </section>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach;?>
    </div>
<?php else:?>
    <div class="csr43-glass-surface vacancy-list__empty">
        <div class="vacancy-list__empty-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <strong class="d-block mb-1">В данный момент нет открытых вакансий</strong>
            <span class="small">Пожалуйста, загляните позже — новые предложения появляются регулярно.</span>
        </div>
    </div>
<?php endif;?>
