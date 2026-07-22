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
    'org_name' => 'Министерство транспорта',
    'org_full_name' => 'Министерство транспорта Кировской области',
    'org_short_name' => 'Министерство транспорта',
    'org_alternate_names' => ['МИНТРАНС КО', 'МИНТРАНС', 'Транспорт Киров'],
    'org_description' => 'Официальный сайт Министерства транспорта Кировской области. Новости, деятельность и контакты.',

    'region_name' => 'Кировская область',
    'region_name_genitive' => 'Кировской области',
    'country_code' => 'RU',
    'locality' => 'Киров',
    'postal_code' => '610035',
    'street_address' => 'Мелькомбинатовский проезд, д. 6',
    'address' => 'г. Киров, Мелькомбинатовский проезд, д. 6',

    'phone' => '+7 (8332) 27-27-20',
    'phone_e164' => '+78332272720',
    'email' => 'udh@udh.kirov.ru',

    'workdays_primary' => 'Пн-Чт: 9:00 – 18:00',
    'workdays_secondary' => 'Пт: 9:00 – 17:00',
    'lunch_break' => 'перерыв: 12:30-13:18',

    'site_url' => 'https://transport.kirovreg.ru',
    'search_path' => '/search/',
    'feedback_path' => '/for_citizens/',
    'privacy_policy_path' => '/about/personal-data-protection-policy/',
    'show_max_robot' => true,
    'max_robot_region_id' => '33000000000',
    'show_development_notice' => true,
    'yandex_metrika_id' => 105110245,
    'gosuslugi_widget_id' => 215879,
    'gosuslugi_org_id' => 492,
    'files_iblock_type' => 'mintrans',
    'files_iblock_id' => 18,
    'links_iblock_type' => 'mintrans',
    'links_iblock_id' => 17,
    'menu_top_root_type' => 'top',
    'menu_top_child_type' => 'left',
    'menu_side_root_type' => 'left',
    'menu_side_child_type' => 'left',
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
        '/for_citizens',
        '/for_citizens/',
    ],

    'title_excluded_pages' => [
        '/',
        '/news/*',
        '!/news/category/*',
    ],
    'logo' => 'gerb_kirov.png',
    'national_project_logo' => 'national_project_infr.png',
    'national_project_logo_alt' => 'Символ проекта «Инфраструктура для жизни»',
    'national_project_url' => 'https://национальныепроекты.рф/new-projects/infrastruktura-dlya-zhizni/',

    'copyright_year_from' => 1991,
    'developer_name' => 'КОГБУ «Центр стратегического развития информационных ресурсов и систем управления»',
    'developer_url' => 'https://csr43.ru/',
    'developer_logo' => '/images/logo_csr.png',
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
