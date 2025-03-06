    <?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @var array $arUrls */
/** @var array $arHeaders */
use Bitrix\Sale\DiscountCouponsManager;

if (!empty($arResult["ERROR_MESSAGE"]))
	ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
$bPriceType    = false;

if ($normalCount > 0):
?>
<div id="basket_items_list">
<div id="basket_items">
    <?
    foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):
        $arHeaders[] = $arHeader["id"];

        // remember which values should be shown not in the separate columns, but inside other columns
        if (in_array($arHeader["id"], array("TYPE")))
        {
            $bPriceType = true;
            continue;
        }
        elseif ($arHeader["id"] == "PROPS")
        {
            $bPropsColumn = true;
            continue;
        }
        elseif ($arHeader["id"] == "DELAY")
        {
            $bDelayColumn = true;
            continue;
        }
        elseif ($arHeader["id"] == "DELETE")
        {
            $bDeleteColumn = true;
            continue;
        }
        elseif ($arHeader["id"] == "WEIGHT")
        {
            $bWeightColumn = true;
        }
    endforeach;
    $skipHeaders = array('PROPS', 'DELAY', 'DELETE', 'TYPE');

    foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):

        if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"):
        ?>
            <div class="basket-item" id="<?=$arItem["ID"]?>"
             data-item-name="<?=$arItem["NAME"]?>"
             data-item-brand="<?=$arItem[$arParams['BRAND_PROPERTY']."_VALUE"]?>"
             data-item-price="<?=$arItem["PRICE"]?>"
             data-item-currency="<?=$arItem["CURRENCY"]?>"
            >
            <?if ($bDeleteColumn):
                ?>
                <a class="delete" href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>"
                   onclick="return deleteProductRow(this)"></a>
                <?
            endif;

            foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader):

                if (in_array($arHeader["id"], $skipHeaders)) // some values are not shown in the columns in this template
                    continue;

                if ($arHeader["id"] == "NAME"):

                    if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
                        $url = $arItem["PREVIEW_PICTURE_SRC"];
                    elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
                        $url = $arItem["DETAIL_PICTURE_SRC"];
                    else:
                        $url = $templateFolder."/images/no_photo.png";
                    endif;?>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="img">
                        <img src="<?=$url?>" alt=""/>
                    </a>
                    <div class="text">
                        <div class="bb1">
                            <a href="<?=$arItem["DETAIL_PAGE_URL"];?>" class="title"><?=$arItem["NAME"]?></a>
                            <div class="cat"><?=$arItem["SECT"] ?></div>
                <?
                elseif ($arHeader["id"] == "PRICE"):
                    ?>
                        <div class="price" id="current_price_<?=$arItem["ID"]?>"><?=format_price($arItem["PRICE"])?>&nbsp;<span class="rub">Р</span></div>
                    </div>
                    <?
                elseif ($arHeader["id"] == "QUANTITY"):
                ?>
                    <div class="bb2">
                        <div class="count">
                            <?
                            $ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
                            $useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
                            $useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
                            ?>
                            <input
                                    type="number" autocomplete="off" min="1"
                                    size="3"
                                    id="<?=$arItem["ID"]?>"
                                    name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
                                    maxlength="18"
                                    value="<?=$arItem["QUANTITY"]?>"
                                    <?/*onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', <?=$ratio?>, <?=$useFloatQuantityJS?>)"*/?>
                            >
                            <input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
                        </div>
                <?
                else:
                    if ($arHeader["id"] == "SUM"):
                    ?>
                        <div class="sum" id="sum_<?=$arItem["ID"]?>">
                        <?
                        echo format_price($arItem['PRICE']*$arItem['QUANTITY']).'&nbsp;&#8381';
                        ?>
                        </div>

                    <?
                    endif;
                endif;
            endforeach;
            ?>
                    </div>
                    </div>
                <div class="clear"></div>
            </div>
        <?
        endif;
    endforeach;
    ?>
	<input type="hidden" id="column_headers" value="<?=htmlspecialcharsbx(implode($arHeaders, ","))?>" />
	<input type="hidden" id="offers_props" value="<?=htmlspecialcharsbx(implode($arParams["OFFERS_PROPS"], ","))?>" />
	<input type="hidden" id="action_var" value="<?=htmlspecialcharsbx($arParams["ACTION_VARIABLE"])?>" />
	<input type="hidden" id="quantity_float" value="<?=($arParams["QUANTITY_FLOAT"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="auto_calculation" value="<?=($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y"?>" />

    <div class="all">
        <div class="btn-block">
        	        <?/*if($arResult["allSum"]>999):*/?>
                    <a href="javascript:void(0)" onclick="checkOut();" id="zakaz_razreshen" style="display:<?=($arResult["allSum"] > 999) ? '': 'none'?>"><span><?=GetMessage("SALE_ORDER")?></span></a>
                    <?/* else: */?>
                        <div id="zakaz_ne_razreshen" class="minsum" style="display:<?=($arResult["allSum"] > 999) ? 'none': ''?>">Минимальная сумма заказа 1000 рублей.<br/>Совершите покупок еще хотя бы на <?= (1000 - $arResult["allSum"])?> рублей. </div>
                    <?/*endif;*/?>
        	
            
        </div>
        <div class="sum">        	        	        	
            <span><?=GetMessage("SALE_TOTAL")?></span> <div id="allSum_FORMATED"><?=format_price( $arResult["allSum"])?>&nbsp;<span class="rub">Р</span></div>
        </div>
    </div>
</div>
</div>
<?
else:
?>
<div id="basket_items_list">
	<table>
		<tbody>
			<tr>
				<td style="text-align:center">
					<div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<?
endif;