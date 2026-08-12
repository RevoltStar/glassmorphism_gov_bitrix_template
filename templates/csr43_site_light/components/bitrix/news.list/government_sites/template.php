<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['GOVERNMENT_SITES'] ?? null) ? $arResult['GOVERNMENT_SITES'] : [];
$groups = is_array($view['groups'] ?? null) ? $view['groups'] : [];
$editAction = site_string($view['element_edit_action'] ?? '');
$deleteAction = site_string($view['element_delete_action'] ?? '');
?>
<div
    id="full-menu"
    class="government-sites"
    role="dialog"
    aria-modal="true"
    aria-labelledby="government-sites-title"
    tabindex="-1"
    data-government-sites-dialog
    hidden
>
    <div class="government-sites__dialog">
        <div class="government-sites__panel">
            <header class="government-sites__header">
                <h2 id="government-sites-title" class="government-sites__heading"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GOVERNMENT_SITES_TITLE'))?></h2>
                <button type="button" class="government-sites__close" data-government-sites-close aria-label="<?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GOVERNMENT_SITES_CLOSE'))?>">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>
            </header>

            <?php if ($groups !== []): ?>
                <div class="government-sites__groups">
                    <?php foreach ($groups as $group): ?>
                        <?php if (!is_array($group)) { continue; } ?>
                        <section class="government-sites__group">
                            <h3 class="government-sites__title"><?=htmlspecialcharsbx(site_string($group['name'] ?? ''))?></h3>
                            <ul class="government-sites__list">
                                <?php foreach (is_array($group['items'] ?? null) ? $group['items'] : [] as $item): ?>
                                    <?php
                                    if (!is_array($item)) {
                                        continue;
                                    }

                                    $id = max(0, (int)($item['id'] ?? 0));
                                    $name = site_string($item['name'] ?? '');
                                    $url = site_url($item['url'] ?? null, '');
                                    $icon = site_css_classes($item['icon'] ?? '', 'bi-building');
                                    if ($id <= 0 || $name === '') {
                                        continue;
                                    }

                                    if ($editAction !== '' && ($editLink = site_string($item['edit_link'] ?? '')) !== '') {
                                        $this->AddEditAction($id, $editLink, $editAction);
                                    }
                                    if ($deleteAction !== '' && ($deleteLink = site_string($item['delete_link'] ?? '')) !== '') {
                                        $this->AddDeleteAction($id, $deleteLink, $deleteAction, ['CONFIRM' => GetMessage('CSR43_LIGHT_GOVERNMENT_SITES_DELETE_CONFIRM')]);
                                    }
                                    ?>
                                    <li id="<?=$this->GetEditAreaId($id)?>" class="government-sites__item">
                                        <i class="government-sites__icon bi <?=htmlspecialcharsbx($icon)?>" aria-hidden="true"></i>
                                        <?php if ($url !== ''): ?>
                                            <a class="government-sites__link" href="<?=htmlspecialcharsbx($url)?>" target="_blank" rel="noopener noreferrer"><?=htmlspecialcharsbx($name)?></a>
                                        <?php else: ?>
                                            <span class="government-sites__text"><?=htmlspecialcharsbx($name)?></span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="government-sites__empty" role="status"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_GOVERNMENT_SITES_EMPTY'))?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
