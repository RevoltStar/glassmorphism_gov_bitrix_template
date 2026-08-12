<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['DIRECTIONS_LIST'] ?? null) ? $arResult['DIRECTIONS_LIST'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$pagerHtml = site_string($view['pager_html'] ?? '');
?>
<?php if (($view['show_top_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="directions-list__pagination"><?=$pagerHtml?></div>
<?php endif; ?>

<?php if ($items !== []): ?>
    <div class="directions-list">
        <div class="directions-list__grid">
            <?php foreach ($items as $item): ?>
                <?php
                if (!is_array($item)) {
                    continue;
                }

                $name = site_string($item['name'] ?? '');
                $url = site_url($item['url'] ?? null, '');
                $image = is_array($item['image'] ?? null) ? $item['image'] : null;
                $tag = $url !== '' ? 'a' : 'article';
                ?>
                <<?=$tag?>
                    class="csr43-light-card csr43-light-card--stretch directions-card<?=$url === '' ? ' directions-card--disabled' : ' csr43-light-card--interactive'?>"
                    <?php if ($url !== ''): ?>href="<?=htmlspecialcharsbx($url)?>" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $name, GetMessage('CSR43_LIGHT_DIRECTIONS_OPEN')))?>"<?php endif; ?>
                >
                    <span class="directions-card__media">
                        <?php if ($image !== null): ?>
                            <img class="directions-card__image" src="<?=htmlspecialcharsbx(site_string($image['url'] ?? ''))?>" alt="<?=htmlspecialcharsbx(site_string($image['alt'] ?? $name))?>" loading="lazy" decoding="async">
                        <?php else: ?>
                            <span class="directions-card__placeholder" aria-hidden="true"><i class="bi bi-compass"></i></span>
                        <?php endif; ?>
                    </span>
                    <h2 class="directions-card__title"><?=htmlspecialcharsbx($name)?></h2>
                </<?=$tag?>>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (($view['show_bottom_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="directions-list__pagination"><?=$pagerHtml?></div>
<?php endif; ?>
