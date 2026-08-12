<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['USEFUL_RESOURCES'] ?? null) ? $arResult['USEFUL_RESOURCES'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$pagerHtml = site_string($view['pager_html'] ?? '');
$modifierClass = site_string($view['modifier_class'] ?? '');
?>
<?php if (($view['show_top_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="useful-resources__pagination"><?=$pagerHtml?></div>
<?php endif; ?>

<?php if ($items !== []): ?>
    <div class="useful-resources<?=$modifierClass !== '' ? ' ' . htmlspecialcharsbx($modifierClass) : ''?>">
        <?php foreach ($items as $item): ?>
            <?php
            if (!is_array($item)) {
                continue;
            }
            $name = site_string($item['name'] ?? '');
            $url = site_url($item['url'] ?? null, '');
            $image = is_array($item['image'] ?? null) ? $item['image'] : null;
            $imageUrl = $image !== null ? site_url($image['url'] ?? null, '') : '';
            ?>
            <div class="useful-resources__item">
                <?php if ($url !== ''): ?><a class="useful-resources__banner" href="<?=htmlspecialcharsbx($url)?>" target="_blank" rel="noopener noreferrer" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $name, GetMessage('CSR43_LIGHT_USEFUL_RESOURCES_OPEN')))?>"><?php else: ?><div class="useful-resources__banner useful-resources__banner--static"><?php endif; ?>
                    <?php if ($imageUrl !== ''): ?>
                        <img class="useful-resources__image" src="<?=htmlspecialcharsbx($imageUrl)?>" alt="<?=htmlspecialcharsbx(site_string($image['alt'] ?? $name))?>" title="<?=htmlspecialcharsbx(site_string($image['title'] ?? ''))?>" loading="lazy" decoding="async">
                    <?php else: ?>
                        <span class="useful-resources__placeholder"><i class="bi bi-card-image" aria-hidden="true"></i><span><?=htmlspecialcharsbx($name)?></span></span>
                    <?php endif; ?>
                <?php if ($url !== ''): ?></a><?php else: ?></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="csr43-light-surface useful-resources__empty" role="status">
        <i class="bi bi-info-circle useful-resources__empty-icon" aria-hidden="true"></i>
        <h2><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_USEFUL_RESOURCES_EMPTY_TITLE'))?></h2>
        <p><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_USEFUL_RESOURCES_EMPTY_TEXT'))?></p>
        <p class="useful-resources__empty-note"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_USEFUL_RESOURCES_EMPTY_NOTE'))?></p>
    </div>
<?php endif; ?>

<?php if (($view['show_bottom_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="useful-resources__pagination"><?=$pagerHtml?></div>
<?php endif; ?>
