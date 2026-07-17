<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?php if(!empty($arResult["ITEMS"])):?>
	<div class="row g-4">
		<?php foreach ($arResult["ITEMS"] as $key=>$actual): ?>
			<div class="col-md-4">
				<?
					$link = "#";
					if(!empty($actual["PROPERTIES"]["LINK"]["VALUE"])){
						$link = $actual["PROPERTIES"]["LINK"]["VALUE"];
					}
				?>
				 <a href="<?=$link?>" class="text-decoration-none">
					 <div class="banner-item" style="background-image: url('<?=$actual['PREVIEW_PICTURE']['SRC']?htmlspecialchars($actual['PREVIEW_PICTURE']['SRC']):"/images/image_not_found.jpg" ?>');">
                        <div class="banner-overlay">
                            <h5 class="fw-bold"><?=htmlspecialchars($actual['NAME'])?></h5>
                            <p class="mb-0 small fw-semibold"><?=htmlspecialchars($actual['PREVIEW_TEXT'])?></p>
                        </div>
                    </div>
                </a>
			</div>
		<?endforeach?>
	</div>
<?endif?>