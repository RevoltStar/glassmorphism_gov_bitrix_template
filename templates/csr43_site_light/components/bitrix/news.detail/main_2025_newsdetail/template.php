<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// Получение ID текущей новости
$currentNewsId = $arResult['ID'];
/*$currentDate = $arResult['ACTIVE_FROM'] ?: $arResult['DATE_CREATE'];*/

// Получение соседних новостей
$navNews = array(
    'PREV' => false,
    'NEXT' => false
);

$res = CIBlockElement::GetList(
    array(
        "ACTIVE_FROM" => "DESC",
        "SORT" => "ASC",
        "ID" => "DESC"
    ),
    array(
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y",
        "ACTIVE_DATE" => "Y"
    ),
    false,
    false,
    array("ID", "NAME", "DETAIL_PAGE_URL", "ACTIVE_FROM")
);

$newsList = array();
while ($ob = $res->GetNextElement()) {
    $newsFields = $ob->GetFields();
    $newsList[] = $newsFields;
}

// Поиск предыдущей и следующей новости
$foundCurrent = false;
foreach ($newsList as $index => $news) {
    if ($news['ID'] == $currentNewsId) {
        $foundCurrent = true;
        continue;
    }
    
    if ($foundCurrent) {
        // Это следующая новость (новости после текущей)
        if (!$navNews['NEXT']) {
            $navNews['NEXT'] = $news;
        }
    } else {
        // Это предыдущая новость (новости до текущей)
        $navNews['PREV'] = $news;
    }
}

// Обработка файлов
$additionalFiles = $arResult['PROPERTIES']['additional_files'];
$downloadFiles = [];
$galleryFiles = [];

// Добавляем DETAIL_PICTURE в начало галереи
if (!empty($arResult['DETAIL_PICTURE']) && isset($arResult['DETAIL_PICTURE']['SRC'])) {
    // Если DETAIL_PICTURE уже массив (обработанный Bitrix)
    if (is_array($arResult['DETAIL_PICTURE'])) {
        $detailPicture = $arResult['DETAIL_PICTURE'];
        $detailPicture['FANCYBOX_NAME'] = $arResult['NAME'] . ' (детальное изображение)';
        array_unshift($galleryFiles, $detailPicture);
    } else {
        // Если это ID файла
        $detailPicture = CFile::GetFileArray($arResult['DETAIL_PICTURE']);
        if ($detailPicture) {
            $detailPicture['FANCYBOX_NAME'] = $arResult['NAME'] . ' (детальное изображение)';
            array_unshift($galleryFiles, $detailPicture);
        }
    }
}
// Добавляем PREVIEW_PICTURE в начало галереи
if (!empty($arResult['PREVIEW_PICTURE']) && isset($arResult['PREVIEW_PICTURE']['SRC'])) {
    // Если PREVIEW_PICTURE уже массив (обработанный Bitrix)
    if (is_array($arResult['PREVIEW_PICTURE'])) {
        $previewPicture = $arResult['PREVIEW_PICTURE'];
        $previewPicture['FANCYBOX_NAME'] = $arResult['NAME'] . ' (анонс)';
        array_unshift($galleryFiles, $previewPicture);
    } else {
        // Если это ID файла
        $previewPicture = CFile::GetFileArray($arResult['PREVIEW_PICTURE']);
        if ($previewPicture) {
            $previewPicture['FANCYBOX_NAME'] = $arResult['NAME'] . ' (анонс)';
            array_unshift($galleryFiles, $previewPicture);
        }
    }
}

// Обрабатываем дополнительные файлы
if (!empty($additionalFiles['VALUE']) && is_array($additionalFiles['VALUE'])) {
    foreach ($additionalFiles['VALUE'] as $key => $fileId) {
        $fileInfo = CFile::GetFileArray($fileId);
        if ($fileInfo) {
            $fileExtension = strtolower(GetFileExtension($fileInfo['FILE_NAME']));
            
            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'mp4', 'avi', 'mov', 'webm'])) {
				if(!empty($fileInfo['DESCRIPTION'])){
					$fileInfo["FANCYBOX_NAME"] = $fileInfo['DESCRIPTION'];
				}else{
					$fileInfo["FANCYBOX_NAME"] = $arResult['NAME'];
				}
				$galleryFiles[] = $fileInfo;
            } else {
                $downloadFiles[] = $fileInfo;
            }
        }
	    }
}

$galleryId = 'news-materials-' . (int)$arResult['ID'] . '-' . $this->randString();
?>

