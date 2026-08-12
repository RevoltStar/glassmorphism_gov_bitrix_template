<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

function site_string(mixed $value, string $default = ''): string
{
    return is_scalar($value) ? (string)$value : $default;
}

function site_string_list(mixed $value): array
{
    if ($value === null || $value === false || $value === '') {
        return [];
    }

    $values = is_array($value) ? $value : [$value];

    return array_values(array_filter(
        array_map(
            static fn (mixed $item): string => trim(site_string($item)),
            $values
        ),
        static fn (string $item): bool => $item !== ''
    ));
}

function site_http_url_with_ascii_host(string $url): ?string
{
    $parts = parse_url($url);
    if (!is_array($parts) || !isset($parts['scheme'], $parts['host'])) {
        return null;
    }

    $host = (string)$parts['host'];
    if (preg_match('/[^\x00-\x7F]/', $host) !== 1) {
        return $url;
    }

    $asciiHost = false;

    try {
        if (function_exists('idn_to_ascii')) {
            $idnaFlags = defined('IDNA_DEFAULT') ? IDNA_DEFAULT : 0;
            $asciiHost = defined('INTL_IDNA_VARIANT_UTS46')
                ? idn_to_ascii($host, $idnaFlags, INTL_IDNA_VARIANT_UTS46)
                : idn_to_ascii($host, $idnaFlags);
        } elseif (class_exists('CBXPunycode')) {
            $encodingErrors = [];
            $asciiHost = CBXPunycode::ToASCII($host, $encodingErrors);
            if ($encodingErrors !== []) {
                $asciiHost = false;
            }
        }
    } catch (Throwable) {
        return null;
    }

    if (
        !is_string($asciiHost)
        || $asciiHost === ''
        || preg_match('/^[\x21-\x7E]+$/D', $asciiHost) !== 1
    ) {
        return null;
    }

    $authority = '';
    if (array_key_exists('user', $parts)) {
        $authority .= (string)$parts['user'];
        if (array_key_exists('pass', $parts)) {
            $authority .= ':' . (string)$parts['pass'];
        }
        $authority .= '@';
    }

    $authority .= $asciiHost;
    if (array_key_exists('port', $parts)) {
        $authority .= ':' . (int)$parts['port'];
    }

    return strtolower((string)$parts['scheme']) . '://' . $authority
        . (string)($parts['path'] ?? '')
        . (array_key_exists('query', $parts) ? '?' . (string)$parts['query'] : '')
        . (array_key_exists('fragment', $parts) ? '#' . (string)$parts['fragment'] : '');
}

function site_url(
    mixed $value,
    string $fallback = '#',
    array $allowedSchemes = ['http', 'https'],
    bool $allowInternal = true
): string {
    if (!is_string($value)) {
        return $fallback;
    }

    $value = trim($value);
    if (
        $value === ''
        || str_contains($value, '\\')
        || preg_match('/[\x00-\x1F\x7F]/', $value)
    ) {
        return $fallback;
    }

    if ($allowInternal) {
        $isRootRelative = preg_match('~^/(?!/)~', $value) === 1;
        $isLocalReference = preg_match('/^[#?](?![\\/])/', $value) === 1;
        if ($isRootRelative || $isLocalReference) {
            return $value;
        }
    }

    $scheme = strtolower((string)parse_url($value, PHP_URL_SCHEME));
    if (!in_array($scheme, $allowedSchemes, true)) {
        return $fallback;
    }

    if (in_array($scheme, ['http', 'https'], true)) {
        $normalizedUrl = site_http_url_with_ascii_host($value);

        return $normalizedUrl !== null
            && filter_var($normalizedUrl, FILTER_VALIDATE_URL) !== false
            ? $normalizedUrl
            : $fallback;
    }

    if ($scheme === 'mailto') {
        $email = substr($value, strlen('mailto:'));
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false
            ? $value
            : $fallback;
    }

    if ($scheme === 'tel') {
        $phone = substr($value, strlen('tel:'));
        return preg_match('/^[0-9+().\s-]+$/D', $phone) === 1
            ? $value
            : $fallback;
    }

    return $fallback;
}

function site_is_external_http_url(string $url): bool
{
    $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
    return in_array($scheme, ['http', 'https'], true);
}

function site_template_image_url(mixed $value, string $fallback = ''): string
{
    if (!defined('SITE_TEMPLATE_PATH')) {
        return $fallback;
    }

    $filename = trim(site_string($value));
    if (
        preg_match(
            '/^[\p{L}\p{N}][\p{L}\p{N}._-]*\.(?:avif|gif|ico|jpe?g|png|svg|webp|bmp)$/Diu',
            $filename
        ) !== 1
    ) {
        return $fallback;
    }

    return rtrim((string)SITE_TEMPLATE_PATH, '/')
        . '/images/'
        . rawurlencode($filename);
}

function site_css_classes(mixed $value, string $default = ''): string
{
    if (!is_string($value)) {
        return $default;
    }

    $value = trim($value);

    return preg_match(
        '/^[a-zA-Z0-9_-]+(?:\s+[a-zA-Z0-9_-]+)*$/D',
        $value
    ) === 1 ? $value : $default;
}

