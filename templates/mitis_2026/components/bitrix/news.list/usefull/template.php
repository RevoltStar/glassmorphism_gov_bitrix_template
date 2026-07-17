<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?php if(!empty($arResult["ITEMS"])):?>
	<div class="row g-3">
		<?php foreach ($arResult["ITEMS"] as $key=>$value): ?>
			<?
				$link = "#";
				$icon = "bi bi-arrow-top-right";
			?>
			<?php if (!empty($value['PROPERTIES']['LINK']['VALUE'])): ?>
			<? $link = $value['PROPERTIES']['LINK']['VALUE']; ?>
			<?endif?>
			<?php if (!empty($value['PROPERTIES']['ICON']['VALUE'])): ?>
			<? $icon = $value['PROPERTIES']['ICON']['VALUE']; ?>
			<?endif?>
            <div class="col-md-3 col-6">
                <a href="<?=$link?>" class="resource-link" target="_blank">
					<div class="h-100 resource-link-info-container">
						<div>
                    		<i class="<?=$icon?> fs-5 me-2" style="color: #2980b9;" aria-hidden="true"></i>
							<span><?=$value["NAME"]?></span>
						</div>
						<div class="text-end">
							<span class="small"><?=$link?></span>
						</div>
					</div>
                </a>
            </div>
		<?endforeach?>
     </div>

<?endif?>