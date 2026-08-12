<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$excludedPages = get_info('layout_excluded_pages', []);
$currentPage = site_string($APPLICATION->GetCurPage(), '/');
$isExcludedPage = site_path_is_excluded($currentPage, $excludedPages);
$is404Error = defined('ERROR_404');
$footerLogoUrl = site_template_image_url(get_info('logo', ''));
$footerPhoneUrl = site_url(
    'tel:' . site_string(get_info('phone_e164', '')),
    '#',
    ['tel'],
    false
);
$footerEmailUrl = site_url(
    'mailto:' . site_string(get_info('email', '')),
    '#',
    ['mailto'],
    false
);
$privacyPolicyUrl = site_url(get_info('privacy_policy_path', ''), '#');
$footerMenu1Title = site_plain_text(get_info('menu_footer_1_title', 'Разделы'));
$footerMenu2Title = site_plain_text(get_info('menu_footer_2_title', 'Информация'));

if (!$isExcludedPage && !$is404Error): ?>
    </div> <!-- Закрывающий для col-lg-8 -->
    <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
        <?php $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "side",
            Array(
                "ALLOW_MULTI_SELECT" => "Y",
                "CHILD_MENU_TYPE" => site_menu_type(get_info('menu_side_child_type', 'right'), 'right'),
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => "",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => site_menu_type(get_info('menu_side_root_type', 'right'), 'right'),
                "USE_EXT" => "Y"
            )
        );?>
    </div> <!-- Закрывающий для col-lg-4 -->
</div> <!-- Закрывающий для row -->
<?php endif ?>
</main>
<!-- Версия для печати -->
		<?php
			$APPLICATION->IncludeComponent(
    			'print.version',
    			'',
    			array(
        			'PAGE_URL' => '', // текущая страница
        			"BUTTON_TEXT_PRINT" => "Версия для печати",
        			"BUTTON_TEXT_NORMAL" => "К обычной версии", 
        			"BUTTON_CLASS" => "btn btn-light border",
        			"OPEN_IN_NEW_WINDOW" => "N"
    			)
			);
		?>
