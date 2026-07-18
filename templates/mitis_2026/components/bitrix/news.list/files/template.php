<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];

// Вспомогательная функция для определения иконки файла (без изменений)
if (!function_exists('getFileIconClass')) {
    function getFileIconClass($extension) {
        $iconMap = [
            'pdf' => 'bi-filetype-pdf',
            'doc' => 'bi-filetype-doc',
            'docx' => 'bi-filetype-docx',
            'xls' => 'bi-filetype-xls',
            'xlsx' => 'bi-filetype-xlsx',
            'ppt' => 'bi-filetype-ppt',
            'pptx' => 'bi-filetype-pptx',
            'zip' => 'bi-file-earmark-zip',
            'rar' => 'bi-filetype-rar',
            'jpg' => 'bi-filetype-jpg',
            'jpeg' => 'bi-filetype-jpg',
            'png' => 'bi-filetype-png',
            'gif' => 'bi-filetype-gif',
            'bmp' => 'bi-filetype-bmp',
            'svg' => 'bi-filetype-svg',
            'webp' => 'bi-filetype-webp',
            'mp4' => 'bi-filetype-mp4',
            'mp3' => 'bi-filetype-mp3',
            'txt' => 'bi-filetype-txt',
            'xml' => 'bi-filetype-xml',
            'csv' => 'bi-filetype-csv',
        ];
        return $iconMap[$extension] ?? 'bi-file-earmark';
    }
}
?>
<?php
$items = $arResult['ITEMS'] ?? [];
$sectionId = max(0, (int)($arParams['SECTION_ID'] ?? 0));
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
if (is_array($items) && $items !== []):
?>
<?php
$galleryId = 'files-' . $this->randString();
$showImageImmediately = ($arParams['SHOW_IMAGE_IMMEDIATELY'] ?? 'N') === 'Y';
?>

<?php if(($arParams["DISPLAY_TOP_PAGER"] ?? 'N') === 'Y'):?>
    <?=$arResult["NAV_STRING"] ?? ''?><br />
<?php endif;?>

