<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

class CookieComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['CACHE_TIME']    = (int)($arParams['CACHE_TIME'] ?? 0);
        $arParams['CHECK_TIMEOUT'] = (int)($arParams['CHECK_TIMEOUT'] ?? 0);
        $arParams['EXPIRE_DAYS']   = (int)($arParams['EXPIRE_DAYS'] ?? 0);
        $arParams['PRESETS']       = (string)($arParams['PRESETS'] ?? '');
        $arParams['SHOW_SETTINGS'] = ($arParams['SHOW_SETTINGS'] ?? 'Y') === 'Y' ? 'Y' : 'N';

        $this->setDefault($arParams['PRESETS'], 'style1');
        $this->setDefault($arParams['EXPIRE_DAYS'], 30);
        $this->setDefault($arParams['CHECK_TIMEOUT'], 2000);
        $this->setDefault($arParams['CACHE_TIME'], 86400);

        return $arParams;
    }

    protected function setDefault(&$value, $defaultValue): void
    {
        if (empty($value))
        {
            $value = $defaultValue;
        }
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
        $this->arResult['ID'] = md5($this->arParams['MESSAGE']);
    }
}
