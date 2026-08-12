<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?if(!empty($arResult['ITEMS'])):?>

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<div class="mt-2 mb-2">
	<?
	// Получаем информацию о разделе
	$section = CIBlockSection::GetByID($arParams['SECTION_ID']);
	if (($arSection = $section->GetNext()) && ($arParams['SHOW_SECTION_NAME']=="Y")):
	?>
	<div class="section-header mb-3">
		<h2 class="section-title h4 mb-2"><?=$arSection['NAME']?></h2>
		<?if($arSection['DESCRIPTION']):?>
			<p class="section-description text-muted"><?=$arSection['DESCRIPTION']?></p>
		<?endif;?>
	</div>
	<?endif;?>
	
	<div class="d-grid gap-3">
    <?foreach($arResult["ITEMS"] as $arItem):
		$link = $arItem['PROPERTIES']['LINK']['VALUE'] ?? '#';
		$isExternal = !empty($link) && $link !== '#';
		$target = $isExternal ? '_blank' : '_self';
		$rel = $isExternal ? 'noopener noreferrer' : '';
		$linkClass = $isExternal ? 'text-decoration-none' : 'text-decoration-none disabled-link';
		
		// Проверяем наличие иконки
		$iconClass = $arItem['PROPERTIES']['ICON']['VALUE'] ?? 'bi-box-arrow-up-right';
		
		// Проверяем наличие описания
		$description = $arItem['PREVIEW_TEXT'] ?? $arItem['DETAIL_TEXT'] ?? '';
		
		// Проверяем наличие даты создания/изменения
		$showDate = $arParams['SHOW_DATE'] !== 'N';
		$dateField = $arParams['DATE_FIELD'] ?? 'TIMESTAMP_X';
		$dateValue = $arItem[$dateField] ?? $arItem['TIMESTAMP_X'];
    ?>
	<a href="<?=$link?>" target="<?=$target?>" rel="<?=$rel?>" class="<?=$linkClass?>" <?if(!$isExternal):?>onclick="return false;"<?endif;?>>
    <div class="card link-card h-100 <?=!$isExternal ? 'card-disabled' : ''?>">
        <div class="card-body">
            <div class="d-flex align-items-start">
				<?if($iconClass):?>
					<i class="bi <?=$iconClass?> me-3 mt-1 flex-shrink-0 text-primary"></i>
				<?endif;?>
				<div class="flex-grow-1">
					<h5 class="card-title mb-1"><?=$arItem["NAME"]?></h5>
					<?if($description):?>
						<p class="card-text text-muted small mb-2"><?=TruncateText($description, 120)?></p>
					<?endif;?>
					
					<?if($showDate && $dateValue && ($arParams['SHOW_UPDATE_DATE']??'Y')!="N"):?>
					<div class="text-muted small">
						<i class="bi bi-clock me-1"></i>
						Обновлено: <?=FormatDate("d.m.Y H:i", MakeTimeStamp($dateValue))?>
					</div>
					<?endif;?>
				</div>
				<?if($isExternal):?>
					<i class="bi bi-arrow-up-right ms-2 flex-shrink-0 text-muted"></i>
				<?endif;?>
			</div>
        </div>
    </div>
	</a>
    <?endforeach;?>
	</div>
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
            <strong class="d-block mb-1">Ссылки не найдены</strong>
            <span class="small">Ссылки временно недоступны, находятся на обновлении или будут добавлены позже.</span>
        </div>
    </div>
</div>
<?endif;?>