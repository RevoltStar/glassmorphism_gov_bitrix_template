<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$currentPage = site_string($APPLICATION->GetCurPage(), '/');
$isHomePage = $currentPage === '/';
$headerLogoUrl = site_template_image_url(get_info('logo', ''));
$nationalProjectUrl = site_url(
    get_info('national_project_url', ''),
    '',
    ['http', 'https'],
    false
);
$nationalProjectLogoUrl = site_template_image_url(get_info('national_project_logo', ''));
$nationalProjectLogoAlt = site_plain_text(get_info('national_project_logo_alt', ''));
$searchPath = site_url(get_info('search_path', ''), '');
$isTitleExcludedPage = false;

foreach (site_string_list(get_info('title_excluded_pages', [])) as $excludedTitlePage) {
    $includeTitle = str_starts_with($excludedTitlePage, '!');
    $excludedTitlePage = $includeTitle ? substr($excludedTitlePage, 1) : $excludedTitlePage;
    $excludeChildren = str_ends_with($excludedTitlePage, '/*');
    $excludedTitlePath = $excludeChildren ? substr($excludedTitlePage, 0, -2) : $excludedTitlePage;
    $excludedTitlePath = '/' . ltrim((string)parse_url($excludedTitlePath, PHP_URL_PATH), '/');
    $excludedTitlePath = $excludedTitlePath === '/' ? '/' : rtrim($excludedTitlePath, '/');
    $normalizedCurrentPage = '/' . ltrim((string)parse_url($currentPage, PHP_URL_PATH), '/');
    $normalizedCurrentPage = $normalizedCurrentPage === '/' ? '/' : rtrim($normalizedCurrentPage, '/');

    if (
        (!$excludeChildren && $normalizedCurrentPage === $excludedTitlePath)
        || (
            $excludeChildren
            && $excludedTitlePath !== '/'
            && str_starts_with($normalizedCurrentPage, $excludedTitlePath . '/')
        )
    ) {
        $isTitleExcludedPage = !$includeTitle;
    }
}
?>
<!DOCTYPE html>
<html lang="<?=htmlspecialcharsbx(site_string(LANGUAGE_ID))?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php $APPLICATION->ShowTitle(); ?></title>

	<?php
		$APPLICATION->SetPageProperty(
    	'canonical',
    	get_canonical_link(["PAGEN_1", "PAGEN_2"])
	);
	?>

	<?php require dirname(__DIR__, 2) . '/include/schema_org.php'; ?>

	<link rel="icon" type="image/svg+xml" href="/favicon.svg">
	<link rel="icon" type="image/x-icon" href="/favicon.ico">
	<?php
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-5.3.0.min.css");
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/bootstrap-icons-1.11.1.min.css");
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/fancybox5.css");
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/ui/light.css");

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/bootstrap-5.3.0.min.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/fancybox5.js");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/script.js");
?>
	<?php $APPLICATION->ShowHead();?>

</head>
<body>
	<a href="#main-content" class="skip-link"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_SKIP_TO_CONTENT'))?></a>
	<div id="panel">
		<?php $APPLICATION->ShowPanel();?>
	</div>
<header class="header">
	<?php
