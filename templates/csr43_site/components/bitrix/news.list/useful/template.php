<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = $arResult['ITEMS'] ?? [];
if (!is_array($items) || $items === []) {
    return;
}
?>
<div class="row g-3">
    <?php foreach ($items as $value): ?>
        <?php
        if (!is_array($value)) {
            continue;
        }

        $link = '#';
        $target = '';
        $rel = '';
        $linkValue = $value['PROPERTIES']['LINK']['VALUE'] ?? null;

        $link = site_url($linkValue);
        if (site_is_external_http_url($link)) {
            $target = '_blank';
            $rel = 'noopener noreferrer';
        }

        $icon = 'bi bi-arrow-up-right';
        $iconValue = $value['PROPERTIES']['ICON']['VALUE'] ?? null;
        if (
            is_string($iconValue)
            && preg_match(
                '/^[a-zA-Z0-9_-]+(?:\s+[a-zA-Z0-9_-]+)*$/D',
                trim($iconValue)
            ) === 1
        ) {
            $icon = trim($iconValue);
        }

        $nameValue = $value['~NAME'] ?? $value['NAME'] ?? '';
        $name = is_scalar($nameValue) ? (string)$nameValue : '';
        ?>
        <div class="col-md-3 col-6">
            <a href="<?=htmlspecialcharsbx($link)?>"
               class="csr43-glass-surface csr43-glass-card--interactive csr43-glass-card--stretch resource-link"
               <?php if ($target !== ''): ?>target="<?=htmlspecialcharsbx($target)?>"<?php endif; ?>
               <?php if ($rel !== ''): ?>rel="<?=htmlspecialcharsbx($rel)?>"<?php endif; ?>>
                <div class="h-100 resource-link__content">
                    <div>
                        <i class="<?=htmlspecialcharsbx($icon)?> fs-5 me-2"
                           style="color: #2980b9;"
                           aria-hidden="true"></i>
                        <span><?=htmlspecialcharsbx($name)?></span>
                    </div>
                    <div class="text-end">
                        <span class="small"><?=htmlspecialcharsbx($link)?></span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
