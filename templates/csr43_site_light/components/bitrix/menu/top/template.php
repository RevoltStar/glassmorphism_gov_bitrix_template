<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$view = is_array($arResult['TOP_MENU'] ?? null) ? $arResult['TOP_MENU'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
if ($items === []) {
    return;
}
$instanceId = 'top-menu-' . $this->randString();
$drawerId = $instanceId . '-drawer';
$renderDesktop = static function (array $nodes, int $level = 1) use (&$renderDesktop): void {
    ?><ul class="<?=$level === 1 ? 'top-menu__desktop-list' : 'top-menu__submenu'?>"><?php
    foreach ($nodes as $node) {
        if (!is_array($node)) { continue; }
        $text = site_string($node['text'] ?? '');
        $url = site_url($node['url'] ?? null, '');
        $children = is_array($node['children'] ?? null) ? $node['children'] : [];
        $state = ($node['is_current'] ?? false) === true ? ' top-menu__item--current' : ((($node['is_in_active_path'] ?? false) === true) ? ' top-menu__item--active-path' : '');
        ?><li class="top-menu__item<?=$children !== [] ? ' top-menu__item--parent' : ''?><?=$state?>"><?php
        if ($url !== ''): ?><a class="top-menu__link" href="<?=htmlspecialcharsbx($url)?>"<?php if (($node['is_current'] ?? false) === true): ?> aria-current="page"<?php endif; ?>><?=htmlspecialcharsbx($text)?></a><?php
        elseif ($children !== []): ?><button type="button" class="top-menu__text top-menu__desktop-toggle"><?=htmlspecialcharsbx($text)?></button><?php
        else: ?><span class="top-menu__text"><?=htmlspecialcharsbx($text)?></span><?php endif;
        if ($children !== []) { $renderDesktop($children, $level + 1); }
        ?></li><?php
    }
    ?></ul><?php
};
$renderMobile = static function (array $nodes, string $instanceId, int $level = 1) use (&$renderMobile): void {
    ?><ul class="<?=$level === 1 ? 'top-menu__mobile-list' : 'top-menu__mobile-submenu'?>"<?=$level > 1 ? ' data-top-menu-submenu' : ''?>><?php
    foreach ($nodes as $node) {
        if (!is_array($node)) { continue; }
        $text = site_string($node['text'] ?? '');
        $url = site_url($node['url'] ?? null, '');
        $children = is_array($node['children'] ?? null) ? $node['children'] : [];
        $isExpanded = ($node['is_in_active_path'] ?? false) === true;
        $submenuId = $instanceId . '-' . site_css_classes($node['id'] ?? '', 'item') . '-submenu';
        ?><li class="top-menu__mobile-item<?=$isExpanded ? ' top-menu__mobile-item--active-path' : ''?>"><div class="top-menu__mobile-row"><?php
        if ($url !== ''): ?><a class="top-menu__mobile-link" href="<?=htmlspecialcharsbx($url)?>"<?php if (($node['is_current'] ?? false) === true): ?> aria-current="page"<?php endif; ?>><?=htmlspecialcharsbx($text)?></a><?php
        else: ?><span class="top-menu__mobile-text"><?=htmlspecialcharsbx($text)?></span><?php endif;
        if ($children !== []): ?><button type="button" class="top-menu__submenu-toggle" data-top-menu-submenu-toggle aria-expanded="<?=$isExpanded ? 'true' : 'false'?>" aria-controls="<?=htmlspecialcharsbx($submenuId)?>" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $text, GetMessage('CSR43_LIGHT_TOP_MENU_SUBMENU')))?>"><i class="bi bi-chevron-down" aria-hidden="true"></i></button><?php endif;
        ?></div><?php if ($children !== []): ?><div id="<?=htmlspecialcharsbx($submenuId)?>"<?=$isExpanded ? '' : ' hidden'?>><?php $renderMobile($children, $instanceId, $level + 1); ?></div><?php endif; ?></li><?php
    }
    ?></ul><?php
};
?>
<div class="top-menu" data-top-menu>
    <nav class="top-menu__desktop" aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_TOP_MENU_NAV'))?>"><?php $renderDesktop($items); ?></nav>
    <button type="button" class="top-menu__open" data-top-menu-open aria-expanded="false" aria-controls="<?=htmlspecialcharsbx($drawerId)?>" aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_TOP_MENU_OPEN'))?>"><i class="bi bi-list" aria-hidden="true"></i></button>
    <div class="top-menu__backdrop" data-top-menu-backdrop hidden></div>
    <aside id="<?=htmlspecialcharsbx($drawerId)?>" class="top-menu__drawer" data-top-menu-drawer aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_TOP_MENU_NAV'))?>" tabindex="-1" hidden>
        <div class="top-menu__drawer-header"><button type="button" class="top-menu__close" data-top-menu-close aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_TOP_MENU_CLOSE'))?>"><i class="bi bi-x-lg" aria-hidden="true"></i></button></div>
        <nav aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_TOP_MENU_MOBILE_NAV'))?>"><?php $renderMobile($items, $instanceId); ?></nav>
    </aside>
</div>
