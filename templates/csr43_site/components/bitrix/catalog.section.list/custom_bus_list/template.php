<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
$arParams = is_array($arParams ?? null) ? $arParams : [];
$arResult = is_array($arResult ?? null) ? $arResult : [];

if (!function_exists('mtRouteCountLabel')) {
    function mtRouteCountLabel(int $count): string
    {
        $lastTwo = $count % 100;
        $last = $count % 10;

        if ($lastTwo >= 11 && $lastTwo <= 14) {
            return $count . ' маршрутов';
        }
        if ($last === 1) {
            return $count . ' маршрут';
        }
        if ($last >= 2 && $last <= 4) {
            return $count . ' маршрута';
        }

        return $count . ' маршрутов';
    }
}

$vehicleTypeLabels = [
    'bus_little' => 'Малый класс',
    'bus_medium' => 'Средний класс',
    'bus_big' => 'Большой класс',
    'trolleybus' => 'Троллейбус',
];
$routeGroups = is_array($arResult['ROUTE_GROUPS'] ?? null)
    ? array_values(array_filter($arResult['ROUTE_GROUPS'], 'is_array'))
    : [];
$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
?>

<?php if ($routeGroups): ?>
<div class="bus-route-list" aria-label="Маршруты общественного транспорта">
    <?php foreach ($routeGroups as $group): ?>
        <?php
        $section = is_array($group['SECTION'] ?? null) ? $group['SECTION'] : [];
        $routes = is_array($group['ROUTES'] ?? null)
            ? array_values(array_filter($group['ROUTES'], 'is_array'))
            : [];
        $sectionId = max(0, (int)($section['ID'] ?? 0));
        $sectionIblockId = max(0, (int)($section['IBLOCK_ID'] ?? $iblockId));
        $sectionName = site_string($section['NAME'] ?? '');
        $isTrolleybus = mb_stripos($sectionName, 'троллейбус') !== false;
        $sectionDomId = 'bus-route-section-' . $sectionId;

        $this->AddEditAction(
            $sectionId,
            site_string($section['EDIT_LINK'] ?? ''),
            CIBlock::GetArrayByID($sectionIblockId, 'SECTION_EDIT')
        );
        $this->AddDeleteAction(
            $sectionId,
            site_string($section['DELETE_LINK'] ?? ''),
            CIBlock::GetArrayByID($sectionIblockId, 'SECTION_DELETE'),
            ['CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')]
        );
        ?>
        <section class="csr43-glass-surface bus-route-list__group"
                 id="<?=htmlspecialcharsbx($this->GetEditAreaId($sectionId))?>"
                 aria-labelledby="<?=$sectionDomId?>">
            <header class="bus-route-list__group-header">
                <span class="csr43-glass-icon bus-route-list__group-icon" aria-hidden="true">
                    <i class="bi <?=$isTrolleybus ? 'bi-lightning-charge' : 'bi-bus-front'?>"></i>
                </span>
                <div class="bus-route-list__group-heading">
                    <h4 class="bus-route-list__group-title" id="<?=$sectionDomId?>">
                        <?=htmlspecialcharsbx($sectionName)?>
                    </h4>
                    <span class="bus-route-list__group-count"><?=mtRouteCountLabel(count($routes))?></span>
                </div>
            </header>

            <div class="bus-route-list__grid">
                <?php foreach ($routes as $route): ?>
                    <?php
                    $routeId = max(0, (int)($route['ID'] ?? 0));
                    $routeName = site_string($route['NAME'] ?? '');
                    $routeStart = site_string($route['START'] ?? '');
                    $routeEnd = site_string($route['END'] ?? '');
                    $mapUrl = site_string($route['MAP_URL'] ?? '');
                    $vehicleType = site_string($route['VEHICLE_TYPE'] ?? '');
                    if (!array_key_exists($vehicleType, $vehicleTypeLabels)) {
                        $vehicleType = '';
                    }
                    $this->AddEditAction(
                        $routeId,
                        '',
                        CIBlock::GetArrayByID($iblockId, 'ELEMENT_EDIT')
                    );
                    $tag = $mapUrl !== '' ? 'a' : 'div';
                    $attributes = $mapUrl !== ''
                        ? ' href="' . htmlspecialcharsbx($mapUrl) . '" target="_blank" rel="noopener noreferrer"'
                        : '';
                    $vehicleImageUrl = '';
                    if ($vehicleType !== '') {
                        $vehicleImageUrl = $templateFolder . '/images/' . $vehicleType . '.jpg';
                        $vehicleImageFile = $_SERVER['DOCUMENT_ROOT'] . $vehicleImageUrl;
                        if (is_file($vehicleImageFile)) {
                            $vehicleImageUrl .= '?v=' . filemtime($vehicleImageFile);
                        }
                    }
                    $vehicleTypeLabel = $vehicleTypeLabels[$vehicleType] ?? 'Тип не указан';
                    ?>
                    <<?=$tag?> class="csr43-glass-card<?=$mapUrl !== '' ? ' csr43-glass-card--interactive' : ''?> bus-route-card"
                        id="<?=htmlspecialcharsbx($this->GetEditAreaId($routeId))?>"<?=$attributes?>>
                        <span class="bus-route-card__top">
                            <span class="bus-route-card__vehicle bus-route-card__vehicle--<?=htmlspecialcharsbx($vehicleType ?: 'unknown')?>" aria-hidden="true">
                                <?php if ($vehicleType !== ''): ?>
                                    <img src="<?=htmlspecialcharsbx($vehicleImageUrl)?>"
                                         alt=""
                                         width="76"
                                         height="57"
                                         loading="lazy">
                                <?php else: ?>
                                    <i class="bi bi-bus-front"></i>
                                <?php endif; ?>
                            </span>
                            <span class="bus-route-card__identity">
                                <span class="bus-route-card__number"><?=htmlspecialcharsbx($routeName)?></span>
                                <span class="bus-route-card__type bus-route-card__type--<?=htmlspecialcharsbx($vehicleType ?: 'unknown')?>">
                                    <?=htmlspecialcharsbx($vehicleTypeLabel)?>
                                </span>
                            </span>
                            <?php if ($mapUrl !== ''): ?>
                                <span class="bus-route-card__external" aria-hidden="true">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </span>
                            <?php endif; ?>
                        </span>

                        <span class="bus-route-card__path">
                            <span class="bus-route-card__stop bus-route-card__stop--start">
                                <span class="bus-route-card__stop-marker" aria-hidden="true"></span>
                                <span><?=htmlspecialcharsbx($routeStart ?: 'Начальная остановка не указана')?></span>
                            </span>
                            <span class="bus-route-card__connector" aria-hidden="true"></span>
                            <span class="bus-route-card__stop bus-route-card__stop--end">
                                <span class="bus-route-card__stop-marker" aria-hidden="true"></span>
                                <span><?=htmlspecialcharsbx($routeEnd ?: 'Конечная остановка не указана')?></span>
                            </span>
                        </span>

                        <?php if ($mapUrl !== ''): ?>
                            <span class="bus-route-card__hint">
                                Открыть маршрут на Яндекс Картах
                                <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </span>
                        <?php endif; ?>
                    </<?=$tag?>>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>
</div>
<?php else: ?>
    <div class="csr43-glass-surface bus-route-list__empty" role="status">
        <i class="bi bi-info-circle" aria-hidden="true"></i>
        Информация о маршрутах временно отсутствует.
    </div>
<?php endif; ?>
