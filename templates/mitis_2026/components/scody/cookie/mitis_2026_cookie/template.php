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
        
        <?if($arParams['SHOW_SETTINGS'] == 'Y'):?>
        <div class="cookie-settings" style="margin-top: 15px;">
            <div class="cookie-option">
                <input type="checkbox" id="cookie_analytics_<?=$wrapId?>" checked>
                <label for="cookie_analytics_<?=$wrapId?>">
                    <?=Loc::getMessage("SCODY_COOKIE_ANALYTICS")?>
                </label>
            </div>
            <?if($arParams['SHOW_PD_CONSENT'] == 'Y'):?>
            <div class="cookie-option">
                <input type="checkbox" id="cookie_pd_<?=$wrapId?>">
                <label for="cookie_pd_<?=$wrapId?>">
                    <?=Loc::getMessage("SCODY_COOKIE_PD_CONSENT")?>
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
            <?=Loc::getMessage("SCODY_COOKIE_REJECT")?>
        </button>
        
        <button class="widget_cookie__btn-accept btn btn-primary" 
                onclick="cookie_<?=$wrapId?>.acceptWithOptions('<?=$wrapId?>')"
                style="<?=$styleBtn?>">
            <?=Loc::getMessage("SCODY_COOKIE_ACCEPT")?>
        </button>
    </div>
    
    <script>
        // Обновленный менеджер cookie с проверкой галочек
        const cookie_<?=$wrapId?> = new CookieManager({
            containerId: '<?=$wrapId?>',
            cookieExpireDays: <?=$arParams['EXPIRE_DAYS']?>,
            checkCookieTimeout: <?=$arParams['CHECK_TIMEOUT']?>,
            cookieName: 'cookie_consent_<?=$wrapId?>',
            analyticsName: 'analytics_consent_<?=$wrapId?>',
            pdConsentName: 'pd_consent_<?=$wrapId?>',
            showSettings: <?=$arParams['SHOW_SETTINGS'] == 'Y' ? 'true' : 'false'?>,
            showPdConsent: <?=$arParams['SHOW_PD_CONSENT'] == 'Y' ? 'true' : 'false'?>
        });
    </script>
</div>