<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

class CookieComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams): array
    {
        $arParams = is_array($arParams) ? $arParams : [];
        $arParams['CACHE_TIME'] = max(0, (int)($arParams['CACHE_TIME'] ?? 86400));
        $arParams['CHECK_TIMEOUT'] = min(60000, max(0, (int)($arParams['CHECK_TIMEOUT'] ?? 2000)));
        $arParams['EXPIRE_DAYS'] = min(3650, max(1, (int)($arParams['EXPIRE_DAYS'] ?? 30)));
        $arParams['PRESETS'] = site_string($arParams['PRESETS'] ?? 'style1');
        $arParams['SHOW_SETTINGS'] = ($arParams['SHOW_SETTINGS'] ?? 'Y') === 'Y' ? 'Y' : 'N';
        if (!in_array($arParams['PRESETS'], ['style1', 'style2', 'style3', 'style4', 'style5'], true)) {
            $arParams['PRESETS'] = 'style1';
        }

        $message = $arParams['~MESSAGE'] ?? $arParams['MESSAGE'] ?? '';
        $arParams['MESSAGE'] = site_string($message);
        $arParams['~MESSAGE'] = $arParams['MESSAGE'];

        return $arParams;
    }

    public function executeComponent(): void
    {
        if ($this->startResultCache())
        {
            $this->getResult();
            $this->IncludeComponentTemplate();
        }
    }

    protected function getResult(): void
    {
        $message = site_string($this->arParams['MESSAGE'] ?? '');
        $this->arResult['ID'] = md5($message);
        $this->arResult['SAFE_MESSAGE'] = site_safe_html($message);
    }
}
