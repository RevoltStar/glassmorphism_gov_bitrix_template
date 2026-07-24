<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$arResult['NAV_NEWS'] = [
    'PREV' => false,
    'NEXT' => false,
];

$resolvePicture = static function ($picture): ?array {
    if (is_array($picture)) {
        return $picture;
    }

    $pictureId = max(0, (int)$picture);

    return $pictureId > 0 ? (CFile::GetFileArray($pictureId) ?: null) : null;
};

$getLocalPicturePath = static function (array $picture): ?string {
    $src = site_url($picture['SRC'] ?? '', '');

    if ($src === '' || !str_starts_with($src, '/')) {
        return null;
    }

    $documentRoot = realpath(\Bitrix\Main\Application::getDocumentRoot());
    $urlPath = parse_url($src, PHP_URL_PATH);

    if (!is_string($documentRoot) || !is_string($urlPath) || $urlPath === '') {
        return null;
    }

    $filePath = realpath(
        $documentRoot
        . DIRECTORY_SEPARATOR
        . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, ltrim(rawurldecode($urlPath), "/\\"))
    );

    if (!is_string($filePath) || !is_file($filePath) || !is_readable($filePath)) {
        return null;
    }

    $normalisePath = static function (string $path): string {
        $path = rtrim(str_replace('\\', '/', $path), '/');

        return PHP_OS_FAMILY === 'Windows' ? strtolower($path) : $path;
    };
    $documentRoot = $normalisePath($documentRoot);
    $filePathForComparison = $normalisePath($filePath);

    return str_starts_with($filePathForComparison, $documentRoot . '/') ? $filePath : null;
};

$previewPicture = $resolvePicture($arResult['PREVIEW_PICTURE'] ?? null);
$detailPicture = $resolvePicture($arResult['DETAIL_PICTURE'] ?? null);
$arResult['DETAIL_PICTURE_DUPLICATES_PREVIEW'] = false;

if ($previewPicture !== null && $detailPicture !== null) {
    $previewPictureId = max(0, (int)($previewPicture['ID'] ?? 0));
    $detailPictureId = max(0, (int)($detailPicture['ID'] ?? 0));
    $previewPictureSrc = site_url($previewPicture['SRC'] ?? '', '');
    $detailPictureSrc = site_url($detailPicture['SRC'] ?? '', '');

    if (
        ($previewPictureId > 0 && $previewPictureId === $detailPictureId)
        || ($previewPictureSrc !== '' && $previewPictureSrc === $detailPictureSrc)
    ) {
        $arResult['DETAIL_PICTURE_DUPLICATES_PREVIEW'] = true;
    } else {
        $previewPictureSize = max(0, (int)($previewPicture['FILE_SIZE'] ?? 0));
        $detailPictureSize = max(0, (int)($detailPicture['FILE_SIZE'] ?? 0));

        if ($previewPictureSize > 0 && $previewPictureSize === $detailPictureSize) {
            $previewFilePath = $getLocalPicturePath($previewPicture);
            $detailFilePath = $getLocalPicturePath($detailPicture);

            if ($previewFilePath !== null && $detailFilePath !== null) {
                if ($previewFilePath === $detailFilePath) {
                    $arResult['DETAIL_PICTURE_DUPLICATES_PREVIEW'] = true;
                } else {
                    $previewHash = hash_file('sha256', $previewFilePath);
                    $detailHash = hash_file('sha256', $detailFilePath);

                    $arResult['DETAIL_PICTURE_DUPLICATES_PREVIEW'] = (
                        is_string($previewHash)
                        && is_string($detailHash)
                        && hash_equals($previewHash, $detailHash)
                    );
                }
            }
        }
    }
}

$currentNewsId = max(0, (int)($arResult['ID'] ?? 0));
$iblockId = max(0, (int)($arResult['IBLOCK_ID'] ?? $arParams['IBLOCK_ID'] ?? 0));

if ($currentNewsId === 0 || $iblockId === 0) {
    return;
}

$neighborResult = CIBlockElement::GetList(
    [
        'ACTIVE_FROM' => 'DESC',
        'SORT' => 'ASC',
        'ID' => 'DESC',
    ],
    [
        'IBLOCK_ID' => $iblockId,
        'ACTIVE' => 'Y',
        'ACTIVE_DATE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
    ],
    false,
    [
        'nPageSize' => 1,
        'nElementID' => $currentNewsId,
    ],
    [
        'ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'ACTIVE_FROM',
    ]
);

if (!is_object($neighborResult)) {
    return;
}

$detailUrl = site_string($arParams['DETAIL_URL'] ?? '');
if ($detailUrl !== '' && method_exists($neighborResult, 'SetUrlTemplates')) {
    $neighborResult->SetUrlTemplates($detailUrl);
}

$neighbors = [];
while ($neighbor = $neighborResult->GetNext()) {
    if (is_array($neighbor)) {
        $neighbors[] = $neighbor;
    }
}

$currentIndex = null;
foreach ($neighbors as $index => $neighbor) {
    if ((int)($neighbor['ID'] ?? 0) === $currentNewsId) {
        $currentIndex = $index;
        break;
    }
}

if ($currentIndex === null) {
    return;
}

$arResult['NAV_NEWS']['PREV'] = $neighbors[$currentIndex - 1] ?? false;
$arResult['NAV_NEWS']['NEXT'] = $neighbors[$currentIndex + 1] ?? false;
