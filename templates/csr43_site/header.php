<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

$headerPhoneUrl = site_url('tel:' . site_string(get_info('phone_e164', '')), '#', ['tel'], false);
$headerEmailUrl = site_url('mailto:' . site_string(get_info('email', '')), '#', ['mailto'], false);
$headerSearchPath = site_url(get_info('search_path', '/search/'), '/search/');
$headerLogoUrl = site_string(get_info('logo', ''), '');
$nationalProjectUrl = site_url(get_info('national_project_url', ''), '#');
$nationalProjectLogo = site_string(get_info('national_project_logo', ''), '');
?>
<!DOCTYPE html>
<html lang="<?=htmlspecialcharsbx(site_string(LANGUAGE_ID))?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php $APPLICATION->ShowTitle();?></title>
	<!-- Предпочтительно SVG -->
	<link rel="icon" type="image/svg+xml" href="<?= SITE_TEMPLATE_PATH ?>/images/favicon.svg">
	<!-- Фолбэк для старых браузеров -->
	<link rel="icon" type="image/x-icon" href="<?= SITE_TEMPLATE_PATH ?>/images/favicon.ico">
	<?php
		$APPLICATION->SetPageProperty(
    	'canonical',
    	get_canonical_link(["PAGEN_1", "PAGEN_2", "q"])
	);
	?>
	<?php include __DIR__ . '/include/schema_org.php'; ?>

	<?php  $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-5.3.0.min.css"); ?>
	<?php  $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/bootstrap-5.3.0.min.js"); ?>

	<?php  $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/jquery-3.6.0.min.js"); ?>
	<?php  $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-icons-1.11.1.min.css"); ?>
	<?php  $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/fontawesome.css"); ?>

	<?php  $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/fancybox5.css"); ?>
	<?php  $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/ui/glass.css"); ?>
	<?php  $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/fancybox5.js"); ?>

	<?php  $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/script.js"); ?>
	<?php $APPLICATION->ShowHead();?>
	<?php if ((bool)get_info('show_max_robot', false)): ?>
		<script src="https://max.gosuslugi.ru/robot-max/iframe/boot/boot.js?saveState=true&amp;position=bottom-left&amp;region=33000000000&amp;platform=default_iframe"></script>
	<?php endif; ?>
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
					<?php
									$APPLICATION->IncludeComponent(
    									"bvi.version", // Пространство имен и имя компонента
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
                <a href="<?=htmlspecialcharsbx($headerPhoneUrl)?>" class="top-contact d-none d-md-flex">
                    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                    <span class="d-none d-xl-inline"><?=htmlspecialcharsbx((string)get_info('phone'))?></span>
                </a>

                <a href="<?=htmlspecialcharsbx($headerEmailUrl)?>" class="top-contact d-none d-md-flex">
                    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                    <span class="d-none d-xl-inline"><?=htmlspecialcharsbx((string)get_info('email'))?></span>
                </a>

                <!-- Поиск -->
                <form class="d-flex search-form" role="search" action="<?=htmlspecialcharsbx($headerSearchPath)?>" method="get">
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
                    <img src="<?=SITE_TEMPLATE_PATH . "/images/" . htmlspecialcharsbx($headerLogoUrl)?>"
                         alt="<?=htmlspecialcharsbx((string)get_info('org_full_name'))?>"
                         class="ministry-logo"
                         width="80"
                         height="80">
                </a>
            </div>

            <div class="col">
				<?php if ($APPLICATION->GetCurPage(false) === "/"): ?>
    			<h1 class="ministry-title">
					<?=htmlspecialcharsbx((string)get_info('org_name'))?>
   				</h1>
				<?php else: ?>
    			<div class="ministry-title">
					<?=htmlspecialcharsbx((string)get_info('org_name'))?>
    			</div>
				<?php endif; ?>
                <div class="ministry-sub">
                    <?=htmlspecialcharsbx((string)get_info('region_name_genitive'))?>
                </div>
            </div>

            <!-- Декоративные элементы -->
            <div class="col-auto d-none d-md-block text-end">
                <div class="decorative-elements" aria-hidden="true">
					<a href="<?=htmlspecialcharsbx($nationalProjectUrl)?>">
						<img src="<?=SITE_TEMPLATE_PATH . "/images/" . htmlspecialcharsbx($nationalProjectLogo)?>" alt="<?=htmlspecialcharsbx((string)get_info('national_project_logo_alt'))?>">
					</a>
                    <div class="mt-2 fw-semibold" style="color: #1e3a5f; font-size: 0.95rem;">
                        <i class="bi bi-geo-alt me-1" style="color: #3498db;" aria-hidden="true"></i>
                        <?=htmlspecialcharsbx((string)get_info('region_name'))?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Уведомление о текущем обновлении сайта -->
<section class="site-development-notice" role="note" aria-label="Информация об обновлении сайта">
    <div class="container">
        <div class="site-development-notice__inner">
            <i class="bi bi-tools site-development-notice__icon" aria-hidden="true"></i>
            <div>
                <div class="site-development-notice__title">Сайт находится в процессе обновления</div>
                <p class="site-development-notice__text">
                    Продолжается перенос и актуализация материалов. Отдельные страницы могут содержать
                    неполную или временно неактуальную информацию.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Навигационное меню -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top"
     aria-label="Основное меню сайта">
    <div class="container">
            <?php
            $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "top",
                Array(
                    "ROOT_MENU_TYPE" => site_menu_type(get_info('menu_top_root_type', 'top'), 'top'),
                    "MAX_LEVEL" => "4",
                    "CHILD_MENU_TYPE" => site_menu_type(get_info('menu_top_child_type', 'right'), 'right'),
                    "USE_EXT" => "N",
                    "MENU_CACHE_TYPE" => "A",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => ""
                )
            );
            ?>
    </div>
</nav>
<?php if ($APPLICATION->GetCurPage() != '/'):?>
<nav class="breadcrumb mb-1 mt-1" aria-label="Хлебные крошки">
	<div class="container">
	<?php
		$APPLICATION->IncludeComponent("bitrix:breadcrumb","breadcrumb",Array(
			"START_FROM" => "0",
			"PATH" => "",
			"SITE_ID" => SITE_ID
		));
	?>
	</div>
</nav>
<?php endif?>
	<main id="main-content"<?php if ($APPLICATION->GetCurPage() != '/'):?> class="container" <?php endif?>>
		<?php
			$currentPage = $APPLICATION->GetCurPage();
			if ($currentPage != '/' && (strpos($currentPage, '/news/') !== 0 || $currentPage == "/news/")):
			?>
			<h1 class="mb-5"><?=$APPLICATION->ShowTitle(false)?></h1>
		<?php endif; ?>
		<?php
			$excludedPages = get_info('layout_excluded_pages', []);

$currentPage = site_string($APPLICATION->GetCurPage(), '/');
$isExcludedPage = site_path_is_excluded($currentPage, $excludedPages);
			$is404Error = defined('ERROR_404');

			if (!$isExcludedPage && !$is404Error): ?>
    			<div class="row mb-5">
       	 			<div class="col-lg-8 order-2 order-lg-1 h-100">
		<?php endif ?>
