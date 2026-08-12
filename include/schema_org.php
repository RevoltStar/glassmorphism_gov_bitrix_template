<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$siteUrl = rtrim(site_url(get_info('site_url'), '', ['http', 'https'], false), '/');
if ($siteUrl === '') {
    return;
}

$logoPath = site_template_image_url(get_info('logo', ''));
$logoUrl = $logoPath !== ''
    ? site_url($siteUrl . '/' . ltrim($logoPath, '/'), '', ['http', 'https'], false)
    : '';
$searchPath = site_url(get_info('search_path'), '/search/');
$searchUrl = site_is_external_http_url($searchPath)
    ? $searchPath
    : $siteUrl . '/' . ltrim($searchPath, '/');
$socialLinks = [];

$socialMenuType = site_menu_type(get_info('menu_social_root_type', 'social'), 'social');
$socialMenu = $APPLICATION->GetMenu($socialMenuType, false, false, SITE_DIR);
$socialMenuItems = is_object($socialMenu) && is_array($socialMenu->arMenu ?? null)
    ? $socialMenu->arMenu
    : [];

foreach ($socialMenuItems as $socialMenuItem) {
    if (
        !is_array($socialMenuItem)
        || (string)($socialMenuItem['PERMISSION'] ?? 'D') <= 'D'
    ) {
        continue;
    }

    $socialLink = site_url(
        $socialMenuItem['LINK'] ?? null,
        '',
        ['http', 'https'],
        false
    );

    if ($socialLink !== '' && site_is_external_http_url($socialLink)) {
        $socialLinks[] = $socialLink;
    }
}
$socialLinks = array_values(array_unique($socialLinks));

$organizationId = $siteUrl . '/#organization';
$websiteId = $siteUrl . '/#website';

$organizationSchema = [
    '@type' => 'GovernmentOrganization',
    '@id' => $organizationId,
    'name' => get_info('org_full_name'),
    'alternateName' => get_info('org_alternate_names'),
    'url' => $siteUrl . '/',
    'logo' => $logoUrl,
    'description' => get_info('org_description'),
    'address' => [
        '@type' => 'PostalAddress',
        'addressCountry' => get_info('country_code'),
        'addressRegion' => get_info('region_name'),
        'addressLocality' => get_info('locality'),
        'postalCode' => get_info('postal_code'),
        'streetAddress' => get_info('street_address'),
    ],
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'contactType' => 'customer service',
        'telephone' => get_info('phone_e164'),
        'email' => get_info('email'),
        'availableLanguage' => ['Russian'],
    ],
    'areaServed' => [
        '@type' => 'State',
        'name' => get_info('region_name'),
    ],
];

if ($socialLinks !== []) {
    $organizationSchema['sameAs'] = $socialLinks;
}

$schema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        $organizationSchema,
        [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $siteUrl . '/',
            'name' => get_info('org_full_name'),
            'description' => get_info('org_description'),
            'publisher' => [
                '@id' => $organizationId,
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => rtrim($searchUrl, '/') . '/?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ],
    ],
];

$schemaJson = json_encode(
    $schema,
    JSON_UNESCAPED_UNICODE
    | JSON_UNESCAPED_SLASHES
    | JSON_HEX_TAG
    | JSON_HEX_AMP
    | JSON_HEX_APOS
    | JSON_HEX_QUOT
    | JSON_INVALID_UTF8_SUBSTITUTE
);
?>
<?php if (is_string($schemaJson)): ?>
<script type="application/ld+json"><?=$schemaJson?></script>
<?php endif; ?>
