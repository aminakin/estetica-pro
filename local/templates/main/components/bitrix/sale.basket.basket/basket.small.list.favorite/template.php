<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixBasketComponent $component */

/*echo '<pre>';
print_r($arParams);
echo '</pre>';
*/

//$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';

global $hrefDoc;
$curPage = $hrefDoc.'?'.$arParams["ACTION_VARIABLE"].'=';

$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);


$normalCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
?>

<form class="basket" name="basket_form" id="basket_form" action="#">

    <a href="#" class="close"></a>
    <div class="h2 align-left">Ваше избранное<span class="hideMobile">(<span class='fcount'><?=$normalCount?></span>)</span> </div>

    <?
	$sect = [];
	foreach ($arResult["GRID"]["ROWS"] as $k => $arItem){?>
    	<?if ($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y"):?>
    	<?$kol = $kol + 1;?>
		<div class="basket-item <?if($kol == $normalCount){?>last<?}?>" id="<?=$arItem['ID']?>" data-item-name="<?=$arItem['NAME']?>" data-item-brand="" data-item-price="<?=$arItem['PRICE']?>" data-item-currency="RUB">
	        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="img">
	        	<?if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
					$url = $arItem["PREVIEW_PICTURE_SRC"];
				elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
					$url = $arItem["DETAIL_PICTURE_SRC"];
				else:
					$url = $templateFolder."/images/no_photo.png";
				endif;?>

	            <img src="<?=$url?>" alt="<?=$arItem['NAME']?>"/>
	        </a>
	        <a href="#" class="delete delete_fav" data-id="<?=$arItem['ID']?>" data-type="fav" data-product="<?=$arItem['PRODUCT_ID']?>"></a>
	        <div class="text">
	            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="title"><?=$arItem['NAME']?></a>
				<?$res = CIBlockElement::GetByID($arItem['PRODUCT_ID']);
				if ($el = $res->GetNext())
				{
					if (!$sect[$el['IBLOCK_SECTION_ID']])
					{
						$ress = CIBlockSection::GetByID($el['IBLOCK_SECTION_ID']);
						if ($sel = $ress->GetNext())
						{
							$sect[$sel['ID']] = $sel['NAME'];
						}
					}
				}?>
	            <div class="cat"><?=$sect[$el['IBLOCK_SECTION_ID']]?></div>
	            <div class="price"><?=format_price($arItem['PRICE'])?>&nbsp;<span class="rub">Р</span></div>
                <?if ($arItem['CAN_BUY']=='Y')
				{?>
					<a href="#" class="blackBtn buyBtn" data-id="<?=$arItem["PRODUCT_ID"]?>">Купить</a>
				<?}
				else
				{?>
					<div class="nogoods">Товар отсутсвует на складе</div>
				<?}?>
	        </div>

	        <div class="clear"></div>
	    </div>
	    <?endif;?>
    <?}?>
   
    

</form>

<?




/*

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
);
$this->addExternalCss($templateData['TEMPLATE_THEME']);

$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"],
	'EVENT_ONCHANGE_ON_START' => (!empty($arResult['EVENT_ONCHANGE_ON_START']) && $arResult['EVENT_ONCHANGE_ON_START'] === 'Y') ? 'Y' : 'N',
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
);
?>
<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>;
</script>
<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");

if($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP')
{
	$APPLICATION->IncludeComponent(
		"bitrix:sale.gift.basket",
		".default",
		array(
			"SHOW_PRICE_COUNT" => 1,
			"PRODUCT_SUBSCRIPTION" => 'N',
			'PRODUCT_ID_VARIABLE' => 'id',
			"PARTIAL_PRODUCT_PROPERTIES" => 'N',
			"USE_PRODUCT_QUANTITY" => 'N',
			"ACTION_VARIABLE" => "actionGift",
			"ADD_PROPERTIES_TO_BASKET" => "Y",

			"BASKET_URL" => $APPLICATION->GetCurPage(),
			"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
			"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

			'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
			'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
			'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
			'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
			'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
			'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
			'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
			'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
			'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
			'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
			'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
			'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

			"LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
		),
		false
	);
}

if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
{
	?>
	<div id="warning_message">
		<?
		if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				ShowError($v);
		}
		?>
	</div>
	<?

	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? 'style="display:none;"' : '';

	$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$delayHidden = ($delayCount == 0) ? 'style="display:none;"' : '';

	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$subscribeHidden = ($subscribeCount == 0) ? 'style="display:none;"' : '';

	$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
	$naHidden = ($naCount == 0) ? 'style="display:none;"' : '';

	foreach (array_keys($arResult['GRID']['HEADERS']) as $id)
	{
		$data = $arResult['GRID']['HEADERS'][$id];
		$headerName = (isset($data['name']) ? (string)$data['name'] : '');
		if ($headerName == '')
			$arResult['GRID']['HEADERS'][$id]['name'] = GetMessage('SALE_'.$data['id']);
		unset($headerName, $data);
	}
	unset($id);

	?>
	
		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form" class="basket">


			<div id="basket_form_container">
				<div class="bx_ordercart <?=$templateData['TEMPLATE_CLASS']; ?>">
					<div class="bx_sort_container">
						
						<div class="h2 align-left">Ваша корзина<span class="hideMobile">(<?=$normalCount?>)</span> </div>
						<?/*<span><?=GetMessage("SALE_ITEMS")?></span>
						<a href="javascript:void(0)" id="basket_toolbar_button" class="current" onclick="showBasketItemsList()"><?=GetMessage("SALE_BASKET_ITEMS")?><div id="normal_count" class="flat" style="display:none">&nbsp;(<?=$normalCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_delayed" onclick="showBasketItemsList(2)" <?=$delayHidden?>><?=GetMessage("SALE_BASKET_ITEMS_DELAYED")?><div id="delay_count" class="flat">&nbsp;(<?=$delayCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_subscribed" onclick="showBasketItemsList(3)" <?=$subscribeHidden?>><?=GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED")?><div id="subscribe_count" class="flat">&nbsp;(<?=$subscribeCount?>)</div></a>
						<a href="javascript:void(0)" id="basket_toolbar_button_not_available" onclick="showBasketItemsList(4)" <?=$naHidden?>><?=GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE")?><div id="not_available_count" class="flat">&nbsp;(<?=$naCount?>)</div></a>?>
					</div>
					<?
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delayed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");
					?>
				</div>
			</div>
			<input type="hidden" name="BasketOrder" value="BasketOrder" />
			<!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
		</form>

	<?

	if($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
	{
		?>
		<div style="margin-top: 35px;"><? $APPLICATION->IncludeComponent(
			"bitrix:sale.gift.basket",
			".default",
			array(
				"SHOW_PRICE_COUNT" => 1,
				"PRODUCT_SUBSCRIPTION" => 'N',
				'PRODUCT_ID_VARIABLE' => 'id',
				"PARTIAL_PRODUCT_PROPERTIES" => 'N',
				"USE_PRODUCT_QUANTITY" => 'N',
				"ACTION_VARIABLE" => "actionGift",
				"ADD_PROPERTIES_TO_BASKET" => "Y",

				"BASKET_URL" => $APPLICATION->GetCurPage(),
				"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
				"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

				"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

				'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
				'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
				'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
				'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
				'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
				'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
				'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
				'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
				'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
				'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
				'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
				'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
				'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
				'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

				"LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
			),
			false
		); ?>
		</div><?
	}
}
else
{
	ShowError($arResult["ERROR_MESSAGE"]);
}
*/?>