<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Page\Asset;

if ($arParams['GUEST_MODE'] !== 'Y')
{
	Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
	Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
}
$this->addExternalCss("/bitrix/css/main/bootstrap.css");

CJSCore::Init(array('clipboard', 'fx'));

$APPLICATION->SetTitle("");

if (!empty($arResult['ERRORS']['FATAL']))
{
	foreach ($arResult['ERRORS']['FATAL'] as $error)
	{
		ShowError($error);
	}

	$component = $this->__component;

	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
	{
		$APPLICATION->AuthForm('', false, false, 'N', false);
	}
}
else
{
	if (!empty($arResult['ERRORS']['NONFATAL']))
	{
		foreach ($arResult['ERRORS']['NONFATAL'] as $error)
		{
			ShowError($error);
		}
	}
	?><?if ($arResult['CANCELED'] !== 'Y'){?>
	<div class="sale-order-detail">
			<div class="sale-order-detail-general-item">
				<div class="sale-order-detail-order-numder">
					<?= Loc::getMessage('SPOD_SUB_ORDER_TITLE', array(
						"#ACCOUNT_NUMBER#"=> htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"]),
						"#DATE_ORDER_CREATE#"=> $arResult["DATE_INSERT_FORMATED"]
					))?>
				</div>
				<div class="sale-order-detail-order-status">
					<?echo htmlspecialcharsbx($arResult["STATUS"]["NAME"]);?>
				</div>
			</div>
			<div class="sale-order-detail-about-order">
				<div class="sale-order-detail-about">
					<div class="personal-wrap secrion-about">
						<div class="table-title">
							<?= Loc::getMessage('SPOD_PERSONAL')?>
						</div>
						<div class="table-value">
							<div class="personal_name">	
								<? $fio = [];
								 foreach ($arResult["ORDER_PROPS"] as $property){
									if($property['ID'] == 35){ $fio[] = $property['VALUE'];} 
									if($property['ID'] == 34){ $fio[] = $property['VALUE'];} 
									if($property['ID'] == 36){ $fio[] = $property['VALUE'];}
								}
								echo implode(" ", $fio);?>
							</div>
							<div  class="personal_email">	
								<? foreach ($arResult["ORDER_PROPS"] as $property){
									if($property['ID'] == 3){ $phone = $property['VALUE'];} 
								}
								echo $phone;?>
							</div>
							<div  class="personal_phone">
								<?foreach ($arResult["ORDER_PROPS"] as $property){
									if($property['ID'] == 2){ $email = $property['VALUE'];} 
								}
								echo $email;?>
							</div>
						</div>
					</div>
					<div class="sale-order-detail-payment  secrion-about">
						<div class="table-title">
							<?= Loc::getMessage('SPOD_DELIVERY')?>
						</div>
						<div class="table-value">
							<?foreach ($arResult['SHIPMENT'] as $shipment){?>
								<?= htmlspecialcharsbx($shipment["DELIVERY_NAME"]) ?>
							<?}?>
						</div>
					</div>
					<div class="sale-order-detail-adress  secrion-about">
						<div class="table-title">
							<?= Loc::getMessage('SPOD_ADDRESS')?>
						</div>
						<div class="table-value">
							<? $adress = [];
							 foreach ($arResult["ORDER_PROPS"] as $property){
								if($property['ID'] == 5){ $adress[] = $property['VALUE'];} 
								if($property['ID'] == 6){ $adress[] = $property['VALUE'];} 
								if($property['ID'] == 7){ $adress[] = $property['VALUE'];}
								if($property['ID'] == 8){ $adress[] = $property['VALUE'];}
							}
							echo implode(" ", $adress);?>
						</div>
					</div>	
					<div class="sale-order-detail-delivery-cost secrion-about">
						<div class="table-title">
							<?= Loc::getMessage('SPOD_DELIVERY_PRICE')?>
						</div>
						<div class="table-value">
							<? echo $shipment['PRICE_DELIVERY_FORMATED'];?>
						</div>
					</div>
					<div class="sale-order-detail-payment secrion-about">
						<div class="table-title">
							<?= Loc::getMessage('SPOD_VARIANT_PAYMENT')?>
						</div>
						<div class="table-value">
							<?foreach ($arResult['PAYMENT'] as $payment){?>
								<?= $payment['PAY_SYSTEM_NAME']?>
							<?}?>
						</div>
					</div>
					<div class="sale-order-detail-full_price secrion-about">
						<div class="table-title">
							<?= Loc::getMessage('SPOD_PRICE_PRODUCT')?>
						</div>
						<div class="table-value">
							<?foreach ($arResult['PAYMENT'] as $payment){?>
								<?=$payment['PRICE_FORMATED']?>
							<?}?>
						</div>
					</div>
				</div>
			</div>
			<div class="sale-order-detail-payment-options-order-content">
				<div class="sale-order-detail-order-section header">
					<div class="sale-order-detail-order-item-td">
						<div class="sale-order-detail-order-item-td-title">
							<?= $arResult['TITLE_COUNT'];?>
						</div>
					</div>
					<div class="sale-order-detail-order-item-td">
						<div class="sale-order-detail-order-item-td-title">
							<?= Loc::getMessage('SPOD_QUANTITY')?>
						</div>
					</div>
					<div class="sale-order-detail-order-item-td">
						<div class="sale-order-detail-order-item-td-title">
							<?= Loc::getMessage('SPOD_ORDER_PRICE')?>
						</div>
					</div>
				</div>
				<? foreach ($arResult['BASKET'] as $basketItem){?>
					<div class="sale-order-detail-order-item-tr">
						<div class="sale-order-detail-order-item-td">
							<div class="sale-order-detail-order-item-block">
								<div class="sale-order-detail-order-item-img-block">
									<a href="<?=$basketItem['DETAIL_PAGE_URL']?>">

										<?if($basketItem['PICTURE']['SRC'] <> ''){
											$imageSrc = $basketItem['PICTURE']['SRC'];
										}else{
											$imageSrc = $this->GetFolder().'/images/no_photo.png';
										}?>
										<div class="sale-order-detail-order-item-imgcontainer"
											 style="background-image: url(<?=$imageSrc?>);
												 background-image: -webkit-image-set(url(<?=$imageSrc?>) 1x,
												 url(<?=$imageSrc?>) 2x)">
										</div>
									</a>
								</div>
								<div class="sale-order-detail-order-item-content">
									<div class="sale-order-detail-order-item-title">
										<a href="<?=$basketItem['DETAIL_PAGE_URL']?>">
											<?=htmlspecialcharsbx($basketItem['NAME'])?>
										</a>
									</div>
									<?
									if (isset($basketItem['PROPS']) && is_array($basketItem['PROPS']))
									{
										foreach ($basketItem['PROPS'] as $itemProps)
										{
											?>
											<div class="sale-order-detail-order-item-color">
											<span class="sale-order-detail-order-item-color-name">
												<?=htmlspecialcharsbx($itemProps['NAME'])?>:</span>
												<span class="sale-order-detail-order-item-color-type">
												<?=htmlspecialcharsbx($itemProps['VALUE'])?></span>
											</div>
											<?
										}
									}
									?>
								</div>
							</div>
						</div>
						<div class="sale-order-detail-order-item-td">
							<div class="sale-order-detail-order-item-td-text">
								<?=$basketItem['QUANTITY']?>
							</div>
						</div>
						<div class="sale-order-detail-order-item-td">
							<div class="sale-order-detail-order-item-td-text">
								<?=$basketItem['FORMATED_SUM']?>
							</div>
						</div>
					</div>
				<?}?>			
			</div>
	</div>
	<div class="order-cancel"><a href="/personal/orders/cancel/<?= $arResult['ACCOUNT_NUMBER'];?>?CANCEL=Y"><?= Loc::getMessage('SPOD_ORDER_CANCEL_CURRENT')?></a></div>
	<?}else{?>

	<?}?>
	<?
	$javascriptParams = array(
		"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
		"templateFolder" => CUtil::JSEscape($templateFolder),
		"templateName" => $this->__component->GetTemplateName(),
		"paymentList" => $paymentData,
		"returnUrl" => $arResult['RETURN_URL'],
	);
	$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
	

	?>
	<script>
		BX.Sale.PersonalOrderComponent.PersonalOrderDetail.init(<?=$javascriptParams?>);
	</script>
<?
}
?>
<div class="order-cancel-popup">
	<div class="title-popup"><?= Loc::getMessage('SPOD_ORDER_CANCEL_POPUP_TITLE', array(
		"#ACCOUNT_NUMBER#"=> htmlspecialcharsbx($arResult["ACCOUNT_NUMBER"])
	))?>
	</div>
	<div class="text-popup">
		<?= Loc::getMessage('SPOD_ORDER_CANCEL_TEXT')?>
	</div>
	<div class="button-popup">
		<a href="/personal/orders/cancel/<?= $arResult['ACCOUNT_NUMBER'];?>?CANCEL=Y"><?= Loc::getMessage('SPOD_ORDER_CANCEL')?>
	</div>
	<div class="no-cancel-popup">
		<?= Loc::getMessage('SPOD_ORDER_NOCANCEL')?>
	</div>
	<div class="close-popup">
		<span></span>
		<span></span>
	</div>
</div>
<div class="shadow-cancel"></div>