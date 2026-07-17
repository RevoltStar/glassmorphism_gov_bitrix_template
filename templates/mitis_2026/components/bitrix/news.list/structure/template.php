<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<div class="glass-org-structure">
    <? foreach ($arResult["SECTIONS"] as $section): ?>
        <div class="glass-department">
            <h2 class="glass-department__title"><?=htmlspecialcharsbx($section["NAME"])?></h2>
            
            <? if (!empty($section["ELEMENTS"])): ?>
                <div class="glass-employees-grid">
                    <? foreach ($section["ELEMENTS"] as $employee): ?>
                        <div class="glass-employee-card">
                            <div class="glass-employee-card__inner">
                                <? if (!empty($employee["PREVIEW_PICTURE"])): 
                                    $file = CFile::ResizeImageGet(
                                        $employee["PREVIEW_PICTURE"],
                                        ["width" => 120, "height" => 120],
                                        BX_RESIZE_IMAGE_EXACT,
                                        true
                                    ); ?>
                                    <div class="glass-employee__photo-wrapper">
                                        <img src="<?=$file["src"]?>"
                                             alt="<?=htmlspecialcharsbx($employee["NAME"])?>"
                                             class="glass-employee__photo"
                                             loading="lazy">
                                    </div>
                                <? else: ?>
                                    <div class="glass-employee__photo-placeholder">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                <? endif; ?>
                                
                                <div class="glass-employee__info">
                                    <div class="glass-employee__name"><?=htmlspecialcharsbx($employee["NAME"])?></div>
                                    <? if (!empty($employee["PREVIEW_TEXT"])): ?>
                                        <div class="glass-employee__position"><?=htmlspecialcharsbx($employee["PREVIEW_TEXT"])?></div>
                                    <? endif; ?>
                                    
                                    <? if (!empty($employee["PROPERTIES"]["PHONE"]["VALUE"])): ?>
                                        <div class="glass-employee__contact">
                                            <i class="fas fa-phone-alt glass-contact-icon"></i>
                                            <span><?=htmlspecialcharsbx($employee["PROPERTIES"]["PHONE"]["VALUE"])?></span>
                                        </div>
                                    <? endif; ?>
                                    
                                    <? if (!empty($employee["PROPERTIES"]["ADDRESS"]["VALUE"])): ?>
                                        <div class="glass-employee__contact">
                                            <i class="fas fa-map-marker-alt glass-contact-icon"></i>
                                            <span><?=htmlspecialcharsbx($employee["PROPERTIES"]["ADDRESS"]["VALUE"])?></span>
                                        </div>
                                    <? endif; ?>
                                    
                                    <? if (!empty($employee["PROPERTIES"]["EMAIL"]["VALUE"])): ?>
                                        <div class="glass-employee__contact glass-employee__email">
                                            <i class="fas fa-envelope glass-contact-icon"></i>
                                            <a href="mailto:<?=htmlspecialcharsbx($employee["PROPERTIES"]["EMAIL"]["VALUE"])?>">
                                                <?=htmlspecialcharsbx($employee["PROPERTIES"]["EMAIL"]["VALUE"])?>
                                            </a>
                                        </div>
                                    <? endif; ?>
                                </div>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
            <? else: ?>
                <p class="glass-department__empty">
                    <i class="fas fa-users-slash me-2"></i> Нет сотрудников
                </p>
            <? endif; ?>
        </div>
    <? endforeach; ?>
</div>