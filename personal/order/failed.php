<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказ не оплачен");
?>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . getLocalPath('/modules/rover.tinkoff/service/result.php');
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>