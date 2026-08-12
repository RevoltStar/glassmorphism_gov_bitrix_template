<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['GEO_STATIONS'] ?? null) ? $arResult['GEO_STATIONS'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$pagerHtml = site_string($view['pager_html'] ?? '');
?>
<?php if (($view['show_top_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="geo-stations__pagination"><?=$pagerHtml?></div>
<?php endif; ?>

<div class="geo-stations">
    <div class="geo-stations__table-wrapper">
        <table class="geo-stations__table">
            <caption><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_CAPTION'))?></caption>
            <thead>
                <tr>
                    <th scope="col"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_TYPE'))?></th>
                    <th scope="col"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_NAME_POINT'))?></th>
                    <th scope="col"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_COORD_SYSTEM'))?></th>
                    <th scope="col"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_MEASURING_DEVICE'))?></th>
                    <th scope="col"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_SERIAL_NUMBER'))?></th>
                    <th scope="col"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_LINK'))?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items !== []): ?>
                    <?php foreach ($items as $item): ?>
                        <?php
                        if (!is_array($item)) {
                            continue;
                        }
                        $url = site_url($item['url'] ?? null, '');
                        $linkText = site_string($item['link_text'] ?? '');
                        ?>
                        <tr>
                            <td><?=htmlspecialcharsbx(site_string($item['type'] ?? ''))?></td>
                            <td><?=htmlspecialcharsbx(site_string($item['name_point'] ?? ''))?></td>
                            <td><?=htmlspecialcharsbx(site_string($item['coord_system'] ?? ''))?></td>
                            <td><?=htmlspecialcharsbx(site_string($item['measuring_device'] ?? ''))?></td>
                            <td><?=htmlspecialcharsbx(site_string($item['serial_number'] ?? ''))?></td>
                            <td><?php if ($url !== ''): ?><a href="<?=htmlspecialcharsbx($url)?>" target="_blank" rel="noopener noreferrer"><?=htmlspecialcharsbx($linkText)?></a><?php else: ?><?=htmlspecialcharsbx($linkText)?><?php endif; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="geo-stations__empty"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GEO_STATIONS_EMPTY'))?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (($view['show_bottom_pager'] ?? false) === true && $pagerHtml !== ''): ?>
    <div class="geo-stations__pagination"><?=$pagerHtml?></div>
<?php endif; ?>
