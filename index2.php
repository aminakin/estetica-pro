<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("description", "Профессиональные материалы для маникюра и наращивания ресниц. Известные и востребованные бренды по официальным ценам производителей. Отправляем в любой город РФ. Быстрая онлайн-оплата заказа на сайте.");
$APPLICATION->SetTitle("Эстетика-PRO");
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/scrollpage.js');
?>
<?$GLOBALS['sliderFilter']=['!PROPERTY_SLIDER'=>false, '>=PROPERTY_DATE_END_SALE'=>ConvertDateTime(date('d.m.Y'), "YYYY-MM-DD")." 00:00:00"]?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>