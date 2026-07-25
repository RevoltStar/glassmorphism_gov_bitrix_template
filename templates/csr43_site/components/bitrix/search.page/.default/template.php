<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
$request = is_array($arResult['REQUEST'] ?? null) ? $arResult['REQUEST'] : [];
$searchItems = is_array($arResult['SEARCH'] ?? null)
    ? array_values(array_filter($arResult['SEARCH'], 'is_array'))
    : [];

$decodeText = static function ($value): string {
    return html_entity_decode(
        site_plain_text($value),
        ENT_QUOTES | ENT_HTML5,
        'UTF-8'
    );
};

$query = array_key_exists('~QUERY', $request)
    ? trim(site_string($request['~QUERY']))
    : trim($decodeText($request['QUERY'] ?? ''));
$originalQuery = trim($decodeText($request['ORIGINAL_QUERY'] ?? ''));
$where = site_string($request['WHERE'] ?? $_GET['where'] ?? '');
$dateFrom = array_key_exists('~FROM', $request)
    ? site_string($request['~FROM'])
    : $decodeText($request['FROM'] ?? $_GET['from'] ?? '');
$dateTo = array_key_exists('~TO', $request)
    ? site_string($request['~TO'])
    : $decodeText($request['TO'] ?? $_GET['to'] ?? '');
$tags = site_string($request['TAGS'] ?? $_GET['tags'] ?? '');
$sort = strtolower(site_string($request['HOW'] ?? $_GET['how'] ?? 'r'));
$sort = in_array($sort, ['r', 'd'], true) ? $sort : 'r';

$showWhere = ($arParams['SHOW_WHERE'] ?? 'N') === 'Y';
$showWhen = ($arParams['SHOW_WHEN'] ?? 'N') === 'Y';
$showOrderBy = ($arParams['SHOW_ORDER_BY'] ?? 'N') === 'Y';
$showItemDate = ($arParams['SHOW_ITEM_DATE_CHANGE'] ?? 'N') === 'Y';
$showItemTags = ($arParams['SHOW_ITEM_TAGS'] ?? 'N') === 'Y';
$dropdown = is_array($arResult['DROPDOWN'] ?? null) ? $arResult['DROPDOWN'] : [];
$showWhereField = $showWhere && $dropdown !== [];
$showAdvanced = $showWhereField || $showWhen || $showOrderBy;
$advancedIsActive = $where !== '' || $dateFrom !== '' || $dateTo !== '' || $sort === 'd';

$errorCode = max(0, (int)($arResult['ERROR_CODE'] ?? 0));
$errorText = site_plain_text($arResult['ERROR_TEXT'] ?? '');
$restarted = ($arResult['RESTART'] ?? false) === true || ($arResult['RESTART'] ?? 'N') === 'Y';

$currentPageUrl = '';
if (isset($APPLICATION) && is_object($APPLICATION) && method_exists($APPLICATION, 'GetCurPage')) {
    $currentPageUrl = site_url($APPLICATION->GetCurPage(false), '');
}

$resetUrl = $currentPageUrl;
if ($resetUrl !== '' && ($query !== '' || $tags !== '')) {
    $resetQuery = [];
    if ($query !== '') {
        $resetQuery['q'] = $query;
    }
    if ($tags !== '') {
        $resetQuery['tags'] = $tags;
    }
    $resetUrl = site_url(
        $resetUrl . '?' . http_build_query($resetQuery, '', '&', PHP_QUERY_RFC3986),
        ''
    );
}

$totalCount = count($searchItems);
$navigationResult = $arResult['NAV_RESULT'] ?? null;
if (is_object($navigationResult) && isset($navigationResult->NavRecordCount)) {
    $totalCount = max(0, (int)$navigationResult->NavRecordCount);
}

$navigationHtml = site_safe_html($arResult['NAV_STRING'] ?? '');
$iconByModule = [
    'iblock' => 'bi-newspaper',
    'main' => 'bi-file-earmark-text',
    'blog' => 'bi-journal-text',
    'forum' => 'bi-chat-left-text',
    'socialnetwork' => 'bi-people',
    'intranet' => 'bi-person',
];
$formId = 'csr43-search-' . $this->randString();
$queryInputId = $formId . '-query';
$whereInputId = $formId . '-where';
$fromInputId = $formId . '-from';
$toInputId = $formId . '-to';
$sortInputId = $formId . '-sort';
$resultsTitleId = $formId . '-results-title';
?>

