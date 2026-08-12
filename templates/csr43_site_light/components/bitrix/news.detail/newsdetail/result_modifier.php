<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

global $APPLICATION;

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$currentNewsId = max(0, (int)($arResult['ID'] ?? 0));
$iblockId = max(0, (int)($arResult['IBLOCK_ID'] ?? $arParams['IBLOCK_ID'] ?? 0));
$properties = is_array($arResult['PROPERTIES'] ?? null) ? $arResult['PROPERTIES'] : [];
$newsName = site_plain_text($arResult['~NAME'] ?? $arResult['NAME'] ?? '');
$showCounter = max(0, (int)($arResult['SHOW_COUNTER'] ?? 0));
$iblockAvailable = Loader::includeModule('iblock');

$view = [
    'id' => $currentNewsId,
    'name' => $newsName,
    'date' => site_plain_text($arResult['DISPLAY_ACTIVE_FROM'] ?? ''),
    'show_counter' => $showCounter,
    'categories' => [],
    'preview_html' => site_safe_html($arResult['~PREVIEW_TEXT'] ?? $arResult['PREVIEW_TEXT'] ?? ''),
    'detail_html' => site_safe_html($arResult['~DETAIL_TEXT'] ?? $arResult['DETAIL_TEXT'] ?? ''),
    'gallery' => [],
    'downloads' => [],
    'navigation' => ['prev' => null, 'next' => null],
    'json_ld' => [],
];
$arResult['NEWS_DETAIL'] = $view;

$categoryProperty = is_array($properties['category'] ?? null) ? $properties['category'] : [];
$categoryNames = is_array($categoryProperty['VALUE'] ?? null)
    ? $categoryProperty['VALUE']
    : [$categoryProperty['VALUE'] ?? ''];
$categoryXmlIds = is_array($categoryProperty['VALUE_XML_ID'] ?? null)
    ? $categoryProperty['VALUE_XML_ID']
    : [$categoryProperty['VALUE_XML_ID'] ?? ''];
foreach ($categoryNames as $index => $categoryName) {
    $categoryName = site_plain_text($categoryName);
    $xmlId = site_string($categoryXmlIds[$index] ?? '');
    if ($categoryName === '' || $xmlId === '') {
        continue;
    }

    $categoryUrl = site_url('/news/category/' . rawurlencode($xmlId) . '/', '');
    if ($categoryUrl === '') {
        continue;
    }

    $view['categories'][] = [
        'name' => site_plain_text($categoryName),
        'url' => $categoryUrl,
    ];
}

$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
$videoExtensions = ['mp4', 'avi', 'mov', 'webm'];
$videoThumbnailUrl = site_url(
    SITE_TEMPLATE_PATH . '/components/bitrix/news.detail/newsdetail/images/video-thumbnail.jpg',
    ''
);
$seenFileIds = [];
$seenFileUrls = [];

$resolveFile = static function (mixed $file): ?array {
    if (is_array($file)) {
        $fileInfo = $file;
    } else {
        $fileId = max(0, (int)$file);
        $fileInfo = $fileId > 0 ? CFile::GetFileArray($fileId) : null;
    }

    if (!is_array($fileInfo)) {
        return null;
    }

    $url = site_url($fileInfo['SRC'] ?? null, '');
    if ($url === '') {
        return null;
    }

    $filename = site_plain_text($fileInfo['FILE_NAME'] ?? '');
    $originalName = site_plain_text($fileInfo['ORIGINAL_NAME'] ?? $filename);
    $extension = strtolower((string)pathinfo($filename !== '' ? $filename : $originalName, PATHINFO_EXTENSION));

    return [
        'id' => max(0, (int)($fileInfo['ID'] ?? 0)),
        'url' => $url,
        'filename' => $filename,
        'original_name' => $originalName,
        'description' => site_plain_text($fileInfo['DESCRIPTION'] ?? ''),
        'extension' => $extension,
        'size' => max(0, (int)($fileInfo['FILE_SIZE'] ?? 0)),
    ];
};

