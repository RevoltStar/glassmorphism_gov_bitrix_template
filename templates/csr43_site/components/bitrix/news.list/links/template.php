<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$arResult = is_array($arResult ?? null) ? $arResult : [];
$arParams = is_array($arParams ?? null) ? $arParams : [];
?>
<?php
$items = $arResult['ITEMS'] ?? [];
$sectionId = max(0, (int)($arParams['SECTION_ID'] ?? 0));
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
if (is_array($items) && $items !== []):
?>

<?php if(($arParams["DISPLAY_TOP_PAGER"] ?? false) === true):?>
        <?=$arResult["NAV_STRING"] ?? ''?><br />
<?php endif;?>

<div class="link-directory mt-2 mb-2">
        <?php
        // Получаем информацию о разделе
        $section = ($sectionId > 0 && $iblockId > 0) ? CIBlockSection::GetList(
            [],
            ['ID' => $sectionId, 'IBLOCK_ID' => $iblockId, 'CHECK_PERMISSIONS' => 'Y'],
            false,
            ['ID', 'NAME', 'DESCRIPTION']
        ) : null;
        if (is_object($section) && ($arSection = $section->Fetch()) && (($arParams['SHOW_SECTION_NAME'] ?? 'N') === "Y")):
        ?>
        <div class="csr43-glass-surface link-directory__header mb-4">
                <h2 class="link-directory__title h4 mb-2"><?=htmlspecialcharsbx(site_string($arSection['~NAME'] ?? $arSection['NAME'] ?? ''))?></h2>
                <?php if($arSection['DESCRIPTION']):?>
                        <p class="link-directory__description text-muted"><?=htmlspecialcharsbx(site_plain_text($arSection['~DESCRIPTION'] ?? $arSection['DESCRIPTION']))?></p>
                <?php endif;?>
        </div>
        <?php endif;?>

        <div class="link-directory__grid">
    <?php foreach($arResult["ITEMS"] as $arItem):
                if (!is_array($arItem)) {
                    continue;
                }

                $link = site_url($arItem['PROPERTIES']['LINK']['VALUE'] ?? null);
                $hasLink = $link !== '#';
                $isExternal = $hasLink && site_is_external_http_url($link);
                $target = $isExternal ? '_blank' : '_self';
                $rel = $isExternal ? 'noopener noreferrer' : '';

                // Иконка (по умолчанию стрелка наружу)
                $iconClass = site_css_classes(
                    $arItem['PROPERTIES']['ICON']['VALUE'] ?? null,
                    'bi-box-arrow-up-right'
                );

                // Описание
                $description = site_plain_text(
                    $arItem['~PREVIEW_TEXT']
                        ?? $arItem['~DETAIL_TEXT']
                        ?? $arItem['PREVIEW_TEXT']
                        ?? $arItem['DETAIL_TEXT']
                        ?? ''
                );
                $name = site_string($arItem['~NAME'] ?? $arItem['NAME'] ?? '');

                // Дата
                $showDate = ($arParams['SHOW_DATE'] ?? 'N') !== 'N';
                $dateField = site_string($arParams['DATE_FIELD'] ?? 'TIMESTAMP_X', 'TIMESTAMP_X');
                if (preg_match('/^[A-Z0-9_]+$/D', $dateField) !== 1) {
                    $dateField = 'TIMESTAMP_X';
                }
                $dateValue = site_string(
                    $arItem[$dateField] ?? $arItem['TIMESTAMP_X'] ?? ''
                );
                $timestamp = $dateValue !== '' ? MakeTimeStamp($dateValue) : 0;
    ?>
        <a href="<?=htmlspecialcharsbx($link)?>"
           target="<?=htmlspecialcharsbx($target)?>"
           rel="<?=htmlspecialcharsbx($rel)?>"
           class="csr43-glass-card<?=$hasLink ? ' csr43-glass-card--interactive' : ''?> link-card <?=!$hasLink ? 'link-card--disabled' : ''?>"
           <?php if(!$hasLink):?>onclick="return false;"<?php endif;?>>
            <div class="link-card__surface">
                <div class="link-card__content">
                    <div class="csr43-glass-icon link-card__icon">
                        <i class="bi <?=htmlspecialcharsbx($iconClass)?>"></i>
                    </div>
                    <div class="link-card__info">
                        <h5 class="link-card__title"><?=htmlspecialcharsbx($name)?></h5>
                        <?php if($description):?>
                            <p class="link-card__description"><?=htmlspecialcharsbx(TruncateText($description, 120))?></p>
                        <?php endif;?>
                        <?php if($showDate && $timestamp > 0 && ($arParams['SHOW_UPDATE_DATE']??'Y')!="N"):?>
                            <div class="link-card__date">
                                <i class="bi bi-clock me-1"></i>
                                Обновлено: <?=htmlspecialcharsbx(FormatDate("d.m.Y H:i", $timestamp))?>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
                <?php if($hasLink):?>
                    <div class="link-card__arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>
                <?php endif;?>
            </div>
        </a>
    <?php endforeach;?>
        </div>
</div>

<?php if(($arParams["DISPLAY_BOTTOM_PAGER"] ?? false) === true):?>
        <div class="link-directory__pagination mt-4">
                <?=$arResult["NAV_STRING"] ?? ''?>
        </div>
<?php endif;?>

<?php else:?>
<div class="csr43-glass-surface link-directory__empty">
    <div class="link-directory__empty-icon">
        <i class="bi bi-folder-x"></i>
    </div>
    <div>
        <strong class="d-block mb-1">Ссылки не найдены</strong>
        <span class="small">Ссылки временно недоступны, находятся на обновлении или будут добавлены позже.</span>
    </div>
</div>
<?php endif;?>
