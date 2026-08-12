<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Преобразует текст с переносами строк в HTML-список
 * @param string $text Исходный текст
 * @param string $defaultText Текст по умолчанию если исходный пуст
 * @return string HTML-код списка
 */
function textToHtmlList($text, $defaultText = "<Не указано>") {
    if (empty($text)) {
        return $defaultText;
    }

    // Если текст уже содержит HTML теги - возвращаем как есть
    if ($text != strip_tags($text)) {
        return $text;
    }

    $lines = explode("\n", $text);
    $lines = array_map('trim', $lines);
    $lines = array_filter($lines);

    if (empty($lines)) {
        return $defaultText;
    }

    $html = '<ul class="vacancy-list">';
    foreach ($lines as $line) {
        $html .= '<li>' . htmlspecialchars($line) . '</li>';
    }
    $html .= '</ul>';

    return $html;
}

/**
 * Получает значение свойства элемента или значение по умолчанию
 * @param array $properties Массив свойств элемента
 * @param string $code Код свойства
 * @param mixed $default Значение по умолчанию
 * @return mixed
 */
function getPropertyValue($properties, $code, $default = null) {
    if (!isset($properties[$code])) {
        return $default;
    }

    $prop = $properties[$code];
    
    if (isset($prop['VALUE']['TEXT'])) {
        return $prop['VALUE']['TEXT'];
    }
    
    return !empty($prop['VALUE']) ? $prop['VALUE'] : $default;
}
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


