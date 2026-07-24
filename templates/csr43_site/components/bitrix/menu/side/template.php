<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$items = $arResult ?? [];
if (!is_array($items) || $items === []) {
    return;
}

$forceDesktop = ($arParams['FORCE_DESKTOP'] ?? 'N') === 'Y';
$childCount = [];
$topLevelPaths = [];
$currentParentIndex = null;
$currentParentLink = null;

$normalizePath = static function (mixed $url): string {
    $path = parse_url(site_string($url), PHP_URL_PATH);

    if (!is_string($path) || $path === '') {
        return '/';
    }

    $path = '/' . ltrim($path, '/');
    $path = preg_replace('#/+#', '/', $path);
    $path = preg_replace('#/index\.php$#', '/', $path);

    return $path === '/' ? '/' : rtrim($path, '/') . '/';
};

$currentPath = $normalizePath($APPLICATION->GetCurPage(false));
$activeIndex = null;
$activeLength = -1;
$activeIsParent = true;
$selectedFallbackIndex = null;
$selectedFallbackLength = -1;
$selectedFallbackIsParent = true;

foreach ($items as $index => $item) {
    if (!is_array($item)) {
        continue;
    }

    $depth = max(1, (int)($item['DEPTH_LEVEL'] ?? 1));
    $link = site_url($item['LINK'] ?? null);

    if ($depth === 1) {
        $currentParentIndex = $index;
        $currentParentLink = $link;
        $childCount[$index] = 0;

        $itemLink = trim(site_string($item['LINK'] ?? ''));
        if (
            $itemLink === ''
            || $itemLink === '#'
            || preg_match('#^[a-z][a-z0-9+\-.]*://#i', $itemLink)
        ) {
            continue;
        }

        $itemPath = $normalizePath($itemLink);
        $topLevelPaths[$index] = $itemPath;
        $itemLength = strlen($itemPath);
        $itemIsParent = !empty($item['IS_PARENT']);

        if (
            !empty($item['SELECTED'])
            && (
                $itemLength > $selectedFallbackLength
                || (
                    $itemLength === $selectedFallbackLength
                    && !$itemIsParent
                    && $selectedFallbackIsParent
                )
            )
        ) {
            $selectedFallbackIndex = $index;
            $selectedFallbackLength = $itemLength;
            $selectedFallbackIsParent = $itemIsParent;
        }

        $isPathMatch = $itemPath === '/'
            ? $currentPath === '/'
            : $currentPath === $itemPath || str_starts_with($currentPath, $itemPath);

        if (
            $isPathMatch
            && (
                $itemLength > $activeLength
                || (
                    $itemLength === $activeLength
                    && !$itemIsParent
                    && $activeIsParent
                )
            )
        ) {
            $activeIndex = $index;
            $activeLength = $itemLength;
            $activeIsParent = $itemIsParent;
        }
    } elseif (
        $depth === 2
        && $currentParentIndex !== null
        && $link !== $currentParentLink
    ) {
        $childCount[$currentParentIndex]++;
    }
}

$activeIndex ??= $selectedFallbackIndex;
$overviewIndex = null;

if (count($topLevelPaths) > 1) {
    $candidateIndex = array_key_first($topLevelPaths);
    $candidatePath = $topLevelPaths[$candidateIndex];
    $containsAllTopLevelItems = true;

    foreach ($topLevelPaths as $index => $itemPath) {
        if ($index === $candidateIndex) {
            continue;
        }

        if ($itemPath === $candidatePath || !str_starts_with($itemPath, $candidatePath)) {
            $containsAllTopLevelItems = false;
            break;
        }
    }

    if ($containsAllTopLevelItems) {
        $overviewIndex = $candidateIndex;
    }
}
?>
<select class="csr43-glass-surface side-menu__select"
        <?php if ($forceDesktop): ?>style="display: none !important;"<?php endif; ?>
        aria-label="Выберите подраздел">
    <option value="">Выберите подраздел...</option>
    <?php foreach ($items as $index => $item): ?>
        <?php
        if (!is_array($item) || (int)($item['DEPTH_LEVEL'] ?? 1) !== 1) {
            continue;
        }

        $link = site_url($item['LINK'] ?? null);
        $text = site_string($item['TEXT'] ?? '');
        $count = $childCount[$index] ?? 0;
        if ($count > 0 && $index !== $overviewIndex) {
            $text .= ' (' . $count . ')';
        }
        $isSelected = $index === $activeIndex;
        ?>
        <option value="<?=htmlspecialcharsbx($link)?>"
                <?php if ($isSelected): ?>selected<?php endif; ?>><?=htmlspecialcharsbx($text)?></option>
    <?php endforeach; ?>
</select>

<ul class="side-menu"
    <?php if ($forceDesktop): ?>style="display: flex !important;"<?php endif; ?>>
    <?php foreach ($items as $index => $item): ?>
        <?php
        if (!is_array($item) || (int)($item['DEPTH_LEVEL'] ?? 1) !== 1) {
            continue;
        }

        $link = site_url($item['LINK'] ?? null);
        $text = site_string($item['TEXT'] ?? '');
        $count = $childCount[$index] ?? 0;
        if ($count > 0 && $index !== $overviewIndex) {
            $text .= ' (' . $count . ')';
        }
        $icon = site_css_classes(
            $item['PARAMS']['ICON'] ?? null,
            'bi bi-arrow-up-right'
        );
        $isSelected = $index === $activeIndex;
        ?>
        <li class="side-menu__item<?=$isSelected ? ' side-menu__item--selected' : ''?>">
            <a href="<?=htmlspecialcharsbx($link)?>"
               class="csr43-glass-surface csr43-glass-card--interactive side-menu__link"
               <?php if ($isSelected): ?>aria-current="page"<?php endif; ?>>
                <i class="<?=htmlspecialcharsbx($icon)?> me-2" aria-hidden="true"></i>
                <?=htmlspecialcharsbx($text)?>
                <span class="side-menu__arrow"></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