function site_menu_type(mixed $value, string $default): string
{
    $value = trim(site_string($value));

    return preg_match('/^[a-zA-Z0-9_-]+$/D', $value) === 1
        ? $value
        : $default;
}

function site_css_url(mixed $value, string $fallback): string
{
    $url = site_url($value, $fallback);

    return (string)json_encode(
        $url,
        JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES
        | JSON_INVALID_UTF8_SUBSTITUTE
    );
}

function site_plain_text(mixed $value): string
{
    return trim(strip_tags(site_string($value)));
}

function site_section_anchor_id(mixed $name, mixed $sectionId = 0): string
{
    $source = site_plain_text($name);
    $transliteration = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
        'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
        'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch',
        'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
    ];

    $slug = strtolower(strtr($source, $transliteration));
    $slug = preg_replace('/[^a-z0-9]+/', '_', $slug);
    $slug = is_string($slug) ? trim($slug, '_') : '';

    if ($slug === '') {
        $normalizedSectionId = max(0, (int)$sectionId);
        $slug = $normalizedSectionId > 0
            ? 'id-' . $normalizedSectionId
            : 'id-' . substr(hash('sha256', $source), 0, 12);
    }

    return 'section-' . $slug;
}

function site_path_is_excluded(mixed $currentPath, mixed $excludedPaths): bool
{
    if (!is_string($currentPath)) {
        return false;
    }

    $currentPath = '/' . ltrim((string)parse_url($currentPath, PHP_URL_PATH), '/');
    $currentPath = $currentPath === '/' ? '/' : rtrim($currentPath, '/');

    foreach (site_string_list($excludedPaths) as $excludedPath) {
        $excludedPath = '/' . ltrim((string)parse_url($excludedPath, PHP_URL_PATH), '/');
        $excludedPath = $excludedPath === '/' ? '/' : rtrim($excludedPath, '/');

        if ($excludedPath === '/') {
            if ($currentPath === '/') {
                return true;
            }
            continue;
        }

        if (
            $currentPath === $excludedPath
            || str_starts_with($currentPath, $excludedPath . '/')
        ) {
            return true;
        }
    }

    return false;
}

function site_safe_html(mixed $value): string
{
    $html = site_string($value);
    if ($html === '') {
        return '';
    }

    if (class_exists('CBXSanitizer')) {
        try {
            $sanitizer = new CBXSanitizer();
            if (defined('CBXSanitizer::SECURE_LEVEL_HIGH')) {
                $sanitizer->SetLevel(CBXSanitizer::SECURE_LEVEL_HIGH);
            }

            // Высокий уровень сохраняет базовое форматирование, но не ссылки.
            // Разрешаем только атрибуты, необходимые для обычной текстовой ссылки.
            $sanitizer->AddTags([
                'a' => ['href', 'title'],
            ]);

            // Не превращаем уже существующие сущности вроде &nbsp; в &amp;nbsp;.
            if (method_exists($sanitizer, 'ApplyDoubleEncode')) {
                $sanitizer->ApplyDoubleEncode(false);
            }

            if (method_exists($sanitizer, 'setDelTagsWithContent')) {
                $sanitizer->setDelTagsWithContent([
                    'script',
                    'style',
                    'iframe',
                    'object',
                    'embed',
                ]);
            }

            return (string)$sanitizer->SanitizeHtml($html);
        } catch (Throwable) {
            // Безопасный fallback ниже.
        }
    }

    return htmlspecialcharsbx($html);
}

function site_safe_pagination_html(mixed $value): string
{
    $html = site_string($value);
    if ($html === '') {
        return '';
    }

    if (class_exists('CBXSanitizer')) {
        try {
            $sanitizer = new CBXSanitizer();
            if (defined('CBXSanitizer::SECURE_LEVEL_HIGH')) {
                $sanitizer->SetLevel(CBXSanitizer::SECURE_LEVEL_HIGH);
            }

            $sanitizer->AddTags([
                'nav' => ['class', 'aria-label'],
                'div' => ['class', 'role', 'aria-label'],
                'ul' => ['class', 'role'],
                'ol' => ['class', 'role'],
                'li' => ['class', 'role'],
                'a' => [
                    'class',
                    'href',
                    'title',
                    'rel',
                    'role',
                    'aria-label',
                    'aria-current',
                    'aria-disabled',
                ],
                'span' => [
                    'class',
                    'role',
                    'aria-label',
                    'aria-current',
                    'aria-hidden',
                    'aria-disabled',
                ],
            ]);

            if (method_exists($sanitizer, 'ApplyDoubleEncode')) {
                $sanitizer->ApplyDoubleEncode(false);
            }

            if (method_exists($sanitizer, 'setDelTagsWithContent')) {
                $sanitizer->setDelTagsWithContent([
                    'script',
                    'style',
                    'iframe',
                    'object',
                    'embed',
                ]);
            }

            return (string)$sanitizer->SanitizeHtml($html);
        } catch (Throwable) {
            // Безопасный fallback ниже.
        }
    }

    return htmlspecialcharsbx($html);
}
