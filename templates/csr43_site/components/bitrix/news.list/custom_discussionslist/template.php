<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = $arResult['ITEMS'] ?? [];
$instanceId = 'discussion-list-' . $this->randString(7);
$fileProperties = [
    'notification_file',
    'explanatory_note_file',
    'summary_report_file',
    'npa_file',
    'questionnaire_file',
    'file_actual',
    'file_project',
];

$getFileIcon = static function (string $extension): string {
    $icons = [
        'pdf' => 'bi-file-earmark-pdf',
        'doc' => 'bi-file-earmark-word',
        'docx' => 'bi-file-earmark-word',
        'xls' => 'bi-file-earmark-excel',
        'xlsx' => 'bi-file-earmark-excel',
        'ppt' => 'bi-file-earmark-slides',
        'pptx' => 'bi-file-earmark-slides',
        'zip' => 'bi-file-earmark-zip',
        'rar' => 'bi-file-earmark-zip',
        '7z' => 'bi-file-earmark-zip',
        'txt' => 'bi-file-earmark-text',
        'jpg' => 'bi-file-earmark-image',
        'jpeg' => 'bi-file-earmark-image',
        'png' => 'bi-file-earmark-image',
        'gif' => 'bi-file-earmark-image',
    ];

    return $icons[strtolower($extension)] ?? 'bi-file-earmark';
};

$formatFileSize = static function (int $bytes): string {
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 1, ',', ' ') . ' МБ';
    }

    return number_format(max($bytes, 0) / 1024, 0, ',', ' ') . ' КБ';
};

$formatDate = static function ($date): string {
    if (!$date) {
        return '';
    }

    return CIBlockFormatProperties::DateFormat(
        'j F Y',
        MakeTimeStamp($date, CSite::GetDateFormat())
    );
};

$itemCount = count($items);
$itemCountLabel = $itemCount . ' ';
if ($itemCount % 100 >= 11 && $itemCount % 100 <= 14) {
    $itemCountLabel .= 'обсуждений';
} elseif ($itemCount % 10 === 1) {
    $itemCountLabel .= 'обсуждение';
} elseif ($itemCount % 10 >= 2 && $itemCount % 10 <= 4) {
    $itemCountLabel .= 'обсуждения';
} else {
    $itemCountLabel .= 'обсуждений';
}
?>

