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
        return filter_var($value, FILTER_VALIDATE_URL) !== false
            ? $value
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

            return (string)$sanitizer->SanitizeHtml($html);
        } catch (Throwable) {
            // Безопасный fallback ниже.
        }
    }

    return htmlspecialcharsbx($html);
}
