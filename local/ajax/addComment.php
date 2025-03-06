<?
/*
 * Добавление комментариев
 *
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent("bitrix:iblock.element.add.form", "comment", Array(
    "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",	// * дата начала *
    "CUSTOM_TITLE_DATE_ACTIVE_TO" => "",	// * дата завершения *
    "CUSTOM_TITLE_DETAIL_PICTURE" => "",	// * подробная картинка *
    "CUSTOM_TITLE_DETAIL_TEXT" => "",	// * подробный текст *
    "CUSTOM_TITLE_IBLOCK_SECTION" => "",	// * раздел инфоблока *
    "CUSTOM_TITLE_NAME" => "Ваше имя",	// * наименование *
    "CUSTOM_TITLE_PREVIEW_PICTURE" => "",	// * картинка анонса *
    "CUSTOM_TITLE_PREVIEW_TEXT" => "комментарий",	// * текст анонса *
    "CUSTOM_TITLE_TAGS" => "",	// * теги *
    "DEFAULT_INPUT_SIZE" => "30",	// Размер полей ввода
    "DETAIL_TEXT_USE_HTML_EDITOR" => "N",	// Использовать визуальный редактор для редактирования подробного текста
    "ELEMENT_ASSOC" => "CREATED_BY",	// Привязка к пользователю
    "GROUPS" => array(	// Группы пользователей, имеющие право на добавление/редактирование
        0 => "2",
    ),
    "IBLOCK_ID" => "8",	// Инфоблок
    "IBLOCK_TYPE" => "comments",	// Тип инфоблока
    "LEVEL_LAST" => "Y",	// Разрешить добавление только на последний уровень рубрикатора
    "LIST_URL" => "",	// Страница со списком своих элементов
    "MAX_FILE_SIZE" => "0",	// Максимальный размер загружаемых файлов, байт (0 - не ограничивать)
    "MAX_LEVELS" => "100000",	// Ограничить кол-во рубрик, в которые можно добавлять элемент
    "MAX_USER_ENTRIES" => "100000",	// Ограничить кол-во элементов для одного пользователя
    "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",	// Использовать визуальный редактор для редактирования текста анонса
    "PROPERTY_CODES" => array(	// Свойства, выводимые на редактирование
        0 => "NAME",
        1 => "PREVIEW_TEXT",
        2 => "41"
    ),
    "PROPERTY_CODES_REQUIRED" => array(	// Свойства, обязательные для заполнения
        0 => "NAME",
        1 => "PREVIEW_TEXT",
    ),
    "RESIZE_IMAGES" => "N",	// Использовать настройки инфоблока для обработки изображений
    "SEF_MODE" => "N",	// Включить поддержку ЧПУ
    "STATUS" => "ANY",	// Редактирование возможно
    "STATUS_NEW" => "NEW",	// Деактивировать элемент
    "USER_MESSAGE_ADD" => "Сообщение успешно добавлено. Оно станет доступно для просмотра после проверки модератором.",	// Сообщение об успешном добавлении
    "USER_MESSAGE_EDIT" => "Сообщение успешно добавлено. Оно станет доступно для просмотра после проверки модератором.",	// Сообщение об успешном сохранении
    "USE_CAPTCHA" => "Y",	// Использовать CAPTCHA
    "AJAX_MODE" => "Y"
),
    false
);?>