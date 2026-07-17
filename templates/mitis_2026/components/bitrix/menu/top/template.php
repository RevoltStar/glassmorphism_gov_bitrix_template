<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

if (!function_exists('customMenuNormalizePath')) {
    function customMenuNormalizePath($url)
    {
        $path = parse_url((string)$url, PHP_URL_PATH);

        if ($path === false || $path === null || $path === '') {
            return '/';
        }

        $path = '/' . ltrim($path, '/');
        $path = preg_replace('#/+#', '/', $path);
        $path = preg_replace('#/index\.php$#', '/', $path);

        if ($path !== '/') {
            $path = rtrim($path, '/') . '/';
        }

        return $path;
    }
}

if (!function_exists('customMenuIsExternalLink')) {
    function customMenuIsExternalLink($url)
    {
        return preg_match('#^[a-z][a-z0-9+\-.]*://#i', (string)$url);
    }
}

if (!function_exists('customMenuBuildTree')) {
    function customMenuBuildTree(array $arResult)
    {
        $tree = [];
        $parents = [];
        $parents[0] = &$tree;

        foreach ($arResult as $index => $item) {
            $level = !empty($item['DEPTH_LEVEL']) ? (int)$item['DEPTH_LEVEL'] : 1;

            if ($level < 1) {
                $level = 1;
            }

            foreach (array_keys($parents) as $parentLevel) {
                if ($parentLevel >= $level) {
                    unset($parents[$parentLevel]);
                }
            }

            if (!isset($parents[$level - 1])) {
                $parents[$level - 1] = &$tree;
            }

            $item['__INDEX'] = $index;
            $item['__LEVEL'] = $level;
            $item['CHILDREN'] = [];

            $parents[$level - 1][] = $item;

            $lastIndex = count($parents[$level - 1]) - 1;
            $parents[$level] = &$parents[$level - 1][$lastIndex]['CHILDREN'];
        }

        return $tree;
    }
}

if (!function_exists('customMenuGetActiveIndex')) {
    function customMenuGetActiveIndex(array $arResult)
    {
        global $APPLICATION;

        $currentPath = customMenuNormalizePath($APPLICATION->GetCurPage(false));

        $activeIndex = null;
        $activeLength = -1;
        $activeDepth = -1;
        $activeIsParent = true;

        $selectedFallbackIndex = null;
        $selectedFallbackLength = -1;
        $selectedFallbackDepth = -1;
        $selectedFallbackIsParent = true;

        foreach ($arResult as $index => $item) {
            if (empty($item['LINK'])) {
                continue;
            }

            $itemLink = trim((string)$item['LINK']);

            if ($itemLink === '' || $itemLink === '#') {
                continue;
            }

            if (customMenuIsExternalLink($itemLink)) {
                continue;
            }

            $itemPath = customMenuNormalizePath($itemLink);
            $itemLength = strlen($itemPath);
            $itemDepth = !empty($item['DEPTH_LEVEL']) ? (int)$item['DEPTH_LEVEL'] : 1;
            $itemIsParent = !empty($item['IS_PARENT']);

            if (!empty($item['SELECTED'])) {
                $isBetterSelected =
                    $itemLength > $selectedFallbackLength ||
                    (
                        $itemLength === $selectedFallbackLength &&
                        (
                            (!$itemIsParent && $selectedFallbackIsParent) ||
                            ($itemDepth > $selectedFallbackDepth) ||
                            ($itemDepth === $selectedFallbackDepth && $selectedFallbackIndex !== null && $index > $selectedFallbackIndex)
                        )
                    );

                if ($isBetterSelected) {
                    $selectedFallbackIndex = $index;
                    $selectedFallbackLength = $itemLength;
                    $selectedFallbackDepth = $itemDepth;
                    $selectedFallbackIsParent = $itemIsParent;
                }
            }

            if ($itemPath === '/') {
                $isMatch = ($currentPath === '/');
            } else {
                $isMatch = (
                    $currentPath === $itemPath ||
                    strpos($currentPath, $itemPath) === 0
                );
            }

            if (!$isMatch) {
                continue;
            }

            $isBetterMatch =
                $itemLength > $activeLength ||
                (
                    $itemLength === $activeLength &&
                    (
                        (!$itemIsParent && $activeIsParent) ||
                        ($itemDepth > $activeDepth) ||
                        ($itemDepth === $activeDepth && $activeIndex !== null && $index > $activeIndex)
                    )
                );

            if ($isBetterMatch) {
                $activeIndex = $index;
                $activeLength = $itemLength;
                $activeDepth = $itemDepth;
                $activeIsParent = $itemIsParent;
            }
        }

        if ($activeIndex !== null) {
            return $activeIndex;
        }

        return $selectedFallbackIndex;
    }
}

