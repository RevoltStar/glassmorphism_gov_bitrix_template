<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
?>

<div class="print-version-component">
    <button 
        type="button" 
        class="<?= htmlspecialcharsbx($arParams['BUTTON_CLASS']) ?>" 
        onclick="window.open('<?= $arResult['BUTTON_URL'] ?>', '<?= $arParams['OPEN_IN_NEW_WINDOW'] ? '_blank' : '_self' ?>')"
    >
        <i class="bi bi-printer"></i>
        <?= htmlspecialcharsbx($arResult['BUTTON_TEXT']) ?>
    </button>
</div>