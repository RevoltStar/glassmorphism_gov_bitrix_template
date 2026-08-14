<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$arResult = is_array($arResult ?? null) ? $arResult : [];

$recordCount = max(0, (int)($arResult['NavRecordCount'] ?? 0));
$pageCount = max(1, (int)($arResult['NavPageCount'] ?? 1));
$currentPage = min($pageCount, max(1, (int)($arResult['NavPageNomer'] ?? 1)));
$navigationNumber = max(1, (int)($arResult['NavNum'] ?? 1));
$showAlways = ($arResult['NavShowAlways'] ?? false) === true
    || ($arResult['NavShowAlways'] ?? 'N') === 'Y';
$showAllAllowed = ($arResult['NavShowAll'] ?? false) === true
    || ($arResult['NavShowAll'] ?? 'N') === 'Y';
$isShowingAll = ($arResult['bShowAll'] ?? false) === true
    || ($arResult['bShowAll'] ?? 'N') === 'Y';
$isDescending = ($arResult['bDescPageNumbering'] ?? false) === true
    || ($arResult['bDescPageNumbering'] ?? 'N') === 'Y';

if (
    $recordCount === 0
    || (!$showAlways && $pageCount <= 1 && !$showAllAllowed && !$isShowingAll)
) {
    return;
}

$rawPath = site_string($arResult['sUrlPath'] ?? '');
$path = site_url($rawPath, '');
if (
    $path === ''
    && isset($APPLICATION)
    && is_object($APPLICATION)
    && method_exists($APPLICATION, 'GetCurPage')
) {
    $path = site_url($APPLICATION->GetCurPage(false), '');
}

$queryString = ltrim(site_string($arResult['NavQueryString'] ?? ''), '?&');
$pageParameter = 'PAGEN_' . $navigationNumber;
$showAllParameter = 'SHOWALL_' . $navigationNumber;

$buildUrl = static function (string $parameter, int|string $value) use (
    $path,
    $queryString
): string {
    $queryParts = [];
    if ($queryString !== '') {
        $queryParts[] = $queryString;
    }
    $queryParts[] = rawurlencode($parameter) . '=' . rawurlencode((string)$value);

    return site_url(
        ($path !== '' ? $path : '') . '?' . implode('&', $queryParts),
        '#'
    );
};

$firstPage = $isDescending ? $pageCount : 1;
$lastPage = $isDescending ? 1 : $pageCount;
$hasPrevious = $isDescending
    ? $currentPage < $pageCount
    : $currentPage > 1;
$hasNext = $isDescending
    ? $currentPage > 1
    : $currentPage < $pageCount;
$previousPage = $isDescending ? $currentPage + 1 : $currentPage - 1;
$nextPage = $isDescending ? $currentPage - 1 : $currentPage + 1;

$pageWindow = 2;
$visiblePages = [$firstPage, $lastPage, $currentPage];
for ($offset = 1; $offset <= $pageWindow; $offset++) {
    if ($currentPage - $offset >= 1) {
        $visiblePages[] = $currentPage - $offset;
    }
    if ($currentPage + $offset <= $pageCount) {
        $visiblePages[] = $currentPage + $offset;
    }
}

$visiblePages = array_values(array_unique($visiblePages));
sort($visiblePages, SORT_NUMERIC);
if ($isDescending) {
    $visiblePages = array_reverse($visiblePages);
}

$pageLabel = static function (string $messageCode, int $page): string {
    return (string)Loc::getMessage($messageCode, ['#PAGE#' => (string)$page]);
};
?>

