<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
    die();
}

$arComponentParameters = [
    'PARAMETERS' => [
        "MESSAGE"       => [
            "PARENT"  => "BASE",
            "NAME"    => GetMessage("COOKIE_MESSAGE"),
            "TYPE"    => "TEXT",
            "ROWS"    => 5,
            "COLS"    => 50,
            "DEFAULT" => GetMessage("COOKIE_MESSAGE_DEFAULT"),
        ],
        "CHECK_TIMEOUT" => [
            "PARENT"  => "BASE",
            "NAME"    => GetMessage("COOKIE_CHECK_TIMEOUT"),
            "TYPE"    => "STRING",
            "DEFAULT" => 3000,
        ],
        "EXPIRE_DAYS"   => [
            "PARENT"  => "BASE",
            "NAME"    => GetMessage("COOKIE_EXPIRE_DAYS"),
            "TYPE"    => "STRING",
            "DEFAULT" => 30,
        ],
        "SHOW_SETTINGS" => [
            "PARENT"  => "BASE",
            "NAME"    => GetMessage("COOKIE_SHOW_SETTINGS"),
            "TYPE"    => "CHECKBOX",
            "DEFAULT" => "Y",
        ],
        'CACHE_TIME'    => [
            "PARENT"  => "PARAMS",
            'DEFAULT' => 86400,
        ],
        "PRESETS"       => [
            "PARENT"  => "BASE",
            "NAME"    => GetMessage("COOKIE_PRESETS"),
            "TYPE"    => "LIST",
            "VALUES"  => [
                "style1" => GetMessage('COOKIE_PRESETS_GREEN'),
                "style2" => GetMessage('COOKIE_PRESETS_ORANGE'),
                "style3" => GetMessage('COOKIE_PRESETS_RED'),
                "style4" => GetMessage('COOKIE_PRESETS_BLUE'),
                "style5" => GetMessage('COOKIE_PRESETS_TURQUOISE'),
            ],
            "DEFAULT" => "style1",
        ],
    ],
];