$addFile = static function (mixed $source, string $fallbackCaption = '') use (
    &$view,
    &$seenFileIds,
    &$seenFileUrls,
    $resolveFile,
    $imageExtensions,
    $videoExtensions,
    $videoThumbnailUrl
): void {
    $file = $resolveFile($source);
    if ($file === null) {
        return;
    }

    if (
        ($file['id'] > 0 && isset($seenFileIds[$file['id']]))
        || isset($seenFileUrls[$file['url']])
    ) {
        return;
    }
    if ($file['id'] > 0) {
        $seenFileIds[$file['id']] = true;
    }
    $seenFileUrls[$file['url']] = true;

    $caption = $file['description'] !== '' ? $file['description'] : site_plain_text($fallbackCaption);
    if (in_array($file['extension'], $imageExtensions, true)) {
        $view['gallery'][] = [
            'url' => $file['url'],
            'thumbnail_url' => $file['url'],
            'caption' => $caption,
            'type' => 'image',
            'fancybox_type' => 'image',
        ];
        return;
    }

    if (in_array($file['extension'], $videoExtensions, true)) {
        $view['gallery'][] = [
            'url' => $file['url'],
            'thumbnail_url' => $videoThumbnailUrl,
            'caption' => $caption,
            'type' => 'video',
            'fancybox_type' => 'html5video',
        ];
        return;
    }

    $iconMap = [
        'pdf' => 'bi-file-earmark-pdf',
        'doc' => 'bi-file-earmark-word',
        'docx' => 'bi-file-earmark-word',
        'xls' => 'bi-file-earmark-excel',
        'xlsx' => 'bi-file-earmark-excel',
        'zip' => 'bi-file-earmark-zip',
        'rar' => 'bi-file-earmark-zip',
        '7z' => 'bi-file-earmark-zip',
    ];
    $view['downloads'][] = [
        'url' => $file['url'],
        'name' => $file['original_name'] !== '' ? $file['original_name'] : $file['filename'],
        'extension' => $file['extension'],
        'size' => $file['size'],
        'display_size' => $file['size'] > 0 ? site_plain_text(CFile::FormatSize($file['size'])) : '',
        'icon' => $iconMap[$file['extension']] ?? 'bi-file-earmark',
    ];
};

if ($iblockAvailable) {
    $addFile($arResult['PREVIEW_PICTURE'] ?? null, $newsName . ' — ' . GetMessage('CSR43_LIGHT_NEWSDETAIL_PREVIEW_IMAGE'));
    $addFile($arResult['DETAIL_PICTURE'] ?? null, $newsName . ' — ' . GetMessage('CSR43_LIGHT_NEWSDETAIL_DETAIL_IMAGE'));

    $additionalFilesProperty = is_array($properties['additional_files'] ?? null)
        ? $properties['additional_files']
        : [];
    foreach (site_string_list($additionalFilesProperty['VALUE'] ?? []) as $fileId) {
        $addFile($fileId, $newsName);
    }
}

if ($currentNewsId > 0 && $iblockId > 0 && $iblockAvailable) {
    $neighborResult = CIBlockElement::GetList(
        ['ACTIVE_FROM' => 'DESC', 'SORT' => 'ASC', 'ID' => 'DESC'],
        [
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'ACTIVE_DATE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
        ],
        false,
        ['nPageSize' => 1, 'nElementID' => $currentNewsId],
        ['ID', 'NAME', 'DETAIL_PAGE_URL', 'ACTIVE_FROM', 'SORT']
    );

    if (is_object($neighborResult)) {
        $detailUrl = site_string($arParams['DETAIL_URL'] ?? '');
        if ($detailUrl !== '' && method_exists($neighborResult, 'SetUrlTemplates')) {
            $neighborResult->SetUrlTemplates($detailUrl);
        }

        $neighbors = [];
        while ($neighbor = $neighborResult->GetNext()) {
            if (is_array($neighbor)) {
                $neighbors[] = $neighbor;
            }
        }

        $currentIndex = null;
        foreach ($neighbors as $index => $neighbor) {
            if (max(0, (int)($neighbor['ID'] ?? 0)) === $currentNewsId) {
                $currentIndex = $index;
                break;
            }
        }

        if ($currentIndex !== null) {
            foreach (['prev' => $currentIndex - 1, 'next' => $currentIndex + 1] as $key => $index) {
                $neighbor = is_array($neighbors[$index] ?? null) ? $neighbors[$index] : [];
                $url = site_url($neighbor['DETAIL_PAGE_URL'] ?? null, '');
                $name = site_plain_text($neighbor['~NAME'] ?? $neighbor['NAME'] ?? '');
                if ($url !== '' && $name !== '') {
                    $view['navigation'][$key] = ['url' => $url, 'name' => $name];
                }
            }
        }
    }
}