<div class="glass-files mt-2 mb-2">
    <?php
    $section = ($sectionId > 0 && $iblockId > 0) ? CIBlockSection::GetList(
        [],
        ['ID' => $sectionId, 'IBLOCK_ID' => $iblockId, 'CHECK_PERMISSIONS' => 'Y'],
        false,
        ['ID', 'NAME', 'DESCRIPTION']
    ) : null;
    if (is_object($section) && ($arSection = $section->Fetch()) && (($arParams['SHOW_SECTION_NAME'] ?? 'N') === "Y")):
    ?>
    <div class="glass-section-header mb-4">
        <h2 class="glass-section-title h4 mb-2"><?=htmlspecialcharsbx(site_string($arSection['~NAME'] ?? $arSection['NAME'] ?? ''))?></h2>
        <?php if($arSection['DESCRIPTION']):?>
            <p class="glass-section-desc text-muted"><?=htmlspecialcharsbx(site_plain_text($arSection['~DESCRIPTION'] ?? $arSection['DESCRIPTION']))?></p>
        <?php endif;?>

        <?php if(($arParams['COLLAPSE_SECTION'] ?? 'N') === "Y"):?>
        <div class="mt-3">
            <button
                class="glass-collapse-btn"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#section-collapse-<?=$sectionId?>"
                aria-expanded="false"
                aria-controls="section-collapse-<?=$sectionId?>">
                <i class="bi bi-chevron-down me-1"></i>Показать/скрыть материалы
            </button>
        </div>
        <?php endif;?>
    </div>
    <?php endif;?>

    <?php if(($arParams['COLLAPSE_SECTION'] ?? 'N') === "Y"):?>
    <div class="collapse glass-section-collapse" id="section-collapse-<?=$sectionId?>">
    <?php endif;?>

    <div class="glass-files-grid">
        <?php foreach($items as $arItem):
            if (!is_array($arItem)) {
                continue;
            }

            $showDate = ($arParams['SHOW_DATE'] ?? 'N') !== 'N';
            $dateField = site_string($arParams['DATE_FIELD'] ?? 'TIMESTAMP_X', 'TIMESTAMP_X');
            if (preg_match('/^[A-Z0-9_]+$/D', $dateField) !== 1) {
                $dateField = 'TIMESTAMP_X';
            }
            $dateValue = site_string($arItem[$dateField] ?? $arItem['TIMESTAMP_X'] ?? '');
            $timestamp = $dateValue !== '' ? MakeTimeStamp($dateValue) : 0;
            $description = site_plain_text(
                $arItem['~PREVIEW_TEXT']
                    ?? $arItem['~DETAIL_TEXT']
                    ?? $arItem['PREVIEW_TEXT']
                    ?? $arItem['DETAIL_TEXT']
                    ?? ''
            );
            $itemName = site_string($arItem['~NAME'] ?? $arItem['NAME'] ?? '');
            $itemId = max(0, (int)($arItem['ID'] ?? 0));
        ?>
        <div class="glass-file-card">
            <div class="glass-file-card-body">
                <div class="glass-file-header mb-3">
                    <h5 class="glass-file-title mb-1"><?=htmlspecialcharsbx($itemName)?></h5>
                    <?php if($description && false):?>
                        <p class="glass-file-desc"><?=htmlspecialcharsbx(TruncateText($description, 150))?></p>
                    <?php endif;?>
                </div>

                <?php if($showDate && $timestamp > 0):?>
                <div class="glass-file-date mb-3">
                    <i class="bi bi-clock me-1"></i>
                    Обновлено: <?=htmlspecialcharsbx(FormatDate("d.m.Y H:i", $timestamp))?>
                </div>
                <?php endif;?>

                <div class="glass-file-list">
                    <?php
                    $fileIDs = site_string_list($arItem['PROPERTIES']['FILES']['VALUE'] ?? []);
                    if ($fileIDs !== []):

                        $videoIndex = 0;
                        $photoIndex = 0;
                        foreach ($fileIDs as $fileID):
                            $fileID = filter_var($fileID, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
                            if ($fileID === false) {
                                continue;
                            }

                            $fileInfo = CFile::GetFileArray($fileID);
                            if (is_array($fileInfo)) {
                                $fileName = site_string($fileInfo['FILE_NAME'] ?? '');
                                $fileExtension = strtolower(GetFileExtension($fileName));
                                if (preg_match('/^[a-z0-9]{1,10}$/D', $fileExtension) !== 1) {
                                    $fileExtension = '';
                                }
                                $fileSize = CFile::FormatSize(max(0, (int)($fileInfo['FILE_SIZE'] ?? 0)));
                                $fileIcon = getFileIconClass($fileExtension);
                                $fileSrc = site_url($fileInfo['SRC'] ?? null, '');
                                if ($fileSrc === '') {
                                    continue;
                                }
                    ?>
                    <div class="glass-file-item">
                        <?php if(in_array($fileExtension, ['mp4', 'webm', 'ogg'], true)):?>
                        <?php $videoIndex++;?>
                        <div class="glass-video-file">
                            <div class="glass-file-header-line d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-play-btn me-2 glass-accent-icon"></i>
                                    <span class="fw-medium">Видеофайл</span>
                                </div>
                                <button class="glass-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#video-<?=$itemId?>-<?=$videoIndex?>" aria-expanded="false">
                                    <i class="bi bi-eye me-1"></i>Показать видео
                                </button>
                            </div>
                            <div class="glass-file-meta d-flex flex-wrap gap-2 mb-2">
                                <span class="glass-file-badge"><?=htmlspecialcharsbx($fileExtension)?></span>
                                <span class="glass-file-size"><?=htmlspecialcharsbx($fileSize)?></span>
                            </div>
                            <div class="collapse" id="video-<?=$itemId?>-<?=$videoIndex?>">
                                <div class="glass-video-container mt-2">
                                    <video class="glass-video" controls preload="metadata">
                                        <source src="<?=htmlspecialcharsbx($fileSrc)?>" type="video/<?=htmlspecialcharsbx($fileExtension)?>">
                                        Ваш браузер не поддерживает видео.
                                    </video>
                                </div>
                            </div>
                        </div>
                        <?php elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'], true)):?>
                        <?php $photoIndex++;?>
                        <?php $galleryCaption = site_string(
                            $fileInfo['DESCRIPTION']
                                ?? $arItem['~NAME']
                                ?? $arItem['NAME']
                                ?? $fileInfo['ORIGINAL_NAME']
                                ?? ''
                        );?>
                        <div class="glass-photo-file">
                            <div class="glass-file-header-line d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-image me-2 glass-accent-icon"></i>
                                    <span class="fw-medium">Изображение</span>
                                </div>
                                <?php if(!$showImageImmediately):?>
                                    <button class="glass-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#photo-<?=$itemId?>-<?=$photoIndex?>" aria-expanded="false">
                                        <i class="bi bi-eye me-1"></i>Показать изображение
                                    </button>
                                <?php endif;?>
                            </div>
                            <div class="glass-file-meta d-flex flex-wrap gap-2 mb-2">
                                <span class="glass-file-badge"><?=htmlspecialcharsbx($fileExtension)?></span>
                                <span class="glass-file-size"><?=htmlspecialcharsbx($fileSize)?></span>
                                <span class="glass-image-dimensions">
                                    <?=max(0, (int)($fileInfo['WIDTH'] ?? 0))?> × <?=max(0, (int)($fileInfo['HEIGHT'] ?? 0))?> px
                                </span>
                            </div>
                            <div<?php if(!$showImageImmediately):?> class="collapse" id="photo-<?=$itemId?>-<?=$photoIndex?>"<?php endif;?>>
                                <div class="glass-image-container mt-2 text-center gallery-media">
                                    <a href="<?=htmlspecialcharsbx($fileSrc)?>"
                                       class="gallery-expand-button glass-image-expand"
                                       data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                                       data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>" data-type="image"
                                       aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $galleryCaption)?>">
                                        <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
                                    </a>
                                    <img src="<?=htmlspecialcharsbx($fileSrc)?>"
                                         alt="<?=htmlspecialcharsbx($galleryCaption)?>"
                                         class="glass-image img-fluid rounded"
                                         loading="lazy">
                                </div>
                            </div>
                        </div>
                        <?php else:?>
                        <a target="_blank" class="glass-download-link" href="<?=htmlspecialcharsbx($fileSrc)?>" rel="noopener noreferrer">
                            <div class="glass-file-icon">
                                <i class="bi <?=htmlspecialcharsbx($fileIcon)?>"></i>
                            </div>
                            <div class="glass-file-info">
								<div class="glass-file-name"><?=htmlspecialcharsbx($description !== '' ? $description : $itemName)?></div>
                                <div class="glass-file-meta">
                                    <span class="glass-file-type text-uppercase"><?=htmlspecialcharsbx($fileExtension)?></span>
                                    <span class="glass-file-size"><?=htmlspecialcharsbx($fileSize)?></span>
                                </div>
                            </div>
                            <div class="glass-download-icon">
                                <i class="bi bi-download"></i>
                            </div>
                        </a>
                        <?php endif?>
                    </div>
                    <?php
                            }
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>

    <?php if(($arParams['COLLAPSE_SECTION'] ?? 'N') === "Y"):?>
    </div>
    <?php endif;?>
