<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var string $templateFolder */

// 1. ПОДКЛЮЧАЕМ СТИЛИ ПЛАГИНА
$this->addExternalCss($templateFolder . '/dist/css/bvi.min.css');

// 2. ПОДКЛЮЧАЕМ СКРИПТ ПЛАГИНА
$this->addExternalJs($templateFolder . '/dist/js/bvi.js');
$this->addExternalJs($templateFolder . '/bvi-accessibility.js');
$this->addExternalCss($templateFolder . '/bvi-accessibility.css');

// 3. ИНИЦИАЛИЗИРУЕМ ПЛАГИН.
Bitrix\Main\Page\Asset::getInstance()->addString(
    '<script>
        // Инициализация происходит после полной загрузки документа
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof isvek !== "undefined") {
                new isvek.Bvi({
                    target: ".bvi-open" // Указываем класс нашей кнопки как триггер
                });
            }else{
        	console.log("Не удалось загрузить bvi-плагин");
            }
        });
    </script>'
);
?>
<style>
    /* Стили для кнопки "Версия для слабовидящих" */
    .vision-btn {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid rgba(52, 152, 219, 0.3);
        color: #1e3a5f;
        padding: 8px 22px;
        border-radius: 40px;
        font-size: 0.95rem;
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.08);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
    }

    .vision-btn:hover {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(52, 152, 219, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(52, 152, 219, 0.15);
    }

    .vision-btn:active {
        transform: translateY(0);
    }
</style>
<!-- 4. ВЫВОДИМ КНОПКУ, КОТОРАЯ БУДЕТ ОТВЕЧАТЬ ЗА ОТКРЫТИЕ ПАНЕЛИ -->
<div style="text-align: right; margin: 10px 0; width: 100%;" class="bvi-component-container">
    <button type="button" class="vision-btn bvi-open" style="width: 100%;" aria-label="Версия для слабовидящих">
        <i class="bi bi-eye-fill me-2" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">Версия для слабовидящих</span>
        <span class="d-inline d-sm-none">Слабовидящим</span>
    </button>
</div>
