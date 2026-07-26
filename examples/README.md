# Примеры вызова компонентов

В каталоге находится по одному каноническому примеру для каждого типа
компонента, представленного в репозитории.

Параметры в каждом вызове разделены на три группы:

1. **Фиксированные** — безопасные и проектные настройки, которые обычно не
   следует менять между размещениями компонента.
2. **Определяет администратор** — источник данных, раздел, свойства, шаблон,
   количество элементов и другие параметры, зависящие от конкретной страницы.
3. **Не оказывают эффекта** — параметры, отключённые выбранной конфигурацией
   либо не используемые выбранным локальным шаблоном. Они оставлены в примере,
   чтобы результат аудита параметров был явным.

Значения вроде `content`, `8`, `news-item-code` и `section_12` являются
примерами. Перед размещением администратор обязан заменить их значениями
конкретной установки.

Штатные компоненты сверены с официальной документацией:

- https://dev.1c-bitrix.ru/user_help/components/sluzhebnie/navigation/breadcrumb.php
- https://dev.1c-bitrix.ru/user_help/components/sluzhebnie/navigation/menu.php
- https://dev.1c-bitrix.ru/user_help/components/content/catalog/catalog_section_list.php
- https://dev.1c-bitrix.ru/user_help/components/content/articles_and_news/news_detail.php
- https://dev.1c-bitrix.ru/user_help/components/content/articles_and_news/news_list.php
- https://dev.1c-bitrix.ru/user_help/components/sluzhebnie/search/search_page.php

Для собственных компонентов источником являются их `.parameters.php`,
`component.php`/`class.php` и шаблоны в этом репозитории.

`bitrix:system.pagenavigation` напрямую на странице не вызывается: его шаблон
выбирается параметром `PAGER_TEMPLATE` родительского компонента. Поэтому
соответствующий пример показывает корректное подключение через `news.list`.
