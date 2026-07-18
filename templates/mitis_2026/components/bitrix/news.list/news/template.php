<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$newsCategories = is_array($arResult['NEWS_CATEGORIES'] ?? null)
	? $arResult['NEWS_CATEGORIES']
	: [];
?>
<?php if(($arParams["DISPLAY_TOP_PAGER"] ?? 'N') === 'Y'):?>
	<?=$arResult["NAV_STRING"] ?? ''?><br />
<?php endif;?>
<?php if (($arParams["SHOW_CATEGORY_FILTER"] ?? "N") === "Y" && $newsCategories !== []): ?>
	<nav class="news-category-filter mb-4" aria-label="Фильтр новостей по рубрикам">
		<a href="/news/" class="news-category-filter__item<?=empty($arParams["CATEGORY_CODE"]) ? " is-active" : ""?>">
			Все новости
		</a>
		<?php foreach ($newsCategories as $category):
			if (!is_array($category)) {
				continue;
			}
			$categoryXmlId = site_string($category['XML_ID'] ?? '');
			$categoryValue = site_string($category['VALUE'] ?? '');
			if ($categoryXmlId === '' || $categoryValue === '') {
				continue;
			}
		?>
			<a href="/news/category/<?=rawurlencode($categoryXmlId)?>/"
			   class="news-category-filter__item<?=(site_string($arParams["CATEGORY_CODE"] ?? '') === $categoryXmlId) ? " is-active" : ""?>">
				<?=htmlspecialcharsbx($categoryValue)?>
			</a>
		<?php endforeach; ?>
	</nav>
<?php endif; ?>
<?php if(!empty($arResult["ITEMS"]) && is_array($arResult["ITEMS"])):?>
	<?php
	//Массив названий месяцев (в родительном падеже) для поля даты
	$months = [1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
	$galleryId = 'news-list-' . $this->randString();
	?>
	<div class="row g-4">
	<?php foreach($arResult["ITEMS"] as $key=>$news):?>
		<?php
		if (!is_array($news)) {
            continue;
        }
        $detailPageUrl = site_url($news['DETAIL_PAGE_URL'] ?? null);
        $previewPictureSrc = site_url($news['PREVIEW_PICTURE']['SRC'] ?? null, '/images/image_not_found.jpg');
        $gallerySrc = site_url($news['DETAIL_PICTURE']['SRC'] ?? null, $previewPictureSrc);
		$galleryCaption = site_string($news['~NAME'] ?? $news['NAME'] ?? '');
		?>
		<div class="col-lg-4 <?php if($key==0 && count($arResult["ITEMS"])%2!=0):?>col-md-12<?php else:?>col-md-6<?php endif?>">
				<div class="news-card gallery-media">
					<!-- Кнопка увеличения для FancyBox (вне ссылки!) -->
					<a href="<?=htmlspecialcharsbx($gallerySrc)?>" class="gallery-expand-button mt-2 me-2"
					   data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
					   data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>" data-type="image"
					   aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $galleryCaption)?>">
						<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
					</a>

					<!-- Ссылка на детальную страницу с изображением -->
					<a href="<?=htmlspecialcharsbx($detailPageUrl)?>" class="news-img-link">
						<div class="news-img">
							<!-- Единое изображение: видимое + источник для FancyBox -->
							<img src="<?=htmlspecialcharsbx($previewPictureSrc)?>"
								 class="news-img-object"
								 alt="<?=htmlspecialcharsbx(site_string($news['PREVIEW_PICTURE']['ALT'] ?? $galleryCaption))?>"
								 loading="lazy">
							<?php
								$d = strtotime(site_string($news['ACTIVE_FROM_X'] ?? ''));
							?>
							<span class="news-date">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?php if ($d !== false): ?><?=htmlspecialcharsbx(date('d', $d) . ' ' . ($months[(int)date('m', $d)] ?? '') . ' ' . date('Y', $d) . ' года')?><?php endif; ?>
                            </span>
						</div>
					</a>

                    <div class="p-4">
						<?php
						$categoryValues = (array)($news["PROPERTIES"]["category"]["VALUE"] ?? array());
						$categoryXmlIds = (array)($news["PROPERTIES"]["category"]["VALUE_XML_ID"] ?? array());
						$newsCategories = array();
						foreach ($categoryValues as $categoryIndex => $categoryValue) {
							$categoryValue = site_string($categoryValue);
							$categoryXmlId = site_string($categoryXmlIds[$categoryIndex] ?? '');
							if ($categoryValue === false || $categoryValue === null || $categoryValue === "" || $categoryXmlId === "") {
								continue;
							}
							$newsCategories[] = array(
								"VALUE" => $categoryValue,
								"XML_ID" => $categoryXmlId,
							);
						}
						?>
						<?php if (!empty($newsCategories)): ?>
							<div class="mb-2">
								<?php foreach ($newsCategories as $category): ?>
									<a href="/news/category/<?=rawurlencode($category["XML_ID"])?>/" class="news-category text-decoration-none">
										<i class="bi bi-tag me-1"></i><?=htmlspecialcharsbx($category["VALUE"])?>
									</a>
								<?php endforeach; ?>
							</div>
						<?php else: ?>
							<div class="mb-2">
								<span class="news-category"><i class="bi bi-tag me-1"></i>Без рубрики</span>
							</div>
						<?php endif; ?>
						<a class="news-name text-decoration-none" href="<?=htmlspecialcharsbx($detailPageUrl)?>">
							<h5 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialcharsbx($galleryCaption)?></h5>
						</a>
                        <p class="mb-3" style="color: #2c6b9e;"><?=htmlspecialcharsbx(mb_strimwidth(site_plain_text($news['~PREVIEW_TEXT'] ?? $news['PREVIEW_TEXT'] ?? ''), 0, 150, '...'))?></p>
                        <a href="<?=htmlspecialcharsbx($detailPageUrl)?>" class="btn btn-outline-primary btn-sm rounded-pill" aria-label="Подробнее о новости <?=htmlspecialcharsbx($galleryCaption)?>">Подробнее</a>
                    </div>
                </div>
            </div>
	<?php endforeach?>
	</div>
<?php else:?>
	<div class="news-empty-state" role="status">
		<i class="bi bi-info-circle me-2" aria-hidden="true"></i>
		Новостей по указанным критериям не найдено
	</div>
<?php endif?>
<?php if(($arParams["DISPLAY_BOTTOM_PAGER"] ?? 'N') === 'Y'):?>
	<br /><?=$arResult["NAV_STRING"] ?? ''?>
<?php endif;?>
