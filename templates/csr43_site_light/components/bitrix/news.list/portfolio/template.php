<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$view = is_array($arResult['PORTFOLIO'] ?? null) ? $arResult['PORTFOLIO'] : [];
$items = is_array($view['items'] ?? null) ? $view['items'] : [];
$editAction = site_string($view['element_edit_action'] ?? '');
$deleteAction = site_string($view['element_delete_action'] ?? '');
?>
<?php if ($items !== []): ?>
    <div class="portfolio-list">
        <?php foreach ($items as $item): ?>
            <?php
            if (!is_array($item)) {
                continue;
            }

            $id = max(0, (int)($item['id'] ?? 0));
            $name = site_string($item['name'] ?? '');
            $url = site_url($item['url'] ?? null, '');
            $image = is_array($item['image'] ?? null) ? $item['image'] : null;
            $imageUrl = $image !== null ? site_url($image['url'] ?? null, '') : '';
            if ($imageUrl === '') {
                $image = null;
            }
            if ($id <= 0 || $name === '') {
                continue;
            }

            if ($editAction !== '' && ($editLink = site_string($item['edit_link'] ?? '')) !== '') {
                $this->AddEditAction($id, $editLink, $editAction);
            }
            if ($deleteAction !== '' && ($deleteLink = site_string($item['delete_link'] ?? '')) !== '') {
                $this->AddDeleteAction($id, $deleteLink, $deleteAction, ['CONFIRM' => GetMessage('CSR43_LIGHT_PORTFOLIO_DELETE_CONFIRM')]);
            }
            ?>
            <article id="<?=$this->GetEditAreaId($id)?>" class="csr43-light-card csr43-light-card--stretch portfolio-card<?=$url !== '' ? ' csr43-light-card--interactive' : ''?>">
                <?php if ($url !== ''): ?><a class="portfolio-card__media" href="<?=htmlspecialcharsbx($url)?>" aria-label="<?=htmlspecialcharsbx(str_replace('#NAME#', $name, GetMessage('CSR43_LIGHT_PORTFOLIO_OPEN')))?>"><?php else: ?><div class="portfolio-card__media portfolio-card__media--static"><?php endif; ?>
                    <?php if ($image !== null): ?>
                        <img
                            class="portfolio-card__image"
                            src="<?=htmlspecialcharsbx($imageUrl)?>"
                            alt="<?=htmlspecialcharsbx($name)?>"
                            <?php if (($image['width'] ?? 0) > 0): ?>width="<?=(int)$image['width']?>"<?php endif; ?>
                            <?php if (($image['height'] ?? 0) > 0): ?>height="<?=(int)$image['height']?>"<?php endif; ?>
                            loading="lazy"
                            decoding="async"
                        >
                    <?php else: ?>
                        <span class="portfolio-card__placeholder"><i class="bi bi-image" aria-hidden="true"></i><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_PORTFOLIO_NO_IMAGE'))?></span>
                    <?php endif; ?>
                <?php if ($url !== ''): ?></a><?php else: ?></div><?php endif; ?>

                <div class="portfolio-card__content">
                    <h2 class="portfolio-card__title"><?=htmlspecialcharsbx($name)?></h2>
                    <?php if (($item['preview_is_html'] ?? false) === true && ($item['preview_html'] ?? '') !== ''): ?>
                        <div class="portfolio-card__preview"><?=$item['preview_html']?></div>
                    <?php elseif (($item['preview_text'] ?? '') !== ''): ?>
                        <p class="portfolio-card__preview"><?=htmlspecialcharsbx(site_string($item['preview_text']))?></p>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
