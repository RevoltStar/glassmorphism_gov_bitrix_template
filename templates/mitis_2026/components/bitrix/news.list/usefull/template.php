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

        if (is_string($linkValue)) {
            $linkValue = trim($linkValue);
            $isInternalLink = preg_match(
                '~^/(?!/)[^\x00-\x1F\x7F]*$~u',
                $linkValue
            ) === 1;
            $scheme = strtolower((string)parse_url($linkValue, PHP_URL_SCHEME));
            $isHttpLink = filter_var($linkValue, FILTER_VALIDATE_URL) !== false
                && in_array($scheme, ['http', 'https'], true);

            if ($isInternalLink || $isHttpLink) {
                $link = $linkValue;
            }

            if ($isHttpLink) {
                $target = '_blank';
                $rel = 'noopener noreferrer';
            }
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
               class="resource-link"
               <?php if ($target !== ''): ?>target="<?=htmlspecialcharsbx($target)?>"<?php endif; ?>
               <?php if ($rel !== ''): ?>rel="<?=htmlspecialcharsbx($rel)?>"<?php endif; ?>>
                <div class="h-100 resource-link-info-container">
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
