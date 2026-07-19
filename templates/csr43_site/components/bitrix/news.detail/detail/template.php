<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$properties = is_array($arResult['PROPERTIES'] ?? null) ? $arResult['PROPERTIES'] : [];
$categoryProperty = is_array($properties['category'] ?? null) ? $properties['category'] : [];
$categoryValues = (array)($categoryProperty['VALUE'] ?? []);
$categoryXmlIds = (array)($categoryProperty['VALUE_XML_ID'] ?? []);
$detailPictureValue = $arResult['DETAIL_PICTURE'] ?? null;
$previewPictureValue = $arResult['PREVIEW_PICTURE'] ?? null;
$previewHtml = site_string($arResult['~PREVIEW_TEXT'] ?? $arResult['PREVIEW_TEXT'] ?? '');
$hasPreviewText = trim($previewHtml) !== '';
$showCounter = max(0, (int)($arResult['SHOW_COUNTER'] ?? 0));

$navNews = is_array($arResult['NAV_NEWS'] ?? null)
    ? $arResult['NAV_NEWS']
    : ['PREV' => false, 'NEXT' => false];

// Обработка файлов
$additionalFiles = is_array($properties['additional_files'] ?? null)
    ? $properties['additional_files']
    : [];
$downloadFiles = [];
$galleryFiles = [];
$newsName = site_string($arResult['~NAME'] ?? $arResult['NAME'] ?? '');

// Добавляем DETAIL_PICTURE в начало галереи
if (!empty($detailPictureValue)) {
    if (is_array($detailPictureValue)) {
        $detailPicture = $detailPictureValue;
        $detailPicture['SRC'] = site_url($detailPicture['SRC'] ?? null, '');
        if ($detailPicture['SRC'] !== '') {
            $detailPicture['FANCYBOX_NAME'] = $newsName . ' (детальное изображение)';
            array_unshift($galleryFiles, $detailPicture);
        }
    } else {
        $detailPictureId = filter_var($detailPictureValue, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $detailPicture = $detailPictureId !== false ? CFile::GetFileArray($detailPictureId) : false;
        if (is_array($detailPicture)) {
            $detailPicture['SRC'] = site_url($detailPicture['SRC'] ?? null, '');
        }
        if (is_array($detailPicture) && $detailPicture['SRC'] !== '') {
            $detailPicture['FANCYBOX_NAME'] = $newsName . ' (детальное изображение)';
            array_unshift($galleryFiles, $detailPicture);
        }
    }
}
// Добавляем PREVIEW_PICTURE в начало галереи
if (!empty($previewPictureValue)) {
    if (is_array($previewPictureValue)) {
        $previewPicture = $previewPictureValue;
        $previewPicture['SRC'] = site_url($previewPicture['SRC'] ?? null, '');
        if ($previewPicture['SRC'] !== '') {
            $previewPicture['FANCYBOX_NAME'] = $newsName . ' (анонс)';
            array_unshift($galleryFiles, $previewPicture);
        }
    } else {
        $previewPictureId = filter_var($previewPictureValue, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $previewPicture = $previewPictureId !== false ? CFile::GetFileArray($previewPictureId) : false;
        if (is_array($previewPicture)) {
            $previewPicture['SRC'] = site_url($previewPicture['SRC'] ?? null, '');
        }
        if (is_array($previewPicture) && $previewPicture['SRC'] !== '') {
            $previewPicture['FANCYBOX_NAME'] = $newsName . ' (анонс)';
            array_unshift($galleryFiles, $previewPicture);
        }
    }
}

// Обрабатываем дополнительные файлы
if (!empty($additionalFiles['VALUE'])) {
    foreach (site_string_list($additionalFiles['VALUE']) as $fileId) {
        $fileId = filter_var($fileId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($fileId === false) {
            continue;
        }
        $fileInfo = CFile::GetFileArray($fileId);
        if (is_array($fileInfo)) {
            $fileInfo['SRC'] = site_url($fileInfo['SRC'] ?? null, '');
            if ($fileInfo['SRC'] === '') {
                continue;
            }
            $fileExtension = strtolower(GetFileExtension(site_string($fileInfo['FILE_NAME'] ?? '')));

            if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'mp4', 'avi', 'mov', 'webm'])) {
                if(!empty($fileInfo['DESCRIPTION'])){
                    $fileInfo["FANCYBOX_NAME"] = site_string($fileInfo['DESCRIPTION']);
                }else{
                    $fileInfo["FANCYBOX_NAME"] = $newsName;
                }
                $galleryFiles[] = $fileInfo;
            } else {
                $downloadFiles[] = $fileInfo;
            }
        }
    }
}
?>

