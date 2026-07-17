<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

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
<?if(!empty($arResult['ITEMS'])):?>
<?
$galleryId = 'files-' . $this->randString();
$showImageImmediately = ($arParams['SHOW_IMAGE_IMMEDIATELY'] ?? 'N') === 'Y';
?>

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
    <?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<div class="glass-files mt-2 mb-2">
    <?
    $section = CIBlockSection::GetByID($arParams['SECTION_ID']);
    if (($arSection = $section->GetNext()) && ($arParams['SHOW_SECTION_NAME']=="Y")):
    ?>
    <div class="glass-section-header mb-4">
        <h2 class="glass-section-title h4 mb-2"><?=$arSection['NAME']?></h2>
        <?if($arSection['DESCRIPTION']):?>
            <p class="glass-section-desc text-muted"><?=$arSection['DESCRIPTION']?></p>
        <?endif;?>
        
        <?if($arParams['COLLAPSE_SECTION']=="Y"):?>
        <div class="mt-3">
            <button
                class="glass-collapse-btn"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#section-collapse-<?=$arParams['SECTION_ID']?>"
                aria-expanded="false"
                aria-controls="section-collapse-<?=$arParams['SECTION_ID']?>">
                <i class="bi bi-chevron-down me-1"></i>Показать/скрыть материалы
            </button>
        </div>
        <?endif;?>
    </div>
    <?endif;?>
    
    <?if($arParams['COLLAPSE_SECTION']=="Y"):?>
    <div class="collapse glass-section-collapse" id="section-collapse-<?=$arParams['SECTION_ID']?>">
    <?endif;?>
    
    <div class="glass-files-grid">
        <?foreach($arResult["ITEMS"] as $arItem):
            $showDate = $arParams['SHOW_DATE'] !== 'N';
            $dateField = $arParams['DATE_FIELD'] ?? 'TIMESTAMP_X';
            $dateValue = $arItem[$dateField] ?? $arItem['TIMESTAMP_X'];
            $description = $arItem['PREVIEW_TEXT'] ?? $arItem['DETAIL_TEXT'] ?? '';
        ?>
        <div class="glass-file-card">
            <div class="glass-file-card-body">
                <div class="glass-file-header mb-3">
                    <h5 class="glass-file-title mb-1"><?=$arItem["NAME"]?></h5>
                    <?if($description && false):?>
                        <p class="glass-file-desc"><?=TruncateText($description, 150)?></p>
                    <?endif;?>
                </div>

                <?if($showDate && $dateValue):?>
                <div class="glass-file-date mb-3">
                    <i class="bi bi-clock me-1"></i>
                    Обновлено: <?=FormatDate("d.m.Y H:i", MakeTimeStamp($dateValue))?>
                </div>
                <?endif;?>

                <div class="glass-file-list">
                    <?php 
                    if (!empty($arItem['PROPERTIES']['FILES']['VALUE'])):
                        if (!is_array($arItem['PROPERTIES']['FILES']['VALUE'])) {
                            $fileIDs = array($arItem['PROPERTIES']['FILES']['VALUE']);
                        } else {
                            $fileIDs = $arItem['PROPERTIES']['FILES']['VALUE'];
                        }
                        
                        $videoIndex = 0;
                        $photoIndex = 0;
                        foreach ($fileIDs as $fileID):
                            $fileInfo = CFile::GetFileArray($fileID);
                            if ($fileInfo) {
                                $fileExtension = strtolower(GetFileExtension($fileInfo["FILE_NAME"]));
                                $fileSize = CFile::FormatSize($fileInfo["FILE_SIZE"]);
                                $fileIcon = getFileIconClass($fileExtension);
                    ?>
                    <div class="glass-file-item">
                        <?if(in_array($fileExtension, ['mp4', 'webm', 'ogg'])):?>
                        <?$videoIndex++;?>
                        <div class="glass-video-file">
                            <div class="glass-file-header-line d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-play-btn me-2 glass-accent-icon"></i>
                                    <span class="fw-medium">Видеофайл</span>
                                </div>
                                <button class="glass-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#video-<?=$arItem['ID']?>-<?=$videoIndex?>" aria-expanded="false">
                                    <i class="bi bi-eye me-1"></i>Показать видео
                                </button>
                            </div>
                            <div class="glass-file-meta d-flex flex-wrap gap-2 mb-2">
                                <span class="glass-file-badge"><?=$fileExtension?></span>
                                <span class="glass-file-size"><?=$fileSize?></span>
                            </div>
                            <div class="collapse" id="video-<?=$arItem['ID']?>-<?=$videoIndex?>">
                                <div class="glass-video-container mt-2">
                                    <video class="glass-video" controls preload="metadata">
                                        <source src="<?=$fileInfo['SRC']?>" type="video/<?=$fileExtension?>">
                                        Ваш браузер не поддерживает видео.
                                    </video>
                                </div>
                            </div>
                        </div>
                        <?elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'])):?>
                        <?$photoIndex++;?>
                        <?$galleryCaption = !empty($fileInfo['DESCRIPTION']) ? $fileInfo['DESCRIPTION'] : (!empty($arItem['NAME']) ? $arItem['NAME'] : ($fileInfo['ORIGINAL_NAME'] ?? ''));?>
                        <div class="glass-photo-file">
                            <div class="glass-file-header-line d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-image me-2 glass-accent-icon"></i>
                                    <span class="fw-medium">Изображение</span>
                                </div>
                                <?if(!$showImageImmediately):?>
                                    <button class="glass-toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#photo-<?=$arItem['ID']?>-<?=$photoIndex?>" aria-expanded="false">
                                        <i class="bi bi-eye me-1"></i>Показать изображение
                                    </button>
                                <?endif;?>
                            </div>
                            <div class="glass-file-meta d-flex flex-wrap gap-2 mb-2">
                                <span class="glass-file-badge"><?=$fileExtension?></span>
                                <span class="glass-file-size"><?=$fileSize?></span>
                                <span class="glass-image-dimensions">
                                    <?=$fileInfo['WIDTH'] ?? '?'?> × <?=$fileInfo['HEIGHT'] ?? '?'?> px
                                </span>
                            </div>
                            <div<?if(!$showImageImmediately):?> class="collapse" id="photo-<?=$arItem['ID']?>-<?=$photoIndex?>"<?endif;?>>
                                <div class="glass-image-container mt-2 text-center gallery-media">
                                    <a href="<?=htmlspecialcharsbx($fileInfo['SRC'])?>"
                                       class="gallery-expand-button glass-image-expand"
                                       data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                                       data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>" data-type="image"
                                       aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $galleryCaption)?>">
                                        <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
                                    </a>
                                    <img src="<?=!empty($fileInfo['SRC']) ? $fileInfo['SRC'] : ''?>" 
                                         alt="<?=htmlspecialcharsbx(
                                             !empty($fileInfo['DESCRIPTION']) ? $fileInfo['DESCRIPTION'] : 
                                             (!empty($arItem['NAME']) ? $arItem['NAME'] : 
                                             (!empty($fileInfo['ORIGINAL_NAME']) ? $fileInfo['ORIGINAL_NAME'] : ''))
                                         )?>" 
                                         class="glass-image img-fluid rounded"
                                         loading="lazy">
                                </div>
                            </div>
                        </div>
                        <?else:?>
                        <a target="_blank" class="glass-download-link" href="<?=$fileInfo['SRC']?>" rel="noopener noreferrer">
                            <div class="glass-file-icon">
                                <i class="bi <?=$fileIcon?>"></i>
                            </div>
                            <div class="glass-file-info">
								<div class="glass-file-name"><?=$description?$description:$arItem["NAME"]?></div>
                                <div class="glass-file-meta">
                                    <span class="glass-file-type text-uppercase"><?=$fileExtension?></span>
                                    <span class="glass-file-size"><?=$fileSize?></span>
                                </div>
                            </div>
                            <div class="glass-download-icon">
                                <i class="bi bi-download"></i>
                            </div>
                        </a>
                        <?endif?>
                    </div>
                    <?php
                            }
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <?endforeach;?>
    </div>
    
    <?if($arParams['COLLAPSE_SECTION']=="Y"):?>
    </div>
    <?endif;?>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <div class="glass-pagination mt-4">
        <?=$arResult["NAV_STRING"]?>
    </div>
<?endif;?>

<?else:?>
<div class="glass-empty-alert">
    <div class="glass-empty-icon">
        <i class="bi bi-folder-x"></i>
    </div>
    <div>
		<strong class="d-block mb-1">Документы не найдены (Раздел: <?=CIBlockSection::GetByID($arParams['SECTION_ID'])->GetNext()["NAME"]?>)</strong>
        <span class="small">Файлы временно недоступны, находятся на обновлении или будут добавлены позже.</span>
    </div>
</div>
<?endif;?>
