<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

$aMenuLinksExt = Array(
    Array(
        "Маникюр", 
        "/new/new-nail/", 
        Array(), 
        Array(), 
        "" 
    ),
    Array(
        "Наращивание ресниц, брови", 
        "/new/new-lash/",
        Array(), 
        Array(), 
        "" 
    )
);

$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>