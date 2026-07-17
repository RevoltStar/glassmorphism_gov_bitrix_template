<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?if(!empty($arResult["ITEMS"])):?>
<?
	$name_total = "Общая готовность";
	$progress_total = "0";
	$link = "#";
?>
<div class="row align-items-center">
	 <div class="col-lg-6">
	<?foreach($arResult["ITEMS"] as $key=>$national):?>
		<?
			if($national["NAME"]==$name_total){
				if (!empty($national['PROPERTIES']['PROGRESS']['VALUE'])){
						$progress_total = $national['PROPERTIES']['PROGRESS']['VALUE'];
				}
				if (!empty($national['PROPERTIES']['LINK']['VALUE'])){
						$link = $national['PROPERTIES']['LINK']['VALUE'];
				}
				continue;
			}
		?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2 align-items-center">
    						<span class="fw-bold" style="color: #1e3a5f;">
        						<?=htmlspecialchars($national['NAME'], ENT_NOQUOTES)?>
    						</span>
    						<div class="d-flex align-items-center gap-3">
        						<span style="color: #2980b9; font-weight: 600;"><?=htmlspecialchars($national['PROPERTIES']['PROGRESS']['VALUE'])??"0"?>%</span>
        						<?php if (!empty($national['PROPERTIES']['LINK']['VALUE'])): ?>
            					<a href="<?=htmlspecialchars($national['PROPERTIES']['LINK']['VALUE'])?>" 
               						class="btn btn-sm btn-outline-primary"  
               						style="border-radius: 20px; padding: 2px 12px; font-size: 12px;">
                					<i class="fas fa-arrow-right" aria-hidden="true"></i>
            					</a>
        						<?php endif; ?>
    						</div>
						</div>
						<div class="gap-2 d-flex flex-wrap">
						<?php if (!empty($national['PROPERTIES']['BADGES']["VALUE"])): ?>
							<?foreach($national['PROPERTIES']['BADGES']["VALUE"] as $line):?>
                        		<span class="badge main-badge"><?=$line?></span>
							<?endforeach?>
						<?endif?>
		 				</div>
                    </div>
	<?endforeach?>
	</div>
	<div class="col-lg-6">
    	<div class="text-center p-4">
                        <i class="fas fa-chart-line fa-4x mb-3"  aria-hidden="true" style="color: #3498db;"></i>
                        <h4 class="fw-bold" style="color: #0a3144;"><?=htmlspecialchars($name_total)?></h4>
                        <p class="display-3 fw-bold" style="color: #2980b9;"><?=htmlspecialchars($progress_total)?>%</p>
                        <p style="color: #2c6b9e;">План на текущий год выполнен на <?=htmlspecialchars($progress_total)?>%</p>
						<a href="<?=htmlspecialchars($link)?>" class="btn btn-glass-blue mt-2">Подробнее о проектах</a>
         </div>
     </div>
</div>
<?endif?>
