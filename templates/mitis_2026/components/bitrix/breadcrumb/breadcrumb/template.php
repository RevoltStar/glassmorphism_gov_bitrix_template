<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;

//delayed function must return a string
if(empty($arResult))
	return "";
$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()
$css = $APPLICATION->GetCSSArray();
if(!is_array($css) || !in_array("/bitrix/css/main/font-awesome.css", $css))
{
	//$strReturn .= '<link href="'.CUtil::GetAdditionalFileURL("/bitrix/css/main/font-awesome.css").'" type="text/css" rel="stylesheet" />'."\n";
}

// Получаем текущий URL для последнего элемента
$baseUrl = rtrim((string)get_info('site_url'), '/');
$currentUrl = $baseUrl . $APPLICATION->GetCurPage();

// Формируем данные для микроразметки BreadcrumbList
$breadcrumbItems = [];
$position = 1;
foreach ($arResult as $index => $item) {
    // Формируем абсолютный URL
    if ($index === count($arResult) - 1) {
        // Для последнего элемента используем текущий URL
        $absoluteLink = $currentUrl;
    } else {
        $absoluteLink = $baseUrl . $item['LINK'];
        if (strpos($item['LINK'], 'http') === 0) {
            $absoluteLink = $item['LINK'];
        }
    }
    
    // Для микроразметки используем очищенные заголовки
    $title = htmlspecialchars_decode($item['TITLE']);
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
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);

// HTML для хлебных крошек в стиле образца
$breadcrumbHtml = '
<ol class="breadcrumb glass-breadcrumb m-0">';

foreach ($arResult as $index => $item) {
    if ($index === count($arResult) - 1) {
        // Последний элемент (активный)
        $lastLink = $item['LINK'] ?: $APPLICATION->GetCurPage();
        $breadcrumbHtml .= '
    <li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($item['TITLE']) . '</li>';
    } else {
        // Обычные элементы с ссылками
        $breadcrumbHtml .= '
    <li class="breadcrumb-item"><a href="' . htmlspecialchars($item['LINK']) . '" class="glass-breadcrumb-link">' . htmlspecialchars($item['TITLE']) . '</a></li>';
    }
}

$breadcrumbHtml .= '
</ol>';

// Возвращаем HTML хлебных крошек и JSON-LD микроразметку
return $breadcrumbHtml . '
<script type="application/ld+json">' . $breadcrumbJson . '</script>';
?>
