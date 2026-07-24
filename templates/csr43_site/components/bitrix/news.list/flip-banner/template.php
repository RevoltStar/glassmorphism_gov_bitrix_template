<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<?php
$items = is_array($arResult['ITEMS'] ?? null)
	? array_values(array_filter($arResult['ITEMS'], 'is_array'))
	: [];
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
				   class="csr43-glass-surface csr43-glass-card--interactive gallery-expand-button me-2 mt-2"
				   data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
				   data-gallery-caption="<?=htmlspecialcharsbx($caption)?>" data-type="image"
				   aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $caption)?>">
					<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
				</a>
				<!-- Основная ссылка карточки (теперь оборачивает весь контент) -->
				<a href="<?=htmlspecialcharsbx(site_url($banner['PROPERTIES']['LINK']['VALUE'] ?? null))?>"
				   class="flip-card"
				   aria-label="<?=htmlspecialcharsbx($name)?>"
				   title="Перейти по ссылке">
					<div class="flip-card__inner">
						<div class="csr43-glass-surface flip-card__face flip-card__front"
							 style="background-image: url(<?=htmlspecialcharsbx(site_css_url($galleryImage, $fallbackImage))?>);">
							<div class="csr43-glass-icon flip-card__icon">
								<i class="bi bi-building"></i>
							</div>
						</div>
						<div class="csr43-glass-surface flip-card__face flip-card__back">
							<div class="flip-card__title"><?=htmlspecialcharsbx($name)?></div>
							<div class="flip-card__description"><?=htmlspecialcharsbx($description)?></div>
						</div>
					</div>
				</a>
			</div>
		<?php endforeach;?>
	</div>
<?php else: ?>
	<div class="csr43-glass-surface rounded-4 p-4 text-center text-muted" role="status">
		<i class="bi bi-info-circle me-2" aria-hidden="true"></i>
		Информационные материалы отсутствуют. Актуальная информация будет размещена в ближайшее время.
	</div>
<?php endif;?>
