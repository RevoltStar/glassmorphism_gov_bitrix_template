<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Единая конфигурация конкретной установки сайта.
 *
 * Значения не экранируются автоматически: контекст вывода определяет место,
 * где используется get_info().
 */
const SITE_INFO = [
    'org_name' => 'Министерство информационных технологий и связи',
    'org_full_name' => 'Министерство информационных технологий и связи Кировской области',
    'org_short_name' => 'Министерство ИТ и связи',
    'org_alternate_names' => ['МИТИС КО', 'МИТИС', 'ИТ Киров'],
    'org_description' => 'Официальный сайт Министерства информационных технологий и связи Кировской области. Новости, деятельность и контакты.',

    'region_name' => 'Кировская область',
    'region_name_genitive' => 'Кировской области',
    'country_code' => 'RU',
    'locality' => 'Киров',
    'postal_code' => '610019',
    'street_address' => 'ул. Защитников Отечества, д. 69',
    'address' => 'г. Киров, ул. Защитников Отечества, д. 69',

    'phone' => '+7 (8332) 27-27-27',
    'phone_e164' => '+78332272727',
    'email' => 'it43@ako.kirov.ru',

    'workdays_primary' => 'Пн-Чт: 9:00 – 18:00',
    'workdays_secondary' => 'Пт: 9:00 – 17:00',
    'lunch_break' => 'перерыв: 12:30-13:18',

    'site_url' => 'https://it.kirovreg.ru',
    'search_path' => '/search/',
    'feedback_path' => '/feedback_online/',
    'privacy_policy_path' => '/doc/personal_data_processing_policies/',
    'show_max_robot' => false,
    'max_robot_region_id' => '33000000000',
    'show_development_notice' => true,
    'yandex_metrika_id' => 105106588,
    'gosuslugi_widget_id' => 215882,
    'gosuslugi_org_id' => 494,
    'files_iblock_type' => 'mitis',
    'files_iblock_id' => 21,
    'links_iblock_type' => 'mitis',
    'links_iblock_id' => 24,
    'menu_top_root_type' => 'top',
    'menu_top_child_type' => 'right',
    'menu_side_root_type' => 'right',
    'menu_side_child_type' => 'right',
    'menu_social_root_type' => 'social',
    'menu_footer_1_root_type' => 'footer_quick',
    'menu_footer_1_title' => 'Быстрые ссылки',
    'menu_footer_2_root_type' => 'footer_info',
    'menu_footer_2_title' => 'Информация',
    'layout_excluded_pages' => [
        '/',
        '/news',
        '/news/',
        '/contacts',
        '/contacts/',
    ],
    // Суффикс /* применяется к дочерним страницам, ! разрешает вывод заголовка.
    'title_excluded_pages' => [
        '/',
        '/news/*',
        '!/news/category/*',
    ],

    // Пути могут быть относительными от корня сайта или абсолютными URL.
    'logo' => 'gerb_kirov.png',
    'national_project_logo' => 'national_project.png',
    'national_project_logo_alt' => 'Символ проекта «Экономика данных и цифровая трансформация государства»',
    'national_project_url' => 'https://digital.gov.ru/target/naczionalnyj-proekt-ekonomika-dannyh-i-czifrovaya-transformacziya-gosudarstva',

    'social_links' => [
        'https://vk.com/informtehkirov',
        'https://ok.ru/informtehkirov',
        'https://t.me/informtehkirov',
        'https://max.ru/id4345326586_gos',
    ],

    'copyright_year_from' => 1991,
    'developer_name' => 'КОГБУ «Центр стратегического развития информационных ресурсов и систем управления»',
    'developer_url' => 'https://csr43.ru/',
    'developer_logo' => 'logo_csr.png',
];

function get_info(string $key, mixed $default = null): mixed
{
    if (array_key_exists($key, SITE_INFO)) {
        return SITE_INFO[$key];
    }

    if (func_num_args() >= 2) {
        return $default;
    }

    throw new OutOfBoundsException(
        sprintf('Настройка сайта "%s" не определена.', $key)
    );
}

function get_info_absolute_url(string $key): string
{
    $value = (string)get_info($key);

    if (preg_match('~^https?://~i', $value)) {
        return $value;
    }

    return rtrim((string)get_info('site_url'), '/') . '/' . ltrim($value, '/');
}
