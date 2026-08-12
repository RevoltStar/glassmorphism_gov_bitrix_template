<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$excludedPages = get_info('layout_excluded_pages', []);
$currentPage = site_string($APPLICATION->GetCurPage(), '/');
$isExcludedPage = site_path_is_excluded($currentPage, $excludedPages);
$is404Error = defined('ERROR_404');
$footerLogoUrl = site_template_image_url(get_info('logo', ''));
$footerPhone = site_plain_text(get_info('phone', ''));
$footerPhoneUrl = site_url(
    'tel:' . site_string(get_info('phone_e164', '')),
    '',
    ['tel'],
    false
);
$footerEmail = site_plain_text(get_info('email', ''));
$footerEmailUrl = site_url(
    'mailto:' . site_string(get_info('email', '')),
    '',
    ['mailto'],
    false
);
$privacyPolicyUrl = site_url(get_info('privacy_policy_path', ''), '');
$footerMenu1Title = site_plain_text(get_info('menu_footer_1_title', 'Разделы'));
$footerMenu2Title = site_plain_text(get_info('menu_footer_2_title', 'Информация'));

if (!$isExcludedPage && !$is404Error): ?>
    </div>
    <div class="col-lg-4 order-1 order-lg-2 mb-4 mb-lg-0">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:menu',
            'side',
            [
                'ALLOW_MULTI_SELECT' => 'Y',
                'CHILD_MENU_TYPE' => site_menu_type(get_info('menu_side_child_type', 'right'), 'right'),
                'DELAY' => 'N',
                'MAX_LEVEL' => '1',
                'MENU_CACHE_GET_VARS' => [],
                'MENU_CACHE_TIME' => '3600',
                'MENU_CACHE_TYPE' => 'N',
                'MENU_CACHE_USE_GROUPS' => 'Y',
                'ROOT_MENU_TYPE' => site_menu_type(get_info('menu_side_root_type', 'right'), 'right'),
                'USE_EXT' => 'Y',
            ]
        ); ?>
    </div>
</div>
<?php endif; ?>
</main>

<?php $APPLICATION->IncludeComponent(
    'print.version',
    '',
    [
        'PAGE_URL' => '',
        'BUTTON_TEXT_PRINT' => GetMessage('CSR43_LIGHT_PRINT_VERSION'),
        'BUTTON_TEXT_NORMAL' => GetMessage('CSR43_LIGHT_NORMAL_VERSION'),
        'BUTTON_CLASS' => 'btn btn-light border',
        'OPEN_IN_NEW_WINDOW' => 'N',
    ]
); ?>

