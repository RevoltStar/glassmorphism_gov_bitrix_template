<div id="mainSlider" class="carousel slide" data-bs-ride="carousel" aria-live="polite" aria-atomic="true" data-bs-interval="5000"  tabindex="0" title="Пока мышка наведена, слайды не перелистываются">
    <!-- Индикаторы -->
    <div class="carousel-indicators">
        <?php foreach ($arResult['ITEMS'] as $index => $item): ?>
            <button type="button" 
                    data-bs-target="#mainSlider" 
                    data-bs-slide-to="<?= $index ?>" 
                    class="<?= $index === 0 ? 'active' : '' ?>" 
                    aria-label="Слайд <?= $index + 1 ?>">
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Слайды -->
    <div class="carousel-inner">
        <?php foreach ($arResult['ITEMS'] as $index => $item): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="slider-content">
                    <!-- Изображение -->
                    <div class="slider-image-container">
                        <img src="<?= $item['PREVIEW_PICTURE']['SRC'] ?>" 
                             class="slider-image" 
                             alt="<?= htmlspecialchars($item['PREVIEW_PICTURE']['ALT'] ?: $item['NAME']) ?>"
						loading="lazy"
						<?if(isset($item['PROPERTIES']['OBJECT_FIT']['VALUE'])):?>style="object-fit:<?=$item['PROPERTIES']['OBJECT_FIT']['VALUE']?>" <?endif?>
						>
                    </div>
                    
                    <!-- Текст поверх изображения -->
                    <div class="slider-text-overlay">
                        <div class="slider-text-content">
                            <h2 class="slider-title">
                                <?= htmlspecialchars($item['NAME']) ?>
                            </h2>
                            
                            <?php if (!empty($item['PREVIEW_TEXT'])): ?>
                                <div class="slider-description">
                                    <?= $item['PREVIEW_TEXT'] ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($item['PROPERTIES']['LINK']['VALUE'])): ?>
							<a href="<?= $item['PROPERTIES']['LINK']['VALUE'] ?>" 
                                class="btn btn-primary slider-btn"
								aria-label="Подробнее о слайде <?=htmlspecialchars($item['NAME'])?>"
							>
                                    Подробнее
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Кнопки навигации -->
    <button class="carousel-control-prev" type="button" data-bs-target="#mainSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Предыдущий</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Следующий</span>
    </button>
</div>