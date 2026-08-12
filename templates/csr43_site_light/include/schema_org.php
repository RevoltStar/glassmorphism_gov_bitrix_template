<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "GovernmentOrganization",
      "@id": "https://csr43.ru/#organization",
      "name": "Кировское областное государственное бюджетное учреждение «Центр стратегического развития информационных ресурсов и систем управления» (КОГБУ ЦСРИРиСУ)",
      "alternateName": ["КОГБУ ЦСРИРиСУ", "ЦСР"],
      "url": "https://csr43.ru/",
      "logo": "https://csr43.ru/images/logo_csr.png",
      "description": "Аккредитованная IT-организация, официальный поставщик IT-услуг для государственных учреждений Кировской области. Координирует проекты цифровизации, разработку сайтов, внедрение ГИС-технологий, подключение к СМЭВ и системы электронного правительства.",
      "address": {
        "@type": "PostalAddress",
        "addressCountry": "RU",
        "addressRegion": "Кировская область",
        "addressLocality": "Киров",
        "postalCode": "<?
								$APPLICATION->IncludeComponent(
    								"bitrix:main.include",
    								"",
    								array(
        								"AREA_FILE_SHOW" => "file",
        								"AREA_FILE_SUFFIX" => "inc",
        								"COMPOSITE_FRAME_MODE" => "A",
        								"COMPOSITE_FRAME_TYPE" => "AUTO",
        								"EDIT_TEMPLATE" => "",
        								"PATH" => SITE_TEMPLATE_PATH . "/include/postal.php"
    								)
								);?>",
        "streetAddress": "<?
								$APPLICATION->IncludeComponent(
    								"bitrix:main.include",
    								"",
    								array(
        								"AREA_FILE_SHOW" => "file",
        								"AREA_FILE_SUFFIX" => "inc",
        								"COMPOSITE_FRAME_MODE" => "A",
        								"COMPOSITE_FRAME_TYPE" => "AUTO",
        								"EDIT_TEMPLATE" => "",
        								"PATH" => SITE_TEMPLATE_PATH . "/include/address.php"
    								)
								);?>"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "telephone": "<?
								$APPLICATION->IncludeComponent(
    								"bitrix:main.include",
    								"",
    								array(
        								"AREA_FILE_SHOW" => "file",
        								"AREA_FILE_SUFFIX" => "inc",
        								"COMPOSITE_FRAME_MODE" => "A",
        								"COMPOSITE_FRAME_TYPE" => "AUTO",
        								"EDIT_TEMPLATE" => "",
        								"PATH" => SITE_TEMPLATE_PATH . "/include/phone_e164.php"
    								)
								);?>",
        "email": "<?
								$APPLICATION->IncludeComponent(
    								"bitrix:main.include",
    								"",
    								array(
        								"AREA_FILE_SHOW" => "file",
        								"AREA_FILE_SUFFIX" => "inc",
        								"COMPOSITE_FRAME_MODE" => "A",
        								"COMPOSITE_FRAME_TYPE" => "AUTO",
        								"EDIT_TEMPLATE" => "",
        								"PATH" => SITE_TEMPLATE_PATH . "/include/email.php"
    								)
								);?>",
        "availableLanguage": ["Russian"]
      },
      "sameAs": [
        "https://vk.com/csrkirov"
      ],
      "areaServed": {
        "@type": "State",
        "name": "Кировская область"
      }
    },
    {
      "@type": "WebSite",
      "@id": "https://csr43.ru/#website",
      "url": "https://csr43.ru/",
      "name": "<?=htmlspecialchars($APPLICATION->ShowTitle())?>",
      "description": "<?=htmlspecialchars($APPLICATION->ShowProperty('description'))?>",
      "publisher": {
        "@id": "https://csr43.ru/#organization"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://csr43.ru/search/?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
  ]
}
</script>