<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arResult
 */

global $APPLICATION;

if (!is_array($arResult)) {
    return '';
}

$arResult = array_values(array_filter($arResult, 'is_array'));
if ($arResult === []) {
    return '';
}

$baseUrl = rtrim(
    site_url(get_info('site_url'), '', ['http', 'https'], false),
    '/'
);
$currentPath = site_url($APPLICATION->GetCurPage(), '/');
$currentUrl = $baseUrl !== '' ? $baseUrl . $currentPath : '';
$lastIndex = count($arResult) - 1;
$items = [];

foreach ($arResult as $index => $item) {
    $title = htmlspecialchars_decode(
        site_string($item['TITLE'] ?? ''),
        ENT_QUOTES | ENT_HTML5
    );
    $title = site_plain_text($title);
    $link = site_url($item['LINK'] ?? null, '');

    $items[] = [
        'title' => $title,
        'link' => $link,
        'is_current' => $index === $lastIndex,
    ];
}

$breadcrumbHtml = '<ol class="breadcrumb csr43-light-surface site-breadcrumb">';

foreach ($items as $item) {
    $escapedTitle = htmlspecialcharsbx($item['title']);

    if ($item['is_current']) {
        $breadcrumbHtml .= '<li class="breadcrumb-item active" aria-current="page">'
            . $escapedTitle
            . '</li>';
        continue;
    }

    $breadcrumbHtml .= '<li class="breadcrumb-item">';
    if ($item['link'] !== '') {
        $breadcrumbHtml .= '<a class="site-breadcrumb__link" href="'
            . htmlspecialcharsbx($item['link'])
            . '">'
            . $escapedTitle
            . '</a>';
    } else {
        $breadcrumbHtml .= '<span class="site-breadcrumb__text">'
            . $escapedTitle
            . '</span>';
    }
    $breadcrumbHtml .= '</li>';
}

$breadcrumbHtml .= '</ol>';

$breadcrumbSchemaItems = [];
$canBuildSchema = $baseUrl !== '' && $currentUrl !== '';

foreach ($items as $index => $item) {
    if (!$canBuildSchema) {
        break;
    }

    if ($item['is_current']) {
        $absoluteUrl = $currentUrl;
    } elseif (site_is_external_http_url($item['link'])) {
        $absoluteUrl = $item['link'];
    } elseif (str_starts_with($item['link'], '/')) {
        $absoluteUrl = $baseUrl . $item['link'];
    } elseif (
        str_starts_with($item['link'], '?')
        || str_starts_with($item['link'], '#')
    ) {
        $absoluteUrl = $currentUrl . $item['link'];
    } else {
        $absoluteUrl = '';
    }

    if ($absoluteUrl === '') {
        $canBuildSchema = false;
        break;
    }

    $breadcrumbSchemaItems[] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => $item['title'],
        'item' => $absoluteUrl,
    ];
}

if (!$canBuildSchema) {
    return $breadcrumbHtml;
}

$breadcrumbJson = json_encode(
    [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbSchemaItems,
    ],
    JSON_UNESCAPED_UNICODE
    | JSON_UNESCAPED_SLASHES
    | JSON_HEX_TAG
    | JSON_HEX_AMP
    | JSON_HEX_APOS
    | JSON_HEX_QUOT
    | JSON_INVALID_UTF8_SUBSTITUTE
);

if (!is_string($breadcrumbJson)) {
    return $breadcrumbHtml;
}

return $breadcrumbHtml
    . '<script type="application/ld+json">'
    . $breadcrumbJson
    . '</script>';
