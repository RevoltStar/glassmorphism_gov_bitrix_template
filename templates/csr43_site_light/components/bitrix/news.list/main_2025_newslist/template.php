<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$galleryId = 'news-list-' . $this->randString();
?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?php if(!empty($arResult["ITEMS"])):?>
    <?php foreach ($arResult["ITEMS"] as $key=>$news): ?>
    <?php $galleryCaption = $news['PREVIEW_PICTURE']['ALT'] ?? $news['NAME']; ?>
    <div class="col-lg-6 col-xl-4 mb-4">
        <div class="card news-card h-100 shadow-sm">
            <!-- Картинка новости -->
            <?php if (!empty($news['PREVIEW_PICTURE']['SRC'])): ?>
            <div class="news-image-container gallery-media">
					<a
						href="<?=htmlspecialcharsbx($news['PREVIEW_PICTURE']['SRC'])?>"
						class="gallery-expand-button me-2 mt-2"
						data-gallery-item
						data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
						data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>"
						data-type="image"
						aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $galleryCaption)?>"
					>
						<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
					</a>
					<a href="<?= htmlspecialchars($news['DETAIL_PAGE_URL']) ?>">
	                	<img src="<?= htmlspecialchars($news['PREVIEW_PICTURE']['SRC']) ?>" 
	                     	alt="<?=htmlspecialcharsbx($galleryCaption)?>" 
	                     	class="card-img-top news-image"
							loading="lazy">
				</a>
            </div>
            <?php endif; ?>
            
            <div class="card-body d-flex flex-column">
                <!-- Рубрики -->
                <?php if (!empty($news['PROPERTIES']['category']['VALUE_ENUM']) && is_array($news['PROPERTIES']['category']['VALUE_ENUM'])): ?>
                <div class="mb-2 categories-wrapper">
                    <?php foreach ($news['PROPERTIES']['category']['VALUE_ENUM'] as $key=>$category): ?>
						<a href="/news/category/<?=$news['PROPERTIES']['category']['VALUE_XML_ID'][$key]?>/" class="w-100">
                        	<span class="badge bg-primary bg-opacity-10 text-primary small category-badge w-100"
      						title="Перейти к рубрике <?= htmlspecialchars($category) ?>">
    						<?= htmlspecialchars($category) ?>
							</span>
						</a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Дата -->
                <div class="text-muted small mb-2">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= date('d.m.Y', strtotime($news['ACTIVE_FROM_X'])) ?>
					<?php if ($arResult["SHOW_COUNTER"]): ?>
            			<span><i class="bi bi-eye me-1"></i><?= $news["SHOW_COUNTER"] ?> просмотров</span>
        			<?php endif; ?>
                </div>
                
                <!-- Заголовок -->
                <h5 class="card-title news-title">
                    <a href="<?= htmlspecialchars($news['DETAIL_PAGE_URL']) ?>" 
                       class="text-decoration-none text-dark">
						<?=htmlspecialchars($news['NAME'], ENT_XML1 | ENT_QUOTES, 'UTF-8', false);?>
                    </a>
                </h5>
                
                <!-- Анонс текста -->
                <?php if (!empty($news['PREVIEW_TEXT'])): ?>
                <p class="card-text text-muted flex-grow-1">
                    <?= htmlspecialchars(mb_strimwidth($news['PREVIEW_TEXT'], 0, 150, '...')) ?>
                </p>
                <?php endif; ?>
                
                <!-- Ссылка "Читать далее" -->
                <div class="mt-auto pt-3">
                    <a href="<?= htmlspecialchars($news['DETAIL_PAGE_URL']) ?>" 
                       class="btn btn-outline-primary btn-sm"
					   aria-label="Читать далее: <?=htmlspecialchars($news['NAME'])?>"
					>
                        Читать далее
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?else:?>
<div class="alert alert-warning mt-4">
    <div class="d-flex align-items-center">
        <i class="bi bi-newspaper me-3 fs-4"></i>
        <div>
            <strong class="d-block mb-1">Новости не найдены</strong>
			<span class="small">Не найдено новостей по указанным критериям. Попробуйте изменить критерии поиска или воспользуйтесь <a href="/search/">поиском</a>.</span>
        </div>
    </div>
</div>
<?endif?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
