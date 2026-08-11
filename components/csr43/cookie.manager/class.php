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

        $arParams['MESSAGE'] = site_plain_text(
            $arParams['~MESSAGE'] ?? $arParams['MESSAGE'] ?? ''
        );
        $arParams['POLICY_TEXT'] = site_plain_text(
            $arParams['~POLICY_TEXT'] ?? $arParams['POLICY_TEXT'] ?? ''
        );
        $arParams['POLICY_URL'] = site_url(
            $arParams['~POLICY_URL'] ?? $arParams['POLICY_URL'] ?? '',
            ''
        );

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
        $message = site_plain_text($this->arParams['MESSAGE'] ?? '');
        $policyText = site_plain_text($this->arParams['POLICY_TEXT'] ?? '');
        $policyUrl = site_url($this->arParams['POLICY_URL'] ?? '', '');

        $this->arResult['ID'] = md5($message . "\0" . $policyText . "\0" . $policyUrl);
        $this->arResult['MESSAGE'] = $message;
        $this->arResult['POLICY_TEXT'] = $policyText;
        $this->arResult['POLICY_URL'] = $policyUrl;
    }
}
