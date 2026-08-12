<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$galleryInstanceId = $this->randString();
?>

<div class="licenses-grid">
    <?foreach($arResult["ITEMS"] as $arItem):?>
	    <?$galleryId = 'license-' . (int)$arItem['ID'] . '-' . $galleryInstanceId;?>
	    <div class="license-card card">
        <div class="card-body">
            <h3 class="license-title"><?=$arItem["NAME"]?></h3>
            <div class="license-images-grid">
                <?php 
                if (!empty($arItem['PROPERTIES']['IMG']['VALUE'])):
                    if (!is_array($arItem['PROPERTIES']['IMG']['VALUE'])) {
                        $fileIDs = array($arItem['PROPERTIES']['IMG']['VALUE']);
                    } else {
                        $fileIDs = $arItem['PROPERTIES']['IMG']['VALUE'];
                    }
                    
	                    foreach ($fileIDs as $fileID):
	                        $fileInfo = CFile::GetFileArray($fileID);
	                        if ($fileInfo) {
							$galleryCaption = $fileInfo['DESCRIPTION'] ?: $arItem['NAME'];
	                ?>
	                <div class="license-image-wrapper">
	                    <div class="license-image-container">
							<a
								href="<?=htmlspecialcharsbx($fileInfo['SRC'])?>"
								class="gallery-item-link"
								data-gallery-item
								data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
								data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>"
								data-type="image"
								aria-label="<?=htmlspecialcharsbx('Открыть: ' . $galleryCaption)?>"
							>
								<img src="<?=$fileInfo['SRC']?>" 
	                             alt="<?=htmlspecialcharsbx($galleryCaption)?>" 
	                             class="license-image"
	                             loading="lazy">
						</a>
                    </div>
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
