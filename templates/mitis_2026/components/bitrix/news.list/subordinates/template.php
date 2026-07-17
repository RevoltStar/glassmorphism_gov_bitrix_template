<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?php if(!empty($arResult["ITEMS"])):?>
	<div class="row g-4">
		<?php foreach ($arResult["ITEMS"] as $key=>$sub): ?>
			<?
			$link = "#";
			$src = "";
			?>
			<?if (!empty($sub['PROPERTIES']['LOGO']['VALUE'])): ?>
            	<?
					$file = CFile::GetFileArray($sub['PROPERTIES']['LOGO']['VALUE']);
					$src = $file["SRC"];
				?>
			<?endif?>
			<?if (!empty($sub['PROPERTIES']['LINK']['VALUE'])): ?>
            	<?$link = $sub['PROPERTIES']['LINK']['VALUE'];?>
			<?endif?>
			<div class="col-md-6">
                <div class="glass-card d-flex align-items-center">
					<?php if(!empty($src)): ?>
					<div class="position-absolute d-block d-md-none" style="right:10px;top:10px;">
						<img style="width:40px; opacity: 0.5;" src="<?=$src?>" alt="Логотип <?=htmlspecialchars($sub['NAME'])?>">
					</div>
					<?endif?>
                    <div class="me-4 d-none d-md-block">
                        <div class="suborg-icon">
							<?php if(!empty($src)): ?>
							<a href="<?=$link?>">
								<img src="<?=$src?>" alt="Логотип <?=htmlspecialchars($sub['NAME'])?>">
							</a>
							<?else:?>
                            <i class="bi bi-building fs-1"></i>
							<?endif?>
                        </div>
                    </div>
                    <div>
                        <h4 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialchars($sub['NAME'])?></h4>
                        <p style="color: #2c6b9e;"><?=htmlspecialchars($sub['PREVIEW_TEXT'])?></p>
						<?if (!empty($sub['PROPERTIES']['BADGES']["VALUE"])): ?>
                        <div class="d-flex gap-2 flex-wrap">
							<?foreach($sub['PROPERTIES']['BADGES']["VALUE"] as $line):?>
                        		<span class="badge sub-badge"><?=$line?></span>
							<?endforeach?>
                        </div>
						<?endif?>
                        <a href="<?=$link?>" class="btn btn-sm btn-outline-primary rounded-pill mt-2">Перейти на сайт</a>
                    </div>
                </div>
            </div>
		<?endforeach?>
	</div>
<?endif?>