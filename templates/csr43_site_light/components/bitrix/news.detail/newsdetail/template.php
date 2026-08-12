<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['NEWS_DETAIL'] ?? null) ? $arResult['NEWS_DETAIL'] : [];
$date = site_string($view['date'] ?? '');
$showCounter = max(0, (int)($view['show_counter'] ?? 0));
$categories = is_array($view['categories'] ?? null) ? $view['categories'] : [];
$previewHtml = site_string($view['preview_html'] ?? '');
$detailHtml = site_string($view['detail_html'] ?? '');
$gallery = is_array($view['gallery'] ?? null) ? $view['gallery'] : [];
$downloads = is_array($view['downloads'] ?? null) ? $view['downloads'] : [];
$navigation = is_array($view['navigation'] ?? null) ? $view['navigation'] : [];
$jsonLd = is_array($view['json_ld'] ?? null) ? $view['json_ld'] : [];
if ($jsonLd !== []) {
    $templateData['JSON_LD'] = $jsonLd;
}
$galleryId = 'newsdetail-' . $this->randString();
?>
<div class="newsdetail">
    <?php if ($date !== '' || $showCounter > 0): ?>
        <div class="newsdetail__meta">
            <?php if ($date !== ''): ?>
                <span class="newsdetail__meta-item"><i class="bi bi-calendar" aria-hidden="true"></i><?=htmlspecialcharsbx($date)?></span>
            <?php endif; ?>
            <?php if ($showCounter > 0): ?>
                <span class="newsdetail__meta-item"><i class="bi bi-eye" aria-hidden="true"></i><?=$showCounter?> <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWSDETAIL_VIEWS'))?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="newsdetail__categories">
        <?php if ($categories !== []): ?>
            <?php foreach ($categories as $category): ?>
                <?php
                if (!is_array($category)) {
                    continue;
                }
                $categoryName = site_string($category['name'] ?? '');
                $categoryUrl = site_url($category['url'] ?? null, '');
                if ($categoryName === '' || $categoryUrl === '') {
                    continue;
                }
                ?>
                <a href="<?=htmlspecialcharsbx($categoryUrl)?>" class="badge csr43-light-badge newsdetail__category">
                    <i class="bi bi-tag" aria-hidden="true"></i><?=htmlspecialcharsbx($categoryName)?>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <span class="badge csr43-light-badge newsdetail__category"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWSDETAIL_UNCATEGORIZED'))?></span>
        <?php endif; ?>
    </div>

    <div class="newsdetail__layout">
        <div class="newsdetail__main">
            <?php if ($previewHtml !== ''): ?>
                <div class="csr43-light-surface newsdetail__preview bvi-speech"><?=$previewHtml?></div>
            <?php endif; ?>
            <?php if ($detailHtml !== ''): ?>
                <article class="newsdetail__content bvi-speech"><?=$detailHtml?></article>
            <?php endif; ?>
        </div>

        <aside class="newsdetail__aside">
            <?php if ($gallery !== []): ?>
                <section class="csr43-light-card newsdetail__materials">
                    <h2 class="newsdetail__section-title"><i class="bi bi-images" aria-hidden="true"></i><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWSDETAIL_MATERIALS'))?></h2>
                    <div class="newsdetail__gallery">
                        <?php foreach ($gallery as $media): ?>
                            <?php
                            if (!is_array($media)) {
                                continue;
                            }
                            $url = site_url($media['url'] ?? null, '');
                            $thumbnailUrl = site_url($media['thumbnail_url'] ?? null, '');
                            $caption = site_string($media['caption'] ?? '');
                            $type = ($media['type'] ?? '') === 'video' ? 'video' : 'image';
                            $fancyboxType = $type === 'video' ? 'html5video' : 'image';
                            if ($url === '' || $thumbnailUrl === '') {
                                continue;
                            }
                            ?>
                            <a href="<?=htmlspecialcharsbx($url)?>"
                               class="newsdetail__gallery-item gallery-media"
                               data-gallery-item
                               data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                               data-gallery-caption="<?=htmlspecialcharsbx($caption)?>"
                               data-type="<?=$fancyboxType?>"
                               aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $caption, GetMessage('CSR43_LIGHT_NEWSDETAIL_OPEN')))?>">
                                <img src="<?=htmlspecialcharsbx($thumbnailUrl)?>" alt="<?=htmlspecialcharsbx($caption)?>" loading="lazy" decoding="async">
                                <?php if ($type === 'video'): ?><i class="bi bi-play-circle-fill newsdetail__play" aria-hidden="true"></i><?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($downloads !== []): ?>
                <section class="csr43-light-card newsdetail__downloads">
                    <h2 class="newsdetail__section-title"><i class="bi bi-paperclip" aria-hidden="true"></i><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWSDETAIL_FILES'))?></h2>
                    <ul class="newsdetail__file-list">
                        <?php foreach ($downloads as $file): ?>
                            <?php
                            if (!is_array($file)) {
                                continue;
                            }
                            $url = site_url($file['url'] ?? null, '');
                            $name = site_string($file['name'] ?? '');
                            $size = site_string($file['display_size'] ?? '');
                            $icon = site_css_classes($file['icon'] ?? '', 'bi-file-earmark');
                            if ($url === '') {
                                continue;
                            }
                            ?>
                            <li class="csr43-light-surface newsdetail__file">
                                <i class="bi <?=htmlspecialcharsbx($icon)?> newsdetail__file-icon" aria-hidden="true"></i>
                                <span class="newsdetail__file-info"><strong><?=htmlspecialcharsbx($name)?></strong><?php if ($size !== ''): ?><small><?=htmlspecialcharsbx($size)?></small><?php endif; ?></span>
                                <a href="<?=htmlspecialcharsbx($url)?>" download class="newsdetail__download" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $name, GetMessage('CSR43_LIGHT_NEWSDETAIL_DOWNLOAD')))?>"><i class="bi bi-download" aria-hidden="true"></i></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

            <?php if (!empty($navigation['prev']) || !empty($navigation['next'])): ?>
                <nav class="csr43-light-card newsdetail__navigation" aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_NEWSDETAIL_NAVIGATION'))?>">
                    <?php foreach (['prev' => 'CSR43_LIGHT_NEWSDETAIL_PREV', 'next' => 'CSR43_LIGHT_NEWSDETAIL_NEXT'] as $key => $message): ?>
                        <?php
                        $neighbor = is_array($navigation[$key] ?? null) ? $navigation[$key] : [];
                        $url = site_url($neighbor['url'] ?? null, '');
                        $name = site_string($neighbor['name'] ?? '');
                        if ($url === '' || $name === '') {
                            continue;
                        }
                        ?>
                        <a href="<?=htmlspecialcharsbx($url)?>" class="newsdetail__navigation-link newsdetail__navigation-link--<?=$key?>">
                            <i class="bi <?=$key === 'prev' ? 'bi-arrow-left' : 'bi-arrow-right'?>" aria-hidden="true"></i>
                            <span><small><?=htmlspecialcharsbx(GetMessage($message))?></small><strong><?=htmlspecialcharsbx($name)?></strong></span>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </aside>
    </div>
</div>
