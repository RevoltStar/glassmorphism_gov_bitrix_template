<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$galleryId = 'files-' . (int)$arParams['SECTION_ID'] . '-' . $this->randString();

// Вспомогательная функция для определения иконки файла
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

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<div class="mt-2 mb-2">
	<?
	$section = CIBlockSection::GetByID($arParams['SECTION_ID']);
	if (($arSection = $section->GetNext()) && ($arParams['SHOW_SECTION_NAME']=="Y")):
	?>
	<div class="section-header mb-3">
		<h2 class="section-title h4 mb-2"><?=$arSection['NAME']?></h2>
		<?if($arSection['DESCRIPTION']):?>
			<p class="section-description text-muted"><?=$arSection['DESCRIPTION']?></p>
		<?endif;?>
		
		<?if($arParams['COLLAPSE_SECTION']=="Y"):?>
		<div class="mt-3">
			<button
				class="btn btn-outline-primary btn-sm"
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
	<div class="collapse" id="section-collapse-<?=$arParams['SECTION_ID']?>">
	<?endif;?>
	
	<div class="d-grid gap-3">
    <?foreach($arResult["ITEMS"] as $arItem):
		// Проверяем наличие даты
		$showDate = $arParams['SHOW_DATE'] !== 'N';
		$dateField = $arParams['DATE_FIELD'] ?? 'TIMESTAMP_X';
		$dateValue = $arItem[$dateField] ?? $arItem['TIMESTAMP_X'];
		
		// Проверяем наличие описания
		$description = $arItem['PREVIEW_TEXT'] ?? $arItem['DETAIL_TEXT'] ?? '';
    ?>
    <div class="card file-card h-100">
        <div class="card-body">
            <div class="file-header mb-3">
                <h5 class="card-title mb-1"><?=$arItem["NAME"]?></h5>
                <?if($description):?>
                    <p class="card-text text-muted small"><?=TruncateText($description, 150)?></p>
                <?endif;?>
            </div>

            <?if($showDate && $dateValue):?>
            <div class="text-muted small mb-3">
                <i class="bi bi-clock me-1"></i>
                Обновлено: <?=FormatDate("d.m.Y H:i", MakeTimeStamp($dateValue))?>
            </div>
            <?endif;?>

            <div class="file-list">
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
								$galleryCaption = !empty($fileInfo['DESCRIPTION']) ? $fileInfo['DESCRIPTION'] :
									(!empty($arItem['NAME']) ? $arItem['NAME'] :
									(!empty($fileInfo['ORIGINAL_NAME']) ? $fileInfo['ORIGINAL_NAME'] : 'Фото-видео материал'));
                ?>
                <div class="file-item">
					<?if(in_array($fileExtension, ['mp4', 'webm', 'ogg'])):?>
					<?$videoIndex++;?>
					<div class="video-file mb-3">
						<div class="video-header d-flex justify-content-between align-items-center mb-2">
							<div class="d-flex align-items-center">
								<i class="bi bi-play-btn me-2 text-primary"></i>
								<span class="fw-medium">Видеофайл</span>
							</div>
							<button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#video-<?=$arItem['ID']?>-<?=$videoIndex?>" aria-expanded="false" aria-controls="video-<?=$arItem['ID']?>-<?=$videoIndex?>">
								<i class="bi bi-eye me-1"></i>Показать видео
							</button>
						</div>
						<div class="video-info d-flex justify-content-between align-items-center text-muted small mb-2">
							<span class="badge bg-secondary"><?=$fileExtension?></span>
							<span class="file-size"><?=$fileSize?></span>
						</div>
						<div class="collapse" id="video-<?=$arItem['ID']?>-<?=$videoIndex?>">
							<div class="card card-body p-0 border-0">
								<video class="w-100 rounded" controls preload="metadata">
									<source src="<?=$fileInfo['SRC']?>" type="video/<?=$fileExtension?>">
									Ваш браузер не поддерживает видео.
								</video>
							</div>
						</div>
					</div>
					<?elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'])):?>
					<?$photoIndex++;?>
					<div class="photo-file mb-3">
						<div class="photo-header d-flex justify-content-between align-items-center mb-2">
							<div class="d-flex align-items-center">
								<i class="bi bi-image me-2 text-success"></i>
								<span class="fw-medium">Изображение</span>
							</div>
							<button class="btn btn-sm btn-outline-success" type="button" data-bs-toggle="collapse" data-bs-target="#photo-<?=$arItem['ID']?>-<?=$photoIndex?>" aria-expanded="false" aria-controls="photo-<?=$arItem['ID']?>-<?=$photoIndex?>">
								<i class="bi bi-eye me-1"></i>Показать изображение
							</button>
						</div>
						<div class="photo-info d-flex justify-content-between align-items-center text-muted small mb-2">
							<span class="badge bg-success"><?=$fileExtension?></span>
							<span class="file-size"><?=$fileSize?></span>
							<span class="image-dimensions">
								<?=$fileInfo['WIDTH'] ?? '?'?> × <?=$fileInfo['HEIGHT'] ?? '?'?> px
							</span>
						</div>
						<div class="collapse" id="photo-<?=$arItem['ID']?>-<?=$photoIndex?>">
								<div class="card card-body p-0 border-0 text-center gallery-media">
									<a
										href="<?=htmlspecialcharsbx($fileInfo['SRC'])?>"
										class="gallery-expand-button"
										data-gallery-item
										data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
										data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>"
										data-type="image"
										aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $galleryCaption)?>"
									>
										<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
									</a>
									<img src="<?=!empty($fileInfo['SRC']) ? $fileInfo['SRC'] : ''?>" 
	     								alt="<?=htmlspecialcharsbx($galleryCaption)?>" 
	     								class="img-fluid rounded max-height-600"
     								loading="lazy">
							</div>
						</div>
					</div>
					<?else:?>
					<a target="_blank" class="file-link text-decoration-none d-flex align-items-center p-3 rounded" href="<?=$fileInfo['SRC']?>" rel="noopener noreferrer">
						<div class="file-icon me-3">
							<i class="bi <?=$fileIcon?> fs-4 text-primary"></i>
						</div>
						<div class="file-info flex-grow-1">
							<div class="file-name fw-medium"><?=$arItem["NAME"]?></div>
							<div class="file-meta d-flex gap-3 text-muted small">
								<span class="file-type text-uppercase"><?=$fileExtension?></span>
								<span class="file-size"><?=$fileSize?></span>
							</div>
						</div>
						<div class="file-action">
							<i class="bi bi-download fs-5 text-success"></i>
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
	<div class="mt-4">
		<?=$arResult["NAV_STRING"]?>
	</div>
<?endif;?>

<?else:?>
<div class="alert alert-warning mt-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-folder-x me-3 fs-4"></i>
        <div>
            <strong class="d-block mb-1">Документы не найдены</strong>
            <span class="small">Файлы временно недоступны, находятся на обновлении или будут добавлены позже.</span>
        </div>
    </div>
</div>
<?endif;?>
