<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$maxLevel = min(10, max(1, (int)($arParams['MAX_LEVEL'] ?? 1)));
$normalizePath = static function (mixed $value): string {
    $path = parse_url(site_string($value), PHP_URL_PATH);
    if (!is_string($path) || $path === '') {
        return '/';
    }
    $path = '/' . ltrim($path, '/');
    $path = preg_replace('#/+#', '/', $path);
    $path = is_string($path) ? preg_replace('#/index\.php$#', '/', $path) : '/';
    return $path === '/' ? '/' : rtrim((string)$path, '/') . '/';
};
$currentPath = $normalizePath($APPLICATION->GetCurPage(false));
$flatNodes = [];
$activeId = null;
$activeLength = -1;
$activeIsExact = false;
$selectedFallbackId = null;
$selectedFallbackDepth = -1;
$counter = 0;

foreach ($arResult as $item) {
    if (!is_array($item)) {
        continue;
    }
    $depth = min($maxLevel, max(1, (int)($item['DEPTH_LEVEL'] ?? 1)));
    if ((int)($item['DEPTH_LEVEL'] ?? 1) > $maxLevel) {
        continue;
    }
    $text = site_plain_text($item['TEXT'] ?? '');
    if ($text === '') {
        continue;
    }
    $url = site_url($item['LINK'] ?? null, '');
    $id = 'item-' . (++$counter);
    $path = '';
    $rawLink = trim(site_string($item['LINK'] ?? ''));
    if ($url !== '' && preg_match('#^[a-z][a-z0-9+.-]*://#i', $rawLink) !== 1 && !str_starts_with($rawLink, '#')) {
        $path = $normalizePath($rawLink);
        $isMatch = $path === '/' ? $currentPath === '/' : ($currentPath === $path || str_starts_with($currentPath, $path));
        if ($isMatch && strlen($path) > $activeLength) {
            $activeId = $id;
            $activeLength = strlen($path);
            $activeIsExact = $currentPath === $path;
        }
    }
    if (!empty($item['SELECTED']) && $depth > $selectedFallbackDepth) {
        $selectedFallbackId = $id;
        $selectedFallbackDepth = $depth;
    }
    $flatNodes[] = [
        'id' => $id,
        'depth' => $depth,
        'text' => $text,
        'url' => $url,
        'path' => $path,
        'is_current' => false,
        'is_in_active_path' => false,
        'children' => [],
    ];
}

if ($activeId === null) {
    $activeId = $selectedFallbackId;
    $activeIsExact = false;
}
$tree = [];
$stack = [];
foreach ($flatNodes as $node) {
    while ($stack !== [] && count($stack) >= $node['depth']) {
        array_pop($stack);
    }
    if ($stack === []) {
        $tree[] = $node;
        $stack[] = &$tree[array_key_last($tree)];
    } else {
        $parent = &$stack[array_key_last($stack)];
        $parent['children'][] = $node;
        $stack[] = &$parent['children'][array_key_last($parent['children'])];
        unset($parent);
    }
}
unset($stack);

$markActivePath = static function (array &$nodes, ?string $targetId, bool $targetIsExact) use (&$markActivePath): bool {
    foreach ($nodes as &$node) {
        $isCurrent = $targetId !== null && $node['id'] === $targetId;
        $hasActiveChild = $markActivePath($node['children'], $targetId, $targetIsExact);
        $node['is_current'] = $isCurrent && $targetIsExact;
        $node['is_in_active_path'] = $isCurrent || $hasActiveChild;
        if ($node['is_in_active_path']) {
            unset($node);
            return true;
        }
    }
    unset($node);
    return false;
};
$markActivePath($tree, $activeId, $activeIsExact);

$arResult['TOP_MENU'] = ['items' => $tree];
