<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;

$GLOBALS['APPLICATION']->RestartBuffer();

if ($_GET['adultView']) {
    setcookie("adultProduct", 1, time() + 60 * 60 * 24 * 365, '/');

    $result = 1;

    echo json_encode($result);
}
