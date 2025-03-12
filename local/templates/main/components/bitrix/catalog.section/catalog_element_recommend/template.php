<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
?>
<?
if (arResult["ITEMS"])
{?>
    <section class="additional">
        <h2 class='catalog-block-header'><?=($arParams['TITLE_H1'])?$arParams['TITLE_H1']:GetMessage('TITLE')?></h2>
        <div class="clear"></div>
        <div class="catalog-list">
            <?
            $prop_val = [];
            foreach($arResult["ITEMS"] as $arItem):?>

                <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>

                <div class="add-block" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <?if ($arResult['ICONS'][$arItem['PROPERTIES']['ICON']['VALUE']])
                    {?>
                        <div class="act" style="background: #<?=$arResult['ICONS'][$arItem['PROPERTIES']['ICON']['VALUE']]['COLOR']?>;"><?=$arResult['ICONS'][$arItem['PROPERTIES']['ICON']['VALUE']]['NAME']?></div>
                    <?}?>
                    <?if ($arItem["RESIZED_IMAGE_SMALL"]["src"])
                    {?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="img"><img src="<?=$arItem["RESIZED_IMAGE_SMALL"]["src"]?>" alt=""></a>
                    <?}
                    else
                    {?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="img"><img src="<?=SITE_TEMPLATE_PATH?>/images/icons/nophoto220x205.png" alt=""></a>
                    <?}?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="name"><?=$arItem['NAME']?></a>
                    <div class="price"><?=format_price($arItem['MIN_PRICE']['VALUE'])?>&nbsp;Р</div>
                    <?if ($arItem['CAN_BUY']=='Y')
					{?>
						<input type="number" autocomplete="off" min="1" value="1" class="qty" />
						<a href="#" <?if($arItem['IBLOCK_SECTION_ID'] == 1190):?>onclick="ym(51439306,'reachGoal','target')"<?endif;?> class="whiteBtn btn btn-link buyBtn" data-id="<?=$arItem['ID']?>"><?=GetMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET')?></a>
					<?}
					else
					{?>
						<div class="nogoods">Товар отсутсвует на складе</div>
					<?}?>
                    <?/*<div class="hover">
                        <div class="price"><?=format_price($arItem['MIN_PRICE']['VALUE'])?>&nbsp;<span class="rub">Р</span></div>
                        <?if ($arItem['CAN_BUY']=='Y')
						{?>
							<input type="number" autocomplete="off" min="1" value="1" class="qty" />
						<?}?>
                        <a href="#" class="whiteBtn quick" data-id="<?=$arItem['ID']?>"><?=GetMessage('Q')?></a>
                        <?if ($arItem['CAN_BUY']=='Y')
						{?>
							<a href="#" class="whiteBtn buyBtn" data-id="<?=$arItem['ID']?>"><?=GetMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET')?></a>
							<a href="#" class="inFavorite invert favoriteBtn" data-favorite-id="<?=$arItem['ID']?>"></a>
						<?}
						else
						{?>
							<div class="nogoods">Товар отсутсвует на складе</div>
						<?}?>
                    </div>*/?>
                </div>
                <?/*<div class="quick_view" data-qid="<?=$arItem['ID'];?>">
                    <a href="#" class="qclose"></a>
                    <div class="img">
                        <?if ($arResult['ICONS'][$arItem['PROPERTIES']['ICON']['VALUE']])
                        {?>
                            <div class="act" style="background: #<?=$arResult['ICONS'][$arItem['PROPERTIES']['ICON']['VALUE']]['COLOR']?>;"><?=$arResult['ICONS'][$arItem['PROPERTIES']['ICON']['VALUE']]['NAME']?></div>
                        <?}?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=($arItem["RESIZED_IMAGE"]["src"])?$arItem["RESIZED_IMAGE"]["src"]:$arItem["RESIZED_IMAGE_SMALL"]["src"]?>" alt=""></a>
                    </div>
                    <div class="other">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="name"><?=$arItem['NAME']?></a>
                        <div class="price"><?=format_price($arItem['MIN_PRICE']['VALUE'])?>&nbsp;<span class="rub">Р</span></div>
                        <?if ($arItem['CAN_BUY']=='Y')
						{?>
							<input type="number" autocomplete="off" min="1" value="1"/>
						<?}?>
                        <div class="clear"></div>
                        <div class="buttons">
							<?if ($arItem['CAN_BUY']=='Y')
							{?>
								<a href="#" class="blackBtn buyBtn" data-id="<?=$arItem['ID']?>"><?=GetMessage('CT_BCS_TPL_MESS_BTN_ADD_TO_BASKET')?></a>
								<a href="#" class="inFavorite favoriteBtn" data-favorite-id="<?=$arItem['ID']?>"></a>
							<?}
							else
							{?>
								<div class="nogoods">Товар отсутсвует на складе</div>
							<?}?>
                        </div>
                        <div>
                            <table class="quicktable">
                                <?foreach ($arItem['PROPERTIES'] as $prop)
                                {
                                    if ($prop['SORT']<=500) continue;
                                    if (!$prop['VALUE']) continue;
                                    if (is_array($prop['VALUE']))
                                    {
                                        foreach ($prop['VALUE'] as $key=>$p)
                                        {?>
                                            <tr>
                                                <td><?=$prop['DESCRIPTION'][$key]?></td>
                                                <td>
                                                    <?
                                                    echo $p;
                                                    ?>
                                                </td>
                                            </tr>
                                        <?}
                                    }
                                    else
                                    {?>
                                        <tr>
                                            <td><?=$prop['NAME']?></td>
                                            <td>
                                                <?
                                                if ($prop['PROPERTY_TYPE']!='E') echo $prop['VALUE'];
                                                else{
                                                    if (!$prop_val[$prop['VALUE']])
                                                    {
                                                        $res_prop = CIBlockElement::GetByID($prop['VALUE']);
                                                        if ($rp = $res_prop->GetNext())
                                                        {
                                                            $prop_val[$prop['VALUE']] = $rp['NAME'];
                                                        }
                                                    }
                                                    echo $prop_val[$prop['VALUE']];
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?}
                                }?>
                            </table>
                        </div>
                    </div>
                </div>*/?>
            <?endforeach;?>
            <div class="clear"></div>
        </div>
    </section>
<?}?>