</div>

<?php if(($arParams["DISPLAY_BOTTOM_PAGER"] ?? 'N') === 'Y'):?>
    <div class="glass-pagination mt-4">
        <?=$arResult["NAV_STRING"] ?? ''?>
    </div>
<?php endif;?>

<?php else:?>
<div class="glass-empty-alert">
    <div class="glass-empty-icon">
        <i class="bi bi-folder-x"></i>
    </div>
    <div>
		<?php
        $emptySectionResult = ($sectionId > 0 && $iblockId > 0) ? CIBlockSection::GetList(
            [],
            ['ID' => $sectionId, 'IBLOCK_ID' => $iblockId, 'CHECK_PERMISSIONS' => 'Y'],
            false,
            ['ID', 'NAME']
        ) : null;
        $emptySection = is_object($emptySectionResult) ? $emptySectionResult->Fetch() : false;
        $emptySectionName = is_array($emptySection)
            ? site_string($emptySection['~NAME'] ?? $emptySection['NAME'] ?? '')
            : '';
        ?>
		<strong class="d-block mb-1">Документы не найдены<?php if ($emptySectionName !== ''): ?> (Раздел: <?=htmlspecialcharsbx($emptySectionName)?>)<?php endif; ?></strong>
        <span class="small">Файлы временно недоступны, находятся на обновлении или будут добавлены позже.</span>
    </div>
</div>
<?php endif;?>
