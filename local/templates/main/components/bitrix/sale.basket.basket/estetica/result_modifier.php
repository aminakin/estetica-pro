<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;

$defaultParams = array(
	'TEMPLATE_THEME' => 'blue'
);
$arParams = array_merge($defaultParams, $arParams);
unset($defaultParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME'])
	{
		$templateId = (string)Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', SITE_ID);
		$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? 'eshop_adapt' : $templateId;
		$arParams['TEMPLATE_THEME'] = (string)Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME'])
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';
$sect = [];
$additional = [];
$noadditional = [];
foreach ($arResult["GRID"]["ROWS"] as $k => $arItem)
{
    if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y") {
        $res = CIBlockElement::GetList([], ['ID' => $arItem['PRODUCT_ID']], false, false);
        $el = $res->GetNextElement();
        $f = $el->GetFields();
        $p = $el->GetProperties();
        if ($f['IBLOCK_SECTION_ID']) {
            if (!$sect[$f['IBLOCK_SECTION_ID']]) {
                $s = CIBlockSection::GetByID($f['IBLOCK_SECTION_ID']);
                $sd = $s->GetNext();
                $sect[$f['IBLOCK_SECTION_ID']] = $sd['NAME'];
            }
            $arResult["GRID"]["ROWS"][$k]['SECT'] = $sect[$f['IBLOCK_SECTION_ID']];
        } else $arResult["GRID"]["ROWS"][$k]['SECT'] = '';
        $noadditional[] = $arItem['PRODUCT_ID'];
        if ($p['ADDITIONAL']) {
            $additional = array_merge($additional, $p['ADDITIONAL']['VALUE']);
        }
    }
}
foreach ($additional as $i=>$v)
{
    if (in_array($v, $noadditional)) unset($additional[$i]);
}
$arResult['ADDITIONAL'] = $additional;
?>