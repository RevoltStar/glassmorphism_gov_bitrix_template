<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);

$resultId = preg_replace('/[^a-f0-9]/D', '', site_string($arResult['ID'] ?? ''));
$wrapId = 'cookie_' . ($resultId !== '' ? $resultId : md5('cookie'));
$preset = site_css_classes($arParams['PRESETS'] ?? null, 'style1');
$expireDays = min(3650, max(1, (int)($arParams['EXPIRE_DAYS'] ?? 30)));
$checkTimeout = min(60000, max(0, (int)($arParams['CHECK_TIMEOUT'] ?? 2000)));
$showSettings = ($arParams['SHOW_SETTINGS'] ?? 'N') === 'Y';
$yandexMetrikaId = max(0, (int)get_info('yandex_metrika_id', 0));
$containerIdJson = json_encode($wrapId, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$message = site_plain_text($arResult['MESSAGE'] ?? '');
$policyText = site_plain_text($arResult['POLICY_TEXT'] ?? '');
$policyUrl = site_url($arResult['POLICY_URL'] ?? '', '');
?>
<div class="widget_cookie widget_cookie__<?=htmlspecialcharsbx($preset)?> cookie__hide"
     id="<?=htmlspecialcharsbx($wrapId)?>">
    <div class="widget_cookie__text">
        <?=htmlspecialcharsbx($message)?>

        <?php if ($policyText !== '' && $policyUrl !== ''): ?>
            <br><br>
            <a target="_blank"
               rel="noopener noreferrer"
               href="<?=htmlspecialcharsbx($policyUrl)?>"><?=htmlspecialcharsbx($policyText)?></a>
        <?php endif; ?>

        <?php if ($showSettings): ?>
            <div class="cookie-settings">
                <div class="cookie-option">
                    <input type="checkbox" id="cookie_session_<?=htmlspecialcharsbx($wrapId)?>" checked disabled>
                    <label for="cookie_session_<?=htmlspecialcharsbx($wrapId)?>"><?=htmlspecialcharsbx(site_string(Loc::getMessage('COOKIE_SESSION')))?></label>
                </div>
                <div class="cookie-option">
                    <input type="checkbox" id="cookie_analytics_<?=htmlspecialcharsbx($wrapId)?>">
                    <label for="cookie_analytics_<?=htmlspecialcharsbx($wrapId)?>"><?=htmlspecialcharsbx(site_string(Loc::getMessage('COOKIE_ANALYTICS')))?></label>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="cookie-buttons">
        <button class="widget_cookie__btn-reject btn btn-secondary"
                onclick="cookie_<?=htmlspecialcharsbx($wrapId)?>.rejectCookies()">
            <?=htmlspecialcharsbx(site_string(Loc::getMessage('COOKIE_REJECT')))?></button>
        <button class="widget_cookie__btn-accept btn btn-primary"
                onclick="cookie_<?=htmlspecialcharsbx($wrapId)?>.acceptWithOptions()">
            <?=htmlspecialcharsbx(site_string(Loc::getMessage('COOKIE_ACCEPT')))?></button>
    </div>

    <script>
        window.YANDEX_METRIKA_ID = <?=$yandexMetrikaId?>;

        const cookie_<?=htmlspecialcharsbx($wrapId)?> = new CookieManager({
            containerId: <?=$containerIdJson?>,
            cookieExpireDays: <?=$expireDays?>,
            checkCookieTimeout: <?=$checkTimeout?>,
            showSettings: <?=$showSettings ? 'true' : 'false'?>,
            analyticsScriptSrc: <?=$yandexMetrikaId > 0 ? "'/local/js/analytics/yandex-metrika.js'" : "''"?>
        });
    </script>
</div>
