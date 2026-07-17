<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();
?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?$APPLICATION->ShowTitle();?></title>
	<!-- Предпочтительно SVG -->
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<!-- Фолбэк для старых браузеров -->
	<link rel="icon" type="image/x-icon" href="/favicon.ico">
	<?
		/*$APPLICATION->SetPageProperty(
    	'canonical',
    	get_canonical_link(["PAGEN_1", "PAGEN_2"])
	);*/
	?>
		<?/*
					$APPLICATION->IncludeComponent(
					"bitrix:main.include",
					"",
					array(
						"AREA_FILE_SHOW" => "file",	// Показывать включаемую область
						"AREA_FILE_SUFFIX" => "inc",	// Суффикс имени файла включаемой области
						"COMPOSITE_FRAME_MODE" => "A",	// Голосование шаблона компонента по умолчанию
						"COMPOSITE_FRAME_TYPE" => "AUTO",	// Содержимое компонента
						"EDIT_TEMPLATE" => "",	// Шаблон области по умолчанию
						"PATH" => "/include/schema_org.php"
					)
					);*/
				?>

	<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-5.3.0.min.css"); ?>
	<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/bootstrap-5.3.0.min.js"); ?>

	<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/jquery-3.6.0.min.js"); ?>
	<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-icons-1.11.1.min.css"); ?>
	<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/fontawesome.css"); ?>

	<? $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/fancybox5.css"); ?>
	<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/fancybox5.js"); ?>

	<? $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/script.js"); ?>
	<?$APPLICATION->ShowHead();?>
<script src="https://max.gosuslugi.ru/robot-max/iframe/boot/boot.js?saveState=true&position=bottom-
left&region=33000000000&platform=default_iframe"></script>
</head>
<body>
	<a href="#main-content" class="skip-link">Перейти к основному содержимому</a>
	<div id="panel">
		<?php $APPLICATION->ShowPanel();?>
	</div>
<!-- Верхняя служебная шапка (контакты, поиск) -->
<header class="header-top">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-5 d-flex align-items-center gap-3">
                <!-- Кнопка слабовидящих -->
					<?
									$APPLICATION->IncludeComponent(
    									"bvi2.version", // Пространство имен и имя компонента
    									".default",          // Имя шаблона
    									array()              // Параметры (пустой массив, так как их нет)
									);
								?>

                <!-- Бейдж официальности -->
                <span class="official-badge d-none d-md-inline-block">
                    <i class="bi bi-patch-check-fill me-1" aria-hidden="true"></i>
                    Официальный сайт
                </span>
            </div>

            <div class="col-md-6 col-lg-7 d-flex justify-content-end align-items-center gap-3">
                <!-- Контакты -->
                <a href="tel:<?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "PATH" => "/include/phone_e164.php"
                    )
                );?>" class="top-contact d-none d-md-flex">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    <span class="d-none d-lg-inline"><?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "PATH" => "/include/phone.php"
                    )
                );?></span>
                </a>
                
                <a href="mailto:<?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "PATH" => "/include/email.php"
                    )
                );?>" class="top-contact d-none d-md-flex">
                    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                    <span class="d-none d-lg-inline"><?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "PATH" => "/include/email.php"
                    )
                );?></span>
                </a>
                
                <!-- Поиск -->
                <form class="d-flex search-form w-100" role="search" action="/search/" method="get">
                    <div class="input-group">
                        <input id="searchInput" 
                               class="form-control" 
                               type="search" 
                               name="q"
                               placeholder="Поиск по сайту" 
                               aria-label="Поиск по сайту">
						<label for="searchInput" class="visually-hidden">Поиск по сайту</label>
                        <button id="searchButton" class="btn" type="submit" aria-label="Найти">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>

<!-- Блок с логотипом и названием организации -->
<div class="main-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-auto">
                <a href="/" class="logo-link" aria-label="На главную">
                    <img src="/images/gerb_kirov_it.png" 
                         alt="Герб Кировской области — официальный символ региона" 
                         class="ministry-logo"
                         width="80"
                         height="80">
                </a>
            </div>
            
            <div class="col">
				<?php if ($APPLICATION->GetCurPage(false) === "/"): ?>
    			<h1 class="ministry-title">
        			Министерство информационных технологий и связи
   				</h1>
				<?php else: ?>
    			<div class="ministry-title">
        			Министерство информационных технологий и связи
    			</div>
				<?php endif; ?>
                <div class="ministry-sub">
                    Кировской области
                </div>
            </div>
            
            <!-- Декоративные элементы -->
            <div class="col-auto d-none d-md-block text-end">
                <div class="decorative-elements" aria-hidden="true">
					<a href="https://digital.gov.ru/target/naczionalnyj-proekt-ekonomika-dannyh-i-czifrovaya-transformacziya-gosudarstva">
						<img src="/images/economics_of_data.png" alt="Символ проекта Экономика данных и цифровая трансформация государства">
					</a>
                    <div class="mt-2 fw-semibold" style="color: #1e3a5f; font-size: 0.95rem;">
                        <i class="bi bi-geo-alt me-1" style="color: #3498db;" aria-hidden="true"></i>
                        Кировская область
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Навигационное меню -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top" 
     aria-label="Основное меню сайта">
    <div class="container">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "top",
                Array(
                    "ROOT_MENU_TYPE" => "top",
                    "MAX_LEVEL" => "4",
                    "CHILD_MENU_TYPE" => "right",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "Y",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array()
                )
            );
            ?>
    </div>
</nav>
<?if ($APPLICATION->GetCurPage() != '/'):?>
<nav class="breadcrumb mb-1 mt-1" aria-label="Хлебные крошки">
	<div class="container">
	<?
		$APPLICATION->IncludeComponent("bitrix:breadcrumb","breadcrumb",Array(
			"START_FROM" => "0", 
			"PATH" => "", 
			"SITE_ID" => "mi"
		));
	?>
	</div>
</nav>
<?endif?>
	<main id="main-content"<?if ($APPLICATION->GetCurPage() != '/'):?> class="container" <?endif?>>
		<?php
			$currentPage = $APPLICATION->GetCurPage();
			if ($currentPage != '/' && (strpos($currentPage, '/news/') !== 0 || $currentPage == "/news/")):
			?>
			<h1 class="mb-5"><?=$APPLICATION->ShowTitle()?></h1>
		<?php endif; ?>
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
    			<div class="row mb-5">
       	 			<div class="col-lg-8 order-2 order-lg-1 h-100">
		<?php endif ?>