<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * компонент получает логотип из справочника Бренда по id
*/
use Bitrix\Main\Loader;
Loader::includeModule('iblock');

$arFilter = Array("IBLOCK_ID"=>$arParams['IBLOCK_ID'], "ID"=>$arParams['BRAND_ID']);
$res = CIBlockElement::GetList(Array(), $arFilter);

if ($ob = $res->GetNextElement()){
    
    $arFields = $ob->GetFields(); 
    $image = CFile::GetPath($arFields['PREVIEW_PICTURE']);
}
$arResult['BRAND_NAME'] = $arFields['NAME'];
$arResult['BRAND_LOGO'] = $image;

$this->IncludeComponentTemplate();
?>