<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?php foreach ($arResult["ITEMS"] as $service): ?>
    <?php
    $images = array();
    
    if (!empty($service['PROPERTIES']['IMAGES']['VALUE']) && is_array($service['PROPERTIES']['IMAGES']['VALUE'])) {
        foreach ($service['PROPERTIES']['IMAGES']['VALUE'] as $fileID) {
            $fileInfo = CFile::GetFileArray($fileID);
            $images[] = array(
                'NAME' => pathinfo($fileInfo['DESCRIPTION'], PATHINFO_FILENAME), 
                'SRC' => $fileInfo['SRC']
            );
            
            // Ограничиваем максимум 6 элементов
            if (count($images) >= 6) {
                break;
            }
        }
    }

    // Добиваем массив до 6 элементов заглушками
    while (count($images) < 6) {
        $images[] = array(
            'NAME' => 'ЭТАП', 
            'SRC' => '/images/image_not_found.jpg'
        );
    }
    ?>

<div class="row mb-5">
	<div class="col-12">
        <h3 class="text-lg-start text-center"><?=htmlspecialchars($service['NAME'])?></h3> <!-- Выравнивание по центру на мобильных -->
    </div>
	<div class="col-lg-4 col-12 d-flex justify-content-center mb-3 mb-lg-0">
		<div class="cube-3d-container" aria-hidden="true">
    		<div class="cube-3d-box" title="Пока мышка наведена, кубик остается неподвижным.">
				<div class="cube-3d">
					<div class="cube-3d-side cube-3d-front" style="background-image: url('<?=$images[0]['SRC']?>')"><span class="cube-text"><?=$images[0]['NAME']?></span></div>
            		<div class="cube-3d-side cube-3d-back" style="background-image: url('<?=$images[2]['SRC']?>')"><span class="cube-text"><?=$images[2]['NAME']?></span></div>
            		<div class="cube-3d-side cube-3d-right" style="background-image: url('<?=$images[1]['SRC']?>')"><span class="cube-text"><?=$images[1]['NAME']?></span></div>
            		<div class="cube-3d-side cube-3d-left" style="background-image: url('<?=$images[4]['SRC']?>')"><span class="cube-text"><?=$images[4]['NAME']?></span></div>
            		<div class="cube-3d-side cube-3d-top" style="background-image: url('<?=$images[3]['SRC']?>')"><span class="cube-text"><?=$images[3]['NAME']?></span></div>
            		<div class="cube-3d-side cube-3d-bottom" style="background-image: url('<?=$images[5]['SRC']?>')"><span class="cube-text"><?=$images[5]['NAME']?></span></div>
        		</div>
    		</div>
		</div>
	</div>
	<div class="col-lg-8 col-12 text-lg-start text-center ps-lg-5"> 
		<?=$service['PREVIEW_TEXT']?>
		<div class="mt-3">
        	<a
				href="<?=$service['PROPERTIES']['LINK']['VALUE']?>"
				class="btn btn-secondary"
			aria-label="Подробнее об услуге:<?=htmlspecialchars($service['NAME'])?>"
			>Подробнее об услуге</a>
    	</div>
	</div>
</div>
<?php endforeach; ?>








