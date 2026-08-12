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
$currentUrl = 'https://csr43.ru' . $APPLICATION->GetCurPage();

// Формируем данные для микроразметки BreadcrumbList
$breadcrumbItems = [];
$position = 1;
$baseUrl = 'https://csr43.ru';

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

// HTML для хлебных крошек
$breadcrumbHtml = '
<ul class="breadcrumb">';
foreach ($arResult as $index => $item) {
	$iconClass = 'bi bi-file-earmark-text';
    if ($index === 0) {
		$iconClass = 'bi bi-house-fill';
        // Первый элемент с иконкой дома
        $breadcrumbHtml .= '
    <li>
        <a href="' . htmlspecialchars($item['LINK']) . '">
            <span class="'. $iconClass .'"></span>
        </a>
    </li>';
    } elseif ($index === count($arResult) - 1) {
		$iconClass='bi bi-arrow-down-right';
        // Последний элемент
        $lastLink = $item['LINK'] ?: $APPLICATION->GetCurPage();
        $breadcrumbHtml .= '
    <li>
        <a href="'. htmlspecialchars($lastLink) .'">
            <span class="' . $iconClass . '"></span> ' . htmlspecialchars($item['TITLE']) . '
        </a>
    </li>';
    } else {
        $iconClass="bi bi-file-earmark-text";
        $breadcrumbHtml .= '
    <li>
        <a href="' . htmlspecialchars($item['LINK']) . '">
            <span class="' . $iconClass . '"></span> ' . htmlspecialchars($item['TITLE']) . '
        </a>
    </li>';
    }
}

$breadcrumbHtml .= '
</ul>';

// Возвращаем HTML хлебных крошек и JSON-LD микроразметку
return $breadcrumbHtml . '
<script type="application/ld+json">' . $breadcrumbJson . '</script>';
?>