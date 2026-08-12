<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<?
// Упрощенный подсчет потомков
$childCount = array();

// Сначала инициализируем счетчики для всех элементов первого уровня
foreach($arResult as $arItem) {
    if ($arItem['DEPTH_LEVEL'] == 1) {
        $childCount[$arItem['TEXT']] = 0;
    }
}

// Затем считаем потомков
$currentParent = null;
foreach($arResult as $arItem) {
    if ($arItem['DEPTH_LEVEL'] == 1) {
        $currentParent = $arItem['TEXT'];
    } elseif ($arItem['DEPTH_LEVEL'] == 2 && $currentParent) {
        $childCount[$currentParent]++;
    }
}
?>

<!-- Select для мобильных устройств -->
<select class="side-menu-select" <?if($arParams['FORCE_DESKTOP']=="Y"):?>style="display:none!important;"<?endif?>>
    <option value="">Выберите подраздел...</option>
    <?foreach($arResult as $arItem):?>
        <?if($arItem['DEPTH_LEVEL'] == 1):?>
            <?
            $text = $arItem['TEXT'];
            $count = $childCount[$arItem['TEXT']] ?? 0;
            if ($count > 0) {
                $text .= ' (' . $count . ')';
            }
            
            $isSelected = $arItem['SELECTED'] && $arItem['LINK'] == $APPLICATION->GetCurPage();
            ?>
            <option value="<?=$arItem['LINK']?>" <?if($isSelected):?>selected<?endif?>>
                <?=$text?>
            </option>
        <?endif?>
    <?endforeach?>
</select>

<!-- Основное меню для десктопов -->
<ul class="side-menu" <?if($arParams['FORCE_DESKTOP']=="Y"):?>style="display:flex!important;"<?endif?>>
    <?foreach($arResult as $arItem):?>
        <?
            $class_ext = "";
            if (!empty($arItem["PARAMS"]["EXT_CLASS"])) {
                $class_ext = " " . $arItem["PARAMS"]["EXT_CLASS"];
            }
        ?>
        <?if($arItem['DEPTH_LEVEL'] == 1):?>
            <?
            $text = $arItem['TEXT'];
            $count = $childCount[$arItem['TEXT']] ?? 0;
            if ($count > 0) {
                $text .= ' (' . $count . ')';
            }
            
            $isSelected = $arItem['SELECTED'] && $arItem['LINK'] == $APPLICATION->GetCurPage();
            ?>
            <li class="<?if($isSelected):?>selected<?endif?>">
                <a href="<?=$arItem['LINK']?>">
                    <?if(!empty($class_ext)):?>
                        <i class="<?=$class_ext?> me-2"></i>
                    <?endif?>
                    <?=$text?>
                </a>
            </li>
        <?endif?>
    <?endforeach?>
	<?if($APPLICATION->GetCurPage()!="/"):?>
		<!--<li>
			<a href="<?=dirname($APPLICATION->GetCurPage())?>" class="w-50" style="background-color:lightgray;">
				<i class="bi bi-box-arrow-up-left me-2"></i>
				Назад
			</a>
		</li>-->
	<?endif?>
</ul>
<?endif?>