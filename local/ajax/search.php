<?
/*
 * Результаты быстрого поиска в шапке
 *
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent("bitrix:search.page", "ajax", Array(
    "AJAX_MODE" => "Y",	// Включить режим AJAX
    "AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
    "AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
    "AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
    "AJAX_OPTION_STYLE" => "Y",	// Включить подгрузку стилей
    "CACHE_TIME" => "3600",	// Время кеширования (сек.)
    "CACHE_TYPE" => "A",	// Тип кеширования
    "CHECK_DATES" => "N",	// Искать только в активных по дате документах
    "DEFAULT_SORT" => "rank",	// Сортировка по умолчанию
    "DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под результатами
    "DISPLAY_TOP_PAGER" => "N",	// Выводить над результатами
    "FILTER_NAME" => "",	// Дополнительный фильтр
    "NO_WORD_LOGIC" => "N",	// Отключить обработку слов как логических операторов
    "PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
    "PAGER_TEMPLATE" => "",	// Название шаблона
    "PAGER_TITLE" => "Результаты поиска",	// Название результатов поиска
    "PAGE_RESULT_COUNT" => "7",	// Количество результатов на странице
    "RESTART" => "Y",	// Искать без учета морфологии (при отсутствии результата поиска)
    "SHOW_WHEN" => "N",	// Показывать фильтр по датам
    "SHOW_WHERE" => "N",	// Показывать выпадающий список "Где искать"
    "USE_LANGUAGE_GUESS" => "Y",	// Включить автоопределение раскладки клавиатуры
    "USE_SUGGEST" => "N",	// Показывать подсказку с поисковыми фразами
    "USE_TITLE_RANK" => "N",	// При ранжировании результата учитывать заголовки
    "arrFILTER" => array(0=>"iblock_catalog",),
    "arrFILTER_iblock_catalog" => array(0=>"14",),
    "COMPONENT_TEMPLATE" => ".default"
),
    false
);?>