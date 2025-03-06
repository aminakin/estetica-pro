<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);
define('NOT_CHECK_PERMISSIONS', true);

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

global $USER;
$userID = $USER->getid();
$result = [];

if (isset($_POST['orderID']) && $_POST['orderID'] !== '') {
    $APPLICATION->IncludeComponent(
        "bitrix:sale.personal.order.detail",
        "ajax",
        array(
            "ACTIVE_DATE_FORMAT" => "d.m.Y",
            "CACHE_GROUPS" => "Y",
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CUSTOM_SELECT_PROPS" => array(""),
            "DISALLOW_CANCEL" => "N",
            "ID" => $_POST['orderID'],
            "PATH_TO_CANCEL" => "",
            "PATH_TO_COPY" => "",
            "PATH_TO_LIST" => "",
            "PATH_TO_PAYMENT" => "payment.php",
            "PICTURE_HEIGHT" => "110",
            "PICTURE_RESAMPLE_TYPE" => "1",
            "PICTURE_WIDTH" => "110",
            "PROP_1" => array(""),
            "PROP_2" => array(""),
            "REFRESH_PRICES" => "N",
            "RESTRICT_CHANGE_PAYSYSTEM" => array("0"),
            "SET_TITLE" => "Y"
        )
    );
} else {
    echo 'Error!';
}