<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;

$arResult['VACANCIES'] = [
    'sections' => [],
    'total' => 0,
    'phone' => '',
    'phone_url' => '',
    'email' => '',
    'email_url' => '',
];

$iblockId = max(0, (int)($arParams['IBLOCK_ID'] ?? 0));
if ($iblockId === 0 || !Loader::includeModule('iblock')) {
    return;
}

$plainText = static function (mixed $value): string {
    if (is_array($value)) {
        if (array_key_exists('TEXT', $value)) {
            $value = $value['TEXT'];
        } else {
            $normalizedValues = [];
            foreach ($value as $item) {
                $normalized = site_plain_text($item);
                if ($normalized !== '') {
                    $normalizedValues[] = $normalized;
                }
            }

            return implode(', ', $normalizedValues);
        }
    }

    return site_plain_text($value);
};

$propertyDisplayValue = static function (mixed $property) use ($plainText): string {
    if (!is_array($property)) {
        return '';
    }

    $enumValue = $property['VALUE_ENUM'] ?? null;
    if ($enumValue !== null && $enumValue !== '' && $enumValue !== []) {
        return $plainText($enumValue);
    }

    return $plainText($property['VALUE'] ?? '');
};

$propertyLines = static function (mixed $property) use ($plainText): array {
    if (!is_array($property)) {
        return [];
    }

    $rawValues = $property['VALUE'] ?? [];
    $rawValues = is_array($rawValues) && !array_key_exists('TEXT', $rawValues)
        ? $rawValues
        : [$rawValues];
    $lines = [];

    foreach ($rawValues as $rawValue) {
        if (is_array($rawValue) && array_key_exists('TEXT', $rawValue)) {
            $rawValue = $rawValue['TEXT'];
        }

        $rawText = site_string($rawValue);
        $rawText = preg_replace('~<\s*br\s*/?\s*>|</\s*(?:li|p|div)\s*>~iu', "\n", $rawText);
        $text = $plainText(is_string($rawText) ? $rawText : '');
        if ($text === '') {
            continue;
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        foreach (explode("\n", $text) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $lines[] = $line;
            }
        }
    }

    return $lines;
};

$vacancyCountLabel = static function (int $count): string {
    $lastTwoDigits = $count % 100;
    $lastDigit = $count % 10;

    if ($lastDigit === 1 && $lastTwoDigits !== 11) {
        $messageCode = 'CSR43_LIGHT_VACANCY_COUNT_ONE';
    } elseif ($lastDigit >= 2 && $lastDigit <= 4 && ($lastTwoDigits < 10 || $lastTwoDigits >= 20)) {
        $messageCode = 'CSR43_LIGHT_VACANCY_COUNT_FEW';
    } else {
        $messageCode = 'CSR43_LIGHT_VACANCY_COUNT_MANY';
    }

    return str_replace('#COUNT#', (string)$count, GetMessage($messageCode));
};

$sections = [];
$sectionResult = CIBlockSection::GetList(
    ['SORT' => 'ASC', 'NAME' => 'ASC'],
    [
        'IBLOCK_ID' => $iblockId,
        'ACTIVE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
    ],
    false,
    ['ID', 'NAME', 'SORT']
);

while ($section = $sectionResult->GetNext()) {
    if (!is_array($section)) {
        continue;
    }

    $sectionId = max(0, (int)($section['ID'] ?? 0));
    if ($sectionId === 0 || isset($sections[$sectionId])) {
        continue;
    }

    $sections[$sectionId] = [
        'id' => $sectionId,
        'name' => site_plain_text($section['~NAME'] ?? $section['NAME'] ?? ''),
        'anchor_id' => 'vacancy-section-' . $sectionId,
        'count' => 0,
        'count_label' => '',
        'items' => [],
    ];
}

if ($sections === []) {
    return;
}

$elements = [];
$elementIds = [];
$elementResult = CIBlockElement::GetList(
    ['SORT' => 'ASC', 'ID' => 'ASC'],
    [
        'IBLOCK_ID' => $iblockId,
        'ACTIVE' => 'Y',
        'CHECK_PERMISSIONS' => 'Y',
    ],
    false,
    false,
    ['ID', 'IBLOCK_SECTION_ID', 'NAME', 'SORT']
);

while ($element = $elementResult->GetNext()) {
    if (!is_array($element)) {
        continue;
    }

    $elementId = max(0, (int)($element['ID'] ?? 0));
    if ($elementId === 0 || isset($elements[$elementId])) {
        continue;
    }

    $elements[$elementId] = $element;
    $elementIds[] = $elementId;
}

$propertiesByElementId = array_fill_keys($elementIds, []);
if ($elementIds !== []) {
    CIBlockElement::GetPropertyValuesArray(
        $propertiesByElementId,
        $iblockId,
        ['ID' => $elementIds],
        ['CODE' => [
            'STATUS',
            'SALARY',
            'RESPONSIBILITIES',
            'CONDITIONS',
            'REQUIREMENTS',
            'OFFERS',
            'ADDRESS',
        ]]
    );
}

foreach ($elements as $elementId => $element) {
    $properties = is_array($propertiesByElementId[$elementId] ?? null)
        ? $propertiesByElementId[$elementId]
        : [];
    $statusProperty = is_array($properties['STATUS'] ?? null) ? $properties['STATUS'] : [];
    $status = site_string($statusProperty['VALUE_XML_ID'] ?? '');
    if ($status !== 'vacant') {
        continue;
    }

    $sectionId = max(0, (int)($element['IBLOCK_SECTION_ID'] ?? 0));
    if (!isset($sections[$sectionId])) {
        continue;
    }

    $sections[$sectionId]['items'][] = [
        'id' => $elementId,
        'name' => site_plain_text($element['~NAME'] ?? $element['NAME'] ?? ''),
        'salary' => $propertyDisplayValue($properties['SALARY'] ?? []),
        'responsibilities' => $propertyLines($properties['RESPONSIBILITIES'] ?? []),
        'requirements' => $propertyLines($properties['REQUIREMENTS'] ?? []),
        'conditions' => $propertyLines($properties['CONDITIONS'] ?? []),
        'offers' => $propertyLines($properties['OFFERS'] ?? []),
        'address' => $propertyDisplayValue($properties['ADDRESS'] ?? []),
    ];
    $arResult['VACANCIES']['total']++;
}

foreach ($sections as $section) {
    $count = count($section['items']);
    if ($count === 0) {
        continue;
    }

    $section['count'] = $count;
    $section['count_label'] = $vacancyCountLabel($count);
    $arResult['VACANCIES']['sections'][] = $section;
}

$phone = site_plain_text(get_info('phone'));
$phoneE164 = site_plain_text(get_info('phone_e164'));
$email = site_plain_text(get_info('email'));
$arResult['VACANCIES']['phone'] = $phone;
$arResult['VACANCIES']['phone_url'] = $phoneE164 !== ''
    ? site_url('tel:' . $phoneE164, '', ['tel'], false)
    : '';
$arResult['VACANCIES']['email'] = $email;
$arResult['VACANCIES']['email_url'] = $email !== ''
    ? site_url('mailto:' . $email, '', ['mailto'], false)
    : '';
