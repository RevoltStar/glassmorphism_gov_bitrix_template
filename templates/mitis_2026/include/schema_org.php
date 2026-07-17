<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$siteUrl = rtrim((string)get_info('site_url'), '/');
$organizationId = $siteUrl . '/#organization';
$websiteId = $siteUrl . '/#website';

$schema = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'GovernmentOrganization',
            '@id' => $organizationId,
            'name' => get_info('org_full_name'),
            'alternateName' => get_info('org_alternate_names'),
            'url' => $siteUrl . '/',
            'logo' => get_info_absolute_url('logo'),
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
            'sameAs' => get_info('social_links'),
            'areaServed' => [
                '@type' => 'State',
                'name' => get_info('region_name'),
            ],
        ],
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
                'target' => $siteUrl . rtrim((string)get_info('search_path'), '/') . '/?q={search_term_string}',
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
    | JSON_THROW_ON_ERROR
);
?>
<script type="application/ld+json"><?=$schemaJson?></script>