<footer class="footer bg-dark text-light pb-5">
    <?php require dirname(__DIR__, 2) . '/include/widget_gosuslugi.php'; ?>

    <div class="container">
        <div class="row py-4 py-lg-5">
            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <div class="d-flex align-items-start mb-3">
                    <?php if ($footerLogoUrl !== ''): ?>
                        <img src="<?=htmlspecialcharsbx($footerLogoUrl)?>"
                             alt="<?=htmlspecialcharsbx(site_plain_text(get_info('org_full_name', '')))?>"
                             class="footer-logo me-3"
                             loading="lazy"
                             decoding="async">
                    <?php endif; ?>
                    <div>
                        <h5 class="fw-bold mb-1"><?=htmlspecialcharsbx(site_plain_text(get_info('org_short_name', '')))?></h5>
                        <p class="small footer-text-muted mb-0"><?=htmlspecialcharsbx(site_plain_text(get_info('org_full_name', '')))?></p>
                    </div>
                </div>
                <p class="small footer-text-muted mb-0"><?=htmlspecialcharsbx(site_plain_text(get_info('org_description', '')))?></p>
            </div>

            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                <h6 class="fw-bold mb-3"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_CONTACTS'))?></h6>
                <?php if ($footerPhone !== ''): ?>
                    <div class="mb-2">
                        <i class="bi bi-telephone me-2 text-primary" aria-hidden="true"></i>
                        <?php if ($footerPhoneUrl !== ''): ?>
                            <a href="<?=htmlspecialcharsbx($footerPhoneUrl)?>" class="text-decoration-none text-light"><?=htmlspecialcharsbx($footerPhone)?></a>
                        <?php else: ?>
                            <span class="text-light"><?=htmlspecialcharsbx($footerPhone)?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($footerEmail !== ''): ?>
                    <div class="mb-2">
                        <i class="bi bi-envelope me-2 text-primary" aria-hidden="true"></i>
                        <?php if ($footerEmailUrl !== ''): ?>
                            <a href="<?=htmlspecialcharsbx($footerEmailUrl)?>" class="text-decoration-none text-light"><?=htmlspecialcharsbx($footerEmail)?></a>
                        <?php else: ?>
                            <span class="text-light"><?=htmlspecialcharsbx($footerEmail)?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="mb-2">
                    <i class="bi bi-geo-alt me-2 text-primary" aria-hidden="true"></i>
                    <span class="footer-text-muted"><?=htmlspecialcharsbx(site_plain_text(get_info('postal_code', '')))?>, <?=htmlspecialcharsbx(site_plain_text(get_info('address', '')))?></span>
                </div>
                <div class="mb-2">
                    <i class="bi bi-clock me-2 text-primary" aria-hidden="true"></i>
                    <span class="footer-text-muted"><?=htmlspecialcharsbx(site_plain_text(get_info('workdays_primary', '')))?></span>
                </div>
                <div class="mb-2">
                    <i class="bi bi-clock me-2 text-primary" aria-hidden="true"></i>
                    <span class="footer-text-muted"><?=htmlspecialcharsbx(site_plain_text(get_info('workdays_secondary', '')))?></span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                <h6 class="fw-bold mb-3"><?=htmlspecialcharsbx($footerMenu1Title)?></h6>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:menu',
                    'footer',
                    [
                        'ROOT_MENU_TYPE' => site_menu_type(get_info('menu_footer_1_root_type', 'footer_quick'), 'footer_quick'),
                        'MAX_LEVEL' => '1',
                        'CHILD_MENU_TYPE' => '',
                        'USE_EXT' => 'N',
                        'DELAY' => 'N',
                        'ALLOW_MULTI_SELECT' => 'Y',
                        'MENU_CACHE_TYPE' => 'A',
                        'MENU_CACHE_TIME' => '3600',
                        'MENU_CACHE_USE_GROUPS' => 'Y',
                        'MENU_CACHE_GET_VARS' => [],
                    ]
                ); ?>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3"><?=htmlspecialcharsbx($footerMenu2Title)?></h6>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:menu',
                    'footer',
                    [
                        'ROOT_MENU_TYPE' => site_menu_type(get_info('menu_footer_2_root_type', 'footer_info'), 'footer_info'),
                        'MAX_LEVEL' => '1',
                        'CHILD_MENU_TYPE' => '',
                        'USE_EXT' => 'N',
                        'DELAY' => 'N',
                        'ALLOW_MULTI_SELECT' => 'Y',
                        'MENU_CACHE_TYPE' => 'A',
                        'MENU_CACHE_TIME' => '3600',
                        'MENU_CACHE_USE_GROUPS' => 'Y',
                        'MENU_CACHE_GET_VARS' => [],
                    ]
                ); ?>
                <?php if ($privacyPolicyUrl !== ''): ?>
                    <a href="<?=htmlspecialcharsbx($privacyPolicyUrl)?>" class="footer-menu__link small"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_PRIVACY_POLICY'))?></a>
                <?php else: ?>
                    <span class="footer-text-muted small"><?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_PRIVACY_POLICY'))?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="border-top border-secondary pt-3">
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <p class="small footer-text-muted mb-0">
                        &copy; <?=htmlspecialcharsbx((string)get_info('copyright_year_from'))?>–<?=date('Y')?>
                        <?=htmlspecialcharsbx(site_plain_text(get_info('org_short_name', '')))?>. <?=htmlspecialcharsbx(GetMessage('CSR43_LIGHT_ALL_RIGHTS_RESERVED'))?>
                    </p>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end justify-content-center">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:menu',
                        'social',
                        [
                            'ROOT_MENU_TYPE' => site_menu_type(get_info('menu_social_root_type', 'social'), 'social'),
                            'MAX_LEVEL' => '1',
                            'CHILD_MENU_TYPE' => '',
                            'USE_EXT' => 'N',
                            'DELAY' => 'N',
                            'ALLOW_MULTI_SELECT' => 'N',
                            'MENU_CACHE_TYPE' => 'A',
                            'MENU_CACHE_TIME' => '3600',
                            'MENU_CACHE_USE_GROUPS' => 'Y',
                            'MENU_CACHE_GET_VARS' => [],
                        ]
                    ); ?>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php
$cookieMessage = site_plain_text(get_info('org_name', ''))
    . ' ' . GetMessage('CSR43_LIGHT_COOKIE_MESSAGE_SUFFIX');

$APPLICATION->IncludeComponent(
    'csr43:cookie.manager',
    '.default',
    [
        'CACHE_TIME' => '86400',
        'CACHE_TYPE' => 'A',
        'CHECK_TIMEOUT' => '3000',
        'EXPIRE_DAYS' => '365',
        'MESSAGE' => $cookieMessage,
        'POLICY_URL' => $privacyPolicyUrl,
        'POLICY_TEXT' => GetMessage('CSR43_LIGHT_PERSONAL_DATA_POLICY'),
        'PRESETS' => 'style4',
        'SHOW_SETTINGS' => 'Y',
    ]
);
?>
</body>
</html>
