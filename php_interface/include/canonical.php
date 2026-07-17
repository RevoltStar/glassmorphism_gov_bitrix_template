<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\SiteTable;

function get_canonical_link(array $allowedParams = []): string
{
    static $host;

    if ($host === null) {
        if (defined('SITE_SERVER_NAME') && SITE_SERVER_NAME !== '') {
            $host = SITE_SERVER_NAME;
        } else {
            $site = \Bitrix\Main\SiteTable::getByPrimary(SITE_ID, [
                'select' => ['SERVER_NAME'],
            ])->fetch();

            $host = trim((string)($site['SERVER_NAME'] ?? ''));
        }

        $host = preg_replace('~^https?://~i', '', $host);
        $host = rtrim($host, '/');

        if ($host === '') {
            throw new RuntimeException(
                'Не заполнен SERVER_NAME для сайта ' . SITE_ID
            );
        }
    }

    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($requestUri, PHP_URL_PATH) ?: '/';

    $path = preg_replace('~/+~', '/', $path);
    $path = preg_replace('~/index\.php$~i', '/', $path);

    $query = [];

    foreach ($allowedParams as $key => $item) {
        /*
         * Поддерживаются два формата:
         *
         * ["PAGEN_1", "PAGEN_2"]
         *
         * [
         *     "PAGEN_1" => callable,
         * ]
         */
        if (is_int($key)) {
            $name = $item;
            $validator = null;
        } else {
            $name = $key;
            $validator = $item;
        }

        if (
            !is_string($name)
            || !preg_match('/^[a-zA-Z0-9_]+$/D', $name)
        ) {
            throw new InvalidArgumentException(
                'Некорректное имя GET-параметра'
            );
        }

        if (!array_key_exists($name, $_GET)) {
            continue;
        }

        // Массивы из query string в canonical не переносим.
        if (!is_scalar($_GET[$name])) {
            continue;
        }

        $value = (string)$_GET[$name];

        if ($validator !== null) {
            if (!is_callable($validator)) {
                throw new InvalidArgumentException(
                    'Валидатор параметра ' . $name . ' должен быть callable'
                );
            }

            $value = $validator($value);

            // null или false означает: параметр не включать.
            if ($value === null || $value === false) {
                continue;
            }

            if (!is_scalar($value)) {
                throw new UnexpectedValueException(
                    'Валидатор параметра ' . $name
                    . ' должен вернуть scalar, null или false'
                );
            }

            $value = (string)$value;
        } elseif (preg_match('/^PAGEN_\d+$/D', $name)) {
            /*
             * Стандартная обработка пагинации:
             * - только положительное целое число;
             * - первую страницу не добавляем.
             */
            $page = filter_var($value, FILTER_VALIDATE_INT);

            if ($page === false || $page <= 1) {
                continue;
            }

            $value = (string)$page;
        }

        $query[$name] = $value;
    }

    $canonical = 'https://' . $host . $path;

    if ($query !== []) {
        $canonical .= '?' . http_build_query(
            $query,
            '',
            '&',
            PHP_QUERY_RFC3986
        );
    }

    return $canonical;
}