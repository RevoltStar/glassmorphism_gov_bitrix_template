<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();
?><!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?$APPLICATION->ShowHead();?>
	<title><?php $APPLICATION->ShowTitle(); ?></title>

	<?
		$APPLICATION->SetPageProperty(
    	'canonical',
    	get_canonical_link(["PAGEN_1", "PAGEN_2"])
	);
	?>

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
						"PATH" => SITE_TEMPLATE_PATH . "/include/schema_org.php"
					)
);
				?>

	<!-- Предпочтительно SVG -->
	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<!-- Фолбэк для старых браузеров -->
	<link rel="icon" type="image/x-icon" href="/favicon.ico">
	<?
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-5.3.0.min.css");
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-icons-1.11.1.min.css");
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/fancybox5.css");

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/jquery-3.6.0.min.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/bootstrap-5.3.0.min.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/fancybox5.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/script.js");
?>

	<?/*
<script src="https://max.gosuslugi.ru/robot-max/iframe/boot/boot.js?saveState=true&position=bottom-
left&initialState=collapsed&region=33000000000&platform=default_iframe"></script>
*/
?>
</head>
<body>
	<a href="#main-content" class="skip-link">Перейти к основному содержимому</a>
	<div id="panel">
		<?php $APPLICATION->ShowPanel();?>
	</div>
<header class="header">
<!-- Списки сайтов министерств (изначально скрыто) -->
	<?