$APPLICATION->IncludeComponent(
    "bitrix:news.list", 
    "government_sites",
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
                <div class="col-lg-6 col-md-12 mb-2 mb-lg-0">
                    <div class="d-flex justify-content-center justify-content-lg-start" role="button">
						<button
							id="government_website_button"
							type="button"
							data-government-sites-toggle
							class="text-center text-lg-start btn btn-light border"
							aria-haspopup="dialog"
        					aria-controls="full-menu"
        					aria-expanded="false"
							>
	                            <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GOVERNMENT_SITES'))?> <?=htmlspecialcharsbx(site_plain_text(get_info('region_name_genitive', '')))?>
                        </button>
                    </div>
                </div>
                
                <div class="col-lg-6 col-md-12">
                    <div class="row g-2 align-items-center">
                        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-5">
                            <div class="d-flex justify-content-center justify-content-lg-end m-0 p-0">
								<?php
									$APPLICATION->IncludeComponent(
										"csr43:bvi.version",
										".default",
										array()
									);
								?>
                            </div>
                        </div>
                        
                        <div class="col-xl-8 col-lg-7 col-md-6 col-sm-7">
                            <?php if ($searchPath !== ''): ?>
                            <form class="input-group input-group-sm" role="search" action="<?=htmlspecialcharsbx($searchPath)?>" method="get">
                                <label class="visually-hidden" for="header-search-query"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_SEARCH_LABEL'))?></label>
                                <input type="search" class="form-control" name="q" id="header-search-query" placeholder="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_SEARCH_PLACEHOLDER'))?>">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search" aria-hidden="true"></i>
                                    <span class="d-none d-sm-inline"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_SEARCH_SUBMIT'))?></span>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="logo-menu-div">
    <div class="container">
        <div class="row align-items-center py-3">
            <div class="col-xl-6 col-lg-6 col-md-12 col-12 mb-3 mb-md-0">
                <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start text-center text-md-start">
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 mb-2 mb-md-0 align-items-center">
                        <a href="/" id="header-main-link" title="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_HOME_LINK_TITLE'))?>" class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 align-items-center text-decoration-none">
                            <?php if ($headerLogoUrl !== ''): ?>
                                <div class="flex-shrink-0">
                                    <img class="logo-header img-fluid"
                                         src="<?=htmlspecialcharsbx($headerLogoUrl)?>"
                                         alt="<?=htmlspecialcharsbx(site_plain_text(get_info('org_full_name', '')))?>"
                                         loading="lazy"
                                         decoding="async">
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-grow-1 text-dark">
                                <div class="organization-name">
                                    <strong class="d-block fs-6 fs-md-5">
                                        <?=htmlspecialcharsbx(site_plain_text(get_info('org_short_name', '')))?>
                                    </strong>
                                    <span class="d-block fs-7 fs-md-6">
                                        <?=htmlspecialcharsbx(site_plain_text(get_info('org_full_name', '')))?>
                                    </span>
                                </div>
                            </div>
                        </a>

                        <?php if ($nationalProjectLogoUrl !== ''): ?>
                            <?php if ($nationalProjectUrl !== ''): ?>
                                <a target="_blank"
                                   rel="noopener noreferrer"
                                   title="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NATIONAL_PROJECT_TITLE'))?>"
                                   href="<?=htmlspecialcharsbx($nationalProjectUrl)?>"
                                   class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 align-items-center text-decoration-none">
                            <?php endif; ?>
                            <div class="flex-shrink-0 d-none d-md-block">
                                <img class="logo-header img-fluid"
                                     src="<?=htmlspecialcharsbx($nationalProjectLogoUrl)?>"
                                     alt="<?=htmlspecialcharsbx($nationalProjectLogoAlt)?>"
                                     loading="lazy"
                                     decoding="async">
                            </div>
                            <?php if ($nationalProjectUrl !== ''): ?>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6 col-lg-6 col-md-12 col-12">
				<div class="d-flex justify-content-center justify-content-lg-end">
                    <div class="navbar navbar-expand-lg p-0">
						<?php
							$APPLICATION->IncludeComponent("bitrix:menu","top",Array(
								"ROOT_MENU_TYPE" => site_menu_type(get_info('menu_top_root_type', 'top'), 'top'),
								"MAX_LEVEL" => "3",
								"CHILD_MENU_TYPE" => site_menu_type(get_info('menu_top_child_type', 'right'), 'right'),
							"USE_EXT" => "Y",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "Y",
							"MENU_CACHE_TYPE" => "N", 
							"MENU_CACHE_TIME" => "3600", 
							"MENU_CACHE_USE_GROUPS" => "Y", 
							"MENU_CACHE_GET_VARS" => "" 
							)
						);?>
                    </div>
				</div>
            </div>
        </div>
    </div>
</div>
<?php if (!$isHomePage): ?>
<nav class="breadcrumb-section" aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_BREADCRUMBS_LABEL'))?>">
	<div class="container">
		<?php
			$APPLICATION->IncludeComponent(
				"bitrix:breadcrumb",
				"breadcrumb",
				Array(
					"START_FROM" => "0", 
					"PATH" => "", 
					"SITE_ID" => SITE_ID
				)
			);
		?>
	</div>
</nav>
<?php endif; ?>

</header>
		<main id="main-content"<?php if (!$isHomePage):?> class="container"<?php endif?>>
			<?php if (!$isTitleExcludedPage): ?>
				<h1 class="page-name bvi-speech"><?=$APPLICATION->ShowTitle(false)?></h1>
			<?php endif; ?>
			<?php
				$excludedPages = get_info('layout_excluded_pages', []);
				$isExcludedPage = site_path_is_excluded($currentPage, $excludedPages);
				$is404Error = defined('ERROR_404');

			if (!$isExcludedPage && !$is404Error): ?>
    			<div class="row mb-5">
       	 			<div class="col-lg-8 order-2 order-lg-1">
		<?php endif ?>