<div class="news-detail">
    <!-- Мета-информация -->
    <div class="news-meta mb-4">
        <span class="me-3"><i class="bi bi-calendar me-1"></i><?= $arResult["DISPLAY_ACTIVE_FROM"] ?></span>
        <?php if ($arResult["SHOW_COUNTER"]): ?>
            <span><i class="bi bi-eye me-1"></i><?= $arResult["SHOW_COUNTER"] ?> просмотров</span>
        <?php endif; ?>
    </div>
    
    <!-- Рубрики -->
    <div class="mb-4">
        <?php if (!empty($arResult['PROPERTIES']['category']['VALUE']) && is_array($arResult['PROPERTIES']['category']['VALUE'])): ?>
            <?php foreach ($arResult["PROPERTIES"]["category"]["VALUE"] as $key => $value): ?>
				<a href="/news/category/<?= $arResult["PROPERTIES"]["category"]["VALUE_XML_ID"][$key] ?>/" class="badge bg-primary category-badge">
                    <span class="bvi-speech"><?= $value ?></span>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <span class="badge bg-primary category-badge">Без рубрики</span>
        <?php endif; ?>
    </div>

    <div class="row mb-5">
        <!-- Основной контент -->
        <div class="col-lg-8 order-2 order-lg-1">
            <!-- Анонс -->
            <?php if ($arResult['PREVIEW_TEXT']): ?>
                <div class="news-preview mb-4 bvi-speech">
                    <?= $arResult['PREVIEW_TEXT'] ?>
                </div>
            <?php endif; ?>

            <!-- Детальный текст -->
            <article class="news-content mb-5 bvi-speech">
                <?= $arResult["DETAIL_TEXT"] ?>
            </article>
        </div>

        <!-- Боковая панель -->
        <aside class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
            <!-- Галерея изображений и видео -->
            <?php if (!empty($galleryFiles)): ?>

	                <section class="mb-4">
                    <h5 class="mb-3">Материалы</h5>
                    <div class="row g-2">
                        <?php foreach ($galleryFiles as $index => $file): ?>
                            <?php
	                            $fileExtension = strtolower(GetFileExtension($file['FILE_NAME']));
	                            $isVideo = in_array($fileExtension, ['mp4', 'avi', 'mov', 'webm']);
	                            $galleryCaption = $file['FANCYBOX_NAME'] ?: $arResult['NAME'];
	                            $thumbnailSrc = $isVideo ? 
									'/bitrix/templates/main_2025/components/bitrix/news.detail/main_2025_newsdetail/images/video-thumbnail.jpg' : 
	                                $file['SRC'];
	                            ?>

	                            <div class="col-6 d-flex justify-content-center">
									<a
										href="<?=htmlspecialcharsbx($file['SRC'])?>"
										class="gallery-item-link gallery-item-wrapper"
										data-gallery-item
										data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
										data-gallery-caption="<?=htmlspecialcharsbx($galleryCaption)?>"
										data-type="<?=$isVideo ? 'html5video' : 'image'?>"
										aria-label="<?=htmlspecialcharsbx('Открыть: ' . $galleryCaption)?>"
									>
	                                    <img
	                                        src="<?=htmlspecialcharsbx($thumbnailSrc)?>"
	                                        alt="<?=htmlspecialcharsbx($galleryCaption)?>"
	                                        class="img-fluid rounded gallery-item"
	                                        loading="lazy"
	                                    >
									</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Файлы для скачивания -->
            <?php if (!empty($downloadFiles)): ?>
                <section class="mb-4">
                    <h5 class="mb-3">Приложенные файлы</h5>
                    <div class="d-grid gap-2">
                        <?php foreach ($downloadFiles as $file): ?>
                            <div class="file-download">
                                <div class="d-flex align-items-center">
                                    <div class="file-icon me-2">
                                        <?php
                                        $fileExtension = strtolower(GetFileExtension($file['FILE_NAME']));
                                        $iconClass = 'bi-file-earmark';
                                        
                                        switch ($fileExtension) {
                                            case 'pdf':
                                                $iconClass = 'bi-file-earmark-pdf text-danger';
                                                break;
                                            case 'doc':
                                            case 'docx':
                                                $iconClass = 'bi-file-earmark-word text-primary';
                                                break;
                                            case 'xls':
                                            case 'xlsx':
                                                $iconClass = 'bi-file-earmark-excel text-success';
                                                break;
                                            case 'zip':
                                            case 'rar':
                                            case '7z':
                                                $iconClass = 'bi-file-earmark-zip text-warning';
                                                break;
                                        }
                                        ?>
                                        <i class="bi <?= $iconClass ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold small"><?= htmlspecialchars($file['ORIGINAL_NAME']) ?></div>
                                        <div class="text-muted extra-small"><?= CFile::FormatSize($file['FILE_SIZE']) ?></div>
                                    </div>
                                    <a href="<?= $file['SRC'] ?>" download="" class="btn btn-sm btn-outline-primary ms-2">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
			 <!-- Навигация между новостями -->
            <nav aria-label="Навигация по новостям" class="mb-4">
                <div class="d-grid gap-2">
                    <?php if (!empty($navNews['PREV'])): ?>
                        <a href="<?= $navNews['PREV']['DETAIL_PAGE_URL'] ?>" class="btn btn-outline-secondary text-start">
                            <i class="bi bi-arrow-left me-2"></i>
                            <div class="small">К более актуальной новости</div>
                            <div class="fw-bold"><?=$navNews['PREV']['NAME']?></div>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary text-start" disabled>
                            <i class="bi bi-arrow-left me-2"></i>
                            <div class="small">К более актуальной новости</div>
                            <div>Нет новости</div>
                        </button>
                    <?php endif; ?>

                    <?php if (!empty($navNews['NEXT'])): ?>
                        <a href="<?= $navNews['NEXT']['DETAIL_PAGE_URL'] ?>" class="btn btn-outline-secondary text-start">
                            <div class="small">К предыдущей новости</div>
                            <div class="fw-bold"><?= $navNews['NEXT']['NAME'] ?></div>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary text-start" disabled>
                            <div class="small">К предыдущей новости</div>
                            <div>Нет новости</div>
                            <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </nav>
        </aside>
    </div>
