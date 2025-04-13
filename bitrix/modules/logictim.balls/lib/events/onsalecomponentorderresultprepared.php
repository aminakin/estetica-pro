<?
namespace Logictim\Balls\Events;


\Bitrix\Main\Loader::includeModule("logictim.balls");

class OnSaleComponentOrderResultPrepared {
	
	
	static function OnSaleComponentOrderResultPrepared($order, &$arUserResult, $request, &$arParams, &$arResult)
	{
			\Logictim\Balls\Ajax\SaleOrderAjax::OnSaleComponentOrderResultPrepared($order, $arUserResult, $request, $arParams, $arResult);
	}
}

?>