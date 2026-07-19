<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arTemplateParameters = [
    'FALLBACK_DETAIL_PAGE_URL' => [
        'PARENT' => 'BASE',
        'NAME' => 'Ссылка на страницу всех направлений деятельности',
        'TYPE' => 'STRING',
        'DEFAULT' => '/activity/',
    ],
];
