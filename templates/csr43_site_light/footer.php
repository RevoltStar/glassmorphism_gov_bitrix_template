<?php
$excludedPages = [
    '/',
    '/news',
    '/news/',
    '/contacts', 
    '/contacts/'
];

$currentPage = $APPLICATION->GetCurPage();
$isExcludedPage = false;

// Особый случай для корневой страницы
if ($currentPage === '/') {
    $isExcludedPage = in_array('/', $excludedPages);
} else {
    // Проверяем, начинается ли текущая страница с любого из префиксов
    foreach ($excludedPages as $excluded) {
        // Для исключения "/" проверяем отдельно, чтобы не совпало с любым путем
        if ($excluded === '/') {
            continue; // "/" уже обработали выше
        }
        
        // Убираем закрывающий слэш для сравнения (если есть)
        $excludedClean = rtrim($excluded, '/');
        $currentPageClean = rtrim($currentPage, '/');
        
        // Проверяем, начинается ли текущая страница с исключенного префикса
        if (str_starts_with($currentPageClean, $excludedClean)) {
            $isExcludedPage = true;
            break;
        }
    }
}
$is404Error = defined('ERROR_404');

if (!$isExcludedPage && !$is404Error): ?>
    </div> <!-- Закрывающий для col-lg-8 -->
    <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "main_2025_sidemenu",
            Array(
                "ALLOW_MULTI_SELECT" => "Y",
                "CHILD_MENU_TYPE" => "right",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => "",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "right",
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
					<?
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
                    <img src="/images/logo_csr.png" alt="КОГБУ ЦСРИРиСУ" class="footer-logo me-3">
                    <div>
                        <h5 class="fw-bold mb-1">КОГБУ ЦСРИРиСУ</h5>
                        <p class="small footer-text-muted mb-0">Центр стратегического развития информационных ресурсов и систем управления</p>
                    </div>
                </div>
                <p class="small footer-text-muted mb-0">
                    Обеспечиваем цифровую трансформацию государственных услуг Кировской области
                </p>
            </div>

            <!-- Контакты -->
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-3">Контакты</h6>
                <div class="mb-2">
                    <i class="bi bi-telephone me-2 text-primary"></i>
                    <a href="tel:<?
								$APPLICATION->IncludeComponent(
    								"bitrix:main.include",
    								"",
    								array(
        								"AREA_FILE_SHOW" => "file",
        								"AREA_FILE_SUFFIX" => "inc",
        								"COMPOSITE_FRAME_MODE" => "A",
        								"COMPOSITE_FRAME_TYPE" => "AUTO",
        								"EDIT_TEMPLATE" => "",
        								"PATH" => SITE_TEMPLATE_PATH . "/include/phone_e164.php"
    								)
								);?>" class="text-decoration-none text-light"><?
																				$APPLICATION->IncludeComponent(
    																				"bitrix:main.include",
    																				"",
    																				array(
        																				"AREA_FILE_SHOW" => "file",
        																				"AREA_FILE_SUFFIX" => "inc",
        																				"COMPOSITE_FRAME_MODE" => "A",
        																				"COMPOSITE_FRAME_TYPE" => "AUTO",
        																				"EDIT_TEMPLATE" => "",
        																				"PATH" => SITE_TEMPLATE_PATH . "/include/phone.php"
    																				)
																				);
					?></a>
                </div>
                <div class="mb-2">
                    <i class="bi bi-envelope me-2 text-primary"></i>
                    <a href="mailto:<?
									$APPLICATION->IncludeComponent(
    									"bitrix:main.include",
    									"",
    									array(
        									"AREA_FILE_SHOW" => "file",
        									"AREA_FILE_SUFFIX" => "inc",
        									"COMPOSITE_FRAME_MODE" => "A",
        									"COMPOSITE_FRAME_TYPE" => "AUTO",
        									"EDIT_TEMPLATE" => "",
        									"PATH" => SITE_TEMPLATE_PATH . "/include/email.php"
    									)
									);?>" class="text-decoration-none text-light"><?
																				$APPLICATION->IncludeComponent(
    																				"bitrix:main.include",
    																				"",
    																				array(
        																				"AREA_FILE_SHOW" => "file",
        																				"AREA_FILE_SUFFIX" => "inc",
        																				"COMPOSITE_FRAME_MODE" => "A",
        																				"COMPOSITE_FRAME_TYPE" => "AUTO",
        																				"EDIT_TEMPLATE" => "",
        																				"PATH" => SITE_TEMPLATE_PATH . "/include/email.php"
    																				)
																				);
					?></a>
                </div>
                <div class="mb-2">
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <span class="footer-text-muted"><?
									$APPLICATION->IncludeComponent(
    									"bitrix:main.include",
    									"",
    									array(
        									"AREA_FILE_SHOW" => "file",
        									"AREA_FILE_SUFFIX" => "inc",
        									"COMPOSITE_FRAME_MODE" => "A",
        									"COMPOSITE_FRAME_TYPE" => "AUTO",
        									"EDIT_TEMPLATE" => "",
        									"PATH" => SITE_TEMPLATE_PATH . "/include/postal.php"
    									)
									);?>, г. Киров, <?
									$APPLICATION->IncludeComponent(
    									"bitrix:main.include",
    									"",
    									array(
        									"AREA_FILE_SHOW" => "file",
        									"AREA_FILE_SUFFIX" => "inc",
        									"COMPOSITE_FRAME_MODE" => "A",
        									"COMPOSITE_FRAME_TYPE" => "AUTO",
        									"EDIT_TEMPLATE" => "",
        									"PATH" => SITE_TEMPLATE_PATH . "/include/address.php"
    									)
									);?></span>
                </div>
                <div class="mb-2">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <span class="footer-text-muted">Пн-Чт: 9:00-18:00</span>
                </div>
				<div class="mb-2">
                    <i class="bi bi-clock me-2 text-primary"></i>
                    <span class="footer-text-muted">Пт: 9:00-17:00</span>
                </div>
            </div>

            <!-- Быстрые ссылки -->
            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h6 class="fw-bold mb-3">Разделы</h6>
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
                <h6 class="fw-bold mb-3">Информация</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="/documents/" class="text-decoration-none footer-text-muted small">Документы</a>
                    </li>
                    <li class="mb-2">
						<a href="/directions/anti_corruption/" class="text-decoration-none footer-text-muted small">Противодействие коррупции</a>
                    </li>
                    <li class="mb-2">
                        <a href="/documents/personal_data_processing_policies/" class="text-decoration-none footer-text-muted small">Политика конфиденциальности</a>
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
    &copy; <?php echo date('Y'); ?> КОГБУ ЦСРИРиСУ. Все права защищены.
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
<?

$cookie_message = 
"Сайт КОГБУ ЦСРИРиСУ использует файлы cookie для работы и аналитики. 
<br><br>
<a target=\"_blank\" href=\"/documents/personal_data_processing_policies/\">Политика обработки персональных данных</a>";

$APPLICATION->IncludeComponent(
	"cookie.manager",
    ".default",
    array(
        "CACHE_TIME" => "86400",
        "CACHE_TYPE" => "A",
        "CHECK_TIMEOUT" => "3000",
        "EXPIRE_DAYS" => "365",
        "MESSAGE" => $cookie_message,
        "PRESETS" => "style4",
        "SHOW_SETTINGS" => "Y", // Показывать настройки
        "SHOW_PD_CONSENT" => "Y" // Показывать согласие на ПДн
    )
);
?>

</body>
</html>