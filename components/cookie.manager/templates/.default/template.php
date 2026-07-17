<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}
use Bitrix\Main\Localization\Loc;
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$wrapId = 'cookie_' . $arResult['ID'];
$yandexMetrikaId = max(0, (int)get_info('yandex_metrika_id', 0));
$styleBtn = $styleWrap = '';
if ($arParams['PRESETS'] === 'custom')
{
    $styleWrap = "border-color: {$arParams['COLOR_BORDER']};background-color: {$arParams['COLOR_BG']};";
    $styleBtn  = "background-color: {$arParams['COLOR_BTN']};";
}
?>
<div class="widget_cookie widget_cookie__<?=$arParams['PRESETS']?> cookie__hide" id="<?=$wrapId?>"
     style="<?=$styleWrap?>"
>
    <div class="widget_cookie__text">
        <?=$arParams['~MESSAGE']?>
        
        <?if($arParams['SHOW_SETTINGS'] === 'Y' || $arParams['SHOW_PD_CONSENT'] === 'Y'):?>
        <div class="cookie-settings" style="margin-top: 15px;">
            <?if($arParams['SHOW_SETTINGS'] === 'Y'):?>
            <div class="cookie-option">
                <input type="checkbox" id="cookie_session_<?=$wrapId?>" checked="" disabled="">
                <label for="cookie_session_<?=$wrapId?>">
                    <?=Loc::getMessage("COOKIE_SESSION")?>
                </label>
            </div>
            <div class="cookie-option">
                <input type="checkbox" id="cookie_analytics_<?=$wrapId?>">
                <label for="cookie_analytics_<?=$wrapId?>">
                    <?=Loc::getMessage("COOKIE_ANALYTICS")?>
                </label>
            </div>
            <?endif;?>
            <?if($arParams['SHOW_PD_CONSENT'] === 'Y'):?>
            <div class="cookie-option">
                <input type="checkbox" id="cookie_pd_<?=$wrapId?>">
                <label for="cookie_pd_<?=$wrapId?>">
                    <?=Loc::getMessage("COOKIE_PD_CONSENT")?>
                </label>
            </div>
            <?endif;?>
        </div>
        <?endif;?>
    </div>
    
    <div class="cookie-buttons" style="display: flex; gap: 10px; margin-top: 15px;">
        <button class="widget_cookie__btn-reject btn btn-secondary" 
                onclick="cookie_<?=$wrapId?>.rejectCookies('<?=$wrapId?>')"
                style="background-color: #6c757d; color: white;">
            <?=Loc::getMessage("COOKIE_REJECT")?>
        </button>
        
        <button class="widget_cookie__btn-accept btn btn-primary" 
                onclick="cookie_<?=$wrapId?>.acceptWithOptions('<?=$wrapId?>')"
                style="<?=$styleBtn?>">
            <?=Loc::getMessage("COOKIE_ACCEPT")?>
        </button>
    </div>
    
    <script>
        window.YANDEX_METRIKA_ID = <?=$yandexMetrikaId?>;

        const cookie_<?=$wrapId?> = new CookieManager({
            containerId: '<?=$wrapId?>',
            cookieExpireDays: <?=$arParams['EXPIRE_DAYS']?>,
            checkCookieTimeout: <?=$arParams['CHECK_TIMEOUT']?>,
            cookieName: 'cookie_consent',
            analyticsName: 'analytics_consent',
            pdConsentName: 'pd_consent',
            showSettings: <?=$arParams['SHOW_SETTINGS'] === 'Y' ? 'true' : 'false'?>,
            showPdConsent: <?=$arParams['SHOW_PD_CONSENT'] === 'Y' ? 'true' : 'false'?>,
            analyticsScriptSrc: <?=$yandexMetrikaId > 0 ? "'/local/metrika.js'" : "''"?>
        });
    </script>
</div>