<!-- Подвал -->
<footer class="footer bg-dark text-light pb-5">
					<?php
					$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					array(
						"AREA_FILE_SHOW" => "file",	// Показывать включаемую область
						"AREA_FILE_SUFFIX" => "inc",	// Суффикс имени файла включаемой области
						"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
						"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
						"EDIT_TEMPLATE" => "",	// Шаблон области по умолчанию
						"PATH" => SITE_TEMPLATE_PATH . "/include/gosuslugi_widget.php"
					)
					);
				?>
    <div class="container">
        <!-- Основной контент подвала -->
        <div class="row py-4 py-lg-5">
            <!-- Логотип и описание -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-start mb-3">
                    <?php if ($footerLogoUrl !== ''): ?>
                        <img src="<?=htmlspecialcharsbx($footerLogoUrl)?>"
                             alt="<?=htmlspecialcharsbx(site_plain_text(get_info('org_full_name', '')))?>"
                             class="footer-logo me-3"
                             loading="lazy"
                             decoding="async">
                    <?php endif; ?>
                    <div>
                        <h5 class="fw-bold mb-1">
                            <?=htmlspecialcharsbx(site_plain_text(get_info('org_short_name', '')))?>
                        </h5>
                        <p class="small footer-text-muted mb-0">
                            <?=htmlspecialcharsbx(site_plain_text(get_info('org_full_name', '')))?>
                        </p>
                    </div>
                </div>
                <p class="small footer-text-muted mb-0">
                    <?=htmlspecialcharsbx(site_plain_text(get_info('org_description', '')))?>
                </p>
            </div>

            <!-- Контакты -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-3">Контакты</h6>
                <div class="mb-2">
                    <i class="bi bi-telephone me-2 text-primary"></i>
                    <a href="<?=htmlspecialcharsbx($footerPhoneUrl)?>" class="text-decoration-none text-light">
                        <?=htmlspecialcharsbx(site_plain_text(get_info('phone', '')))?>
                    </a>
                </div>
                <div class="mb-2">
                    <i class="bi bi-envelope me-2 text-primary"></i>
                    <a href="<?=htmlspecialcharsbx($footerEmailUrl)?>" class="text-decoration-none text-light">
                        <?=htmlspecialcharsbx(site_plain_text(get_info('email', '')))?>
                    </a>
                </div>
                <div class="mb-2">
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <span class="footer-text-muted">
                        <?=htmlspecialcharsbx(site_plain_text(get_info('postal_code', '')))?>,
                        <?=htmlspecialcharsbx(site_plain_text(get_info('address', '')))?>
                    </span>
                </div>
                <div class="mb-2">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <span class="footer-text-muted"><?=htmlspecialcharsbx(site_plain_text(get_info('workdays_primary', '')))?></span>
                </div>
				<div class="mb-2">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <span class="footer-text-muted"><?=htmlspecialcharsbx(site_plain_text(get_info('workdays_secondary', '')))?></span>
                </div>
            </div>

            <!-- Быстрые ссылки -->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h6 class="fw-bold mb-3"><?=htmlspecialcharsbx($footerMenu1Title)?></h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="/about/" class="text-decoration-none footer-text-muted small">Об учреждении</a>
                    </li>
                    <li class="mb-2">
                        <a href="/directions/paid_service/" class="text-decoration-none footer-text-muted small">Платные услуги</a>
                    </li>
                    <li class="mb-2">
                        <a href="/news/" class="text-decoration-none footer-text-muted small">Новости</a>
                    </li>
                    <li class="mb-2">
                        <a href="/contacts/" class="text-decoration-none footer-text-muted small">Контакты</a>
                    </li>
					<li class="mb-2">
						<a href="/about/vacancy/" class="text-decoration-none footer-text-muted small">Вакантные места</a>
                    </li>
                </ul>
            </div>

            <!-- Дополнительная информация -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3"><?=htmlspecialcharsbx($footerMenu2Title)?></h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="/documents/" class="text-decoration-none footer-text-muted small">Документы</a>
                    </li>
                    <li class="mb-2">
						<a href="/directions/anti_corruption/" class="text-decoration-none footer-text-muted small">Противодействие коррупции</a>
                    </li>
                    <li class="mb-2">
                        <a href="<?=htmlspecialcharsbx($privacyPolicyUrl)?>" class="text-decoration-none footer-text-muted small">Политика конфиденциальности</a>
                    </li>
                    <li class="mb-2">
                        <a href="/sitemap/" class="text-decoration-none footer-text-muted small">Карта сайта</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Нижняя часть подвала -->
<div class="border-top border-secondary pt-3">
    <div class="row align-items-center">
        <!-- Копирайт -->
        <div class="col-md-6 mb-2 mb-md-0">
           <p class="small footer-text-muted mb-0">
    &copy; <?=htmlspecialcharsbx((string)get_info('copyright_year_from'))?>–<?=date('Y')?>
    <?=htmlspecialcharsbx(site_plain_text(get_info('org_short_name', '')))?>. Все права защищены.
</p>
        </div>

        <!-- Социальные сети и доп. ссылки -->
        <div class="col-md-6">
            <div class="d-flex justify-content-md-end justify-content-center align-items-center">
                <!-- Социальные сети -->
                <div class="d-flex me-4">
                    <!--<a href="https://vk.com/csrkirov" target="_blank" class="text-decoration-none  footer-text-muted me-3" aria-label="ВКонтакте" title="ВКонтакте">
                        <i class="bi bi-vimeo" aria-hidden="true"></i>
                    </a>-->
						<a href="https://vk.com/csrkirov" target="_blank" class="text-decoration-none text-white footer-text-muted me-3" aria-label="ВКонтакте" title="Перейти в группу во ВКонтакте">
							<img src="/images/vk.svg" class="footer-social-icon" alt="Логотип ВКонтакте">
                    	</a>
                </div>

            </div>
        </div>
    </div>
</div>
    </div>
</footer>
<?php

$cookieMessage = site_plain_text(get_info('org_name', ''))
    . ' использует файлы cookie для работы и аналитики.';

$APPLICATION->IncludeComponent(
	"csr43:cookie.manager",
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
