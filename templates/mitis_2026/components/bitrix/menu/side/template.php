<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (!empty($arResult)):?>

<?
// Упрощенный подсчет потомков (сохранён)
$childCount = array();

foreach($arResult as $arItem) {
    if ($arItem['DEPTH_LEVEL'] == 1) {
        $childCount[$arItem['TEXT']] = 0;
    }
}

$currentParent = null;
foreach($arResult as $key=>$arItem) {
	/*Не считаем потомки для первого элемента*/
	/*Предполагаем, что первый пункт меню ссылается на текущую страницу*/
	if($key==0){
		continue;
	}
    if ($arItem['DEPTH_LEVEL'] == 1) {
        $currentParent = $arItem['TEXT'];
		$currentParentLink =  $arItem["LINK"];
    } elseif ($arItem['DEPTH_LEVEL'] == 2 && $currentParent && $arItem["LINK"]!=$currentParentLink) {
        $childCount[$currentParent]++;
    }
}
?>

<!-- Мобильный селект (стеклянный) -->
<select class="side-menu-select glass-select" <?if($arParams['FORCE_DESKTOP']=="Y"):?>style="display:none!important;"<?endif?>>
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

<!-- Основное меню для десктопов (стеклянные карточки) -->
<ul class="side-menu glass-side-menu" <?if($arParams['FORCE_DESKTOP']=="Y"):?>style="display:flex!important;"<?endif?>>
    <?foreach($arResult as $arItem):?>
        <?
            $class_ext = "bi bi-arrow-up-right";
            if (!empty($arItem["PARAMS"]["ICON"])) {
                $class_ext = " " . $arItem["PARAMS"]["ICON"];
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
            <li class="glass-menu-item <?if($isSelected):?>glass-menu-item--selected<?endif?>">
                <a href="<?=$arItem['LINK']?>" class="glass-menu-link">
                    <?if(!empty($class_ext)):?>
                        <i class="<?=$class_ext?> me-2" aria-hidden="true"></i>
                    <?endif?>
                    <?=$text?>
                    <span class="glass-menu-arrow"></span>
                </a>
            </li>
        <?endif?>
    <?endforeach?>
</ul>

<?endif?>