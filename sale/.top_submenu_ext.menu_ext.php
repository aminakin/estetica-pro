<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

$aMenuLinksExt = Array(
    Array(
        "Маникюр", 
        "/sale/sale-nail/", 
        Array(), 
        Array(), 
        "" 
    ),
    Array(
        "Наращивание ресниц, брови", 
        "/sale/sale-lash/",
        Array(), 
        Array(), 
        "" 
    )
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>