<nav class="csr43-pagination" aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_LABEL'))?>">
    <ul class="csr43-light-surface csr43-pagination__list">
        <li class="csr43-pagination__item csr43-pagination__item--edge">
            <?php if ($hasPrevious && !$isShowingAll): ?>
                <a
                    class="csr43-pagination__link"
                    href="<?=htmlspecialcharsbx($buildUrl($pageParameter, $firstPage))?>"
                    aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_FIRST'))?>">
                    <span aria-hidden="true">«</span>
                </a>
            <?php else: ?>
                <span class="csr43-pagination__link csr43-pagination__link--disabled" aria-hidden="true">«</span>
            <?php endif; ?>
        </li>

        <li class="csr43-pagination__item">
            <?php if ($hasPrevious && !$isShowingAll): ?>
                <a
                    class="csr43-pagination__link"
                    href="<?=htmlspecialcharsbx($buildUrl($pageParameter, $previousPage))?>"
                    aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_PREVIOUS'))?>">
                    <span aria-hidden="true">‹</span>
                </a>
            <?php else: ?>
                <span class="csr43-pagination__link csr43-pagination__link--disabled" aria-hidden="true">‹</span>
            <?php endif; ?>
        </li>

        <?php if (!$isShowingAll): ?>
            <?php $previousVisiblePage = null; ?>
            <?php foreach ($visiblePages as $page): ?>
                <?php if (
                    $previousVisiblePage !== null
                    && abs($page - $previousVisiblePage) > 1
                ): ?>
                    <li class="csr43-pagination__item csr43-pagination__item--ellipsis">
                        <span
                            class="csr43-pagination__ellipsis"
                            aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_ELLIPSIS'))?>">
                            …
                        </span>
                    </li>
                <?php endif; ?>

                <li class="csr43-pagination__item">
                    <?php if ($page === $currentPage): ?>
                        <span
                            class="csr43-pagination__link csr43-pagination__link--current"
                            aria-current="page"
                            aria-label="<?=htmlspecialcharsbx($pageLabel('CSR43_PAGINATION_CURRENT_PAGE', $page))?>">
                            <?=htmlspecialcharsbx((string)$page)?>
                        </span>
                    <?php else: ?>
                        <a
                            class="csr43-pagination__link"
                            href="<?=htmlspecialcharsbx($buildUrl($pageParameter, $page))?>"
                            aria-label="<?=htmlspecialcharsbx($pageLabel('CSR43_PAGINATION_PAGE', $page))?>">
                            <?=htmlspecialcharsbx((string)$page)?>
                        </a>
                    <?php endif; ?>
                </li>

                <?php $previousVisiblePage = $page; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <li class="csr43-pagination__item">
            <?php if ($hasNext && !$isShowingAll): ?>
                <a
                    class="csr43-pagination__link"
                    href="<?=htmlspecialcharsbx($buildUrl($pageParameter, $nextPage))?>"
                    aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_NEXT'))?>">
                    <span aria-hidden="true">›</span>
                </a>
            <?php else: ?>
                <span class="csr43-pagination__link csr43-pagination__link--disabled" aria-hidden="true">›</span>
            <?php endif; ?>
        </li>

        <li class="csr43-pagination__item csr43-pagination__item--edge">
            <?php if ($hasNext && !$isShowingAll): ?>
                <a
                    class="csr43-pagination__link"
                    href="<?=htmlspecialcharsbx($buildUrl($pageParameter, $lastPage))?>"
                    aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_LAST'))?>">
                    <span aria-hidden="true">»</span>
                </a>
            <?php else: ?>
                <span class="csr43-pagination__link csr43-pagination__link--disabled" aria-hidden="true">»</span>
            <?php endif; ?>
        </li>

        <?php if ($showAllAllowed): ?>
            <li class="csr43-pagination__item csr43-pagination__item--all">
                <?php if ($isShowingAll): ?>
                    <a
                        class="csr43-pagination__all"
                        href="<?=htmlspecialcharsbx($buildUrl($showAllParameter, 0))?>">
                        <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_SHOW_PAGES'))?>
                    </a>
                <?php else: ?>
                    <a
                        class="csr43-pagination__all"
                        href="<?=htmlspecialcharsbx($buildUrl($showAllParameter, 1))?>">
                        <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_PAGINATION_SHOW_ALL'))?>
                    </a>
                <?php endif; ?>
            </li>
        <?php endif; ?>
    </ul>
</nav>
