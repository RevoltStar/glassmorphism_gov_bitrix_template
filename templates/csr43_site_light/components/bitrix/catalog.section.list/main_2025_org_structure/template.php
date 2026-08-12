<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
// Транслитерация кириллицы
function toSlug($text) {
    $translit = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
        'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
        'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch',
        'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya'
    ];
    
    $text = strtr($text, $translit);
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '_', $text);
    $text = trim($text, '_');
    
	    return $text;
}

$galleryId = 'org-structure-' . $this->randString();
?>
<div class="org-structure container py-4">
    <div class="catalog-section-list">
        <?
        $TOP_DEPTH = $arResult["SECTION"]["DEPTH_LEVEL"];
        $CURRENT_DEPTH = $TOP_DEPTH;
        $openContainers = 0;

        foreach($arResult["SECTIONS"] as $arSection):
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
            
            // Закрываем контейнеры, если текущий уровень меньше предыдущего
            while($CURRENT_DEPTH > $arSection["DEPTH_LEVEL"] && $openContainers > 0):
                ?></div><!-- Закрытие подраздела уровня <?=$CURRENT_DEPTH?> --><?
                $CURRENT_DEPTH--;
                $openContainers--;
            endwhile;

            // Открываем новый контейнер, если текущий уровень больше предыдущего
            if($CURRENT_DEPTH < $arSection["DEPTH_LEVEL"]):
                ?><div class="subsection-container level-<?=$arSection["DEPTH_LEVEL"]?>" data-level="<?=$arSection["DEPTH_LEVEL"]?>"><?
                $openContainers++;
            endif;

            // Определяем классы для разных уровней иерархии
            $levelClass = 'level-' . $arSection["DEPTH_LEVEL"];
            $sectionClass = '';

			switch($arSection["DEPTH_LEVEL"]) {
                case 1:
                    $sectionClass = 'top-level-section leadership-section';
                    $icon = '🏢';
                    break;
                case 2:
                    $sectionClass = 'department-section';
                    $icon = '📊';
                    break;
                case 3:
                    $sectionClass = 'subdepartment-section';
                    $icon = '👥';
                    break;
                default:
                    $sectionClass = 'nested-section';
                    $icon = '📁';
            }
            
            // Особый класс для руководства
            if($arSection["NAME"] == 'Руководство') {
                $sectionClass .= ' main-leadership';
                $icon = '👑';
			}
			/*Убираем все значки*/
			$icon = '';
            ?>

            <div 
				class="org-section <?=$sectionClass?> <?=$levelClass?>" 
				id="section-<?=toSlug($arSection["NAME"])?>" >
                <!-- Заголовок раздела с иконкой уровня -->
                <div class="section-header card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center hierarchy-level-<?=$arSection["DEPTH_LEVEL"]?>">
                        <div class="section-title-info">
                            <span class="level-icon me-2"><?=$icon?></span>
                            <span class="section-name h5 mb-0"><?=$arSection["NAME"]?></span>
                        </div>
                    </div>
                </div>

                <!-- Вывод сотрудников раздела -->
                <?php if($arParams["SHOW_ELEMENTS"] == "Y" && $arSection["ELEMENT_CNT"] > 0):
                    $arFilter = array(
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => $arSection["ID"],
                        "INCLUDE_SUBSECTIONS" => "N",
                        "ACTIVE" => "Y"
                    );
                    $arSelect = array("ID", "NAME", "DETAIL_PAGE_URL", "PREVIEW_TEXT", "PROPERTIES", "PREVIEW_PICTURE");
                    
                    $rsElements = CIBlockElement::GetList(
                        array("SORT" => "ASC"),
                        $arFilter,
                        false,
                        false,
                        $arSelect
                    );

                    if($rsElements->SelectedRowsCount() > 0): 
                        $allEmployees = array();
                        while($arElement = $rsElements->GetNext()) {
                            $allEmployees[] = $arElement;
                        }
                        
                        // Фильтруем сотрудников с установленным статусом
                        $validEmployees = array();
                        foreach($allEmployees as $arElement) {
                            $elementId = $arElement["ID"];
                            $iblockId = $arSection["IBLOCK_ID"];
                            $properties = \CIBlockElement::GetProperty(
                                $iblockId,
                                $elementId,
                                array("sort" => "asc"),
                                array("VALUE" => "")
                            );
                            $arProperties = array();
                            while ($prop = $properties->Fetch()) {
                                $arProperties[$prop["CODE"]] = $prop;
                            }

                            $status = $arProperties["STATUS"]["VALUE_XML_ID"];
                            
                            // Пропускаем только элементы без статуса
                            if(empty($status)){
                                continue;
                            }
                            
                            $validEmployees[] = array(
                                'element' => $arElement,
                                'properties' => $arProperties,
                                'status' => $status
                            );
                        }
                        
                        $visibleEmployees = array_slice($validEmployees, 0, 4);
                        $hiddenEmployees = array_slice($validEmployees, 4);
                        $hasHiddenEmployees = count($hiddenEmployees) > 0;
                        $sectionId = 'section-' . $arSection["ID"];
                        ?>
                        <div class="section-employees">
                            <div class="employees-grid">
                                <?php 
                                // Отображаем видимых сотрудников
                                foreach($visibleEmployees as $employeeData):
                                    $arElement = $employeeData['element'];
                                    $arProperties = $employeeData['properties'];
                                    $status = $employeeData['status'];

                                    $phone = !empty($arProperties["PHONE"]["VALUE"]) ? $arProperties["PHONE"]["VALUE"] : "<Не указан>";
                                    $cabinet = !empty($arProperties["CABINET"]["VALUE"]) ? $arProperties["CABINET"]["VALUE"] : "<Не указан>";
                                    $address = !empty($arProperties["ADDRESS"]["VALUE_ENUM"]) ? $arProperties["ADDRESS"]["VALUE_ENUM"] : "<Не указан>";
                                    $position = $arElement['PREVIEW_TEXT'] ?: $arElement['NAME'];
                                    
                                    $previewImage = $arElement['PREVIEW_PICTURE'] ? CFile::GetPath($arElement['PREVIEW_PICTURE']) : false;
                                    ?>
                                    
                                    <div class="employee-card-wrapper">
                                        <div class="employee-card card h-100">
                                            <div class="card-body text-center">
                                                <!-- Фото сотрудника -->
	                                                <?php if ($status!="vacant" && $previewImage): ?>
	                                                     <div class="employee-photo mb-3 gallery-media">
	                                                        <a
	                                                            href="<?=htmlspecialcharsbx($previewImage)?>"
	                                                            class="gallery-expand-button"
	                                                            data-gallery-item
	                                                            data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
	                                                            data-gallery-caption="<?=htmlspecialcharsbx($position)?>"
	                                                            data-type="image"
	                                                            aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $position)?>"
	                                                        >
	                                                            <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
	                                                        </a>
	                                                        <img src="<?=htmlspecialcharsbx($previewImage)?>" 
	                                                             alt="<?= htmlspecialcharsbx($position) ?>" 
	                                                             class="img-fluid employee-avatar">
                                                    </div>
                                                <?php elseif ($status!="vacant"): ?>
                                                    <div class="employee-photo mb-3">
                                                        <div class="no-photo-avatar rounded-circle">
                                                            <i class="bi bi-person-circle"></i>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Информация о сотруднике -->
                                                <div class="employee-info">
                                                    <?php if ($status=="vacant"): ?>
                                                        <div class="vacant-position text-muted">
                                                            <i class="bi bi-person-x fs-1"></i>
                                                            <div class="mt-2">[Должность вакантна]</div>
                                                            <div class="position-name small"><?=$arElement['NAME']?></div>
                                                        </div>
                                                    <?php else: ?>
                                                        <h6 class="employee-name card-title"><?=$position?></h6>
                                                        <div class="employee-position text-muted small mb-2">
                                                            <?=$arElement['NAME']?><?if($status=="acting_director"):?> (И.О.)<?endif?>
                                                        </div>
                                                        
                                                        <!-- Контакты -->
                                                        <div class="employee-contacts">
                                                            <?php if($phone && $phone != "<Не указан>"): ?>
                                                                <div class="contact-item">
                                                                    <i class="bi bi-telephone"></i>
                                                                    <a href="tel:<?=$arParams["PHONE"] . $phone?>" class="text-decoration-none text-black">
                                                                        <?=$phone?>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <?php if($cabinet && $cabinet != "<Не указан>"): ?>
                                                                <div class="contact-item">
                                                                    <i class="bi bi-geo-alt"></i>
                                                                    Каб. <?=$cabinet?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if($address && $address != "<Не указан>"): ?>
                                                                <div class="contact-item">
                                                                    <i class="bi bi-map"></i>
                                                                    <?=$address?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <!-- Скрытые сотрудники -->
                                <?php if($hasHiddenEmployees): ?>
                                    <div class="hidden-employees" id="hidden-employees-<?=$sectionId?>" style="display: none;">
                                        <?php foreach($hiddenEmployees as $employeeData):
                                            $arElement = $employeeData['element'];
                                            $arProperties = $employeeData['properties'];
                                            $status = $employeeData['status'];

                                            $phone = !empty($arProperties["PHONE"]["VALUE"]) ? $arProperties["PHONE"]["VALUE"] : "<Не указан>";
                                            $cabinet = !empty($arProperties["CABINET"]["VALUE"]) ? $arProperties["CABINET"]["VALUE"] : "<Не указан>";
                                            $address = !empty($arProperties["ADDRESS"]["VALUE_ENUM"]) ? $arProperties["ADDRESS"]["VALUE_ENUM"] : "<Не указан>";
                                            $position = $arElement['PREVIEW_TEXT'] ?: $arElement['NAME'];
                                            
                                            $previewImage = $arElement['PREVIEW_PICTURE'] ? CFile::GetPath($arElement['PREVIEW_PICTURE']) : false;
                                            ?>
                                            
                                            <div class="employee-card-wrapper">
                                                <div class="employee-card card h-100">
                                                    <div class="card-body text-center">
                                                        <!-- Фото сотрудника -->
	                                                        <?php if ($status!="vacant" && $previewImage): ?>
	                                                             <div class="employee-photo mb-3 gallery-media">
	                                                                <a
	                                                                    href="<?=htmlspecialcharsbx($previewImage)?>"
	                                                                    class="gallery-expand-button"
	                                                                    data-gallery-item
	                                                                    data-fancybox="<?=htmlspecialcharsbx($galleryId)?>"
	                                                                    data-gallery-caption="<?=htmlspecialcharsbx($position)?>"
	                                                                    data-type="image"
	                                                                    aria-label="<?=htmlspecialcharsbx('Увеличить: ' . $position)?>"
	                                                                >
	                                                                    <i class="bi bi-arrows-angle-expand" aria-hidden="true"></i>
	                                                                </a>
	                                                                <img src="<?=htmlspecialcharsbx($previewImage)?>" 
	                                                                     alt="<?= htmlspecialcharsbx($position) ?>" 
	                                                                     class="img-fluid employee-avatar">
                                                            </div>
                                                        <?php elseif ($status!="vacant"): ?>
                                                            <div class="employee-photo mb-3">
                                                                <div class="no-photo-avatar rounded-circle">
                                                                    <i class="bi bi-person-circle"></i>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <!-- Информация о сотруднике -->
                                                        <div class="employee-info">
                                                            <?php if ($status=="vacant"): ?>
                                                                <div class="vacant-position text-muted">
                                                                    <i class="bi bi-person-x fs-1"></i>
                                                                    <div class="mt-2">[Должность вакантна]</div>
                                                                    <div class="position-name small"><?=$arElement['NAME']?></div>
                                                                </div>
                                                            <?php else: ?>
                                                                <h6 class="employee-name card-title"><?=$position?></h6>
                                                                <div class="employee-position text-muted small mb-2">
                                                                    <?=$arElement['NAME']?><?if($status=="acting_director"):?> (И.О.)<?endif?>
                                                                </div>
                                                                
                                                                <!-- Контакты -->
                                                                <div class="employee-contacts">
                                                                    <?php if($phone && $phone != "<Не указан>"): ?>
                                                                        <div class="contact-item">
                                                                            <i class="bi bi-telephone"></i>
                                                                            <a href="tel:<?=$arParams["PHONE"] . $phone?>" class="text-decoration-none text-black">
                                                                                <?=$phone?>
                                                                            </a>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <?php if($cabinet && $cabinet != "<Не указан>"): ?>
                                                                        <div class="contact-item">
                                                                            <i class="bi bi-geo-alt"></i>
                                                                            Каб. <?=$cabinet?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if($address && $address != "<Не указан>"): ?>
                                                                        <div class="contact-item">
                                                                            <i class="bi bi-map"></i>
                                                                            <?=$address?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Кнопка "Ещё" -->
                            <?php if($hasHiddenEmployees): ?>
                                <div class="text-center mt-3 mb-3">
                                    <button type="button" 
                                            class="btn btn-outline-primary show-more-btn" 
                                            data-section="<?=$sectionId?>"
                                            data-state="hidden">
                                        <span class="btn-text">Ещё <?=count($hiddenEmployees)?> сотрудников</span>
                                        <i class="bi bi-chevron-down ms-1"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif;
                endif; ?>
            </div>
            
            <?
            $CURRENT_DEPTH = $arSection["DEPTH_LEVEL"];
        endforeach;

        // Закрываем все оставшиеся контейнеры
        while($openContainers > 0):
            ?></div><!-- Закрытие контейнера --><?
            $openContainers--;
        endwhile;
        ?>
    </div>
</div>
