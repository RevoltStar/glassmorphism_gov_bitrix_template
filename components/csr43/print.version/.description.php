<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentDescription = [
    'NAME' => GetMessage('CSR43_PRINT_VERSION_NAME'),
    'DESCRIPTION' => GetMessage('CSR43_PRINT_VERSION_DESCRIPTION'),
    'ICON' => '/images/icon.gif',
    'CACHE_PATH' => 'Y',
    'PATH' => [
        'ID' => 'csr43',
        'NAME' => GetMessage('CSR43_PRINT_VERSION_PATH'),
    ],
    'COMPLEX' => 'N',
];