<?php if ($items): ?>
    <section class="discussion-list" id="<?=$instanceId?>" data-discussions-list>
        <div class="discussion-list__header">
            <span class="discussion-list__header-icon" aria-hidden="true">
                <i class="bi bi-chat-square-text"></i>
            </span>
            <div class="discussion-list__heading">
                <h2 class="discussion-list__title">Материалы общественных обсуждений</h2>
                <p class="discussion-list__subtitle">Выберите тему, чтобы посмотреть сроки и документы.</p>
            </div>
            <span class="discussion-list__count"><?=htmlspecialcharsbx($itemCountLabel)?></span>
        </div>

        <div class="discussion-list__selector-wrap">
            <label class="discussion-list__selector-label" for="<?=$instanceId?>-selector">
                <i class="bi bi-list-check" aria-hidden="true"></i>
                Общественное обсуждение
            </label>
            <div class="discussion-list__select-shell">
                <select class="discussion-list__selector" id="<?=$instanceId?>-selector" data-discussion-selector>
                    <option value="">Выберите тему…</option>
                    <?php foreach ($items as $index => $item): ?>
                        <option value="<?=htmlspecialcharsbx((string)$index)?>"><?=htmlspecialcharsbx($item['NAME'])?></option>
                    <?php endforeach; ?>
                </select>
                <i class="bi bi-chevron-down discussion-list__select-icon" aria-hidden="true"></i>
            </div>
        </div>

        <div class="discussion-list__hint" data-discussion-hint>
            <span class="discussion-list__hint-icon" aria-hidden="true"><i class="bi bi-hand-index-thumb"></i></span>
            <span>После выбора темы здесь появятся сроки, материалы и форма для отправки мнения.</span>
        </div>

        <div class="discussion-list__items">
            <?php foreach ($items as $index => $item): ?>
                <?php
                $itemId = (int)$item['ID'];
                $cardId = $instanceId . '-item-' . $index;
                $dateFrom = $formatDate($item['DATE_ACTIVE_FROM'] ?? null);
                $dateTo = $formatDate($item['DATE_ACTIVE_TO'] ?? null);
                $files = [];

                foreach ($fileProperties as $propertyCode) {
                    $property = $item['PROPERTIES'][$propertyCode] ?? [];
                    $fileIds = $property['VALUE'] ?? [];
                    if (!$fileIds) {
                        continue;
                    }
                    if (!is_array($fileIds)) {
                        $fileIds = [$fileIds];
                    }

                    foreach ($fileIds as $fileId) {
                        $fileInfo = CFile::GetFileArray($fileId);
                        if (!$fileInfo) {
                            continue;
                        }
                        $originalName = (string)($fileInfo['ORIGINAL_NAME'] ?: basename($fileInfo['SRC']));
                        $extension = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
                        $files[] = [
                            'label' => (string)($property['NAME'] ?: 'Документ'),
                            'name' => $originalName,
                            'src' => (string)$fileInfo['SRC'],
                            'extension' => $extension,
                            'size' => $formatFileSize((int)$fileInfo['FILE_SIZE']),
                            'icon' => $getFileIcon($extension),
                        ];
                    }
                }

                $this->AddEditAction(
                    $itemId,
                    $item['EDIT_LINK'],
                    CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_EDIT')
                );
                $this->AddDeleteAction(
                    $itemId,
                    $item['DELETE_LINK'],
                    CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_DELETE'),
                    ['CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]
                );
                ?>
                <article class="discussion-card"
                         id="<?=$this->GetEditAreaId($itemId)?>"
                         data-discussion-card="<?=htmlspecialcharsbx((string)$index)?>"
                         hidden>
                    <div class="discussion-card__top">
                        <span class="discussion-card__icon" aria-hidden="true"><i class="bi bi-megaphone"></i></span>
                        <div class="discussion-card__heading">
                            <span class="discussion-card__eyebrow">Общественное обсуждение</span>
                            <h3 class="discussion-card__title" id="<?=$cardId?>-title"><?=htmlspecialcharsbx($item['NAME'])?></h3>
                        </div>
                    </div>

                    <?php if ($dateFrom !== '' || $dateTo !== ''): ?>
                        <div class="discussion-card__period" aria-label="Сроки проведения">
                            <?php if ($dateFrom !== ''): ?>
                                <div class="discussion-card__date">
                                    <span class="discussion-card__date-icon" aria-hidden="true"><i class="bi bi-calendar-check"></i></span>
                                    <span><small>Начало</small><strong><?=htmlspecialcharsbx($dateFrom)?></strong></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($dateTo !== ''): ?>
                                <div class="discussion-card__date">
                                    <span class="discussion-card__date-icon" aria-hidden="true"><i class="bi bi-calendar-event"></i></span>
                                    <span><small>Окончание</small><strong><?=htmlspecialcharsbx($dateTo)?></strong></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="discussion-card__documents-heading">
                        <span>Документы и материалы</span>
                        <?php if ($files): ?><span class="discussion-card__documents-count"><?=count($files)?></span><?php endif; ?>
                    </div>

                    <?php if ($files): ?>
                        <div class="discussion-card__documents">
                            <?php foreach ($files as $file): ?>
                                <a class="discussion-file" href="<?=htmlspecialcharsbx($file['src'])?>" download>
                                    <span class="discussion-file__icon" aria-hidden="true"><i class="bi <?=$file['icon']?>"></i></span>
                                    <span class="discussion-file__content">
                                        <strong class="discussion-file__label"><?=htmlspecialcharsbx($file['label'])?></strong>
                                        <span class="discussion-file__name"><?=htmlspecialcharsbx($file['name'])?></span>
                                        <span class="discussion-file__meta"><?=htmlspecialcharsbx(strtoupper($file['extension']) ?: 'Файл')?> · <?=htmlspecialcharsbx($file['size'])?></span>
                                    </span>
                                    <span class="discussion-file__download" aria-hidden="true"><i class="bi bi-download"></i></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="discussion-card__no-files">
                            <i class="bi bi-info-circle" aria-hidden="true"></i>
                            Материалы для этого обсуждения пока не опубликованы.
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <div class="discussion-list-empty" role="status">
        <span class="discussion-list-empty__icon" aria-hidden="true"><i class="bi bi-chat-square"></i></span>
        <div>
            <strong>Нет активных обсуждений</strong>
            <p>Новые материалы будут опубликованы в этом разделе.</p>
        </div>
    </div>
<?php endif; ?>
