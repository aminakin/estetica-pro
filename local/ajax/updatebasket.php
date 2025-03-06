<?php
	use Bitrix\Main\Loader;

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	global $APPLICATION;

Loader::includeModule('catalog');
Loader::includeModule('sale');

print_r($_REQUEST);

if (isset($_REQUEST['id']) && intval($_REQUEST['id']) > 0) {
	$productId = intval($_REQUEST['id']);
} else {
	die();
}
if($_REQUEST['act'] == 'remove') {
	CSaleBasket::Delete($productId);
} 
else {
	if (isset($_REQUEST['quantity']) && intval($_REQUEST['quantity']) > 0) {
		$qty = intval($_REQUEST['quantity']);
		if($_REQUEST['act'] == 'minus'){
			$qty--;
		}
		elseif($_REQUEST['act'] == 'plus'){
			$qty++;
		}
	} else {
		$qty = 1;
	}
	$arFields = array(
			"QUANTITY" => $qty,
			"DELAY" => "N"
	);
	if(CSaleBasket::Update($productId, $arFields)){
		die('success');
	}
	else {
		die('unsuccess');
	}
}
