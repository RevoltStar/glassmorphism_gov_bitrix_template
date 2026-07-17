<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<?if(!empty($arResult['ITEMS'])):?>

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
        <?=$arResult["NAV_STRING"]?><br />
<?endif;?>

<div class="glass-links mt-2 mb-2">
        <?
        // Получаем информацию о разделе
        $section = CIBlockSection::GetByID($arParams['SECTION_ID']);
        if (($arSection = $section->GetNext()) && ($arParams['SHOW_SECTION_NAME']=="Y")):
        ?>
        <div class="glass-section-header mb-4">
                <h2 class="glass-section-title h4 mb-2"><?=$arSection['NAME']?></h2>
                <?if($arSection['DESCRIPTION']):?>
                        <p class="glass-section-desc text-muted"><?=$arSection['DESCRIPTION']?></p>
                <?endif;?>
        </div>
        <?endif;?>

        <div class="glass-links-grid">
    <?foreach($arResult["ITEMS"] as $arItem):
                $link = $arItem['PROPERTIES']['LINK']['VALUE'] ?? '#';
                $isExternal = !empty($link) && $link !== '#';
                $target = $isExternal ? '_blank' : '_self';
                $rel = $isExternal ? 'noopener noreferrer' : '';
                
                // Иконка (по умолчанию стрелка наружу)
                $iconClass = $arItem['PROPERTIES']['ICON']['VALUE'] ?? 'bi-box-arrow-up-right';
                
                // Описание
                $description = $arItem['PREVIEW_TEXT'] ?? $arItem['DETAIL_TEXT'] ?? '';

                // Дата
                $showDate = $arParams['SHOW_DATE'] !== 'N';
                $dateField = $arParams['DATE_FIELD'] ?? 'TIMESTAMP_X';
                $dateValue = $arItem[$dateField] ?? $arItem['TIMESTAMP_X'];
    ?>
        <a href="<?=$link?>" 
           target="<?=$target?>" 
           rel="<?=$rel?>" 
           class="glass-link-card <?=!$isExternal ? 'glass-link-card--disabled' : ''?>"
           <?if(!$isExternal):?>onclick="return false;"<?endif;?>>
            <div class="glass-link-card-inner">
                <div class="glass-link-card-content">
                    <div class="glass-link-icon">
                        <i class="bi <?=$iconClass?>"></i>
                    </div>
                    <div class="glass-link-info">
                        <h5 class="glass-link-title"><?=$arItem["NAME"]?></h5>
                        <?if($description):?>
                            <p class="glass-link-desc"><?=TruncateText($description, 120)?></p>
                        <?endif;?>
                        <?if($showDate && $dateValue && ($arParams['SHOW_UPDATE_DATE']??'Y')!="N"):?>
                            <div class="glass-link-date">
                                <i class="bi bi-clock me-1"></i>
                                Обновлено: <?=FormatDate("d.m.Y H:i", MakeTimeStamp($dateValue))?>
                            </div>
                        <?endif;?>
                    </div>
                </div>
                <?if($isExternal):?>
                    <div class="glass-link-arrow">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>
                <?endif;?>
            </div>
        </a>
    <?endforeach;?>
        </div>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <div class="glass-pagination mt-4">
                <?=$arResult["NAV_STRING"]?>
        </div>
<?endif;?>

<?else:?>
<div class="glass-empty-alert">
    <div class="glass-empty-icon">
        <i class="bi bi-folder-x"></i>
    </div>
    <div>
        <strong class="d-block mb-1">Ссылки не найдены</strong>
        <span class="small">Ссылки временно недоступны, находятся на обновлении или будут добавлены позже.</span>
    </div>
</div>
<?endif;?>