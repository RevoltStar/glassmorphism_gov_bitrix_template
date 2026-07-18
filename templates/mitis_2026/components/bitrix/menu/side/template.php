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
$currentParentIndex = null;
$currentParentLink = null;

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
    } elseif (
        $depth === 2
        && $currentParentIndex !== null
        && $link !== $currentParentLink
    ) {
        $childCount[$currentParentIndex]++;
    }
}
?>
<select class="side-menu-select glass-select"
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
        if ($count > 0) {
            $text .= ' (' . $count . ')';
        }
        $isSelected = !empty($item['SELECTED'])
            && $link === $APPLICATION->GetCurPage();
        ?>
        <option value="<?=htmlspecialcharsbx($link)?>"
                <?php if ($isSelected): ?>selected<?php endif; ?>><?=htmlspecialcharsbx($text)?></option>
    <?php endforeach; ?>
</select>

<ul class="side-menu glass-side-menu"
    <?php if ($forceDesktop): ?>style="display: flex !important;"<?php endif; ?>>
    <?php foreach ($items as $index => $item): ?>
        <?php
        if (!is_array($item) || (int)($item['DEPTH_LEVEL'] ?? 1) !== 1) {
            continue;
        }

        $link = site_url($item['LINK'] ?? null);
        $text = site_string($item['TEXT'] ?? '');
        $count = $childCount[$index] ?? 0;
        if ($count > 0) {
            $text .= ' (' . $count . ')';
        }
        $icon = site_css_classes(
            $item['PARAMS']['ICON'] ?? null,
            'bi bi-arrow-up-right'
        );
        $isSelected = !empty($item['SELECTED'])
            && $link === $APPLICATION->GetCurPage();
        ?>
        <li class="glass-menu-item<?=$isSelected ? ' glass-menu-item--selected' : ''?>">
            <a href="<?=htmlspecialcharsbx($link)?>" class="glass-menu-link">
                <i class="<?=htmlspecialcharsbx($icon)?> me-2" aria-hidden="true"></i>
                <?=htmlspecialcharsbx($text)?>
                <span class="glass-menu-arrow"></span>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
