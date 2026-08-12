<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$view = is_array($arResult['SIDE_MENU'] ?? null) ? $arResult['SIDE_MENU'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
if ($items === []) { return; }
$forceDesktop = ($view['force_desktop'] ?? false) === true;
?>
<nav class="side-menu<?=$forceDesktop ? ' side-menu--force-desktop' : ''?>" data-side-menu aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_SIDE_MENU_NAV'))?>">
    <label class="side-menu__select-label">
        <span class="visually-hidden"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_SIDE_MENU_SELECT_LABEL'))?></span>
        <select class="side-menu__select" data-side-menu-select>
            <option value=""><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_SIDE_MENU_PLACEHOLDER'))?></option>
            <?php foreach ($items as $item): ?>
                <?php
                if (!is_array($item)) { continue; }
                $url = site_url($item['url'] ?? null, '');
                $text = site_string($item['text'] ?? '');
                $count = max(0, (int)($item['child_count'] ?? 0));
                $label = $count > 0 ? $text . ' (' . $count . ')' : $text;
                ?>
                <option value="<?=htmlspecialcharsbx($url)?>"<?=$url === '' ? ' disabled' : ''?><?php if (($item['is_in_active_path'] ?? false) === true): ?> selected<?php endif; ?>><?=htmlspecialcharsbx($label)?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <ul class="side-menu__list">
        <?php foreach ($items as $item): ?>
            <?php
            if (!is_array($item)) { continue; }
            $url = site_url($item['url'] ?? null, '');
            $text = site_string($item['text'] ?? '');
            $iconClass = site_css_classes($item['icon_class'] ?? '', '');
            $count = max(0, (int)($item['child_count'] ?? 0));
            $label = $count > 0 ? $text . ' (' . $count . ')' : $text;
            $isCurrent = ($item['is_current'] ?? false) === true;
            $isActivePath = ($item['is_in_active_path'] ?? false) === true;
            ?>
            <li class="side-menu__item<?=$isCurrent ? ' side-menu__item--current' : ($isActivePath ? ' side-menu__item--active-path' : '')?>">
                <?php if ($url !== ''): ?><a class="side-menu__link" href="<?=htmlspecialcharsbx($url)?>"<?=$isCurrent ? ' aria-current="page"' : ''?>><?php else: ?><span class="side-menu__link side-menu__link--static"><?php endif; ?>
                    <?php if ($iconClass !== ''): ?><i class="side-menu__icon <?=htmlspecialcharsbx($iconClass)?>" aria-hidden="true"></i><?php endif; ?>
                    <span><?=htmlspecialcharsbx($label)?></span>
                    <i class="bi bi-arrow-right side-menu__arrow" aria-hidden="true"></i>
                <?php if ($url !== ''): ?></a><?php else: ?></span><?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
