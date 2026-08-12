<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if (empty($arResult) || !is_array($arResult))
	return "";
$arResult = array_values(array_filter($arResult, 'is_array'));
if ($arResult === []) {
    return '';
}
$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();
if(!is_array($css) || !in_array("/bitrix/css/main/font-awesome.css", $css))
{
	//$strReturn .= '<link href="'.CUtil::GetAdditionalFileURL("/bitrix/css/main/font-awesome.css").'" type="text/css" rel="stylesheet" />'."\n";
}

// Получаем текущий URL для последнего элемента
$baseUrl = rtrim(site_url(get_info('site_url'), '', ['http', 'https'], false), '/');
$currentPath = site_url($APPLICATION->GetCurPage(), '/');
$currentUrl = $baseUrl . $currentPath;

// Формируем данные для микроразметки BreadcrumbList
$breadcrumbItems = [];
$position = 1;
foreach ($arResult as $index => $item) {
    $itemLink = site_url($item['LINK'] ?? null, '/');
    // Формируем абсолютный URL
    if ($index === count($arResult) - 1) {
        // Для последнего элемента используем текущий URL
        $absoluteLink = $currentUrl;
    } else {
        $absoluteLink = $baseUrl . $itemLink;
        if (site_is_external_http_url($itemLink)) {
            $absoluteLink = $itemLink;
        }
    }

    // Для микроразметки используем очищенные заголовки
    $title = htmlspecialchars_decode(site_string($item['TITLE'] ?? ''));
    $title = strip_tags($title);
    $title = trim($title);

    $breadcrumbItems[] = [
        'position' => $position,
        'name' => $title,
        'item' => $absoluteLink
    ];
    $position++;
}

// Микроразметка BreadcrumbList
$breadcrumbJson = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => array_map(function($item) {
        return [
            '@type' => 'ListItem',
            'position' => $item['position'],
            'name' => $item['name'],
            'item' => $item['item']
        ];
    }, $breadcrumbItems)
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE);

// HTML для хлебных крошек в стиле образца
$breadcrumbHtml = '
<ol class="breadcrumb csr43-glass-surface csr43-glass-card--interactive site-breadcrumb m-0">';

foreach ($arResult as $index => $item) {
    $itemTitle = site_string($item['TITLE'] ?? '');
    $itemLink = site_url($item['LINK'] ?? null);
    if ($index === count($arResult) - 1) {
        // Последний элемент (активный)
        $breadcrumbHtml .= '
    <li class="breadcrumb-item active csr43-glass-surface" aria-current="page">' . htmlspecialcharsbx($itemTitle) . '</li>';
    } else {
        // Обычные элементы с ссылками
        $breadcrumbHtml .= '
    <li class="breadcrumb-item"><a href="' . htmlspecialcharsbx($itemLink) . '" class="site-breadcrumb__link">' . htmlspecialcharsbx($itemTitle) . '</a></li>';
    }
}

$breadcrumbHtml .= '
</ol>';

// Возвращаем HTML хлебных крошек и JSON-LD микроразметку
return $breadcrumbHtml . '
<script type="application/ld+json">' . $breadcrumbJson . '</script>';
?>
