<?php
$excludedPages = get_info('layout_excluded_pages', []);
$currentPage = site_string($APPLICATION->GetCurPage(), '/');
$isExcludedPage = site_path_is_excluded($currentPage, $excludedPages);
$is404Error = defined('ERROR_404');
$footerLogoUrl = site_url(get_info('logo', ''), '');
$footerPhoneUrl = site_url('tel:' . site_string(get_info('phone_e164', '')), '#', ['tel'], false);
$footerEmailUrl = site_url('mailto:' . site_string(get_info('email', '')), '#', ['mailto'], false);
$feedbackUrl = site_url(get_info('feedback_path', ''), '#');
$privacyPolicyUrl = site_url(get_info('privacy_policy_path', ''), '#');
$developerUrl = site_url(get_info('developer_url', ''), '#');
$developerLogoUrl = site_url(get_info('developer_logo', ''), '');
$footerMenu1Title = site_plain_text(get_info('menu_footer_1_title', 'Быстрые ссылки'));
$footerMenu2Title = site_plain_text(get_info('menu_footer_2_title', 'Информация'));

if (!$isExcludedPage && !$is404Error): ?>
    </div> <!-- Закрывающий для col-lg-8 -->
<div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0  side-menu-wrapper">
        <?php $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "side",
            Array(
                "CHILD_MENU_TYPE" => site_menu_type(get_info('menu_side_child_type', 'right'), 'right'),
                "MAX_LEVEL" => "2",
                "MENU_CACHE_GET_VARS" => "",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => site_menu_type(get_info('menu_side_root_type', 'right'), 'right'),
                "USE_EXT" => "N"
            )
        );?>
    </div> <!-- Закрывающий для col-lg-4 -->
