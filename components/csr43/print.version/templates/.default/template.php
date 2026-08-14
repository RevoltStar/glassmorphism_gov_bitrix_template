<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$isPrintMode = ($arResult['IS_PRINT_MODE'] ?? false) === true;
$buttonUrl = site_url($arResult['BUTTON_URL'] ?? null, '');
$buttonText = site_plain_text($arResult['BUTTON_TEXT'] ?? '');
$buttonClass = site_css_classes($arResult['BUTTON_CLASS'] ?? '');
$openInNewWindow = ($arResult['OPEN_IN_NEW_WINDOW'] ?? false) === true;
$linkClasses = 'print-version__link';
if ($buttonClass !== '') {
    $linkClasses .= ' ' . $buttonClass;
}

if ($buttonUrl === '' || $buttonText === '') {
    return;
}
?>
<div class="print-version<?php if ($isPrintMode): ?> print-version--active<?php endif; ?>"
     data-print-version
     data-print-mode="<?=$isPrintMode ? 'Y' : 'N'?>">
    <a href="<?=htmlspecialcharsbx($buttonUrl)?>"
       class="<?=htmlspecialcharsbx($linkClasses)?>"<?php if ($openInNewWindow): ?>
       target="_blank"
       rel="noopener noreferrer"<?php endif; ?>>
        <i class="bi <?=$isPrintMode ? 'bi-arrow-counterclockwise' : 'bi-printer'?> print-version__icon"
           aria-hidden="true"></i>
        <span class="print-version__text"><?=htmlspecialcharsbx($buttonText)?></span>
    </a>
</div>
