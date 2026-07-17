<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?if(!empty($arResult["ITEMS"])):?>
    <div class="glass-vacancies-list">
        <?foreach ($arResult["ITEMS"] as $vacancy):
            $responsibilities = $vacancy["PROPERTIES"]["responsibilities"]["VALUE"] ?? [];
            $requirements = $vacancy["PROPERTIES"]["requirements"]["VALUE"] ?? [];
            $conditions = $vacancy["PROPERTIES"]["conditions"]["VALUE"] ?? [];
            $offers = $vacancy["PROPERTIES"]["offer"]["VALUE"] ?? [];
            $location = $vacancy["PROPERTIES"]["location"]["VALUE"] ?? "";
            $salary = $vacancy["PROPERTIES"]["salary"]["VALUE"] ?? "";
            $format = $vacancy["PROPERTIES"]["format"]["VALUE"] ?? "";
        ?>
        <div class="glass-vacancy-card">
            <div class="glass-vacancy-card-inner">
                <!-- Заголовок вакансии -->
                <div class="glass-vacancy-header">
                    <h3 class="glass-vacancy-title"><?=$vacancy["NAME"]?></h3>
                    <?if($format):?>
                        <span class="glass-vacancy-badge">
                            <i class="fas fa-laptop-code me-1"></i> <?=$format?>
                        </span>
                    <?endif;?>
                </div>

                <!-- Основная информация: две колонки (обязанности + условия) -->
                <div class="glass-vacancy-row">
                    <div class="glass-vacancy-col">
                        <div class="glass-vacancy-section">
                            <h5 class="glass-section-heading">
                                <i class="fas fa-tasks"></i> Обязанности
                            </h5>
                            <?if(!empty($responsibilities)):?>
                                <ul class="glass-vacancy-list">
                                    <?foreach($responsibilities as $item):?>
                                        <li><?=$item?></li>
                                    <?endforeach;?>
                                </ul>
                            <?else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?endif;?>
                        </div>
                    </div>
                    <div class="glass-vacancy-col">
                        <div class="glass-vacancy-section">
                            <h5 class="glass-section-heading">
                                <i class="fas fa-clock"></i> Условия
                            </h5>
                            <?if(!empty($requirements)):?>
                                <ul class="glass-vacancy-list">
                                    <?foreach($requirements as $item):?>
                                        <li><?=$item?></li>
                                    <?endforeach;?>
                                </ul>
                            <?else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?endif;?>
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
                            <?if(!empty($conditions)):?>
                                <ul class="glass-vacancy-list">
                                    <?foreach($conditions as $item):?>
                                        <li><?=$item?></li>
                                    <?endforeach;?>
                                </ul>
                            <?else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?endif;?>
                        </div>
                    </div>
                    <div class="glass-vacancy-col">
                        <div class="glass-vacancy-section">
                            <h5 class="glass-section-heading">
                                <i class="fas fa-gift"></i> Мы предлагаем
                            </h5>
                            <?if(!empty($offers)):?>
                                <ul class="glass-vacancy-list">
                                    <?foreach($offers as $item):?>
                                        <li><?=$item?></li>
                                    <?endforeach;?>
                                </ul>
                            <?else:?>
                                <p class="glass-vacancy-empty">Информация временно отсутствует</p>
                            <?endif;?>
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
                                    <?=!empty($location) ? htmlspecialcharsbx($location) : 'Не указано'?>
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
                                    <?=!empty($salary) ? $salary . ' ₽' : 'Не указана'?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Нижняя панель: дата + кнопка -->
                <div class="glass-vacancy-action">
                    <span class="glass-vacancy-date">
                        <i class="far fa-calendar-alt me-1"></i> Опубликовано: <?=FormatDate("d.m.Y", MakeTimeStamp($vacancy["TIMESTAMP_X"]))?>
                    </span>
                    <button class="glass-vacancy-btn">
                        <i class="fas fa-paper-plane me-1"></i> Откликнуться
                    </button>
                </div>
            </div>
        </div>
        <?endforeach;?>
    </div>
<?else:?>
    <div class="glass-empty-alert">
        <div class="glass-empty-icon">
            <i class="fas fa-info-circle"></i>
        </div>
        <div>
            <strong class="d-block mb-1">В данный момент нет открытых вакансий</strong>
            <span class="small">Пожалуйста, загляните позже — новые предложения появляются регулярно.</span>
        </div>
    </div>
<?endif;?>