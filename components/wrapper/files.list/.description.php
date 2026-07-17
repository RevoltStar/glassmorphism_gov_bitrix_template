<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentDescription = [
    'NAME' => 'Прикреплённые файлы',
    'DESCRIPTION' => 'Выводит файлы из заданного раздела инфоблока «Прикреплённые файлы».',
    'SORT' => 10,
    'COMPLEX' => 'N',
    'CACHE_PATH' => 'Y',
    'PATH' => [
        'ID' => 'wrapper',
        'NAME' => 'Обёртки MT 2026',
        'SORT' => 20,
    ],
];

