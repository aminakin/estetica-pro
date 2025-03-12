<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach($arResult['SECTIONS'] as $key=>$item)
{
    if ($item['ELEMENT_CNT']<=0) unset($arResult['SECTIONS'][$key]);
}
?>