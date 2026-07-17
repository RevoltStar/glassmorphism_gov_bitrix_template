<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$rsSections = CIBlockSection::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID'=> $arParams['IBLOCK_ID']), false, ["ID", "NAME", "UF_DESCRIPTION"]);
while ($arSection = $rsSections->GetNext()) {
	$arResult['POSITION'][$arSection['ID']] = $arSection['UF_DESCRIPTION'];
}