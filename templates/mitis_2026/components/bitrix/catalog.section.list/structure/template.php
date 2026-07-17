<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

// Построение дерева
function buildTree($sections, $parentId = null) {
    $tree = [];
    foreach ($sections as $section) {
        $sectionParent = $section['IBLOCK_SECTION_ID'] ?? null;
        if ($sectionParent === $parentId) {
            $children = buildTree($sections, $section['ID']);
            if (!empty($children)) {
                $section['CHILDREN'] = $children;
            }
            $tree[] = $section;
        }
    }
    return $tree;
}

$treeSections = buildTree($arResult['SECTIONS']);
$galleryId = 'org-structure-' . $this->randString();

if (!empty($treeSections)) {
?>
<div class="content__org-structure org-structure">
    <ul class="org-structure__root">
        <? foreach ($treeSections as $section): ?>
            <?= renderNode($section, $arResult, $this, $galleryId) ?>
        <? endforeach; ?>
    </ul>
</div>
<?php
}

// Рекурсивная функция отрисовки узла
function renderNode($section, $arResult, $componentTemplate, $galleryId) {
    ob_start();
    $hasChildren = !empty($section['CHILDREN']);
    $editId = $componentTemplate->GetEditAreaId($section['ID']);
    $avatarSrc = $section['PICTURE']['SRC'] ?? '';
    $position = htmlspecialcharsbx($arResult['POSITION'][$section['ID']] ?? '');
    $name = htmlspecialcharsbx($section['NAME']);
    ?>
    <li class="org-structure__node" data-depth="<?= (int)$section['DEPTH_LEVEL'] ?>">
        <div class="org-structure__card" id="<?= $editId ?>">
            <? if ($avatarSrc): ?>
                <div class="org-structure__avatar gallery-media" style="background-image: url('<?=htmlspecialcharsbx($avatarSrc)?>');">
					<a href="<?=htmlspecialcharsbx($avatarSrc)?>" class="gallery-expand-button me-2 mt-2"
					   data-gallery-item data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
					   data-gallery-caption="<?=$name?>" data-type="image"
					   aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $section['NAME'])?>">
						<i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
					</a>
				</div>
            <? else: ?>
                <div class="org-structure__avatar org-structure__avatar--placeholder">
                    <i class="bi bi-person-circle"></i>
                </div>
            <? endif; ?>
            <div class="org-structure__info">
                <div class="org-structure__title"><?= $name ?></div>
                <div class="org-structure__description"><?= $position ?></div>
            </div>
            <? if ($hasChildren): ?>
                <button class="org-structure__toggle btn btn-link" aria-label="Раскрыть/скрыть">
                    <i class="bi bi-dash-circle"></i>
                </button>
            <? endif; ?>
        </div>
        <? if ($hasChildren): ?>
            <ul class="org-structure__children">
                <? foreach ($section['CHILDREN'] as $child): ?>
                    <?= renderNode($child, $arResult, $componentTemplate, $galleryId) ?>
                <? endforeach; ?>
            </ul>
        <? endif; ?>
    </li>
    <?php
    return ob_get_clean();
}
?>
