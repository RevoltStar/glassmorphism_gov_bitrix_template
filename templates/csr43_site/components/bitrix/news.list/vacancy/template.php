<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php
$items = $arResult['ITEMS'] ?? [];
if (is_array($items) && $items !== []):
?>
    <div class="glass-vacancies-list">
        <?php foreach ($items as $vacancy):
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
        ?>
        <div class="glass-vacancy-card">
            <div class="glass-vacancy-card-inner">
                <!-- Заголовок вакансии -->
                <div class="glass-vacancy-header">
                    <h3 class="glass-vacancy-title"><?=htmlspecialcharsbx($name)?></h3>
                    <?php if($format):?>
                        <span class="glass-vacancy-badge">
                            <i class="fas fa-laptop-code me-1"></i> <?=htmlspecialcharsbx($format)?>
                        </span>
                    <?php endif;?>
                </div>

                <!-- Основная информация: две колонки (обязанности + условия) -->
                <div class="glass-vacancy-row">
                    <div class="glass-vacancy-col">
                        <div class="glass-vacancy-section">
                            <h5 class="glass-section-heading">
                                <i class="fas fa-tasks"></i> Обязанности
                            </h5>
                            <?php if(!empty($responsibilities)):?>
                                <ul class="glass-vacancy-list">
                                    <?php foreach($responsibilities as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="glass-vacancy-col">
                        <div class="glass-vacancy-section">
                            <h5 class="glass-section-heading">
                                <i class="fas fa-clock"></i> Условия
                            </h5>
                            <?php if(!empty($conditions)):?>
                                <ul class="glass-vacancy-list">
                                    <?php foreach($conditions as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <!-- Вторая строка: требования + предложения -->
                <div class="glass-vacancy-row">
                    <div class="glass-vacancy-col">
                        <div class="glass-vacancy-section">
                            <h5 class="glass-section-heading">
                                <i class="fas fa-clipboard-list"></i> Требования
                            </h5>
                            <?php if(!empty($requirements)):?>
                                <ul class="glass-vacancy-list">
                                    <?php foreach($requirements as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="glass-vacancy-col">
                        <div class="glass-vacancy-section">
                            <h5 class="glass-section-heading">
                                <i class="fas fa-gift"></i> Мы предлагаем
                            </h5>
                            <?php if(!empty($offers)):?>
                                <ul class="glass-vacancy-list">
                                    <?php foreach($offers as $item):?>
                                        <li><?=htmlspecialcharsbx($item)?></li>
                                    <?php endforeach;?>
                                </ul>
                            <?php else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <!-- Местоположение и зарплата -->
                <div class="glass-vacancy-row glass-vacancy-footer">
                    <div class="glass-vacancy-col">
                        <div class="glass-info-block glass-location-block">
                            <i class="fas fa-map-marker-alt glass-info-icon"></i>
                            <div>
                                <div class="glass-info-label">Место работы</div>
                                <div class="glass-info-value">
                                    <?=$location !== '' ? htmlspecialcharsbx($location) : 'Не указано'?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="glass-vacancy-col">
                        <div class="glass-info-block glass-salary-block">
                            <i class="fas fa-ruble-sign glass-info-icon"></i>
                            <div>
                                <div class="glass-info-label">Зарплата</div>
                                <div class="glass-info-value glass-salary-value">
                                    <?=$salary !== '' ? htmlspecialcharsbx($salary) . ' ₽' : 'Не указана'?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Нижняя панель: дата + кнопка -->
                <div class="glass-vacancy-action">
                    <span class="glass-vacancy-date">
                        <?php if ($publishedDate !== ''): ?>
                            <i class="far fa-calendar-alt me-1"></i> Опубликовано: <?=htmlspecialcharsbx($publishedDate)?>
                        <?php endif; ?>
                    </span>
                    <button class="glass-vacancy-btn">
                        <i class="fas fa-paper-plane me-1"></i> Откликнуться
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
<?php else:?>
    <div class="glass-empty-alert">
        <div class="glass-empty-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <strong class="d-block mb-1">В данный момент нет открытых вакансий</strong>
            <span class="small">Пожалуйста, загляните позже — новые предложения появляются регулярно.</span>
        </div>
    </div>
<?php endif;?>