</div>

<?php
// Подготовка данных для микроразметки
$currentUrl = 'https://csr43.ru' . $APPLICATION->GetCurPage();

// Дата публикации в формате ISO 8601
$datePublished = '';
if ($arResult['ACTIVE_FROM']) {
    $datePublished = date('c', strtotime($arResult['ACTIVE_FROM']));
} elseif ($arResult['DATE_CREATE']) {
    $datePublished = date('c', strtotime($arResult['DATE_CREATE']));
}

// Дата модификации
$dateModified = $datePublished;
if ($arResult['TIMESTAMP_X']) {
    $dateModified = date('c', strtotime($arResult['TIMESTAMP_X']));
}

// Описание (обрезаем до 200 символов)
$description = '';
if (!empty($arResult['PREVIEW_TEXT'])) {
    $description = htmlspecialchars(strip_tags($arResult['PREVIEW_TEXT']));
    if (mb_strlen($description) > 200) {
        $description = mb_substr($description, 0, 200) . '...';
    }
}

// Категории
$articleSections = [];
if (!empty($arResult['PROPERTIES']['category']['VALUE'])) {
    if (is_array($arResult['PROPERTIES']['category']['VALUE'])) {
        $articleSections = $arResult['PROPERTIES']['category']['VALUE'];
    } else {
        $articleSections = [$arResult['PROPERTIES']['category']['VALUE']];
    }
}

// Только изображения для микроразметки (без видео)
$imagesForMarkup = [];
foreach ($galleryFiles as $file) {
    $fileExtension = strtolower(GetFileExtension($file['FILE_NAME']));
    // Только изображения для schema.org
    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
        $src = $file['SRC'];
        // Проверяем, нужен ли домен
        if (strpos($src, 'http') !== 0) {
            $src = 'https://csr43.ru' . $src;
        }
        $imagesForMarkup[] = $src;
    }
}

// Если нет изображений, используем дефолтное изображение новости
if (empty($imagesForMarkup)) {
    $imagesForMarkup[] = 'https://csr43.ru/images/logo_csr.png';
}

// Формируем данные для JSON-LD
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $arResult['NAME'],
    'url' => $currentUrl,
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => $currentUrl
    ]
];

// Добавляем даты только если они есть
if ($datePublished) {
    $jsonLd['datePublished'] = $datePublished;
    $jsonLd['dateModified'] = $dateModified;
}

// Используем ссылку на организацию из header.php
$jsonLd['author'] = [
    '@id' => 'https://csr43.ru/#organization'
];

$jsonLd['publisher'] = [
    '@id' => 'https://csr43.ru/#organization'
];

// Изображения
$jsonLd['image'] = $imagesForMarkup;

// Описание
if ($description) {
    $jsonLd['description'] = $description;
}

// Разделы
if (!empty($articleSections)) {
    $jsonLd['articleSection'] = $articleSections;
}

// Счетчик просмотров
if ($arResult["SHOW_COUNTER"] && (int)$arResult["SHOW_COUNTER"] > 0) {
    $jsonLd['interactionStatistic'] = [
        '@type' => 'InteractionCounter',
        'interactionType' => 'https://schema.org/ViewAction',
        'userInteractionCount' => (int)$arResult["SHOW_COUNTER"]
    ];
}
?>

<!-- Микроразметка для детальной страницы новости -->
<script type="application/ld+json">
<?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>
