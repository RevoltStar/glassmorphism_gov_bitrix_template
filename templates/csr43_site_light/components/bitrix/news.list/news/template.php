<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$view = is_array($arResult['NEWS_LIST'] ?? null) ? $arResult['NEWS_LIST'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$pagerHtml = site_string($view['pager_html'] ?? '');
$galleryId = 'news-list-' . $this->randString();
?>
<?php if (($view['show_top_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="news-list__pagination"><?=$pagerHtml?></div>
<?php endif; ?>

<?php if ($items !== []): ?>
    <div class="news-list">
        <div class="news-list__grid">
            <?php foreach ($items as $item): ?>
                <?php
                if (!is_array($item)) {
                    continue;
                }
                $name = site_string($item['name'] ?? '');
                $detailUrl = site_url($item['detail_url'] ?? null, '');
                $date = site_string($item['date'] ?? '');
                $counter = max(0, (int)($item['show_counter'] ?? 0));
                $preview = site_string($item['preview_text'] ?? '');
                $image = is_array($item['image'] ?? null) ? $item['image'] : null;
                $categories = is_array($item['categories'] ?? null) ? $item['categories'] : [];
                ?>
                <article class="csr43-light-card csr43-light-card--interactive news-card">
                    <?php if ($image !== null): ?>
                        <?php $imageUrl = site_url($image['url'] ?? null, ''); $imageAlt = site_string($image['alt'] ?? $name); ?>
                        <?php if ($imageUrl !== ''): ?>
                            <div class="news-card__media gallery-media">
                                <a href="<?=htmlspecialcharsbx($imageUrl)?>" class="news-card__expand" data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>" data-gallery-caption="<?=htmlspecialcharsbx($imageAlt)?>" data-type="image" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $imageAlt, GetMessage('CSR43_LIGHT_NEWS_OPEN_IMAGE')))?>"><i class="bi bi-arrows-angle-expand" aria-hidden="true"></i></a>
                                <?php if ($detailUrl !== ''): ?><a href="<?=htmlspecialcharsbx($detailUrl)?>" class="news-card__image-link"><?php endif; ?>
                                <img src="<?=htmlspecialcharsbx($imageUrl)?>" alt="<?=htmlspecialcharsbx($imageAlt)?>" class="news-card__image" loading="lazy" decoding="async">
                                <?php if ($detailUrl !== ''): ?></a><?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="news-card__body">
                        <?php if ($categories !== []): ?><div class="news-card__categories">
                            <?php foreach ($categories as $category): ?><?php if (is_array($category) && ($categoryUrl = site_url($category['url'] ?? null, '')) !== ''): ?><a href="<?=htmlspecialcharsbx($categoryUrl)?>" class="badge csr43-light-badge news-card__category"><?=htmlspecialcharsbx(site_string($category['name'] ?? ''))?></a><?php endif; ?><?php endforeach; ?>
                        </div><?php endif; ?>
                        <?php if ($date !== '' || $counter > 0): ?><div class="news-card__meta"><?php if ($date !== ''): ?><span><i class="bi bi-calendar3" aria-hidden="true"></i><?=htmlspecialcharsbx($date)?></span><?php endif; ?><?php if ($counter > 0): ?><span><i class="bi bi-eye" aria-hidden="true"></i><?=$counter?> <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWS_VIEWS'))?></span><?php endif; ?></div><?php endif; ?>
                        <?php if (($item['show_name'] ?? false) === true && $name !== ''): ?><h2 class="news-card__title"><?php if ($detailUrl !== ''): ?><a href="<?=htmlspecialcharsbx($detailUrl)?>"><?=htmlspecialcharsbx($name)?></a><?php else: ?><?=htmlspecialcharsbx($name)?><?php endif; ?></h2><?php endif; ?>
                        <?php if ($preview !== ''): ?><p class="news-card__preview"><?=htmlspecialcharsbx($preview)?></p><?php endif; ?>
                        <?php if ($detailUrl !== ''): ?><a href="<?=htmlspecialcharsbx($detailUrl)?>" class="news-card__more" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $name, GetMessage('CSR43_LIGHT_NEWS_READ_MORE_ARIA')))?>"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWS_READ_MORE'))?></a><?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
<?php else: ?>
    <div class="csr43-light-surface news-list__empty" role="status"><i class="bi bi-newspaper" aria-hidden="true"></i><div><strong><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWS_EMPTY_TITLE'))?></strong><p><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWS_EMPTY_TEXT'))?><?php $searchUrl = site_url($view['search_url'] ?? null, ''); if ($searchUrl !== ''): ?> <a href="<?=htmlspecialcharsbx($searchUrl)?>"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWS_SEARCH'))?></a><?php endif; ?></p></div></div>
<?php endif; ?>

<?php if (($view['show_bottom_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="news-list__pagination"><?=$pagerHtml?></div>
<?php endif; ?>
