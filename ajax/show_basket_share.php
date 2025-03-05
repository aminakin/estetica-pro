<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Sale;
Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");

	$fuser = Sale\Fuser::getId();
	$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
	$basketItems = $basket->getBasketItems();

	foreach ($basket as $basketItem) {
		$basketString .= '&'.$basketItem->getProductId().'='.$basketItem->getQuantity();
	}
	echo 'https://estetica-pro.ru/personal/cart/?addbasketproducts=y'. $basketString;
?>
