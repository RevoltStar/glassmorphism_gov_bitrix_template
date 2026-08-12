<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$normalizePath = static function (mixed $value): string {
    $path = parse_url(site_string($value), PHP_URL_PATH);
    if (!is_string($path) || $path === '') { return '/'; }
    $path = '/' . ltrim($path, '/');
    $path = preg_replace('#/+#', '/', $path);
    $path = is_string($path) ? preg_replace('#/index\.php$#', '/', $path) : '/';
    return $path === '/' ? '/' : rtrim((string)$path, '/') . '/';
};
$currentPath = $normalizePath($APPLICATION->GetCurPage(false));
$items = [];
$currentTopIndex = null;
$activeIndex = null;
$activeLength = -1;
$activeIsExact = false;
$selectedFallbackIndex = null;

foreach ($arResult as $item) {
    if (!is_array($item)) { continue; }
    $depth = max(1, (int)($item['DEPTH_LEVEL'] ?? 1));
    if ($depth === 1) {
        $text = site_plain_text($item['TEXT'] ?? '');
        if ($text === '') { $currentTopIndex = null; continue; }
        $url = site_url($item['LINK'] ?? null, '');
        $rawLink = trim(site_string($item['LINK'] ?? ''));
        $path = '';
        $index = count($items);
        $items[] = [
            'id' => 'item-' . ($index + 1),
            'text' => $text,
            'url' => $url,
            'icon_class' => site_css_classes((is_array($item['PARAMS'] ?? null) ? $item['PARAMS']['EXT_CLASS'] ?? '' : ''), ''),
            'child_count' => 0,
            'is_current' => false,
            'is_in_active_path' => false,
        ];
        $currentTopIndex = $index;
        if (!empty($item['SELECTED']) && $selectedFallbackIndex === null) { $selectedFallbackIndex = $index; }
        if ($url !== '' && preg_match('#^[a-z][a-z0-9+.-]*://#i', $rawLink) !== 1 && !str_starts_with($rawLink, '#')) {
            $path = $normalizePath($rawLink);
            $isMatch = $path === '/' ? $currentPath === '/' : ($currentPath === $path || str_starts_with($currentPath, $path));
            if ($isMatch && strlen($path) > $activeLength) {
                $activeIndex = $index;
                $activeLength = strlen($path);
                $activeIsExact = $currentPath === $path;
            }
        }
    } elseif ($depth === 2 && $currentTopIndex !== null) {
        $items[$currentTopIndex]['child_count']++;
    }
}

if ($activeIndex === null) {
    $activeIndex = $selectedFallbackIndex;
    $activeIsExact = false;
}
if ($activeIndex !== null && isset($items[$activeIndex])) {
    $items[$activeIndex]['is_current'] = $activeIsExact;
    $items[$activeIndex]['is_in_active_path'] = true;
}
$arResult['SIDE_MENU'] = [
    'items' => $items,
    'force_desktop' => ($arParams['FORCE_DESKTOP'] ?? 'N') === 'Y',
];
