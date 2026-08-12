<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['FILE_LIST'] ?? null) ? $arResult['FILE_LIST'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$section = is_array($view['section'] ?? null) ? $view['section'] : null;
$pager = site_string($view['pager_html'] ?? '');
$galleryId = 'files-' . $this->randString();

$renderMetadata = static function (array $file, bool $showDimensions): string {
    $extension = strtoupper(site_string($file['extension'] ?? ''));
    $displaySize = site_string($file['display_size'] ?? '');
    $width = max(0, (int)($file['width'] ?? 0));
    $height = max(0, (int)($file['height'] ?? 0));

    ob_start();
    ?>
    <?php if ($extension !== '' || $displaySize !== '' || ($showDimensions && $width > 0 && $height > 0)): ?>
        <div class="file-entry__meta">
            <?php if ($extension !== ''): ?><span><?=htmlspecialcharsbx($extension)?></span><?php endif; ?>
            <?php if ($displaySize !== ''): ?><span><?=htmlspecialcharsbx($displaySize)?></span><?php endif; ?>
            <?php if ($showDimensions && $width > 0 && $height > 0): ?><span><?=$width?> × <?=$height?> px</span><?php endif; ?>
        </div>
    <?php endif; ?>
    <?php
    return (string)ob_get_clean();
};

$renderItems = static function (array $items, bool $showImages, string $galleryId) use ($renderMetadata): string {
    ob_start();
    ?>
    <div class="file-library__items">
        <?php foreach ($items as $item): ?>
            <?php if (!is_array($item)) { continue; } ?>
            <article class="csr43-light-card file-card">
                <header class="file-card__header">
                    <h3><?=htmlspecialcharsbx(site_string($item['name'] ?? ''))?></h3>
                    <?php if (($item['description'] ?? '') !== ''): ?><p><?=htmlspecialcharsbx(site_string($item['description']))?></p><?php endif; ?>
                    <?php if (($item['date'] ?? '') !== ''): ?><span class="file-card__date"><i class="bi bi-clock" aria-hidden="true"></i><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_UPDATED'))?> <?=htmlspecialcharsbx(site_string($item['date']))?></span><?php endif; ?>
                </header>
                <div class="file-card__files">
                    <?php foreach (is_array($item['files'] ?? null) ? $item['files'] : [] as $file): ?>
                        <?php
                        if (!is_array($file)) { continue; }
                        $url = site_url($file['url'] ?? null, '');
                        if ($url === '') { continue; }
                        $type = site_string($file['type'] ?? 'download');
                        $caption = site_string($file['caption'] ?? '');
                        ?>
                        <?php if ($type === 'image'): ?>
                            <?php ob_start(); ?>
                            <div class="file-entry__preview gallery-media"><a href="<?=htmlspecialcharsbx($url)?>" data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>" data-gallery-caption="<?=htmlspecialcharsbx($caption)?>" data-type="image" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $caption, GetMessage('CSR43_LIGHT_FILES_ENLARGE')))?>"><img src="<?=htmlspecialcharsbx($url)?>" alt="<?=htmlspecialcharsbx($caption)?>" loading="lazy" decoding="async"></a></div>
                            <?php $media = (string)ob_get_clean(); ?>
                            <div class="file-entry file-entry--image"><strong><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_IMAGE'))?></strong><?=$renderMetadata($file, true)?><?php if ($showImages): ?><?=$media?><?php else: ?><details><summary><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_SHOW_IMAGE'))?></summary><?=$media?></details><?php endif; ?></div>
                        <?php elseif ($type === 'video'): ?>
                            <div class="file-entry file-entry--video"><?=$renderMetadata($file, false)?><details><summary><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_SHOW_VIDEO'))?></summary><video controls preload="metadata"><source src="<?=htmlspecialcharsbx($url)?>" type="<?=htmlspecialcharsbx(site_string($file['mime'] ?? ''))?>"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_VIDEO_FALLBACK'))?></video></details></div>
                        <?php else: ?>
                            <a href="<?=htmlspecialcharsbx($url)?>" target="_blank" rel="noopener noreferrer" class="file-entry file-entry--download"><i class="bi <?=htmlspecialcharsbx(site_css_classes($file['icon'] ?? '', 'bi-file-earmark'))?>" aria-hidden="true"></i><span><strong><?=htmlspecialcharsbx(site_string($file['display_name'] ?? ''))?></strong><?=$renderMetadata($file, false)?></span><i class="bi bi-download" aria-hidden="true"></i></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <?php
    return (string)ob_get_clean();
};
?>
<?php if (($view['show_top_pager'] ?? false) === true && $pager !== ''): ?><div class="file-library__pagination"><?=$pager?></div><?php endif; ?>
<?php if ($items !== []): ?>
    <section class="file-library">
        <?php if ($section !== null): ?><header class="file-library__header"><h2><?=htmlspecialcharsbx(site_string($section['name'] ?? ''))?></h2><?php if (($section['description'] ?? '') !== ''): ?><p><?=htmlspecialcharsbx(site_string($section['description']))?></p><?php endif; ?></header><?php endif; ?>
        <?php if (($view['collapse_section'] ?? false) === true): ?><details class="file-library__collapse"><summary><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_TOGGLE'))?></summary><?=$renderItems($items, ($view['show_image_immediately'] ?? false) === true, $galleryId)?></details><?php else: ?><?=$renderItems($items, ($view['show_image_immediately'] ?? false) === true, $galleryId)?><?php endif; ?>
    </section>
<?php else: ?>
    <div class="csr43-light-surface file-library__empty" role="status"><i class="bi bi-folder-x" aria-hidden="true"></i><div><strong><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_EMPTY_TITLE'))?></strong><p><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_FILES_EMPTY_TEXT'))?></p></div></div>
<?php endif; ?>
<?php if (($view['show_bottom_pager'] ?? false) === true && $pager !== ''): ?><div class="file-library__pagination"><?=$pager?></div><?php endif; ?>
