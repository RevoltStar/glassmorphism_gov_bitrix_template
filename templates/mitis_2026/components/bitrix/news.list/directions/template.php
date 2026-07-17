<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?php if(!empty($arResult["ITEMS"])):?>
	<?
	//Добавляем фиктивный элемент
	$arResult["ITEMS"][] = array("NAME"=>"Посмотреть все направления деятельности", "PREVIEW_TEXT"=>"", "PROPERTIES"=>array("ICON"=>array("VALUE"=>"fa fa-arrow-right")));
	?>
	<div class="row g-4">
		<?php foreach ($arResult["ITEMS"] as $key=>$dir): ?>
			 <div class="col-md-3 col-sm-6">
                <div class="glass-card text-center">
					<?php if (!empty($dir['PROPERTIES']['ICON']['VALUE'])): ?>
                        <div class="activity-icon mx-auto">
                            <i class="<?=$dir['PROPERTIES']['ICON']['VALUE']?>"></i>
                        </div>
					<?php endif; ?>
                    <h5 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialchars($dir["NAME"])?></h5>
                    <p class="small" style="color: #2c6b9e;"><?=htmlspecialchars($dir["PREVIEW_TEXT"])?></p>
                    <a href="#" class="text-decoration-none small" style="color: #2980b9;">Подробнее <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
		<?endforeach?>
	</div>
<?endif?>