if (!function_exists('customMenuMakeSafeId')) {
    function customMenuMakeSafeId($value, $fallback = 'menu_item')
    {
        $value = (string)$value;
        $value = preg_replace('/[^a-zA-Z0-9_-]/', '_', $value);
        $value = trim($value, '_');

        return $value !== '' ? $value : $fallback;
    }
}

if (!function_exists('customMenuGetItemId')) {
    function customMenuGetItemId(array $item)
    {
        $index = isset($item['__INDEX']) ? (int)$item['__INDEX'] : 0;
        $level = isset($item['__LEVEL']) ? (int)$item['__LEVEL'] : 1;

        $rawId = !empty($item['ID'])
            ? $item['ID'] . '_' . $index . '_lvl_' . $level
            : 'idx_' . $index . '_lvl_' . $level;

        return customMenuMakeSafeId($rawId);
    }
}

if (!function_exists('customMenuGetIconHtml')) {
    function customMenuGetIconHtml(array $item)
    {
        if (empty($item['PARAMS']['ICON'])) {
            return '';
        }

        $iconClass = htmlspecialcharsbx($item['PARAMS']['ICON']);

        return '<i class="' . $iconClass . ' me-2" aria-hidden="true"></i>';
    }
}

if (!function_exists('customMenuHasActiveInItems')) {
    function customMenuHasActiveInItems(array $items, $activeIndex)
    {
        if ($activeIndex === null) {
            return false;
        }

        foreach ($items as $item) {
            if (isset($item['__INDEX']) && (int)$item['__INDEX'] === (int)$activeIndex) {
                return true;
            }

            if (!empty($item['CHILDREN']) && customMenuHasActiveInItems($item['CHILDREN'], $activeIndex)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('customMenuGetStateClass')) {
    function customMenuGetStateClass(array $item, $activeIndex)
    {
        if ($activeIndex !== null && isset($item['__INDEX']) && (int)$item['__INDEX'] === (int)$activeIndex) {
            return 'active';
        }

        if (!empty($item['CHILDREN']) && customMenuHasActiveInItems($item['CHILDREN'], $activeIndex)) {
            return 'section-active';
        }

        return '';
    }
}

if (!function_exists('customMenuFindPathByIndex')) {
    function customMenuFindPathByIndex(array $items, $activeIndex)
    {
        foreach ($items as $item) {
            if (isset($item['__INDEX']) && (int)$item['__INDEX'] === (int)$activeIndex) {
                return [$item];
            }

            if (!empty($item['CHILDREN'])) {
                $childPath = customMenuFindPathByIndex($item['CHILDREN'], $activeIndex);

                if (!empty($childPath)) {
                    array_unshift($childPath, $item);
                    return $childPath;
                }
            }
        }

        return [];
    }
}

if (!function_exists('customMenuGetInitialPanelIdsForTop')) {
    function customMenuGetInitialPanelIdsForTop(array $topItem, $activeIndex)
    {
        if ($activeIndex === null) {
            return [];
        }

        $path = customMenuFindPathByIndex([$topItem], $activeIndex);

        /*
         * Если активный пункт находится на 1 или 2 уровне,
         * показываем обычную карточку второго уровня.
         */
        if (count($path) <= 2) {
            return [];
        }

        $panelIds = [];

        /*
         * Пример:
         * [О министерстве, Структура, Руководство министерства]
         *
         * При открытии верхнего dropdown надо сразу показать карточку "Структура",
         * а не общий список второго уровня.
         */
        for ($i = 1; $i < count($path) - 1; $i++) {
            if (!empty($path[$i]['CHILDREN'])) {
                $panelIds[] = 'menu-panel-' . customMenuGetItemId($path[$i]);
            }
        }

        return $panelIds;
    }
}

if (!function_exists('customMenuRenderPanelItems')) {
    function customMenuRenderPanelItems(array $items, $activeIndex, $withBack = false, $title = '')
    {
        if ($withBack) {
            ?>
            <li class="menu-back-row">
                <button type="button"
                        class="dropdown-item menu-back-button"
                        data-menu-panel-back>
                    <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
                    <span>Назад</span>
                </button>
            </li>

            <?php if ($title !== ''): ?>
                <li>
                    <div class="menu-panel-title">
                        <?= htmlspecialcharsbx($title) ?>
                    </div>
                </li>
            <?php endif; ?>
            <?php
        }

        foreach ($items as $item) {
            $hasChildren = !empty($item['CHILDREN']);
            $stateClass = customMenuGetStateClass($item, $activeIndex);
            $safeText = htmlspecialcharsbx($item['TEXT']);
            $iconHtml = customMenuGetIconHtml($item);

            if ($hasChildren) {
                $panelId = 'menu-panel-' . customMenuGetItemId($item);
                ?>
                <li class="justify-content-center">
                    <button type="button"
                            class="dropdown-item dropdown-toggle <?= $stateClass ?> text-wrap menu-panel-open"
                            data-menu-panel-target="<?= htmlspecialcharsbx($panelId) ?>">
                        <?= $iconHtml ?>
                        <span><?= $safeText ?></span>
                    </button>
                </li>
                <?php
            } else {
                $safeLink = htmlspecialcharsbx($item['LINK']);
                ?>
                <li class="justify-content-center">
                    <a class="dropdown-item <?= $stateClass ?> text-wrap"
                       href="<?= $safeLink ?>">
                        <?= $iconHtml ?>
                        <span><?= $safeText ?></span>
                    </a>
                </li>
                <?php
            }
        }
    }
}

if (!function_exists('customMenuRenderPanelTemplates')) {
    function customMenuRenderPanelTemplates(array $items, $activeIndex)
    {
        foreach ($items as $item) {
            if (empty($item['CHILDREN'])) {
                continue;
            }

            $panelId = 'menu-panel-' . customMenuGetItemId($item);
            ?>
            <template id="<?= htmlspecialcharsbx($panelId) ?>">
                <?php customMenuRenderPanelItems($item['CHILDREN'], $activeIndex, true, $item['TEXT']); ?>
            </template>
            <?php

            customMenuRenderPanelTemplates($item['CHILDREN'], $activeIndex);
        }
    }
}

$menuTree = customMenuBuildTree($arResult);
$activeIndex = customMenuGetActiveIndex($arResult);
?>

<!-- Кнопка мобильного меню и бренд -->
<a class="navbar-brand d-lg-none fw-bold" href="#" style="color: #1e3a5f;">
    <i class="bi bi-grid-3x3-gap-fill me-1" aria-hidden="true" style="color: #2980b9;"></i>
    Меню сайта
</a>

<button class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNav"
        aria-controls="mainNav"
        aria-expanded="false"
        aria-label="Открыть меню">
    <span class="navbar-toggler-icon"></span>
</button>

<!-- Навигационные ссылки -->
<div class="collapse navbar-collapse" id="mainNav">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center flex-wrap justify-content-around">
        <?php foreach ($menuTree as $topItem): ?>
            <?php
            $hasChildren = !empty($topItem['CHILDREN']);
            $topId = customMenuGetItemId($topItem);
            $topStateClass = customMenuGetStateClass($topItem, $activeIndex);
            $safeText = htmlspecialcharsbx($topItem['TEXT']);
            $iconHtml = customMenuGetIconHtml($topItem);
            ?>

            <?php if ($hasChildren): ?>
                <?php
                $initialPanelIds = customMenuGetInitialPanelIdsForTop($topItem, $activeIndex);
                $initialPanelJson = htmlspecialcharsbx(
                    json_encode($initialPanelIds, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                );
                ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= $topStateClass ?> justify-content-center"
                       href="#"
                       id="dropdown-<?= htmlspecialcharsbx($topId) ?>"
                       role="button"
                       data-bs-toggle="dropdown"
                       data-bs-auto-close="outside"
                       aria-expanded="false">
                        <?= $iconHtml ?>
                        <span><?= $safeText ?></span>
                    </a>

                    <ul class="dropdown-menu"
                        aria-labelledby="dropdown-<?= htmlspecialcharsbx($topId) ?>"
                        data-menu-root-panel
                        data-menu-initial-panels='<?= $initialPanelJson ?>'>
                        <?php customMenuRenderPanelItems($topItem['CHILDREN'], $activeIndex); ?>
                    </ul>

                    <?php customMenuRenderPanelTemplates($topItem['CHILDREN'], $activeIndex); ?>
                </li>
            <?php else: ?>
                <li class="nav-item justify-content-center">
                    <a class="nav-link <?= $topStateClass ?> text-wrap"
                       href="<?= htmlspecialcharsbx($topItem['LINK']) ?>">
                        <?= $iconHtml ?>
                        <span><?= $safeText ?></span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>

<script>
(function () {
    const menuState = new WeakMap();

    function getState(menu) {
        if (!menuState.has(menu)) {
            menuState.set(menu, {
                initialHtml: menu.innerHTML,
                stack: [],
                initialized: false
            });
        }

        return menuState.get(menu);
    }

    function openPanel(menu, panelId, pushCurrent) {
        const template = document.getElementById(panelId);

        if (!template) {
            return false;
        }

        const state = getState(menu);

        if (pushCurrent) {
            state.stack.push(menu.innerHTML);
        }

        menu.innerHTML = template.innerHTML;
        menu.scrollTop = 0;

        return true;
    }

    function applyInitialPanels(menu) {
        const state = getState(menu);

        if (state.initialized) {
            return;
        }

        state.initialized = true;

        const rawPanels = menu.getAttribute('data-menu-initial-panels');

        if (!rawPanels) {
            return;
        }

        let panelIds = [];

        try {
            panelIds = JSON.parse(rawPanels);
        } catch (error) {
            return;
        }

        if (!Array.isArray(panelIds) || panelIds.length === 0) {
            return;
        }

        panelIds.forEach(function (panelId) {
            openPanel(menu, panelId, true);
        });
    }

    function resetMenu(menu) {
        const state = getState(menu);

        menu.innerHTML = state.initialHtml;
        state.stack = [];
        state.initialized = false;
        menu.scrollTop = 0;
    }

    document.addEventListener('click', function (event) {
        const openButton = event.target.closest('[data-menu-panel-target]');

        if (openButton) {
            const menu = openButton.closest('.dropdown-menu[data-menu-root-panel]');
            const panelId = openButton.getAttribute('data-menu-panel-target');

            if (!menu || !panelId) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            openPanel(menu, panelId, true);

            return;
        }

        const backButton = event.target.closest('[data-menu-panel-back]');

        if (backButton) {
            const menu = backButton.closest('.dropdown-menu[data-menu-root-panel]');

            if (!menu) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            const state = getState(menu);

            if (state.stack.length > 0) {
                menu.innerHTML = state.stack.pop();
                menu.scrollTop = 0;
            }
        }
    });

    document.addEventListener('show.bs.dropdown', function (event) {
        const dropdown = event.target.closest('.dropdown');

        if (!dropdown) {
            return;
        }

        const menu = dropdown.querySelector('.dropdown-menu[data-menu-root-panel]');

        if (!menu) {
            return;
        }

        applyInitialPanels(menu);
    });

    document.addEventListener('hidden.bs.dropdown', function (event) {
        const dropdown = event.target.closest('.dropdown');

        if (!dropdown) {
            return;
        }

        dropdown
            .querySelectorAll('.dropdown-menu[data-menu-root-panel]')
            .forEach(resetMenu);
    });
})();
</script>