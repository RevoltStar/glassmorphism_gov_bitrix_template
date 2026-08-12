<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
<!-- Десктопное меню -->
<ul class="top-menu">

<?
$previousLevel = 0;
foreach($arResult as $arItem):?>

	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<li><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a>
				<ul>
		<?else:?>
			<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>" class="parent"><?=$arItem["TEXT"]?></a>
				<ul>
		<?endif?>

	<?else:?>

		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<?
				if($arItem['LINK']=="/"){
					if($APPLICATION->GetCurPage() != '/'){
						$arItem['SELECTED'] = false;
					}
				}
			?>
			<li><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a></li>
		<?else:?>
			<li<?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</ul>
<div class="menu-clear-left"></div>

<!-- Мобильное меню -->
<div class="mobile-menu">
    <!-- Гамбургер кнопка -->
    <div class="mobile-menu-toggle" id="mobileMenuToggle">
        <span></span>
        <span></span>
        <span></span>
    </div>
    
    <!-- Оверлей -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
    
    <!-- Боковое меню -->
    <div class="mobile-menu-sidebar" id="mobileMenuSidebar">
        <div class="mobile-menu-header">
            <div class="mobile-menu-close" id="mobileMenuClose">
                <span></span>
                <span></span>
            </div>
        </div>
        
        <ul class="mobile-menu-list">
            <?
            $previousLevel = 0;
            foreach($arResult as $arItem):?>
            
                <?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
                    <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
                <?endif?>
            
                <?if ($arItem["IS_PARENT"]):?>
            
                    <li class="mobile-menu-item mobile-menu-parent">
                        <div class="mobile-menu-link-wrapper">
                            <a href="<?=$arItem["LINK"]?>" class="mobile-menu-link <?if ($arItem["SELECTED"]):?>mobile-menu-selected<?endif?>">
                                <?=$arItem["TEXT"]?>
                            </a>
                            <span class="mobile-menu-arrow"></span>
                        </div>
                        <ul class="mobile-submenu">
            
                <?else:?>
                    <li class="mobile-menu-item">
					<?
							if($arItem['LINK']=="/"){
								if($APPLICATION->GetCurPage() != '/'){
									$arItem['SELECTED'] = false;
								}
							}
					?>
                        <a href="<?=$arItem["LINK"]?>" class="mobile-menu-link <?if ($arItem["SELECTED"]):?>mobile-menu-selected<?endif?>">
                            <?=$arItem["TEXT"]?>
                        </a>
                    </li>
                <?endif?>
            
                <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
            
            <?endforeach?>
            
            <?if ($previousLevel > 1)://close last item tags?>
                <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
            <?endif?>
        </ul>
    </div>
</div>

<?endif?>