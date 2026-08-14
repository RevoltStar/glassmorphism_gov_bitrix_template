<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class PrintVersionComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams['PAGE_URL'] = trim($arParams['PAGE_URL']);
        $arParams['BUTTON_TEXT_PRINT'] = trim($arParams['BUTTON_TEXT_PRINT']) ?: 'Версия для печати';
        $arParams['BUTTON_TEXT_NORMAL'] = trim($arParams['BUTTON_TEXT_NORMAL']) ?: 'К обычной версии';
        $arParams['BUTTON_CLASS'] = trim($arParams['BUTTON_CLASS']) ?: 'print-version-btn';
        $arParams['OPEN_IN_NEW_WINDOW'] = $arParams['OPEN_IN_NEW_WINDOW'] === 'Y';
        
        return $arParams;
    }

    public function executeComponent()
    {
        if ($this->arParams['PAGE_URL'] === '') {
            $this->arParams['PAGE_URL'] = $this->getCurrentPageUrl();
        }

        // Определяем текущий режим
        $this->arResult['IS_PRINT_MODE'] = $this->isPrintMode();
        
        // Генерируем соответствующий URL
        $this->arResult['BUTTON_URL'] = $this->generateButtonUrl();
        $this->arResult['BUTTON_TEXT'] = $this->getButtonText();
        
        $this->includeComponentTemplate();
    }

    private function getCurrentPageUrl()
    {
        $protocol = (CMain::IsHTTPS()) ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    private function isPrintMode()
    {
        return isset($_GET['print']) && $_GET['print'] === 'Y';
    }

    private function generateButtonUrl()
    {
        $url = $this->arParams['PAGE_URL'];
        
        if ($this->arResult['IS_PRINT_MODE']) {
            // Удаляем параметр print из URL
            return $this->removePrintParam($url);
        } else {
            // Добавляем параметр print=Y к URL
            $separator = (strpos($url, '?') === false) ? '?' : '&';
            return $url . $separator . 'print=Y';
        }
    }

    private function removePrintParam($url)
    {
        // Разбираем URL на компоненты
        $urlParts = parse_url($url);
        
        if (!isset($urlParts['query'])) {
            return $url; // Нет параметров - возвращаем как есть
        }
        
        // Разбираем параметры запроса
        parse_str($urlParts['query'], $params);
        
        // Удаляем параметр print
        unset($params['print']);
        
        // Собираем URL обратно
        $newQuery = http_build_query($params);
        $urlParts['query'] = $newQuery ?: null;
        
        return $this->buildUrl($urlParts);
    }

    private function buildUrl($parts)
    {
        $url = '';
        
        if (isset($parts['scheme'])) {
            $url .= $parts['scheme'] . '://';
        }
        if (isset($parts['host'])) {
            $url .= $parts['host'];
        }
        if (isset($parts['port'])) {
            $url .= ':' . $parts['port'];
        }
        if (isset($parts['path'])) {
            $url .= $parts['path'];
        }
        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }
        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }
        
        return $url;
    }

    private function getButtonText()
    {
        return $this->arResult['IS_PRINT_MODE'] 
            ? $this->arParams['BUTTON_TEXT_NORMAL']
            : $this->arParams['BUTTON_TEXT_PRINT'];
    }
}
?>