$APPLICATION->IncludeComponent(
    "bitrix:news.list", 
    "main_2025_informationresources", 
    array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "N",
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_DATE" => "N",
        "DISPLAY_NAME" => "N",
        "DISPLAY_PICTURE" => "N",
        "DISPLAY_PREVIEW_TEXT" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "FIELD_CODE" => array(
            0 => "",
            1 => "",
        ),
        "FILTER_NAME" => "",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "IBLOCK_ID" => "6",
        "IBLOCK_TYPE" => "content",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "INCLUDE_SUBSECTIONS" => "N",
        "MESSAGE_404" => "",
        "NEWS_COUNT" => "9999",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => ".default",
        "PAGER_TITLE" => "Новости",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "PROPERTY_CODE" => array(
            0 => "LINK",
            1 => "SECTION_NAME",
            2 => "",
        ),
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SORT_BY1" => "SORT",
        "SORT_BY2" => "NAME",
        "SORT_ORDER1" => "ASC",
        "SORT_ORDER2" => "ASC",
        "STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO"
    ),
    false
);?>
    <div class="special-div">
        <div class="container">
            <div class="row align-items-center">
                <!-- Левая колонка -->
                <div class="col-lg-6 col-md-12 mb-2 mb-lg-0">
                    <div class="d-flex justify-content-center justify-content-lg-start" role="button">
                        <button 
							id="government_website_button"
							class="text-center text-lg-start btn btn-light border"
							aria-haspopup="dialog"
        					aria-controls="full-menu"
        					aria-expanded="false"
							>
                            Сайты органов власти Кировской области
                        </button>
                    </div>
                </div>
                
                <!-- Правая колонка -->
                <div class="col-lg-6 col-md-12">
                    <div class="row g-2 align-items-center">
                        <!-- Версия для слабовидящих -->
                        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-5">
                            <div class="d-flex justify-content-center justify-content-lg-end m-0 p-0">
								<?
									/*$APPLICATION->IncludeComponent(
										"vision:vision.special",
										"main_2025_visionspecial",
										Array(
										),
										false
									);*/
									$APPLICATION->IncludeComponent(
    									"bvi.version", // Пространство имен и имя компонента
    									".default",          // Имя шаблона
    									array()              // Параметры (пустой массив, так как их нет)
									);
								?>
                            </div>
                        </div>
                        
                        <!-- Поиск -->
                        <div class="col-xl-8 col-lg-7 col-md-6 col-sm-7">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Поиск..." aria-label="Поиск" id="searchInput">
                                <button class="btn btn-primary" type="button" id="searchButton" aria-label="Найти">
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                    <span class="d-none d-sm-inline">Найти</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Лого и меню -->
<div class="logo-menu-div">
    <div class="container">
        <div class="row align-items-center py-3">
            <!-- Логотипы и название -->
            <div class="col-xl-6 col-lg-6 col-md-12 col-12 mb-3 mb-md-0">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start text-center text-md-start">
                    <!-- Логотипы и название -->
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 mb-2 mb-md-0 align-items-center">
                        <!-- Общая ссылка для логотипа ЦСР и названия -->
                        <a href="/" id="header-main-link" title="Вернуться на Главную" class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 align-items-center text-decoration-none">
                            <!-- Логотип ЦСР -->
                            <div class="flex-shrink-0">
                                <img class="logo-header img-fluid" src="/images/logo_csr.png" alt="КОГБУ ЦСРИРиСУ" style="max-height: 80px;" loading="lazy">
                            </div>
                            
                            <!-- Название организации -->
                            <div class="flex-grow-1 text-dark">
                                <div class="organization-name">
                                    <strong class="d-block fs-6 fs-md-5">КОГБУ</strong>
                                    <span class="d-block fs-7 fs-md-6">Центр стратегического развития</span>
                                    <span class="d-block fs-7 fs-md-6">информационных ресурсов</span>
                                    <span class="d-block fs-7 fs-md-6">и систем управления</span>
                                </div>
                            </div>
                        </a>

                        <!-- Логотип Экономика данных (скрывается на мобильных) -->
						<a target="_blank" title="Перейти на страницу проекта «Экономика данных»" href="https://digital.gov.ru/target/naczionalnyj-proekt-ekonomika-dannyh-i-czifrovaya-transformacziya-gosudarstva" class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 align-items-center text-decoration-none">
							<div class="flex-shrink-0 d-none d-md-block">
                            	<img class="logo-header img-fluid" src="/images/economics_of_data.png" alt="Национальный проект Экономика Данных" style="max-height: 80px;" loading="lazy">
                        	</div>
						</a>
                    </div>
                </div>
            </div>
            
            <!-- Меню -->
            <div class="col-xl-6 col-lg-6 col-md-12 col-12">
				<div class="d-flex justify-content-center justify-content-lg-end">
                    <nav class="navbar navbar-expand-lg p-0" aria-label="Основная навигация сайта">
						<?
						$APPLICATION->IncludeComponent("bitrix:menu","main_2025_mainmenu",Array(
							"ROOT_MENU_TYPE" => "top", 
							"MAX_LEVEL" => "3", 
							"CHILD_MENU_TYPE" => "right", 
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "Y",
							"MENU_CACHE_TYPE" => "N", 
							"MENU_CACHE_TIME" => "3600", 
							"MENU_CACHE_USE_GROUPS" => "Y", 
							"MENU_CACHE_GET_VARS" => "" 
							)
						);?>
                    </nav>
				</div>
            </div>
        </div>
    </div>
</div>
<nav class="breadcrumb-section" aria-label="Хлебные крошки" <?if ($APPLICATION->GetCurPage() == '/'):?>style="display:none;"<?endif?>>
	<div class="container">
		<?
			$APPLICATION->IncludeComponent(
				"bitrix:breadcrumb",
				"main_2025_breadcrumb",
				Array(
					"START_FROM" => "0", 
					"PATH" => "", 
					"SITE_ID" => "s1" 
				)
			);
		?>
	</div>
</nav>

</header>
	<main id="main-content" <?if ($APPLICATION->GetCurPage() != '/'):?> class="container" <?endif?>>
		<?if ($APPLICATION->GetCurPage() != '/'):?>
			<h1 class="page-name bvi-speech"><?=$APPLICATION->ShowTitle(false)?></h1>
		<?endif?>
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
       	 			<div class="col-lg-8 order-2 order-lg-1">
		<?php endif ?>