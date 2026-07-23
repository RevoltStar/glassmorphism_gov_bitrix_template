<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

if (!function_exists('mitisBuildSectionTree')) {
    function mitisBuildSectionTree(array $sections, int $parentId = 0, array $path = []): array
    {
        $tree = [];
        foreach ($sections as $section) {
            if (!is_array($section)) {
                continue;
            }

            $sectionId = max(0, (int)($section['ID'] ?? 0));
            $sectionParentId = max(0, (int)($section['IBLOCK_SECTION_ID'] ?? 0));
            if ($sectionId === 0 || $sectionParentId !== $parentId || isset($path[$sectionId])) {
                continue;
            }

            $childPath = $path;
            $childPath[$sectionId] = true;
            $children = mitisBuildSectionTree($sections, $sectionId, $childPath);
            if ($children !== []) {
                $section['CHILDREN'] = $children;
            }
            $tree[] = $section;
        }

        return $tree;
    }
}

if (!function_exists('mitisRenderSectionNode')) {
    function mitisRenderSectionNode(
        array $section,
        array $result,
        CBitrixComponentTemplate $componentTemplate,
        string $galleryId
    ): string {
        $sectionId = max(0, (int)($section['ID'] ?? 0));
        $hasChildren = !empty($section['CHILDREN']) && is_array($section['CHILDREN']);
        $editId = site_string($componentTemplate->GetEditAreaId($sectionId));
        $avatarSrc = site_url($section['PICTURE']['SRC'] ?? null, '');
        $avatarCssUrl = site_css_url($avatarSrc, '');
        $position = site_string($result['POSITION'][$sectionId] ?? '');
        $name = site_string($section['~NAME'] ?? $section['NAME'] ?? '');
        $depth = max(0, (int)($section['DEPTH_LEVEL'] ?? 0));

        ob_start();
        ?>
        <li class="org-structure__node" data-depth="<?=$depth?>">
            <div class="org-structure__card" id="<?=htmlspecialcharsbx($editId)?>">
                <?php if ($avatarSrc !== ''): ?>
                    <div class="org-structure__avatar gallery-media"
                         style="background-image: url(<?=htmlspecialcharsbx($avatarCssUrl)?>);">
                        <a href="<?=htmlspecialcharsbx($avatarSrc)?>"
                           class="gallery-expand-button me-2 mt-2"
                           data-gallery-item
                           data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
                           data-gallery-caption="<?=htmlspecialcharsbx($name)?>"
                           data-type="image"
                           aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $name)?>">
                            <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="org-structure__avatar org-structure__avatar--placeholder">
                        <i class="bi bi-person-circle" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>
                <div class="org-structure__info">
                    <div class="org-structure__title"><?=htmlspecialcharsbx($name)?></div>
                    <div class="org-structure__description"><?=htmlspecialcharsbx($position)?></div>
                </div>
                <?php if ($hasChildren): ?>
                    <button class="org-structure__toggle btn btn-link" aria-label="Раскрыть/скрыть">
                        <i class="bi bi-dash-circle" aria-hidden="true"></i>
                    </button>
                <?php endif; ?>
            </div>
            <?php if ($hasChildren): ?>
                <ul class="org-structure__children">
                    <?php foreach ($section['CHILDREN'] as $child): ?>
                        <?php if (is_array($child)): ?>
                            <?=mitisRenderSectionNode($child, $result, $componentTemplate, $galleryId)?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
        <?php
        return (string)ob_get_clean();
    }
}

$sections = $arResult['SECTIONS'] ?? [];
$treeSections = is_array($sections) ? mitisBuildSectionTree($sections) : [];
$galleryId = 'org-structure-' . $this->randString();
?>
<?php if ($treeSections !== []): ?>
    <div class="content__org-structure org-structure">
        <ul class="org-structure__root">
            <?php foreach ($treeSections as $section): ?>
                <?=mitisRenderSectionNode($section, $arResult, $this, $galleryId)?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php else: ?>
    <div class="csr43-glass-surface rounded-4 p-4 text-center text-muted" role="status">
        <i class="bi bi-info-circle me-2" aria-hidden="true"></i>
        Не удалось получить информацию о руководстве.
    </div>
<?php endif; ?>
