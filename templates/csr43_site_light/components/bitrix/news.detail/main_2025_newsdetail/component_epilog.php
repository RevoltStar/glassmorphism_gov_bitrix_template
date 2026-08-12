<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

if (
    empty($templateData['JSON_LD'])
    || !is_array($templateData['JSON_LD'])
) {
    return;
}

$json = json_encode(
    $templateData['JSON_LD'],
    JSON_UNESCAPED_UNICODE
    | JSON_UNESCAPED_SLASHES
    | JSON_HEX_TAG
    | JSON_HEX_AMP
    | JSON_HEX_APOS
    | JSON_HEX_QUOT
);

if ($json === false) {
    AddMessage2Log(
        'Не удалось сформировать JSON-LD для новости: '
        . json_last_error_msg(),
        'news.detail'
    );

    return;
}

Asset::getInstance()->addString(
    '<script type="application/ld+json">'
    . $json
    . '</script>',
    true
);