</div> <!-- Закрывающий для row -->
<?php endif ?>
</main>
<!-- Подвал -->
<footer class="footer">

    <!-- Основная информация в подвале -->
    <div class="container py-5">
        <div class="row g-4">

            <!-- Колонка 1: Логотип и контакты -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?=SITE_TEMPLATE_PATH . htmlspecialcharsbx($footerLogoUrl)?>"
                         alt="<?=htmlspecialcharsbx((string)get_info('org_full_name'))?>"
                         class="footer-logo">
                    <div class="ms-3">
                        <h5 class="footer-title"><?=htmlspecialcharsbx((string)get_info('org_short_name'))?></h5>
                        <span class="footer-sub"><?=htmlspecialcharsbx((string)get_info('region_name_genitive'))?></span>
                    </div>
                </div>

                <p class="footer-text">
                    <?=htmlspecialcharsbx((string)get_info('org_description'))?>
                    Все материалы сайта доступны по лицензии Creative Commons Attribution.
                </p>
                <?php
						$APPLICATION->IncludeComponent("bitrix:menu","social",Array(
							"ROOT_MENU_TYPE" => site_menu_type(get_info('menu_social_root_type', 'social'), 'social'),
							"MAX_LEVEL" => "1",
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N",
							"MENU_CACHE_TYPE" => "N",
							"MENU_CACHE_TIME" => "3600",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"MENU_CACHE_GET_VARS" => ""
							)
						);?>
            </div>

            <!-- Колонка 2: Быстрые ссылки -->
            <div class="col-lg-2 col-md-6 col-sm-6 col-6">
                <?php if ($footerMenu1Title !== ''): ?>
                    <h6 class="footer-menu__title">
                        <i class="bi bi-link-45deg" aria-hidden="true"></i>
                        <?=htmlspecialcharsbx($footerMenu1Title)?>
                    </h6>
                <?php endif; ?>
                <?php $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "footer",
                    [
                        "ROOT_MENU_TYPE" => site_menu_type(
                            get_info('menu_footer_1_root_type', 'footer_quick'),
                            'footer_quick'
                        ),
                        "MAX_LEVEL" => "1",
                        "USE_EXT" => "N",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => "",
                    ]
                ); ?>
            </div>

            <!-- Колонка 3: Информация -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                <?php if ($footerMenu2Title !== ''): ?>
                    <h6 class="footer-menu__title">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                        <?=htmlspecialcharsbx($footerMenu2Title)?>
                    </h6>
                <?php endif; ?>
                <?php $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "footer",
                    [
                        "ROOT_MENU_TYPE" => site_menu_type(
                            get_info('menu_footer_2_root_type', 'footer_info'),
                            'footer_info'
                        ),
                        "MAX_LEVEL" => "1",
                        "USE_EXT" => "N",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N",
                        "MENU_CACHE_TYPE" => "A",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => "",
                    ]
                ); ?>
            </div>

            <!-- Колонка 4: Контакты и обратная связь -->
            <div class="col-lg-3 col-md-6 col-12">
                <h6 class="footer-contact__title">
                    <i class="bi bi-telephone"></i> Контакты
                </h6>

                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="footer-contact-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                    </div>
                    <div class="footer-text">
                        <?=htmlspecialcharsbx((string)get_info('postal_code'))?>,
                        <?=htmlspecialcharsbx((string)get_info('address'))?>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="footer-contact-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                    </div>
                    <div>
                        <div class="footer-contact-item">
                            <a href="<?=htmlspecialcharsbx($footerPhoneUrl)?>"><?=htmlspecialcharsbx((string)get_info('phone'))?></a>
                        </div>
                        <div class="footer-contact-label">приемная</div>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="footer-contact-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                    </div>
                    <div>
                        <div class="footer-contact-item">
                            <a href="<?=htmlspecialcharsbx($footerEmailUrl)?>"><?=htmlspecialcharsbx((string)get_info('email'))?></a>
                        </div>
                        <div class="footer-contact-label">общие вопросы</div>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="footer-contact-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                    </div>
                    <div>
                        <div class="footer-contact-item"><?=htmlspecialcharsbx((string)get_info('workdays_primary'))?></div>
                        <div class="footer-contact-label"><?=htmlspecialcharsbx((string)get_info('lunch_break'))?></div>
                    </div>
                </div>
				<div class="d-flex mb-3">
                    <div class="me-3">
                        <div class="footer-contact-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                    </div>
                    <div>
                        <div class="footer-contact-item"><?=htmlspecialcharsbx((string)get_info('workdays_secondary'))?></div>
                        <div class="footer-contact-label"><?=htmlspecialcharsbx((string)get_info('lunch_break'))?></div>
                    </div>
                </div>

                <!-- Кнопка обратной связи -->
				<div class="d-flex mb-3">
				<a href="<?=htmlspecialcharsbx($feedbackUrl)?>" class="footer-btn">
					<nobr><i class="bi bi-chat-dots"></i> Написать обращение</nobr>
                </a>
				</div>
            </div>
        </div>
    </div>

    <!-- Нижняя часть подвала (копирайт, политика, счетчики) -->
    <div class="footer-bottom">
        <div class="container py-3">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <span class="footer-text-light">
                        <i class="bi bi-c-circle me-1" style="color: #3498db;"></i>
                        <?=htmlspecialcharsbx((string)get_info('copyright_year_from'))?>–<?=date('Y')?>
                        <?=htmlspecialcharsbx((string)get_info('org_full_name'))?>
                    </span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="<?=htmlspecialcharsbx($privacyPolicyUrl)?>" class="footer-bottom-link">Политика конфиденциальности</a>
                    <a href="<?=htmlspecialcharsbx($privacyPolicyUrl)?>" class="footer-bottom-link">Использование cookie</a>
                    <span class="footer-dot"></span>
                    <span class="footer-dot footer-dot-secondary"></span>
                </div>
            </div>

            <!-- Дополнительная строка со счетчиками и госсимволикой -->
            <div class="row mt-2">
                <div class="col-12 d-flex flex-wrap justify-content-center
            justify-content-md-end align-items-center gap-2 gap-md-3">
                    <span class="footer-badge">
                        <i class="bi bi-eye"></i> 0+
                    </span>
                    <span class="footer-badge">
                        <i class="bi bi-activity"></i> Яндекс.Метрика
                    </span>
                    <span class="footer-badge">
                        <i class="bi bi-shield-check"></i> Госуслуги
                    </span>
                </div>
            </div>
			<div class="row mt-3">
                <div class="col-12 d-flex justify-content-end">
                    <a class="footer-developer" href="<?=htmlspecialcharsbx($developerUrl)?>" target="_blank" rel="noopener noreferrer">
                        <span class="footer-developer__logo-wrap">
                            <img class="footer-developer__logo" src="<?=SITE_TEMPLATE_PATH . htmlspecialcharsbx($developerLogoUrl)?>" width="48" height="49" alt="Логотип Центра стратегического развития">
                        </span>
                        <span class="footer-developer__content">
                            <span class="footer-developer__label">Сайт разработан</span>
                            <span class="footer-developer__name">
                                <?=htmlspecialcharsbx((string)get_info('developer_name'))?>
                            </span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php

$cookieMessage = site_plain_text(get_info('org_name'))
    . ' использует файлы cookie для работы и аналитики.';

$APPLICATION->IncludeComponent(
	"cookie.manager",
    ".default",
    array(
        "CACHE_TIME" => "86400",
        "CACHE_TYPE" => "A",
        "CHECK_TIMEOUT" => "3000",
        "EXPIRE_DAYS" => "365",
        "MESSAGE" => $cookieMessage,
        "POLICY_URL" => $privacyPolicyUrl,
        "POLICY_TEXT" => "Политика обработки персональных данных",
        "PRESETS" => "style4",
        "SHOW_SETTINGS" => "Y" // Показывать настройки
    )
);

?>

</body>
</html>
