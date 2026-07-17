<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?php if (($arParams["SHOW_CATEGORY_FILTER"] ?? "N") === "Y" && !empty($arResult["NEWS_CATEGORIES"])): ?>
	<nav class="news-category-filter mb-4" aria-label="Фильтр новостей по рубрикам">
		<a href="/news/" class="news-category-filter__item<?=empty($arParams["CATEGORY_CODE"]) ? " is-active" : ""?>">
			Все новости
		</a>
		<?php foreach ($arResult["NEWS_CATEGORIES"] as $category): ?>
			<a href="/news/category/<?=rawurlencode($category["XML_ID"])?>/"
			   class="news-category-filter__item<?=($arParams["CATEGORY_CODE"] === $category["XML_ID"]) ? " is-active" : ""?>">
				<?=htmlspecialcharsbx($category["VALUE"])?>
			</a>
		<?php endforeach; ?>
	</nav>
<?php endif; ?>
<?if(!empty($arResult["ITEMS"]) && is_array($arResult["ITEMS"])):?>
	<?
	//Массив названий месяцев (в родительном падеже) для поля даты
	$months = [1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
	$galleryId = 'news-list-' . $this->randString();
	?>
	<div class="row g-4">
	<?foreach($arResult["ITEMS"] as $key=>$news):?>
		<?
		$gallerySrc = $news['DETAIL_PICTURE']['SRC'] ?? ($news['PREVIEW_PICTURE']['SRC'] ?? '/images/image_not_found.jpg');
		$galleryCaption = $news['NAME'];
		?>
		<div class="col-lg-4 <?if($key==0 && count($arResult["ITEMS"])%2!=0):?>col-md-12<?else:?>col-md-6<?endif?>">
				<div class="news-card gallery-media">
					<!-- Кнопка увеличения для FancyBox (вне ссылки!) -->
					<a href="<?=htmlspecialcharsbx($gallerySrc)?>" class="gallery-expand-button mt-2 me-2"
					   data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
					   data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>" data-type="image"
					   aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $galleryCaption)?>">
						<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
					</a>
					
					<!-- Ссылка на детальную страницу с изображением -->
					<a href="<?= htmlspecialchars($news['DETAIL_PAGE_URL']) ?>" class="news-img-link">
						<div class="news-img">
							<!-- Единое изображение: видимое + источник для FancyBox -->
							<img src="<?=$news['PREVIEW_PICTURE']['SRC'] ? htmlspecialchars($news['PREVIEW_PICTURE']['SRC']) : '/images/image_not_found.jpg' ?>"
								 class="news-img-object"
								 alt="<?= htmlspecialchars($news['PREVIEW_PICTURE']['ALT'] ?? $news['NAME']) ?>" 
								 loading="lazy">
							<?php
								$d = strtotime($news['ACTIVE_FROM_X']);
							?>
							<span class="news-date">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?= date('d', $d) . ' ' . $months[(int)date('m', $d)] . ' ' . date('Y', $d) . ' года' ?>
                            </span>
						</div>
					</a>
					
                    <div class="p-4">
						<?php
						$categoryValues = (array)($news["PROPERTIES"]["category"]["VALUE"] ?? array());
						$categoryXmlIds = (array)($news["PROPERTIES"]["category"]["VALUE_XML_ID"] ?? array());
						$newsCategories = array();
						foreach ($categoryValues as $categoryIndex => $categoryValue) {
							$categoryXmlId = $categoryXmlIds[$categoryIndex] ?? "";
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
						<a class="news-name text-decoration-none" href="<?= htmlspecialchars($news['DETAIL_PAGE_URL']) ?>">
							<h5 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialchars($news['NAME'], ENT_XML1 | ENT_QUOTES, 'UTF-8', false);?></h5>
						</a>
                        <p class="mb-3" style="color: #2c6b9e;"><?= htmlspecialchars(mb_strimwidth(strip_tags($news['PREVIEW_TEXT']), 0, 150, '...')) ?></p>
                        <a href="<?= htmlspecialchars($news['DETAIL_PAGE_URL']) ?>" class="btn btn-outline-primary btn-sm rounded-pill" aria-label="Подробнее о новости <?=htmlspecialchars($news['NAME'], ENT_XML1 | ENT_QUOTES, 'UTF-8', false);?>">Подробнее</a>
                    </div>
                </div>
            </div>
	<?endforeach?>
	</div>
<?else:?>
	<div class="news-empty-state" role="status">
		<i class="bi bi-info-circle me-2" aria-hidden="true"></i>
		Новостей по указанным критериям не найдено
	</div>
<?endif?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
