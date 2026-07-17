<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "GovernmentOrganization",
      "@id": "https://it.kirovreg.ru/#organization",
      "name": "Министерство информационных технологий и связи Кировской области (МИТИС)",
      "alternateName": ["МИТИС КО", "МИТИС", "ИТ Киров"],
      "url": "https://it.kirovreg.ru/",
      "logo": "https://it.kirovreg.ru/images/gerb_kirov_it.png",
      "description": "Официальный сайт министерства информационных технологий и связи Кировской области. Новости. Деятельность. Контакты.",
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
        								"PATH" => "/include/postal.php"
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
        								"PATH" => "/include/address.php"
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
        								"PATH" => "/include/phone_e164.php"
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
        								"PATH" => "/include/email.php"
    								)
								);?>",
        "availableLanguage": ["Russian"]
      },
      "sameAs": [
			"https://vk.com/informtehkirov",
		    "https://ok.ru/informtehkirov",
			"https://t.me/informtehkirov",
			"https://max.ru/id4345326586_gos"
      ],
      "areaServed": {
        "@type": "State",
        "name": "Кировская область"
      }
    },
    {
      "@type": "WebSite",
      "@id": "https://it.kirovreg.ru/#website",
      "url": "https://it.kirovreg.ru/",
      "name": "<?=htmlspecialchars($APPLICATION->ShowTitle())?>",
      "description": "<?=htmlspecialchars($APPLICATION->ShowProperty('description'))?>",
      "publisher": {
        "@id": "https://it.kirovreg.ru/#organization"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://it.kirovreg.ru/search/?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
  ]
}
</script>