$siteUrl = rtrim(site_url(get_info('site_url'), '', ['http', 'https'], false), '/');
$currentPath = site_url($APPLICATION->GetCurPage(), '/');
$currentUrl = $siteUrl !== '' ? site_url($siteUrl . $currentPath, '', ['http', 'https'], false) : '';

if ($siteUrl !== '' && $currentUrl !== '' && $newsName !== '') {
    $toIsoDate = static function (mixed $value): string {
        $timestamp = strtotime(site_string($value));
        return $timestamp !== false ? date('c', $timestamp) : '';
    };
    $published = $toIsoDate($arResult['ACTIVE_FROM'] ?? '');
    if ($published === '') {
        $published = $toIsoDate($arResult['DATE_CREATE'] ?? '');
    }
    $modified = $toIsoDate($arResult['TIMESTAMP_X'] ?? '');
    if ($modified === '') {
        $modified = $published;
    }

    $descriptionSource = site_string($arResult['~PREVIEW_TEXT'] ?? $arResult['PREVIEW_TEXT'] ?? '');
    $description = html_entity_decode(strip_tags($descriptionSource), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $description = trim((string)preg_replace('/\s+/u', ' ', $description));
    if (mb_strlen($description) > 200) {
        $description = rtrim(mb_substr($description, 0, 200)) . '…';
    }

    $imageUrls = [];
    foreach ($view['gallery'] as $media) {
        if (!is_array($media) || ($media['type'] ?? '') !== 'image') {
            continue;
        }
        $mediaUrl = site_string($media['url'] ?? '');
        $absoluteUrl = site_is_external_http_url($mediaUrl)
            ? site_url($mediaUrl, '', ['http', 'https'], false)
            : site_url($siteUrl . '/' . ltrim($mediaUrl, '/'), '', ['http', 'https'], false);
        if ($absoluteUrl !== '') {
            $imageUrls[$absoluteUrl] = true;
        }
    }
    if ($imageUrls === []) {
        $logoPath = site_template_image_url(get_info('logo', ''));
        $logoUrl = $logoPath !== ''
            ? site_url($siteUrl . '/' . ltrim($logoPath, '/'), '', ['http', 'https'], false)
            : '';
        if ($logoUrl !== '') {
            $imageUrls[$logoUrl] = true;
        }
    }

    $organization = ['@id' => $siteUrl . '/#organization'];
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $newsName,
        'url' => $currentUrl,
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $currentUrl],
        'author' => $organization,
        'publisher' => $organization,
    ];
    if ($published !== '') {
        $jsonLd['datePublished'] = $published;
    }
    if ($modified !== '') {
        $jsonLd['dateModified'] = $modified;
    }
    if ($description !== '') {
        $jsonLd['description'] = $description;
    }
    if ($imageUrls !== []) {
        $jsonLd['image'] = array_keys($imageUrls);
    }
    $articleSections = array_values(array_unique(array_column($view['categories'], 'name')));
    if ($articleSections !== []) {
        $jsonLd['articleSection'] = $articleSections;
    }
    if ($showCounter > 0) {
        $jsonLd['interactionStatistic'] = [
            '@type' => 'InteractionCounter',
            'interactionType' => 'https://schema.org/ViewAction',
            'userInteractionCount' => $showCounter,
        ];
    }
    $view['json_ld'] = $jsonLd;
}

$arResult['NEWS_DETAIL'] = $view;