<div class="news-detail news-detail__container">
    <div class="csr43-glass-card news-detail__card p-4 p-lg-5 mb-4">
		 <!-- Заголовок новости -->
        <h1 class="news-detail__title mb-3"><?=htmlspecialcharsbx($newsName)?></h1>
        <!-- Мета-информация -->
        <div class="news-detail__meta d-flex flex-wrap align-items-center gap-3 mb-4">
            <span class="news-detail__meta-badge">
                <i class="news-detail__accent-icon bi bi-calendar3 me-1"></i> <?=htmlspecialcharsbx(site_string($arResult["DISPLAY_ACTIVE_FROM"] ?? ''))?>
            </span>
            <?php if ($showCounter > 0): ?>
                <span class="news-detail__meta-badge">
                    <i class="news-detail__accent-icon bi bi-eye me-1"></i> <?=$showCounter?> просмотров
                </span>
            <?php endif; ?>
        </div>

        <!-- Рубрики -->
        <div class="mb-4 d-flex flex-wrap gap-2">
            <?php
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
                <?php foreach ($newsCategories as $category): ?>
                    <a href="/news/category/<?=rawurlencode($category["XML_ID"])?>/" class="news-detail__category">
                        <span class="bvi-speech"><i class="bi bi-tag me-1"></i><?=htmlspecialcharsbx($category["VALUE"])?></span>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="news-detail__category"><i class="bi bi-tag me-1"></i>Без рубрики</span>
            <?php endif; ?>
        </div>
		<hr>
        <div class="row g-4">
            <!-- Основной контент -->
            <div class="col-lg-8 order-2 order-lg-1">
                <!-- Анонс -->
                <?php if ($hasPreviewText): ?>
                    <div class="news-detail__preview mb-4 bvi-speech">
                        <i class="news-detail__accent-icon bi bi-quote me-2"></i>
                        <?=site_safe_html($previewHtml)?>
                    </div>
                <?php endif; ?>

                <!-- Детальный текст -->
                <article class="news-detail__content mb-5 bvi-speech">
                    <?=site_safe_html($arResult['~DETAIL_TEXT'] ?? $arResult['DETAIL_TEXT'] ?? '')?>
                </article>

                <!-- Поделиться в соцсетях (дополнительно) -->
                <div class="news-detail__share d-flex align-items-center gap-3 mt-4 pt-4">
                    <span class="news-detail__share-label">Оставайтесь с нами:</span>
                    <?php
						$APPLICATION->IncludeComponent("bitrix:menu","social",Array(
							"ROOT_MENU_TYPE" => site_menu_type(get_info('menu_social_root_type', 'social'), 'social'),
							"MAX_LEVEL" => "1",
							"USE_EXT" => "N",
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
                    <section class="csr43-glass-card news-detail__card p-4 mb-4">
                        <h5 class="news-detail__section-title mb-3">
                            <i class="news-detail__accent-icon bi bi-images me-2"></i> Материалы
                        </h5>
                        <div class="row g-2">
                            <?php foreach ($galleryFiles as $index => $file): ?>
                                <?php
                                $fileExtension = strtolower(GetFileExtension(site_string($file['FILE_NAME'] ?? '')));
                                $isVideo = in_array($fileExtension, ['mp4', 'avi', 'mov', 'webm']);
                                $thumbnailSrc = $isVideo ?
                                    SITE_TEMPLATE_PATH . '/components/bitrix/news.detail/detail/images/video-thumbnail.jpg' :
                                    $file['SRC'];
                                ?>

                                <div class="col-6 d-flex justify-content-center">
                                    <a href="<?=htmlspecialcharsbx($file['SRC'])?>"
                                       class="news-detail__gallery-trigger news-detail__gallery-link"
                                       data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                                       data-gallery-caption="<?=htmlspecialcharsbx($file['FANCYBOX_NAME'])?>"
                                       data-type="<?=$isVideo ? 'html5video' : 'image'?>"
                                       aria-label="<?=htmlspecialcharsbx('Открыть: ' . $file['FANCYBOX_NAME'])?>">
                                        <?php if ($isVideo): ?>
                                            <div class="news-detail__media-thumb">
                                                <img src="<?=htmlspecialcharsbx($thumbnailSrc)?>" alt="<?=htmlspecialcharsbx($file['FANCYBOX_NAME'])?>" class="img-fluid rounded" loading="lazy">
                                                <div class="news-detail__play-icon"><i class="bi bi-play-circle-fill" aria-hidden="true"></i></div>
                                            </div>
                                        <?php else: ?>
                                            <div class="news-detail__media-thumb">
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
                    <section class="csr43-glass-card news-detail__card p-4 mb-4">
                        <h5 class="news-detail__section-title mb-3">
                            <i class="news-detail__accent-icon bi bi-paperclip me-2"></i> Приложенные файлы
                        </h5>
                        <div class="news-detail__downloads d-grid gap-2">
                            <?php foreach ($downloadFiles as $file): ?>
                                <div class="news-detail__file">
                                    <div class="d-flex align-items-center">
                                        <div class="news-detail__file-icon me-3">
                                            <?php
                                            $fileExtension = strtolower(GetFileExtension(site_string($file['FILE_NAME'] ?? '')));
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
                                             <div class="news-detail__file-name fw-bold small"><?=htmlspecialcharsbx(site_string($file['ORIGINAL_NAME'] ?? ''))?></div>
                                             <div class="news-detail__file-size text-muted"><?=htmlspecialcharsbx(CFile::FormatSize(max(0, (int)($file['FILE_SIZE'] ?? 0))))?></div>
                                        </div>
                                        <a href="<?=htmlspecialcharsbx(site_url($file['SRC'] ?? null, ''))?>" download class="news-detail__download-button ms-2">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Навигация между новостями -->
                <section class="csr43-glass-card news-detail__card p-4">
                    <h5 class="news-detail__section-title mb-3">
                        <i class="news-detail__accent-icon bi bi-arrow-left-right me-2"></i> Другие новости
                    </h5>
                    <div class="d-grid gap-3">
                        <?php if (!empty($navNews['PREV'])): ?>
                            <a href="<?=htmlspecialcharsbx(site_url($navNews['PREV']['DETAIL_PAGE_URL'] ?? null))?>" class="news-detail__nav-link">
                                <div class="d-flex align-items-center">
                                    <div class="news-detail__nav-icon me-3">
                                        <i class="news-detail__nav-icon--active bi bi-arrow-left-circle-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small text-muted">К более актуальной новости</div>
                                        <div class="news-detail__nav-title fw-bold"><?=htmlspecialcharsbx(site_string($navNews['PREV']['~NAME'] ?? $navNews['PREV']['NAME'] ?? ''))?></div>
                                    </div>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="news-detail__nav-link news-detail__nav-link--disabled">
                                <div class="d-flex align-items-center">
                                    <div class="news-detail__nav-icon me-3">
                                        <i class="news-detail__nav-icon--disabled bi bi-arrow-left-circle"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small text-muted">К более актуальной новости</div>
                                        <div class="text-muted">Нет новости</div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($navNews['NEXT'])): ?>
                            <a href="<?=htmlspecialcharsbx(site_url($navNews['NEXT']['DETAIL_PAGE_URL'] ?? null))?>" class="news-detail__nav-link">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 text-end me-3">
                                        <div class="small text-muted">К предыдущей новости</div>
                                        <div class="news-detail__nav-title fw-bold"><?=htmlspecialcharsbx(site_string($navNews['NEXT']['~NAME'] ?? $navNews['NEXT']['NAME'] ?? ''))?></div>
                                    </div>
                                    <div class="news-detail__nav-icon">
                                        <i class="news-detail__nav-icon--active bi bi-arrow-right-circle-fill"></i>
                                    </div>
                                </div>
                            </a>
                        <?php else: ?>
                            <div class="news-detail__nav-link news-detail__nav-link--disabled">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 text-end me-3">
                                        <div class="small text-muted">К предыдущей новости</div>
                                        <div class="text-muted">Нет новости</div>
                                    </div>
                                    <div class="news-detail__nav-icon">
                                        <i class="news-detail__nav-icon--disabled bi bi-arrow-right-circle"></i>
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
$siteUrl = rtrim(site_url(get_info('site_url'), '', ['http', 'https'], false), '/');
$currentUrl = $siteUrl . $APPLICATION->GetCurPage();
$organizationId = $siteUrl . '/#organization';

// Дата публикации в формате ISO 8601
$datePublished = '';
$activeFromTimestamp = strtotime(site_string($arResult['ACTIVE_FROM'] ?? ''));
$dateCreateTimestamp = strtotime(site_string($arResult['DATE_CREATE'] ?? ''));
if ($activeFromTimestamp !== false) {
    $datePublished = date('c', $activeFromTimestamp);
} elseif ($dateCreateTimestamp !== false) {
    $datePublished = date('c', $dateCreateTimestamp);
}

// Дата модификации
$dateModified = $datePublished;
$modifiedTimestamp = strtotime(site_string($arResult['TIMESTAMP_X'] ?? ''));
if ($modifiedTimestamp !== false) {
    $dateModified = date('c', $modifiedTimestamp);
}

// Описание (обрезаем до 200 символов)
$description = '';
if ($hasPreviewText) {
    $description = site_plain_text($previewHtml);
    if (mb_strlen($description) > 200) {
        $description = mb_substr($description, 0, 200) . '...';
    }
}

// Категории
$articleSections = [];
$articleSections = site_string_list($categoryProperty['VALUE'] ?? []);

// Только изображения для микроразметки (без видео)
$imagesForMarkup = [];
foreach ($galleryFiles as $file) {
    $fileExtension = strtolower(GetFileExtension(site_string($file['FILE_NAME'] ?? '')));
    // Только изображения для schema.org
    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
        $src = site_url($file['SRC'] ?? null, '');
        if ($src === '') {
            continue;
        }
        // Проверяем, нужен ли домен
        if (strpos($src, 'http') !== 0) {
            $src = $siteUrl . '/' . ltrim($src, '/');
        }
        $imagesForMarkup[] = $src;
    }
}

// Если нет изображений, используем дефолтное изображение новости
if (empty($imagesForMarkup)) {
    $defaultImage = site_url(get_info_absolute_url('logo'), '', ['http', 'https'], false);
    if ($defaultImage !== '') {
        $imagesForMarkup[] = $defaultImage;
    }
}

// Формируем данные для JSON-LD
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $newsName,
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
if ($showCounter > 0) {
    $jsonLd['interactionStatistic'] = [
        '@type' => 'InteractionCounter',
        'interactionType' => 'https://schema.org/ViewAction',
        'userInteractionCount' => $showCounter
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
    | JSON_INVALID_UTF8_SUBSTITUTE
)?>
</script>
