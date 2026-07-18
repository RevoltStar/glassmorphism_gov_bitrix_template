<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}
use Bitrix\Main\Localization\Loc;
/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);
$wrapId = 'cookie_' . $arResult['ID'];
$yandexMetrikaId = max(0, (int)get_info('yandex_metrika_id', 0));
?>
<div class="widget_cookie widget_cookie__<?=$arParams['PRESETS']?> cookie__hide" id="<?=$wrapId?>">
    <div class="widget_cookie__text">
        <?=$arParams['~MESSAGE']?>
        
        <?if($arParams['SHOW_SETTINGS'] === 'Y'):?>
        <div class="cookie-settings">
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
        </div>
        <?endif;?>
    </div>
    
    <div class="cookie-buttons">
        <button class="widget_cookie__btn-reject btn btn-secondary" 
                onclick="cookie_<?=$wrapId?>.rejectCookies()">
            <?=Loc::getMessage("COOKIE_REJECT")?>
        </button>
        
        <button class="widget_cookie__btn-accept btn btn-primary" 
                onclick="cookie_<?=$wrapId?>.acceptWithOptions()">
            <?=Loc::getMessage("COOKIE_ACCEPT")?>
        </button>
    </div>
    
    <script>
        window.YANDEX_METRIKA_ID = <?=$yandexMetrikaId?>;

        const cookie_<?=$wrapId?> = new CookieManager({
            containerId: '<?=$wrapId?>',
            cookieExpireDays: <?=$arParams['EXPIRE_DAYS']?>,
            checkCookieTimeout: <?=$arParams['CHECK_TIMEOUT']?>,
            showSettings: <?=$arParams['SHOW_SETTINGS'] === 'Y' ? 'true' : 'false'?>,
            analyticsScriptSrc: <?=$yandexMetrikaId > 0 ? "'/local/metrika.js'" : "''"?>
        });
    </script>
</div>