?>
<div class="vacant-positions container py-4">
    <?
    $hasVacancies = false;
    
    // Получаем все активные разделы инфоблока
    $arSections = array();
    $rsSections = CIBlockSection::GetList(
        array("SORT" => "ASC", "NAME" => "ASC"),
        array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ACTIVE" => "Y"
        ),
        false,
        array("ID", "NAME", "SORT")
    );
    
    while ($arSection = $rsSections->GetNext()) {
        $arSections[$arSection["ID"]] = $arSection;
    }
    
    // Группируем элементы по разделам
    $sectionElements = array();
    
    $arFilter = [
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "ACTIVE" => "Y"
    ];
    
    $arSelect = [
        "ID", "NAME", "IBLOCK_SECTION_ID", 
        "DETAIL_PAGE_URL", "PREVIEW_TEXT", 
        "PROPERTIES", "PREVIEW_PICTURE", "SORT"
    ];
    
    $rsElements = CIBlockElement::GetList(
        ["SORT" => "ASC"],
        $arFilter,
        false,
        false,
        $arSelect
    );

    while ($arElement = $rsElements->GetNext()) {
        $elementId = $arElement["ID"];
        $iblockId = $arParams["IBLOCK_ID"];
        
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
        
        if (!isset($arProperties['STATUS']) || $arProperties['STATUS']['VALUE_XML_ID'] != "vacant") {
            continue;
        }

        $hasVacancies = true;
        $sectionId = $arElement["IBLOCK_SECTION_ID"];
        
        if (!isset($sectionElements[$sectionId])) {
            $sectionElements[$sectionId] = array();
        }
        
        $sectionElements[$sectionId][] = $arElement;
    }

	?>
	<div class="fast-link-department d-grid gap-2 mb-3">
	<?
	//Выводим меню быстрого переход
	foreach ($arSections as $sectionId => $arSection) {
		if (!isset($sectionElements[$sectionId]) || empty($sectionElements[$sectionId])) {
            continue;
        }
		$count = count($sectionElements[$sectionId]);
		if ($count % 10 == 1 && $count % 100 != 11) {
    		$word = 'вакансия';
		} elseif ($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) {
    		$word = 'вакансии';
		} else {
    		$word = 'вакансий';
		}
	?>
		<a title="Перейти к вакансиям этого отдела" href="#<?=toSlug($arSection['NAME'])?>" class="btn btn-light border w-100 text-start"><p><?=$arSection['NAME']?> (<?=$count?> <?=$word?>)</p></a>
	<?
	}
	?>
	</div>
	<?
    
    // Выводим элементы по разделам
    foreach ($arSections as $sectionId => $arSection) {
        if (!isset($sectionElements[$sectionId]) || empty($sectionElements[$sectionId])) {
            continue;
        }
        echo '<div class="section-group mb-3">';
        echo '<h3 class="section-title mb-3" id="'. toSlug($arSection['NAME']) .'">' . htmlspecialchars($arSection["NAME"]) . '</h3>';
        echo '<div class="row">';
        
        foreach ($sectionElements[$sectionId] as $arElement) {
            $elementId = $arElement["ID"];
            $iblockId = $arParams["IBLOCK_ID"];
            
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
            
            // Получаем данные
            $salary = getPropertyValue($arProperties, 'SALARY', '<Не указана>');
            $responsibilities = textToHtmlList(getPropertyValue($arProperties, 'RESPONSIBILITIES'));
            $conditions = textToHtmlList(getPropertyValue($arProperties, 'CONDITIONS'));
            $requirements = textToHtmlList(getPropertyValue($arProperties, 'REQUIREMENTS'));
            $offers = textToHtmlList(getPropertyValue($arProperties, 'OFFERS'));
            $address = $arProperties['ADDRESS']['VALUE_ENUM'];
            ?>
            
            <div class="mb-4">
                <div class="vacant-card card h-100">
                    <div class="card-body">
						<h5 class="card-title fw-bold"><?= htmlspecialchars($arElement['NAME']) ?><span class="text-muted small"> в <?=(mb_strtolower(htmlspecialchars($arSection["NAME"])))?></span></h5>
                        
                        <div class="vacant-badge mb-3">
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-person-x"></i> Вакансия !
                            </span>
                        </div>
                        
                        <div class="vacancy-details">
                            <div class="detail-item">
                                <h5 class="detail-title fw-semibold">
                                    <i class="bi bi-list-task"></i> Обязанности:
                                </h5>
                                <?= $responsibilities ?>
                            </div>
                            
                            <div class="detail-item">
                                <h5 class="detail-title fw-semibold">
                                    <i class="bi bi-check-circle"></i> Требования:
                                </h5>
                                <?= $requirements ?>
                            </div>
                            
                            <div class="detail-item">
                                <h5 class="detail-title fw-semibold">
                                    <i class="bi bi-star"></i> Условия:
                                </h5>
                                <?= $conditions ?>
                            </div>
                            
                            <div class="detail-item">
                                <h5 class="detail-title fw-semibold">
                                    <i class="bi bi-gift"></i> Мы предлагаем:
                                </h5>
                                <?= $offers ?>
                            </div>
                            
                            <div class="detail-item">
                                <h5 class="detail-title fw-semibold">
                                    <i class="bi bi-building"></i> Место работы:
                                </h5>
                                <?= $address ?>
                            </div>
                            
                            <div class="detail-item salary">
                                <h5 class="detail-title fw-semibold">
                                    <i class="bi bi-cash-stack"></i> Зарплата:
                                </h5>
                                <span><?= $salary . ' ₽' ?></span>
                            </div>
                        </div>

                        <p class="mt-3">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample-<?=$elementId?>" aria-expanded="false" aria-controls="collapseExample-<?=$elementId?>">
                                <i class="bi bi-info-circle me-2"></i> Подробнее о вакансии
                            </button>
                        </p>
                        
                        <div class="collapse mt-2" id="collapseExample-<?=$elementId?>">
                            <div class="card card-body shadow-sm">
                                <p class="mb-2"><i class="bi bi-telephone me-2"></i> <b>Тел.</b> <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "inc",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "EDIT_TEMPLATE" => "",
										"PATH" => "/local/templates/main_2025/include/phone.php"
                                    )
                                ); ?></p>
                                <p class="mb-0"><i class="bi bi-envelope me-2"></i> <b>EMAIL:</b> <a href="mailto:<?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "inc",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/templates/main_2025/include/email.php"
                                    )
                                ); ?>"><?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:main.include",
                                    "",
                                    array(
                                        "AREA_FILE_SHOW" => "file",
                                        "AREA_FILE_SUFFIX" => "inc",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "EDIT_TEMPLATE" => "",
                                        "PATH" => "/local/templates/main_2025/include/email.php"
                                    )
                                ); ?></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?
        }
        
        echo '</div></div>'; // Закрываем .row и .section-group
    }
    ?>
    
    <?php if (!$hasVacancies): ?>
        <div class="alert alert-info">
            В настоящее время открытых вакансий нет. Следите за обновлениями!
        </div>
    <?php endif; ?>
</div>