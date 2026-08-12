<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<div class="table-responsive">
	<table class="table table-hover table-bordered" style="font-size: 0.9rem;">
	<thead>
      <tr>
        <!--<th class="align-middle text-center">Номер</th>-->
        <th class="align-middle text-center">Вид геодезической сети</th>
        <th class="align-middle text-center">Название пункта геодезической сети</th>
        <th class="align-middle text-center">Система координат пункта геодезической сети</th>
        <th class="align-middle text-center">Наименование и обозначение типа средства измерений – прибора (инструмента, аппаратуры)</th>
        <th class="align-middle text-center">Заводской или серийный номер средства измерений</th>
		<th class="align-middle text-center">Ссылка на реквизиты свидетельства о проверке прибора</th>
      </tr>
    </thead>
	<?if($arResult['ITEMS']):?>
	<tbody>
	<?php foreach ($arResult["ITEMS"] as $key=>$station): ?>
		<tr>
			<!--<td class="align-middle text-center"><?=$key+1?></td>-->
			<td class="align-middle text-center"><?=$station["PROPERTIES"]["TYPE"]["VALUE"]?></td>
			<td class="align-middle text-center"><?=$station["PROPERTIES"]["NAME_POINT"]["VALUE"]?></td>
			<td class="align-middle text-center"><?=$station["PROPERTIES"]["COORD_SYSTEM"]["VALUE"]?></td>
			<td class="align-middle text-center"><?=$station["PROPERTIES"]["MEASURING_DEVICE"]["VALUE"]?></td>
			<td class="align-middle text-center"><?=$station["PROPERTIES"]["SERIAL_NUMBER"]["VALUE"]?></td>
			<td class="align-middle text-center"><a target="_blank" href="<?=$station["PROPERTIES"]["LINK"]["VALUE"]?>"><?=$station["PROPERTIES"]["LINK"]["VALUE"]?></a></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<?else:?>
		<tbody>
			<tr>
				<td colspan="7">Нет данных</td>
			</tr>
		</tbody>
	<?endif;?>
  </table>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>