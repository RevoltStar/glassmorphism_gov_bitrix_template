<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = $arResult['ITEMS'] ?? [];
$instanceId = 'citizen-faq-' . $this->randString(7);
$contentId = $instanceId . '-content';
$accordionId = $instanceId . '-accordion';
$questionCount = count($items);

if ($questionCount % 100 >= 11 && $questionCount % 100 <= 14) {
    $questionLabel = $questionCount . ' вопросов';
} elseif ($questionCount % 10 === 1) {
    $questionLabel = $questionCount . ' вопрос';
} elseif ($questionCount % 10 >= 2 && $questionCount % 10 <= 4) {
    $questionLabel = $questionCount . ' вопроса';
} else {
    $questionLabel = $questionCount . ' вопросов';
}
?>

<div class="container mt-4">
    <?php if ($items): ?>
        <section class="csr43-glass-surface citizen-faq" aria-labelledby="<?=$instanceId?>-title">
            <button class="citizen-faq__toggle collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#<?=$contentId?>"
                    aria-expanded="false"
                    aria-controls="<?=$contentId?>">
                <span class="citizen-faq__heading">
                    <span class="csr43-glass-icon citizen-faq__heading-icon" aria-hidden="true">
                        <i class="bi bi-question-lg"></i>
                    </span>
                    <span class="citizen-faq__heading-text">
                        <span class="citizen-faq__title" id="<?=$instanceId?>-title">Часто задаваемые вопросы</span>
                        <span class="citizen-faq__count"><?=$questionLabel?></span>
                    </span>
                </span>
                <span class="citizen-faq__toggle-icon" aria-hidden="true">
                    <i class="bi bi-chevron-down"></i>
                </span>
            </button>

            <div class="collapse" id="<?=$contentId?>">
                <div class="citizen-faq__items" id="<?=$accordionId?>">
                    <?php foreach ($items as $index => $item): ?>
                        <?php
                        $itemId = (int)$item['ID'];
                        $headingId = $instanceId . '-heading-' . $itemId;
                        $answerId = $instanceId . '-answer-' . $itemId;
                        $previewText = (string)($item['PREVIEW_TEXT'] ?? '');
                        $detailText = (string)($item['DETAIL_TEXT'] ?? '');
                        $answer = trim($previewText !== '' ? $previewText : $detailText);
                        $answerType = $previewText !== ''
                            ? (string)($item['PREVIEW_TEXT_TYPE'] ?? 'text')
                            : (string)($item['DETAIL_TEXT_TYPE'] ?? 'text');

                        $this->AddEditAction(
                            $itemId,
                            $item['EDIT_LINK'],
                            CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_EDIT')
                        );
                        $this->AddDeleteAction(
                            $itemId,
                            $item['DELETE_LINK'],
                            CIBlock::GetArrayByID($item['IBLOCK_ID'], 'ELEMENT_DELETE'),
                            ['CONFIRM' => GetMessage('CT_FAQ_ELEMENT_DELETE_CONFIRM')]
                        );
                        ?>
                        <article class="csr43-glass-surface citizen-faq__item" id="<?=$this->GetEditAreaId($itemId)?>">
                            <h3 class="citizen-faq__question" id="<?=$headingId?>">
                                <button class="citizen-faq__question-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?=$answerId?>"
                                        aria-expanded="false"
                                        aria-controls="<?=$answerId?>">
                                    <span class="citizen-faq__number" aria-hidden="true">
                                        <?=str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT)?>
                                    </span>
                                    <span class="citizen-faq__question-text"><?=htmlspecialcharsbx($item['NAME'])?></span>
                                    <span class="citizen-faq__question-icon" aria-hidden="true">
                                        <i class="bi bi-plus-lg"></i>
                                    </span>
                                </button>
                            </h3>

                            <div class="collapse"
                                 id="<?=$answerId?>"
                                 aria-labelledby="<?=$headingId?>"
                                 data-bs-parent="#<?=$accordionId?>">
                                <div class="citizen-faq__answer">
                                    <?php if ($answer !== ''): ?>
                                        <?php if ($answerType === 'html'): ?>
                                            <?=site_safe_html($answer)?>
                                        <?php else: ?>
                                            <?=nl2br(htmlspecialcharsbx($answer))?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Ответ на вопрос готовится.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php else: ?>
        <div class="csr43-glass-surface citizen-faq__empty" role="status">
            <i class="bi bi-info-circle" aria-hidden="true"></i>
            Часто задаваемые вопросы пока не опубликованы.
        </div>
    <?php endif; ?>
</div>
