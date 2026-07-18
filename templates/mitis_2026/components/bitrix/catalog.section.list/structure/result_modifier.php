<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
$arResult['POSITION'] = [];
if ($iblockId === 0) {
    return;
}

$rsSections = CIBlockSection::GetList(array(), array('ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockId, 'CHECK_PERMISSIONS' => 'Y'), false, ["ID", "NAME", "UF_DESCRIPTION"]);
while ($arSection = $rsSections->GetNext()) {
	$sectionId = max(0, (int)($arSection['ID'] ?? 0));
    if ($sectionId > 0) {
        $arResult['POSITION'][$sectionId] = site_string($arSection['UF_DESCRIPTION'] ?? '');
    }
}
