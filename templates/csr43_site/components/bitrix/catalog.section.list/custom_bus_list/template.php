<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

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

$allowedVehicleTypes = ['bus_big', 'bus_medium', 'bus_little', 'trolleybus'];
$vehicleTypeLabels = [
    'bus_little' => 'Малый класс',
    'bus_medium' => 'Средний класс',
    'bus_big' => 'Большой класс',
    'trolleybus' => 'Троллейбус',
];
$routeGroups = [];

$resolveVehicleType = static function (array $property) use ($allowedVehicleTypes): string {
    $xmlId = trim((string)($property['VALUE_XML_ID'] ?? ''));

    return in_array($xmlId, $allowedVehicleTypes, true) ? $xmlId : '';
};

$sections = is_array($arResult['SECTIONS'] ?? null)
    ? array_values(array_filter($arResult['SECTIONS'], 'is_array'))
    : [];

foreach ($sections as $section) {
    $this->AddEditAction(
        $section['ID'],
        $section['EDIT_LINK'],
        CIBlock::GetArrayByID($section['IBLOCK_ID'], 'SECTION_EDIT')
    );
    $this->AddDeleteAction(
        $section['ID'],
        $section['DELETE_LINK'],
        CIBlock::GetArrayByID($section['IBLOCK_ID'], 'SECTION_DELETE'),
        ['CONFIRM' => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')]
    );

    $routes = [];
    if (($arParams['SHOW_ELEMENTS'] ?? 'N') === 'Y') {
        $elements = CIBlockElement::GetList(
            ['SORT' => 'ASC', 'NAME' => 'ASC', 'ID' => 'ASC'],
            [
                'IBLOCK_ID' => (int)$arParams['IBLOCK_ID'],
                'SECTION_ID' => (int)$section['ID'],
                'INCLUDE_SUBSECTIONS' => 'N',
                'ACTIVE' => 'Y',
                'ACTIVE_DATE' => 'Y',
                'CHECK_PERMISSIONS' => 'Y',
                'MIN_PERMISSION' => 'R',
            ],
            false,
            false,
            ['ID', 'IBLOCK_ID', 'NAME', 'SORT']
        );

        while ($element = $elements->GetNext()) {
            $properties = [];
            $vehicleTypeProperty = [];
            $propertyResult = CIBlockElement::GetProperty(
                (int)$section['IBLOCK_ID'],
                (int)$element['ID'],
                ['sort' => 'asc'],
                []
            );
            while ($property = $propertyResult->Fetch()) {
                $propertyCode = (string)$property['CODE'];
                if ($propertyCode === 'ts_type') {
                    $vehicleTypeProperty = $property;
                    continue;
                }

                $properties[$propertyCode] = (string)$property['VALUE'];
            }

            $vehicleType = $resolveVehicleType($vehicleTypeProperty);
            $mapUrl = trim($properties['link_yandex'] ?? '');
            if (!preg_match('~^https://(?:www\.)?yandex\.ru/~i', $mapUrl)) {
                $mapUrl = '';
            }

            $routes[] = [
                'ID' => (int)$element['ID'],
                'NAME' => (string)$element['NAME'],
                'START' => trim($properties['start_way'] ?? ''),
                'END' => trim($properties['end_way'] ?? ''),
                'MAP_URL' => $mapUrl,
                'VEHICLE_TYPE' => $vehicleType,
            ];
        }
    }

    if ($routes) {
        $routeGroups[] = [
            'SECTION' => $section,
            'ROUTES' => $routes,
        ];
    }
}
?>

<?php if ($routeGroups): ?>
<div class="bus-route-list" aria-label="Маршруты общественного транспорта">
    <?php foreach ($routeGroups as $group): ?>
        <?php
        $section = $group['SECTION'];
        $routes = $group['ROUTES'];
        $isTrolleybus = mb_stripos((string)$section['NAME'], 'троллейбус') !== false;
        $sectionDomId = 'bus-route-section-' . (int)$section['ID'];
        ?>
        <section class="csr43-glass-surface bus-route-list__group"
                 id="<?=htmlspecialcharsbx($this->GetEditAreaId($section['ID']))?>"
                 aria-labelledby="<?=$sectionDomId?>">
            <header class="bus-route-list__group-header">
                <span class="csr43-glass-icon bus-route-list__group-icon" aria-hidden="true">
                    <i class="bi <?=$isTrolleybus ? 'bi-lightning-charge' : 'bi-bus-front'?>"></i>
                </span>
                <div class="bus-route-list__group-heading">
                    <h4 class="bus-route-list__group-title" id="<?=$sectionDomId?>">
                        <?=htmlspecialcharsbx($section['NAME'])?>
                    </h4>
                    <span class="bus-route-list__group-count"><?=mtRouteCountLabel(count($routes))?></span>
                </div>
            </header>

            <div class="bus-route-list__grid">
                <?php foreach ($routes as $route): ?>
                    <?php
                    $this->AddEditAction(
                        $route['ID'],
                        '',
                        CIBlock::GetArrayByID((int)$arParams['IBLOCK_ID'], 'ELEMENT_EDIT')
                    );
                    $tag = $route['MAP_URL'] !== '' ? 'a' : 'div';
                    $attributes = $route['MAP_URL'] !== ''
                        ? ' href="' . htmlspecialcharsbx($route['MAP_URL']) . '" target="_blank" rel="noopener noreferrer"'
                        : '';
                    $vehicleImageUrl = '';
                    if ($route['VEHICLE_TYPE'] !== '') {
                        $vehicleImageUrl = $templateFolder . '/images/' . $route['VEHICLE_TYPE'] . '.jpg';
                        $vehicleImageFile = $_SERVER['DOCUMENT_ROOT'] . $vehicleImageUrl;
                        if (is_file($vehicleImageFile)) {
                            $vehicleImageUrl .= '?v=' . filemtime($vehicleImageFile);
                        }
                    }
                    $vehicleTypeLabel = $vehicleTypeLabels[$route['VEHICLE_TYPE']] ?? 'Тип не указан';
                    ?>
                    <<?=$tag?> class="csr43-glass-card<?=$route['MAP_URL'] !== '' ? ' csr43-glass-card--interactive' : ''?> bus-route-card"
                        id="<?=htmlspecialcharsbx($this->GetEditAreaId($route['ID']))?>"<?=$attributes?>>
                        <span class="bus-route-card__top">
                            <span class="bus-route-card__vehicle bus-route-card__vehicle--<?=htmlspecialcharsbx($route['VEHICLE_TYPE'] ?: 'unknown')?>" aria-hidden="true">
                                <?php if ($route['VEHICLE_TYPE'] !== ''): ?>
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
                                <span class="bus-route-card__number"><?=htmlspecialcharsbx($route['NAME'])?></span>
                                <span class="bus-route-card__type bus-route-card__type--<?=htmlspecialcharsbx($route['VEHICLE_TYPE'] ?: 'unknown')?>">
                                    <?=htmlspecialcharsbx($vehicleTypeLabel)?>
                                </span>
                            </span>
                            <?php if ($route['MAP_URL'] !== ''): ?>
                                <span class="bus-route-card__external" aria-hidden="true">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                </span>
                            <?php endif; ?>
                        </span>

                        <span class="bus-route-card__path">
                            <span class="bus-route-card__stop bus-route-card__stop--start">
                                <span class="bus-route-card__stop-marker" aria-hidden="true"></span>
                                <span><?=htmlspecialcharsbx($route['START'] ?: 'Начальная остановка не указана')?></span>
                            </span>
                            <span class="bus-route-card__connector" aria-hidden="true"></span>
                            <span class="bus-route-card__stop bus-route-card__stop--end">
                                <span class="bus-route-card__stop-marker" aria-hidden="true"></span>
                                <span><?=htmlspecialcharsbx($route['END'] ?: 'Конечная остановка не указана')?></span>
                            </span>
                        </span>

                        <?php if ($route['MAP_URL'] !== ''): ?>
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
