<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;

Loc::loadMessages(__FILE__);

class PrintVersionComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams): array
    {
        $arParams = is_array($arParams) ? $arParams : [];
        $printText = site_plain_text($arParams['BUTTON_TEXT_PRINT'] ?? '');
        $normalText = site_plain_text($arParams['BUTTON_TEXT_NORMAL'] ?? '');

        return [
            'PAGE_URL' => site_string($arParams['PAGE_URL'] ?? ''),
            'BUTTON_TEXT_PRINT' => $printText !== ''
                ? $printText
                : site_plain_text(Loc::getMessage('CSR43_PRINT_VERSION_PRINT')),
            'BUTTON_TEXT_NORMAL' => $normalText !== ''
                ? $normalText
                : site_plain_text(Loc::getMessage('CSR43_PRINT_VERSION_NORMAL')),
            'BUTTON_CLASS' => site_css_classes($arParams['BUTTON_CLASS'] ?? ''),
            'OPEN_IN_NEW_WINDOW' => ($arParams['OPEN_IN_NEW_WINDOW'] ?? 'N') === 'Y',
        ];
    }

    public function executeComponent(): void
    {
        $isPrintMode = $this->isPrintMode();
        $pageUrl = $this->resolvePageUrl();

        $this->arResult['IS_PRINT_MODE'] = $isPrintMode;
        $this->arResult['BUTTON_URL'] = $this->buildButtonUrl($pageUrl, $isPrintMode);
        $this->arResult['BUTTON_TEXT'] = $isPrintMode
            ? $this->arParams['BUTTON_TEXT_NORMAL']
            : $this->arParams['BUTTON_TEXT_PRINT'];
        $this->arResult['BUTTON_CLASS'] = $this->arParams['BUTTON_CLASS'];
        $this->arResult['OPEN_IN_NEW_WINDOW'] = $this->arParams['OPEN_IN_NEW_WINDOW'];

        $this->includeComponentTemplate();
    }

    private function isPrintMode(): bool
    {
        $printParam = Context::getCurrent()->getRequest()->getQuery('print');

        return site_string($printParam) === 'Y';
    }

    private function resolvePageUrl(): string
    {
        $configuredUrl = site_url($this->arParams['PAGE_URL'] ?? '', '');
        if ($configuredUrl !== '') {
            return $configuredUrl;
        }

        $requestUri = Context::getCurrent()->getRequest()->getRequestUri();

        return site_url($requestUri, '/');
    }

    private function buildButtonUrl(string $pageUrl, bool $isPrintMode): string
    {
        try {
            $uri = new Uri($pageUrl);
            $uri->deleteParams(['print']);

            if (!$isPrintMode) {
                $uri->addParams(['print' => 'Y']);
            }

            return site_url($uri->getUri(), '/');
        } catch (Throwable) {
            return '/';
        }
    }
}
