<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Конфигурация шаблона csr43_site_light.
 *
 * Значения не экранируются автоматически: контекст вывода определяет место,
 * где используется get_info().
 */
const SITE_INFO = [
    'org_name' => 'КОГБУ «ЦСРИРиСУ»',
    'org_full_name' => 'Кировское областное государственное бюджетное учреждение «Центр стратегического развития информационных ресурсов и систем управления»',
    'org_short_name' => 'КОГБУ ЦСРИРиСУ',
    'org_alternate_names' => ['КОГБУ ЦСРИРиСУ', 'ЦСР'],
    'org_description' => 'Аккредитованная IT-организация, официальный поставщик IT-услуг для государственных учреждений Кировской области. Координирует проекты цифровизации, разработку сайтов, внедрение ГИС-технологий, подключение к СМЭВ и системы электронного правительства.',

    'region_name' => 'Кировская область',
    'region_name_genitive' => 'Кировской области',
    'country_code' => 'RU',
    'locality' => 'Киров',
    'postal_code' => '610020',
    'street_address' => 'ул. Владимирская, д. 54',
    'address' => 'г. Киров, ул. Владимирская, д. 54',

    'phone' => '+7 (8332) 27-27-47',
    'phone_e164' => '+78332272747',
    'email' => 'csr@csr43.ru',

    'workdays_primary' => 'Пн-Чт: 9:00 – 18:00',
    'workdays_secondary' => 'Пт: 9:00 – 17:00',
    'lunch_break' => 'перерыв: 12:30-13:18',

    'site_url' => 'https://csr43.ru',
    'search_path' => '/search/',
    'feedback_path' => '/contacts/feedback/',
    'privacy_policy_path' => '/documents/personal_data_processing_policies/',
    'show_max_robot' => false,
    'max_robot_region_id' => '33000000000',
    'show_development_notice' => false,
    'yandex_metrika_id' => 104440589,
    'gosuslugi_widget_id' => 308235,
    'gosuslugi_org_id' => 308235,
    'files_iblock_type' => '',
    'files_iblock_id' => 0,
    'links_iblock_type' => '',
    'links_iblock_id' => 0,
    'menu_top_root_type' => 'top',
    'menu_top_child_type' => 'right',
    'menu_side_root_type' => 'right',
    'menu_side_child_type' => 'right',
    'menu_social_root_type' => 'social',
    'menu_footer_1_root_type' => 'footer_quick',
    'menu_footer_1_title' => 'Разделы',
    'menu_footer_2_root_type' => 'footer_info',
    'menu_footer_2_title' => 'Информация',
    'layout_excluded_pages' => [
        '/',
        '/news',
        '/news/',
        '/contacts',
        '/contacts/',
    ],
    'title_excluded_pages' => [
        '/',
        '/news/*',
        '!/news/category/*',
    ],

    'logo' => 'logo_csr.png',
    'national_project_logo' => 'economics_of_data.png',
    'national_project_logo_alt' => 'Символ проекта «Экономика данных и цифровая трансформация государства»',
    'national_project_url' => 'https://digital.gov.ru/target/naczionalnyj-proekt-ekonomika-dannyh-i-czifrovaya-transformacziya-gosudarstva',

    'copyright_year_from' => 2025,
    'developer_name' => 'КОГБУ «Центр стратегического развития информационных ресурсов и систем управления»',
    'developer_url' => 'https://csr43.ru/',
    'developer_logo' => 'logo_csr.png',
];

const SITE_INFO_CONFIG = 'csr43_site_light';

if (!function_exists('get_info') && !function_exists('get_info_absolute_url')) {
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
}
