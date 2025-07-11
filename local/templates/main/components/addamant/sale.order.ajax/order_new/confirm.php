<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var $APPLICATION CMain
 */
GLOBAL $USER;
 /*if ($USER->IsAdmin()){
 	?><pre><?
 	//var_export($arResult);
?></pre><?
 }*/
?>
<div class="comfirm_wrap">
	<? if($arResult["ORDER"]['CANCELED'] != 'Y'){?>
		<? if (!empty($arResult["ORDER"])): ?>
				<div class="confirm_thanks"><?= Loc::getMessage('SOA_THANKS')?></div>
				<div class="confirm_order"><?=Loc::getMessage("SOA_ORDER_SUC", array("#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"] ))?></div>
				<div class="comfirm_text"><?=Loc::getMessage('SOA_MANAGER')?></div>
		<? else: ?>
			<? $arResult["ORDER"]["ACCOUNT_NUMBER"] = '';?>
				<div class="confirm_order"><?=Loc::getMessage("SOA_ORDER_SUC_NO_AUTH")?></div>
				<div class="comfirm_text"><?=Loc::getMessage('SOA_MANAGER')?></div>
		<? endif;
	}else{?>
		<div class="confirm_order"><?=Loc::getMessage("SOA_ORDER_SUC_CANCEL", array("#ORDER_ID#" => $arResult["ORDER"]["ACCOUNT_NUMBER"] ))?></div>
		<div class="comfirm_text"><?=Loc::getMessage('SOA_MANAGER')?></div>
		<div class="order-cancel"><a href="/cart/order/?ID=<?=$arResult["ORDER"]["ACCOUNT_NUMBER"];?>&CANCEL=Y"><?=Loc::getMessage("SOA_TO_CATALOG")?></a></div>
	<?}?>
	<div class="off_vpn">
		<?=Loc::getMessage("SOA_OFF_VPN")?>
	</div>
</div>
<div class="wrapper-payment">
<?if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y'){
		if (!empty($arResult["PAYMENT"])){
			foreach ($arResult["PAYMENT"] as $payment){
				if ($payment["PAID"] != 'Y'){
					if (!empty($arResult['PAY_SYSTEM_LIST'])&& array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])){
						$arPaySystem = $arResult['PAY_SYSTEM_LIST_BY_PAYMENT_ID'][$payment["ID"]];
						if (empty($arPaySystem["ERROR"])){?>
										<div class="pay_name"><?=Loc::getMessage("SOA_PAY") ?></div>
										<?=CFile::ShowImage($arPaySystem["LOGOTIP"], 100, 100, "border=0\"", "", false) ?>
										<div class="paysystem_name"><?=$arPaySystem["NAME"] ?></div>
										<br/>
										<? if (strlen($arPaySystem["ACTION_FILE"]) > 0 && $arPaySystem["NEW_WINDOW"] == "Y" && $arPaySystem["IS_CASH"] != "Y"): ?>
											<?
											$orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
											$paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
											?>
											<script>
												window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
											</script>
										<?=Loc::getMessage("SOA_PAY_LINK", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&PAYMENT_ID=".$paymentAccountNumber))?>
										<? if (CSalePdf::isPdfAvailable() && $arPaySystem['IS_AFFORD_PDF']): ?>
										<br/>
											<?=Loc::getMessage("SOA_PAY_PDF", array("#LINK#" => $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$orderAccountNumber."&pdf=1&DOWNLOAD=Y"))?>
										<? endif ?>
										<? else: ?>
											<?=$arPaySystem["BUFFERED_OUTPUT"]?>
										<? endif ?>
							<?}else{?>
							<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
							<?}
					}else{?>
						<span style="color:red;"><?=Loc::getMessage("SOA_ORDER_PS_ERROR")?></span>
					<?}
				}
			}
		}
	}else{/*?>
		<br /><strong><?=$arParams['MESS_PAY_SYSTEM_PAYABLE_ERROR']?></strong>
	<?*/}?>
</div>
<?
if ($USER->IsAuthorized()){
	/*$ID = $arResult["ORDER"]["ACCOUNT_NUMBER"];
	$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.order.detail", 
		"template1", 
		array(
			"ACTIVE_DATE_FORMAT" => "d F Y",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "3600",
			"CACHE_TYPE" => "A",
			"CUSTOM_SELECT_PROPS" => array(
			),
			"DISALLOW_CANCEL" => "N",
			"ID" => $ID,
			"PATH_TO_CANCEL" => "",
			"PATH_TO_COPY" => "",
			"PATH_TO_LIST" => "",
			"PATH_TO_PAYMENT" => "payment.php",
			"PICTURE_HEIGHT" => "110",
			"PICTURE_RESAMPLE_TYPE" => "1", 
			"PICTURE_WIDTH" => "110",
			"PROP_1" => array(
			),
			"PROP_2" => array(
			),
			"REFRESH_PRICES" => "N",
			"RESTRICT_CHANGE_PAYSYSTEM" => array(
				0 => "0",
			),
			"SET_TITLE" => "N",
			"COMPONENT_TEMPLATE" => "template1"
		),
		false
	);*/
}
if($arResult["ORDER"]['CANCELED'] != 'Y'){
	if ($arParams["SET_TITLE"] == "Y"){
		$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_COMPLETE"));
	}	
}else{
	if ($arParams["SET_TITLE"] == "Y"){
		$APPLICATION->SetTitle(Loc::getMessage("SOA_ORDER_SUC_CANCELED"));
	}
}
//http://www.demo.estetica-pro.ru/cart/order/?ORDER_ID=5174&clear_cache=Y?>