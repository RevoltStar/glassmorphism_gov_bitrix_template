<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true){die();}

$arComponentDescription = [
    'NAME'        => GetMessage('COOKIE_NAME'),
    'DESCRIPTION' => GetMessage('COOKIE_DESCRIPTION'),
    'SORT'        => 10,
    'COMPLEX'     => 'N',
    'CACHE_PATH'  => 'Y',
    'PATH'        => [
        "ID"   => "cookie_section",
        "NAME" => GetMessage("SECTIONS_NAME"),
        "SORT" => 10,
        "CHILD" => array(
            'ID'    => 'cookie_manager',
            "NAME" => GetMessage("SECTION_NAME"),
            "SORT" => 10,
        ),
    ],
];
