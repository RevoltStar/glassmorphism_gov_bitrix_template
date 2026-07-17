<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

//
class CookieComponent extends \CBitrixComponent
{
    /** @var \Bitrix\Main\HttpResponse */
    protected \Bitrix\Main\Response|\Bitrix\Main\HttpResponse $response;

    /** @var \Bitrix\Main\HttpRequest|\Bitrix\Main\Request */
    protected $request;

    //region Base functions

    /**
     * PublicViteComponent constructor.
     *
     * @param   null  $component
     *
     * @throws SystemException
     */
    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    //
    public function onPrepareComponentParams($arParams): array
    {
        $arParams['CACHE_TIME']    = (int)($arParams['CACHE_TIME'] ?? 0);
        $arParams['CHECK_TIMEOUT'] = (int)($arParams['CHECK_TIMEOUT'] ?? 0);
        $arParams['EXPIRE_DAYS']   = (int)($arParams['EXPIRE_DAYS'] ?? 0);
        $arParams['PRESETS']       = (string)($arParams['PRESETS'] ?? '');
        $arParams['SHOW_SETTINGS'] = ($arParams['SHOW_SETTINGS'] ?? 'Y') === 'Y' ? 'Y' : 'N';
        $arParams['SHOW_PD_CONSENT'] = ($arParams['SHOW_PD_CONSENT'] ?? 'N') === 'Y' ? 'Y' : 'N';

        $this->setDefault($arParams['PRESETS'], 'style1');
        $this->setDefault($arParams['EXPIRE_DAYS'], 30);
        $this->setDefault($arParams['CHECK_TIMEOUT'], 2000);
        $this->setDefault($arParams['CACHE_TIME'], 86400);

        // 
        return $arParams;
    }

    public function setDefault(&$value, $defaultValue): void
    {
        if (empty($value))
        {
            $value = $defaultValue;
        }
    }

    public function executeComponent()
    {
        if ($this->startResultCache())
        {
            try
            {
                $this->getResult();
                $this->IncludeComponentTemplate();
            }
            catch (Exception $e)
            {
                $this->AbortResultCache();
                // $this->response->setStatus('404 Not Found');
                \Bitrix\Iblock\Component\Tools::process404(
                    Loc::getMessage('PAGE_NOT_FOUND'),
                    true,
                    true
                );
				/*ShowError($e->getMessage());*/
                $this->abortResultCache();
            }
        }

        return parent::executeComponent();
    }

    protected function listKeysSignedParameters(): array
    {
        return [
            'TITLE',
        ];
    }

    //endregion Base functions

    //region custom functions

    // 
    protected function getResult(): void
    {
        $this->arResult = [];

        //

        $this->arResult['ID'] = md5($this->arParams['MESSAGE']);

    }

    //endregion custom functions

}