<div class="search-page">
    <form
        class="csr43-glass-card search-page__form"
        action="<?=htmlspecialcharsbx($currentPageUrl)?>"
        method="get"
        role="search"
        aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_FORM_LABEL'))?>">
        <div class="search-page__query">
            <label class="visually-hidden" for="<?=htmlspecialcharsbx($queryInputId)?>">
                <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_QUERY_LABEL'))?>
            </label>
            <div class="search-page__query-control">
                <i class="bi bi-search search-page__query-icon" aria-hidden="true"></i>
                <input
                    id="<?=htmlspecialcharsbx($queryInputId)?>"
                    class="form-control search-page__query-input"
                    type="search"
                    name="q"
                    value="<?=htmlspecialcharsbx($query)?>"
                    placeholder="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_QUERY_PLACEHOLDER'))?>"
                    maxlength="255"
                    enterkeyhint="search">
                <button class="btn search-page__submit" type="submit">
                    <i class="bi bi-search me-2" aria-hidden="true"></i>
                    <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_SUBMIT'))?>
                </button>
            </div>
        </div>

        <?php if ($tags !== ''): ?>
            <input type="hidden" name="tags" value="<?=htmlspecialcharsbx($tags)?>">
        <?php endif; ?>

        <?php if ($showAdvanced): ?>
            <details class="search-page__filters mt-3"<?=$advancedIsActive ? ' open' : ''?>>
                <summary class="search-page__filters-summary">
                    <i class="bi bi-sliders me-2" aria-hidden="true"></i>
                    <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_FILTERS'))?>
                </summary>

                <div class="search-page__filters-grid mt-3">
                    <?php if ($showWhereField): ?>
                        <div>
                            <label class="form-label" for="<?=htmlspecialcharsbx($whereInputId)?>">
                                <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_WHERE_LABEL'))?>
                            </label>
                            <select id="<?=htmlspecialcharsbx($whereInputId)?>" class="form-select" name="where">
                                <option value="">
                                    <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_WHERE_ALL'))?>
                                </option>
                                <?php foreach ($dropdown as $whereCode => $whereName): ?>
                                    <?php
                                    $whereCode = site_string($whereCode);
                                    $whereName = $decodeText($whereName);
                                    if ($whereCode === '' || $whereName === '') {
                                        continue;
                                    }
                                    ?>
                                    <option
                                        value="<?=htmlspecialcharsbx($whereCode)?>"
                                        <?=$whereCode === $where ? 'selected' : ''?>>
                                        <?=htmlspecialcharsbx($whereName)?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ($showWhen): ?>
                        <div>
                            <label class="form-label" for="<?=htmlspecialcharsbx($fromInputId)?>">
                                <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_DATE_FROM'))?>
                            </label>
                            <input
                                id="<?=htmlspecialcharsbx($fromInputId)?>"
                                class="form-control"
                                type="text"
                                name="from"
                                value="<?=htmlspecialcharsbx($dateFrom)?>"
                                placeholder="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_DATE_PLACEHOLDER'))?>"
                                inputmode="numeric"
                                autocomplete="off">
                        </div>
                        <div>
                            <label class="form-label" for="<?=htmlspecialcharsbx($toInputId)?>">
                                <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_DATE_TO'))?>
                            </label>
                            <input
                                id="<?=htmlspecialcharsbx($toInputId)?>"
                                class="form-control"
                                type="text"
                                name="to"
                                value="<?=htmlspecialcharsbx($dateTo)?>"
                                placeholder="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_DATE_PLACEHOLDER'))?>"
                                inputmode="numeric"
                                autocomplete="off">
                        </div>
                    <?php endif; ?>

                    <?php if ($showOrderBy): ?>
                        <div>
                            <label class="form-label" for="<?=htmlspecialcharsbx($sortInputId)?>">
                                <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_SORT_LABEL'))?>
                            </label>
                            <select id="<?=htmlspecialcharsbx($sortInputId)?>" class="form-select" name="how">
                                <option value="r"<?=$sort === 'r' ? ' selected' : ''?>>
                                    <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_SORT_RELEVANCE'))?>
                                </option>
                                <option value="d"<?=$sort === 'd' ? ' selected' : ''?>>
                                    <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_SORT_DATE'))?>
                                </option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($resetUrl !== '' && $advancedIsActive): ?>
                    <a class="search-page__reset mt-3" href="<?=htmlspecialcharsbx($resetUrl)?>">
                        <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>
                        <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_RESET'))?>
                    </a>
                <?php endif; ?>
            </details>
        <?php endif; ?>
    </form>

    <?php if ($originalQuery !== '' && $originalQuery !== $query): ?>
        <?php
        $correctedMessage = (string)Loc::getMessage(
            'CSR43_SEARCH_CORRECTED',
            [
                '#ORIGINAL#' => $originalQuery,
                '#QUERY#' => $query,
            ]
        );
        ?>
        <div class="csr43-glass-surface search-page__notice" role="status">
            <i class="bi bi-spellcheck" aria-hidden="true"></i>
            <span><?=htmlspecialcharsbx($correctedMessage)?></span>
        </div>
    <?php endif; ?>

    <?php if ($restarted && $searchItems !== []): ?>
        <div class="csr43-glass-surface search-page__notice" role="status">
            <i class="bi bi-info-circle" aria-hidden="true"></i>
            <span><?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_RESTARTED'))?></span>
        </div>
    <?php endif; ?>

    <?php if ($errorCode > 0): ?>
        <div class="csr43-glass-surface search-page__state search-page__state--error" role="alert">
            <div class="csr43-glass-icon search-page__state-icon" aria-hidden="true">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <div>
                <h2 class="h4 mb-2"><?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_ERROR_TITLE'))?></h2>
                <?php if ($errorText !== ''): ?>
                    <p class="mb-0"><?=htmlspecialcharsbx($errorText)?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif ($query === ''): ?>
        <div class="csr43-glass-surface search-page__state" role="status">
            <div class="csr43-glass-icon search-page__state-icon" aria-hidden="true">
                <i class="bi bi-search"></i>
            </div>
            <div>
                <h2 class="h4 mb-2"><?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_INITIAL_TITLE'))?></h2>
                <p class="mb-0"><?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_INITIAL_TEXT'))?></p>
            </div>
        </div>
    <?php elseif ($searchItems === []): ?>
        <div class="csr43-glass-surface search-page__state" role="status">
            <div class="csr43-glass-icon search-page__state-icon" aria-hidden="true">
                <i class="bi bi-search-heart"></i>
            </div>
            <div>
                <h2 class="h4 mb-2"><?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_EMPTY_TITLE'))?></h2>
                <p class="mb-0"><?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_EMPTY_TEXT'))?></p>
            </div>
        </div>
    <?php else: ?>
        <section class="search-page__results" aria-labelledby="<?=htmlspecialcharsbx($resultsTitleId)?>">
            <div class="search-page__results-header">
                <h2 id="<?=htmlspecialcharsbx($resultsTitleId)?>" class="h3 mb-0">
                    <?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_RESULTS_TITLE'))?>
                </h2>
                <span class="badge csr43-glass-badge search-page__count">
                    <?=htmlspecialcharsbx((string)Loc::getMessage(
                        'CSR43_SEARCH_FOUND',
                        ['#COUNT#' => (string)$totalCount]
                    ))?>
                </span>
            </div>

            <?php if (($arParams['DISPLAY_TOP_PAGER'] ?? 'N') === 'Y' && $navigationHtml !== ''): ?>
                <nav class="search-page__pagination" aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_RESULTS_TITLE'))?>">
                    <?=$navigationHtml?>
                </nav>
            <?php endif; ?>

            <div class="search-page__list">
                <?php foreach ($searchItems as $searchItem): ?>
                    <?php
                    $rawUrl = site_string($searchItem['~URL'] ?? '');
                    if ($rawUrl === '') {
                        $rawUrl = html_entity_decode(
                            site_string($searchItem['URL'] ?? ''),
                            ENT_QUOTES | ENT_HTML5,
                            'UTF-8'
                        );
                    }
                    $resultUrl = site_url($rawUrl, '');

                    $formattedTitle = site_string($searchItem['TITLE_FORMATED'] ?? '');
                    $titleHtml = $formattedTitle !== ''
                        ? site_safe_html($formattedTitle)
                        : htmlspecialcharsbx(
                            site_plain_text($searchItem['~TITLE'] ?? $searchItem['TITLE'] ?? '')
                        );
                    if ($titleHtml === '') {
                        $titleHtml = htmlspecialcharsbx(
                            (string)Loc::getMessage('CSR43_SEARCH_RESULT_DEFAULT_TITLE')
                        );
                    }

                    $formattedBody = site_string($searchItem['BODY_FORMATED'] ?? '');
                    $bodyHtml = $formattedBody !== ''
                        ? site_safe_html($formattedBody)
                        : htmlspecialcharsbx(
                            site_plain_text($searchItem['~BODY'] ?? $searchItem['BODY'] ?? '')
                        );

                    $moduleId = strtolower(site_string($searchItem['MODULE_ID'] ?? ''));
                    $iconClass = $iconByModule[$moduleId] ?? 'bi-file-earmark-text';
                    $dateChanged = site_plain_text($searchItem['DATE_CHANGE'] ?? '');
                    $displayUrl = $resultUrl !== ''
                        ? site_plain_text((string)preg_replace('/[?#].*$/', '', $resultUrl))
                        : '';

                    $itemTags = [];
                    if ($showItemTags) {
                        $rawTags = site_string($searchItem['TAGS'] ?? '');
                        if ($rawTags !== '') {
                            $itemTags = array_slice(
                                array_values(array_filter(array_map(
                                    'trim',
                                    preg_split('/[,;]+/u', $rawTags) ?: []
                                ))),
                                0,
                                8
                            );
                        }
                    }
                    ?>
                    <article class="csr43-glass-card csr43-glass-card--interactive search-result">
                        <div class="csr43-glass-icon search-result__icon" aria-hidden="true">
                            <i class="bi <?=htmlspecialcharsbx($iconClass)?>"></i>
                        </div>
                        <div class="search-result__content">
                            <h3 class="search-result__title h5">
                                <?php if ($resultUrl !== ''): ?>
                                    <a href="<?=htmlspecialcharsbx($resultUrl)?>"><?=$titleHtml?></a>
                                <?php else: ?>
                                    <?=$titleHtml?>
                                <?php endif; ?>
                            </h3>

                            <?php if ($bodyHtml !== ''): ?>
                                <div class="search-result__description"><?=$bodyHtml?></div>
                            <?php endif; ?>

                            <?php if (($showItemDate && $dateChanged !== '') || $displayUrl !== ''): ?>
                                <div class="search-result__meta">
                                    <?php if ($showItemDate && $dateChanged !== ''): ?>
                                        <span>
                                            <i class="bi bi-clock me-1" aria-hidden="true"></i>
                                            <?=htmlspecialcharsbx((string)Loc::getMessage(
                                                'CSR43_SEARCH_UPDATED',
                                                ['#DATE#' => $dateChanged]
                                            ))?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($displayUrl !== ''): ?>
                                        <span class="search-result__url">
                                            <i class="bi bi-link-45deg me-1" aria-hidden="true"></i>
                                            <?=htmlspecialcharsbx($displayUrl)?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($itemTags !== []): ?>
                                <div class="search-result__tags" aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_TAGS_LABEL'))?>">
                                    <?php foreach ($itemTags as $itemTag): ?>
                                        <span class="badge csr43-glass-badge"><?=htmlspecialcharsbx($itemTag)?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if (($arParams['DISPLAY_BOTTOM_PAGER'] ?? 'N') === 'Y' && $navigationHtml !== ''): ?>
                <nav class="search-page__pagination" aria-label="<?=htmlspecialcharsbx((string)Loc::getMessage('CSR43_SEARCH_RESULTS_TITLE'))?>">
                    <?=$navigationHtml?>
                </nav>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</div>
