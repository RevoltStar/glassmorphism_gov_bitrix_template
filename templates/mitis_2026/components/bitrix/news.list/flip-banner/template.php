<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?php if(!empty($arResult["ITEMS"])):?>
	<?php $galleryId = 'flip-banner-' . $this->randString(); ?>
	<div class="banner-grid">
		<?php foreach ($arResult["ITEMS"] as $key=>$banner): 
			// Определяем изображение для галереи
			$galleryImage = $banner['PREVIEW_PICTURE']['SRC'] ?? "/images/image_not_found.jpg";
			
			// Получаем детальную картинку для галереи (если есть)
			$detailImage = $banner['DETAIL_PICTURE']['SRC'] ?? $galleryImage;
			
			// Формируем подпись для галереи
			$caption = $banner["NAME"];
			if (!empty($banner["PREVIEW_TEXT"])) {
				$caption .= " - " . strip_tags($banner["PREVIEW_TEXT"]);
			}
		?>
			<div class="gallery-media">
				<!-- Кнопка для FancyBox-->
				<a href="<?=htmlspecialcharsbx($detailImage)?>"
				   class="gallery-expand-button me-2 mt-2"
				   data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
				   data-gallery-caption="<?=htmlspecialcharsbx($caption)?>" data-type="image"
				   aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $caption)?>">
					<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
				</a>
				<!-- Основная ссылка карточки (теперь оборачивает весь контент) -->
				<a href="<?=htmlspecialchars($banner['PROPERTIES']['LINK']['VALUE'] ?: "#")?>"
				   class="glass-flip-card"
				   aria-label="<?=htmlspecialchars($banner["NAME"])?>"
				   title="Перейти по ссылке">
					<div class="glass-flip-card-inner">
						<div class="glass-flip-front" 
							 style="background-image: url('<?=htmlspecialchars($galleryImage)?>');">
							<div class="front-icon">
								<i class="bi bi-building"></i>
							</div>
						</div>
						<div class="glass-flip-back">
							<div class="banner-title"><?=htmlspecialchars($banner["NAME"])?></div>
							<div class="banner-desc"><?=htmlspecialchars($banner["PREVIEW_TEXT"] ?? '', ENT_NOQUOTES)?></div>
						</div>
					</div>
				</a>

			</div>
		<?php endforeach;?>
	</div>
<?php endif;?>
