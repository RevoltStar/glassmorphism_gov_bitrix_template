<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?php if(!empty($arResult["ITEMS"])):?>
	<?
	//Добавляем фиктивный элемент
	$arResult["ITEMS"][] = 
	array(
		"NAME"=>"Посмотреть все проекты",
		"PREVIEW_TEXT"=>"Нажмите, чтобы перейти",
		"PROPERTIES"=>array(
			"ICON"=>array("VALUE"=>"fa fa-arrow-right"),
			"LINK"=>array("VALUE"=>"/activity/implemintation-of-regional-projects/")));
	?>
	<div class="row g-4">
		<?php foreach ($arResult["ITEMS"] as $key=>$project): ?>
			<div class="col-md-6">
				<a href="<?=htmlspecialchars($project['PROPERTIES']['LINK']['VALUE']?$project['PROPERTIES']['LINK']['VALUE']:"#")?>" class="text-decoration-none">
				<div class="glass-card d-flex">
                    <div class="me-3">
						<?php if (!empty($project['PROPERTIES']['ICON']['VALUE'])): ?>
                        <div class="activity-icon" style="width: 60px; height: 60px;">
                            <i class="<?=$project['PROPERTIES']['ICON']['VALUE']?>" aria-hidden="true"></i>
                        </div>
						 <?php endif; ?>
                    </div>
                    <div class="w-100">
                        <h5 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialchars($project['NAME'], ENT_NOQUOTES)?></h5>
                        <p style="color: #2c6b9e;"><?= htmlspecialchars($project['PREVIEW_TEXT'], ENT_NOQUOTES) ?></p>
						<div class="gap-2 d-flex flex-wrap">
						<?php if (!empty($project['PROPERTIES']['BADGES']["VALUE"])): ?>
							<?foreach($project['PROPERTIES']['BADGES']["VALUE"] as $line):?>
                        		<span class="badge main-badge"><?=$line?></span>
							<?endforeach?>
						<?endif?>
						</div>
                    </div>
                </div>
				</a>
            </div>
		<?endforeach?>
	</div>
<?endif?>