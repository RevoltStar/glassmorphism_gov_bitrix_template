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
<div class="row g-4">
    <?php foreach ($items as $subordinate): ?>
        <?php
        if (!is_array($subordinate)) {
            continue;
        }

        $link = site_url($subordinate['PROPERTIES']['LINK']['VALUE'] ?? null);
        $logoValue = $subordinate['PROPERTIES']['LOGO']['VALUE'] ?? null;
        $logoId = filter_var($logoValue, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
        $logoSrc = '';
        if ($logoId !== false) {
            $file = CFile::GetFileArray($logoId);
            if (is_array($file)) {
                $logoSrc = site_url($file['SRC'] ?? null, '');
            }
        }

        $name = site_string(
            $subordinate['~NAME'] ?? $subordinate['NAME'] ?? ''
        );
        $preview = site_plain_text(
            $subordinate['~PREVIEW_TEXT']
                ?? $subordinate['PREVIEW_TEXT']
                ?? ''
        );
        $badges = site_string_list(
            $subordinate['PROPERTIES']['BADGES']['VALUE'] ?? []
        );
        ?>
        <div class="col-md-6">
            <div class="glass-card d-flex align-items-center">
                <?php if ($logoSrc !== ''): ?>
                    <div class="position-absolute d-block d-md-none" style="right: 10px; top: 10px;">
                        <img style="width: 40px; opacity: 0.5;"
                             src="<?=htmlspecialcharsbx($logoSrc)?>"
                             alt="Логотип <?=htmlspecialcharsbx($name)?>">
                    </div>
                <?php endif; ?>
                <div class="me-4 d-none d-md-block">
                    <div class="suborg-icon">
                        <?php if ($logoSrc !== ''): ?>
                            <a href="<?=htmlspecialcharsbx($link)?>">
                                <img src="<?=htmlspecialcharsbx($logoSrc)?>"
                                     alt="Логотип <?=htmlspecialcharsbx($name)?>">
                            </a>
                        <?php else: ?>
                            <i class="bi bi-building fs-1" aria-hidden="true"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <div>
                    <h4 class="fw-bold" style="color: #1e3a5f;"><?=htmlspecialcharsbx($name)?></h4>
                    <?php if ($preview !== ''): ?>
                        <p style="color: #2c6b9e;"><?=htmlspecialcharsbx($preview)?></p>
                    <?php endif; ?>
                    <?php if ($badges !== []): ?>
                        <div class="d-flex gap-2 flex-wrap">
                            <?php foreach ($badges as $badge): ?>
                                <span class="badge sub-badge"><?=htmlspecialcharsbx($badge)?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($link !== '#'): ?>
                        <a href="<?=htmlspecialcharsbx($link)?>"
                           class="btn btn-sm btn-outline-primary rounded-pill mt-2">Перейти на сайт</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
