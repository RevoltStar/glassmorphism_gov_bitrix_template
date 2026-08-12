<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['SECTION_TREE'] ?? null) ? $arResult['SECTION_TREE'] : [];
$nodes = is_array($view['nodes'] ?? null) ? $view['nodes'] : [];
$selectedTitle = site_string($view['selected_title'] ?? '');
$editAction = site_string($view['section_edit_action'] ?? '');
$deleteAction = site_string($view['section_delete_action'] ?? '');

$renderNodes = static function (
    array $items,
    CBitrixComponentTemplate $componentTemplate,
    string $editAction,
    string $deleteAction,
    bool $nested = false
) use (&$renderNodes): string {
    ob_start();
    ?>
    <ul class="section-tree__list<?php if ($nested): ?> section-tree__list--nested<?php endif; ?>">
        <?php foreach ($items as $node): ?>
            <?php
            if (!is_array($node)) {
                continue;
            }

            $sectionId = max(0, (int)($node['id'] ?? 0));
            if ($sectionId === 0) {
                continue;
            }
            $name = site_string($node['name'] ?? '');
            $url = site_url($node['url'] ?? null, '');
            $count = max(0, (int)($node['count'] ?? 0));
            $showCount = ($node['show_count'] ?? false) === true;
            $isCurrent = ($node['is_current'] ?? false) === true;
            $children = is_array($node['children'] ?? null) ? $node['children'] : [];

            $componentTemplate->AddEditAction(
                $sectionId,
                site_string($node['edit_link'] ?? ''),
                $editAction
            );
            $componentTemplate->AddDeleteAction(
                $sectionId,
                site_string($node['delete_link'] ?? ''),
                $deleteAction,
                ['CONFIRM' => GetMessage('CSR43_LIGHT_TREE_DELETE_CONFIRM')]
            );
            ?>
            <li class="section-tree__item" id="<?=htmlspecialcharsbx($componentTemplate->GetEditAreaId($sectionId))?>">
                <?php if ($isCurrent): ?>
                    <span class="section-tree__link section-tree__link--current" aria-current="page">
                        <span class="section-tree__label"><?=htmlspecialcharsbx($name)?></span>
                        <?php if ($showCount): ?><span class="section-tree__count">(<?=$count?>)</span><?php endif; ?>
                    </span>
                <?php elseif ($url !== ''): ?>
                    <a class="section-tree__link" href="<?=htmlspecialcharsbx($url)?>">
                        <span class="section-tree__label"><?=htmlspecialcharsbx($name)?></span>
                        <?php if ($showCount): ?><span class="section-tree__count">(<?=$count?>)</span><?php endif; ?>
                    </a>
                <?php else: ?>
                    <span class="section-tree__link section-tree__link--text">
                        <span class="section-tree__label"><?=htmlspecialcharsbx($name)?></span>
                        <?php if ($showCount): ?><span class="section-tree__count">(<?=$count?>)</span><?php endif; ?>
                    </span>
                <?php endif; ?>

                <?php if ($children !== []): ?>
                    <?=$renderNodes($children, $componentTemplate, $editAction, $deleteAction, true)?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
    return (string)ob_get_clean();
};
?>
<?php if ($nodes !== []): ?>
    <nav class="csr43-light-surface section-tree" aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_TREE_LABEL'))?>">
        <?=$renderNodes($nodes, $this, $editAction, $deleteAction)?>
    </nav>
    <?php if ($selectedTitle !== ''): ?>
        <h2 class="section-tree__current-title"><?=htmlspecialcharsbx($selectedTitle)?></h2>
    <?php endif; ?>
<?php endif; ?>
