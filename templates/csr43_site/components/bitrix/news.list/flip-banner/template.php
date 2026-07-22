<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?php
$items = is_array($arResult['ITEMS'] ?? null) ? $arResult['ITEMS'] : [];
$fallbackImage = site_template_image_url('image_not_found.svg');
if ($items !== []):
?>
	<?php $galleryId = 'flip-banner-' . $this->randString(); ?>
	<div class="banner-grid">
		<?php foreach ($items as $key=>$banner):
			// Определяем изображение для галереи
			if (!is_array($banner)) {
                continue;
            }
            $galleryImage = site_url($banner['PREVIEW_PICTURE']['SRC'] ?? null, $fallbackImage);

			// Получаем детальную картинку для галереи (если есть)
			$detailImage = site_url($banner['DETAIL_PICTURE']['SRC'] ?? null, $galleryImage);

			// Формируем подпись для галереи
			$name = site_string($banner['~NAME'] ?? $banner['NAME'] ?? '');
			$description = site_plain_text($banner['~PREVIEW_TEXT'] ?? $banner['PREVIEW_TEXT'] ?? '');
			$caption = $name;
			if ($description !== '') {
				$caption .= " - " . $description;
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
				<a href="<?=htmlspecialcharsbx(site_url($banner['PROPERTIES']['LINK']['VALUE'] ?? null))?>"
				   class="glass-flip-card"
				   aria-label="<?=htmlspecialcharsbx($name)?>"
				   title="Перейти по ссылке">
					<div class="glass-flip-card-inner">
						<div class="glass-flip-front"
							 style="background-image: url(<?=htmlspecialcharsbx(site_css_url($galleryImage, $fallbackImage))?>);">
							<div class="front-icon">
								<i class="bi bi-building"></i>
							</div>
						</div>
						<div class="glass-flip-back">
							<div class="banner-title"><?=htmlspecialcharsbx($name)?></div>
							<div class="banner-desc"><?=htmlspecialcharsbx($description)?></div>
						</div>
					</div>
				</a>

			</div>
		<?php endforeach;?>
	</div>
<?php endif;?>
