<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

// Получение ID текущей новости
$currentNewsId = $arResult['ID'];
$currentDate = $arResult['ACTIVE_FROM'] ?: $arResult['DATE_CREATE'];

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
?>

<div class="news-detail glass-container">
    <div class="glass-card p-4 p-lg-5 mb-4">
		 <!-- Заголовок новости -->
        <h1 class="news-title mb-3"><?= htmlspecialcharsbx($arResult["NAME"]) ?></h1>
        <!-- Мета-информация -->
        <div class="news-meta d-flex flex-wrap align-items-center gap-3 mb-4">
            <span class="glass-badge">
                <i class="bi bi-calendar3 me-1" style="color: #2980b9;"></i> <?= $arResult["DISPLAY_ACTIVE_FROM"] ?>
            </span>
            <?php if ($arResult["SHOW_COUNTER"]): ?>
                <span class="glass-badge">
                    <i class="bi bi-eye me-1" style="color: #2980b9;"></i> <?= $arResult["SHOW_COUNTER"] ?> просмотров
                </span>
            <?php endif; ?>
        </div>

        <!-- Рубрики -->
        <div class="mb-4 d-flex flex-wrap gap-2">
            <?php
            $categoryValues = (array)($arResult['PROPERTIES']['category']['VALUE'] ?? array());
            $categoryXmlIds = (array)($arResult['PROPERTIES']['category']['VALUE_XML_ID'] ?? array());
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
                <?php foreach ($newsCategories as $category): ?>
                    <a href="/news/category/<?=rawurlencode($category["XML_ID"])?>/" class="glass-category">
                        <span class="bvi-speech"><i class="bi bi-tag me-1"></i><?=htmlspecialcharsbx($category["VALUE"])?></span>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="glass-category"><i class="bi bi-tag me-1"></i>Без рубрики</span>
            <?php endif; ?>
        </div>
		<hr>
        <div class="row g-4">
            <!-- Основной контент -->
            <div class="col-lg-8 order-2 order-lg-1">
                <!-- Анонс -->
                <?php if ($arResult['PREVIEW_TEXT']): ?>
                    <div class="news-preview glass-preview mb-4 bvi-speech">
                        <i class="bi bi-quote me-2" style="color: #3498db;"></i>
                        <?= $arResult['PREVIEW_TEXT'] ?>
                    </div>
                <?php endif; ?>

                <!-- Детальный текст -->
                <article class="news-content mb-5 bvi-speech">
                    <?= $arResult["DETAIL_TEXT"] ?>
                </article>
                
                <!-- Поделиться в соцсетях (дополнительно) -->
                <div class="share-block d-flex align-items-center gap-3 mt-4 pt-4" style="border-top: 1px solid rgba(52,152,219,0.2);">
                    <span style="color: #1e3a5f; font-weight: 600;">Оставайтесь с нами:</span>
                    <?
						$APPLICATION->IncludeComponent("bitrix:menu","social",Array(
							"ROOT_MENU_TYPE" => "social", 
							"MAX_LEVEL" => "1", 
							"CHILD_MENU_TYPE" => "social", 
							"USE_EXT" => "N",
							"DELAY" => "N",
							"ALLOW_MULTI_SELECT" => "N",
							"MENU_CACHE_TYPE" => "N", 
							"MENU_CACHE_TIME" => "3600", 
							"MENU_CACHE_USE_GROUPS" => "Y", 
							"MENU_CACHE_GET_VARS" => "" 
							)
						);?>
                </div>
            </div>

            <!-- Боковая панель -->
            <aside class="col-lg-4 order-1 order-lg-2">
                <!-- Галерея изображений и видео -->
                <?php if (!empty($galleryFiles)): ?>
                    <?php $galleryId = 'news-detail-' . $this->randString(); ?>
                    <section class="glass-card p-4 mb-4">
                        <h5 class="section-title-mini mb-3">
                            <i class="bi bi-images me-2" style="color: #2980b9;"></i> Материалы
                        </h5>
                        <div class="row g-2">
                            <?php foreach ($galleryFiles as $index => $file): ?>
                                <?php
                                $fileExtension = strtolower(GetFileExtension($file['FILE_NAME']));
                                $isVideo = in_array($fileExtension, ['mp4', 'avi', 'mov', 'webm']);
                                $thumbnailSrc = $isVideo ? 
                                    '/bitrix/templates/mitis_2026/components/bitrix/news.detail/detail/images/video-thumbnail.jpg' : 
                                    $file['SRC'];
                                ?>

                                <div class="col-6 d-flex justify-content-center">
                                    <a href="<?=htmlspecialcharsbx($file['SRC'])?>"
                                       class="gallery-item-link gallery-item-wrapper"
                                       data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                                       data-gallery-caption="<?=htmlspecialcharsbx($file['FANCYBOX_NAME'])?>"
                                       data-type="<?=$isVideo ? 'html5video' : 'image'?>"
                                       aria-label="<?=htmlspecialcharsbx('Открыть: ' . $file['FANCYBOX_NAME'])?>">
                                        <?php if ($isVideo): ?>
                                            <div class="gallery-item glass-image-thumb">
                                                <img src="/bitrix/templates/mitis_2026/components/bitrix/news.detail/detail/images/video-thumbnail.jpg" alt="<?=htmlspecialcharsbx($file['FANCYBOX_NAME'])?>" class="img-fluid rounded" loading="lazy">
                                                <div class="play-icon"><i class="bi bi-play-circle-fill" aria-hidden="true"></i></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="gallery-item glass-image-thumb">
                                                <img src="<?=htmlspecialcharsbx($thumbnailSrc)?>" alt="<?=htmlspecialcharsbx($file['FANCYBOX_NAME'])?>" class="img-fluid rounded" loading="lazy">
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Файлы для скачивания -->
                <?php if (!empty($downloadFiles)): ?>
                    <section class="glass-card p-4 mb-4">
                        <h5 class="section-title-mini mb-3">
                            <i class="bi bi-paperclip me-2" style="color: #2980b9;"></i> Приложенные файлы
                        </h5>
                        <div class="d-grid gap-2 download-files-grid">
                            <?php foreach ($downloadFiles as $file): ?>
                                <div class="file-download glass-file-item">
                                    <div class="d-flex align-items-center">
                                        <div class="file-icon me-3">
                                            <?php
                                            $fileExtension = strtolower(GetFileExtension($file['FILE_NAME']));
                                            $iconClass = 'bi-file-earmark';
                                            $colorClass = 'text-primary';
                                            
                                            switch ($fileExtension) {
                                                case 'pdf':
                                                    $iconClass = 'bi-file-earmark-pdf';
                                                    $colorClass = 'text-danger';
                                                    break;
                                                case 'doc':
                                                case 'docx':
                                                    $iconClass = 'bi-file-earmark-word';
                                                    $colorClass = 'text-primary';
                                                    break;
                                                case 'xls':
                                                case 'xlsx':
                                                    $iconClass = 'bi-file-earmark-excel';
                                                    $colorClass = 'text-success';
                                                    break;
                                                case 'zip':
                                                case 'rar':
                                                case '7z':
                                                    $iconClass = 'bi-file-earmark-zip';
                                                    $colorClass = 'text-warning';
                                                    break;
                                            }
                                            ?>
                                            <i class="bi <?= $iconClass ?> <?= $colorClass ?> fs-3"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small" style="color: #1e3a5f;"><?= htmlspecialchars($file['ORIGINAL_NAME']) ?></div>
                                            <div class="text-muted extra-small"><?= CFile::FormatSize($file['FILE_SIZE']) ?></div>
                                        </div>
                                        <a href="<?= $file['SRC'] ?>" download class="glass-download-btn ms-2">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
                
                <!-- Навигация между новостями -->
                <section class="glass-card p-4">
                    <h5 class="section-title-mini mb-3">
                        <i class="bi bi-arrow-left-right me-2" style="color: #2980b9;"></i> Другие новости
                    </h5>
                    <div class="d-grid gap-3">
                        <?php if (!empty($navNews['PREV'])): ?>
                            <a href="<?= $navNews['PREV']['DETAIL_PAGE_URL'] ?>" class="glass-nav-link">
                                <div class="d-flex align-items-center">
                                    <div class="nav-icon me-3">
                                        <i class="bi bi-arrow-left-circle-fill" style="color: #3498db; font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small text-muted">К более актуальной новости</div>
                                        <div class="fw-bold" style="color: #1e3a5f;"><?= $navNews['PREV']['NAME'] ?></div>
                                    </div>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="glass-nav-link disabled">
                                <div class="d-flex align-items-center">
                                    <div class="nav-icon me-3">
                                        <i class="bi bi-arrow-left-circle" style="color: #ccc; font-size: 1.5rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small text-muted">К более актуальной новости</div>
                                        <div class="text-muted">Нет новости</div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($navNews['NEXT'])): ?>
                            <a href="<?= $navNews['NEXT']['DETAIL_PAGE_URL'] ?>" class="glass-nav-link">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 text-end me-3">
                                        <div class="small text-muted">К предыдущей новости</div>
                                        <div class="fw-bold" style="color: #1e3a5f;"><?= $navNews['NEXT']['NAME'] ?></div>
                                    </div>
                                    <div class="nav-icon">
                                        <i class="bi bi-arrow-right-circle-fill" style="color: #3498db; font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="glass-nav-link disabled">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 text-end me-3">
                                        <div class="small text-muted">К предыдущей новости</div>
                                        <div class="text-muted">Нет новости</div>
                                    </div>
                                    <div class="nav-icon">
                                        <i class="bi bi-arrow-right-circle" style="color: #ccc; font-size: 1.5rem;"></i>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
<?php
// Подготовка данных для микроразметки
$siteUrl = rtrim((string)get_info('site_url'), '/');
$currentUrl = $siteUrl . $APPLICATION->GetCurPage();
$organizationId = $siteUrl . '/#organization';

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
    $description = trim(htmlspecialchars_decode(strip_tags($arResult['PREVIEW_TEXT'])));
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
            $src = $siteUrl . '/' . ltrim($src, '/');
        }
        $imagesForMarkup[] = $src;
    }
}

// Если нет изображений, используем дефолтное изображение новости
if (empty($imagesForMarkup)) {
    $imagesForMarkup[] = get_info_absolute_url('logo');
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
    '@id' => $organizationId
];

$jsonLd['publisher'] = [
    '@id' => $organizationId
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
<?=json_encode(
    $jsonLd,
    JSON_UNESCAPED_UNICODE
    | JSON_UNESCAPED_SLASHES
    | JSON_HEX_TAG
    | JSON_HEX_AMP
    | JSON_HEX_APOS
    | JSON_HEX_QUOT
    | JSON_THROW_ON_ERROR
)?>
</script>
