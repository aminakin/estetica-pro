<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */
?>

<div class="product-item product-item-container"  id="<?= $item["ID"];?>">
    <div class="product-item-image-block">
        <? global $USER;?>
        <?if($USER->IsAuthorized()):?>
            <div class="product-item-favorite-image-block" data-item="<?= $item["ID"];?>">
                <?= classBuildSCG::buildSVG('favorite');?>
            </div>
        <?else:?>
            <a class="product-item-favorite-image-block no_auth_fav linkOpen" href="#no_fav_popup" data-item="<?= $item["ID"];?>">
                <?= classBuildSCG::buildSVG('favorite');?>
            </a>
        <?endif;?>
        <? if ($item['PROPERTIES']['ADULT_PRODUCT']['VALUE'] === 'Y' && empty($_COOKIE['adultProduct'])) {?>
                <div class="product-item-adult-block">
                    <div class="product-item-adult-block-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="9" viewBox="0 0 15 9" fill="none">
                            <path d="M14 1C13.6275 1.86444 13.1487 2.66609 12.5774 3.3815C12.4097 3.59081 12.2334 3.7913 12.0491 3.98232L13.5564 5.66997M11.3634 4.61383C10.3657 5.43548 9.17811 5.91824 7.94223 6.00453V8M7.05866 6.00453C5.82287 5.91853 4.63525 5.43612 3.63746 4.61482L2.07001 6.37175M2.95181 3.98331C2.76723 3.79228 2.59062 3.59179 2.42255 3.38249C1.85127 2.6671 1.37242 1.86545 1 1.00099" stroke="black" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="product-item-adult-block-text">Товары для взрослых</div>
                    <div onclick="openCloseModal(true)" class="product-item-adult-block-button">подтвердите ваш возраст</div>
                </div>
            <? } else { ?>
                <a class="product-item-image-wrapper"  href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $imgTitle ?>" data-entity="image-wrapper" >
                    <span
                        class="product-item-image-slider-slide-container slide"
                        id="<?= $itemIds['PICT_SLIDER'] ?>"
                        style="<?= ($showSlider ? '' : 'display: none') ?>;"
                        data-slider-interval="<?= $arParams['SLIDER_INTERVAL'] ?>"
                        data-slider-wrap="true">
                    <? if ($showSlider) {
                        foreach ($morePhoto as $key => $photo) {?>
                            <span class="product-item-image-slide item <?= ($key == 0 ? 'active' : '') ?>"
                                  style="background-image: url(<?= $photo['SRC'] ?>);">
                            </span>
                            <?}
                    } ?>
                    </span> 
                    <?$file = CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], array('width'=>300, 'height'=>300), BX_RESIZE_IMAGE_PROPORTIONAL, true);   ?>
                    <? if($file == false):
                        $file['src'] = '/local/templates/main/components/bitrix/catalog.section/addamant/images/no_photo_new.png';
                    endif;?>
                        <span class="product-item-image-original preload"
                              id="<?= $itemIds['PICT'] ?>"
                              style="background-image: url(<?= SITE_TEMPLATE_PATH ?>/images/icons/loader.svg)"
                              data-src = "<?= $file['src'] ?>">
                        </span>
                        <?
                        if ($item['SECOND_PICT']) {
                            $bgImage = !empty($item['PREVIEW_PICTURE_SECOND']) ? $item['PREVIEW_PICTURE_SECOND']['SRC'] : $item['PREVIEW_PICTURE']['SRC']; ?>
                            <span class="product-item-image-alternative preload" id="<?= $itemIds['SECOND_PICT'] ?>" 
                             data-src="<?=$file['src'];?>" style="background-image: url(<?= SITE_TEMPLATE_PATH ?>/images/icons/loader.svg);<?= ($showSlider ? 'display: none' : '') ?>">
                            </span>
                            <?
                        }

                        if ($item['LABEL']) {
                            ?>
                            <div class="product-item-label-text <?= $labelPositionClass ?>" id="<?= $itemIds['STICKER_ID'] ?>">
                                <?
                                if (!empty($item['LABEL_ARRAY_VALUE'])) {
                                    foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value) {
                                        ?>
                                        <div<?= (!isset($item['LABEL_PROP_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                    <span class="<?= $code ?>" title="<?= $value ?>">
                                        <?//= $code=='AKTSIONNYY'?'Sale%':$value ?>
                                        <?
                                        switch ($code) {
                                            case 'AKTSIONNYY':
                                                echo 'Sale%';
                                                break;
                                            case 'SKIDKA':
                                                echo 'New';
                                                break;
                                            default:
                                                echo $value;
                                                break;
                                        }
                                        ?>
                                    </span>
                                        </div>
                                        <?
                                    }
                                }
                                ?>
                            </div>
                            <?
                        }
                        ?>
                        <div class="product-item-image-slider-control-container" id="<?= $itemIds['PICT_SLIDER'] ?>_indicator"
                             style="<?= ($showSlider ? '' : 'display: none') ?>;">
                            <?
                            if ($showSlider) {
                                foreach ($morePhoto as $key => $photo) {
                                    ?>
                                    <div class="product-item-image-slider-control<?= ($key == 0 ? ' active' : '') ?>"
                                         data-go-to="<?= $key ?>"></div>
                                    <?
                                }
                            }
                            ?>
                        </div>
                        <?
                        if ($arParams['SLIDER_PROGRESS'] === 'Y') {
                            ?>
                            <div class="product-item-image-slider-progress-bar-container">
                                <div class="product-item-image-slider-progress-bar" id="<?= $itemIds['PICT_SLIDER'] ?>_progress_bar"
                                     style="width: 0;"></div>
                            </div>
                            <?
                        }
                        ?>
                    </a>
                    <script>
			        	$('[data-src]').lazy({
    		        	    afterLoad: function(element) {
    		        	        $(element).removeClass('preload');
    		        	    },
    		        	});
                    </script>
            <?}?>
    </div>
    <div class="product-item-title">
        <a href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $productTitle ?>" >
            <?
            /*$productTitle = trim($productTitle);
            $next_name = substr($productTitle, 1);
            $first_letter = substr($productTitle, 0, 1);
            $productTitle = strtolower($first_letter) . $next_name;*/
            ?>
            <?= $productTitle?>
        </a>
    </div>
    <div class="product-item-payment-block">


    </div>
    <?
    if (!empty($arParams['PRODUCT_BLOCKS_ORDER'])) {
        foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName) {
            switch ($blockName) {
                case 'price': ?>
                    <?if($item['PROPERTIES']['BEZNACHISLENIYABONUSA']['VALUE'] == 'Да'):?>
                    <div class="no-bonus-product">
                        <a class="linkOpen" href="#about_bonus"><?= GetMessage('CT_BCE_QUESTION'); ?><span>?</span></a>
                    </div>
                    <?endif;?>
                    <div class="product-item-info-container product-item-price-container price-block" data-entity="price-block">
                        <? if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y') {
                            ?>
                            <div class="product-item-label-ring <?= $discountPositionClass ?>" id="<?= $itemIds['DSC_PERC'] ?>"
                                 style="<?= ($price['PERCENT'] > 0 ? '' : 'display: none') ?>;">
                                <span><?= -$price['PERCENT'] ?>%</span>
                            </div>
                            <?
                        }?>
                        <span class="product-item-price-current <?= ($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? '' : 'old') ?>" id="<?= $itemIds['PRICE'] ?>">
                            <?
                            if (!empty($price)) {
                                if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers) {
                                    echo Loc::getMessage(
                                        'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
                                        array(
                                            '#PRICE#' => $price['PRINT_RATIO_PRICE'],
                                            '#VALUE#' => $measureRatio,
                                            '#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
                                        )
                                    );
                                } else {
                                    echo $price['PRINT_RATIO_PRICE'];
                                }
                            }
                            ?>
                        </span>
                        <?if ($arParams['SHOW_OLD_PRICE'] === 'Y') {
                            ?>
                            <div class="product-item-price-old-wrapper">
                                <?/*span <?= ($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '') ?>>
                                    Старая цена
                                </span*/?>
                                <span class="product-item-price-old" id="<?= $itemIds['PRICE_OLD'] ?>"
                                    <?= ($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '') ?>>
                                    <?= $haveOffers ? '' : $price['PRINT_RATIO_BASE_PRICE'] ?>
                                </span>&nbsp;
                            </div>
                            <?
                        }
                        ?>

                    </div>
                    
                    <?
                    break;

                case 'quantityLimit':
                    if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                        if ($haveOffers) {
                            if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y') {
                                ?>
								<div class="product-item-info-container quantity-limit-block"
								     id="<?= $itemIds['QUANTITY_LIMIT'] ?>"
								     style="display: none;" data-entity="quantity-limit-block">
									<div class="product-item-info-container-title">
                                        <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
										<span class="product-item-quantity" data-entity="quantity-limit-value"></span>
									</div>
								</div>
                                <?
                            }
                        } else {
                            if (
                                $measureRatio
                                && (float)$actualItem['CATALOG_QUANTITY'] > 0
                                && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                                && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                            ) {
                                ?>
								<div class="product-item-info-container quantity-limit-block"
								     id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
									<div class="product-item-info-container-title">
                                        <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
										<span class="product-item-quantity">
											<?
                                            if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                                                if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                                    echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                                } else {
                                                    echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                                }
                                            } else {
                                                echo $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                                            }
                                            ?>
										</span>
									</div>
								</div>
                                <?
                            }
                        }
                    }

                    break;

                case 'quantity':
                    if (!$haveOffers) {
                        if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY']) {
                            ?>
							<div class="product-item-info-container" data-entity="quantity-block">
								<!--                product-item-hidden-->
								<div class="product-item-amount">
									<div class="product-item-amount-field-container jq-number qty">

										<a class="product-item-amount-field-btn-minus jq-number__spin minus"
										   id="<?= $itemIds['QUANTITY_DOWN'] ?>"
										   href="javascript:void(0)" rel="nofollow">
										</a>

										<div class="jq-number__field">
											<input class="product-item-amount-field" id="<?= $itemIds['QUANTITY'] ?>"
											       type="number"
											       name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>"
											       value="<?= $measureRatio ?>">
										</div>

										<a class="product-item-amount-field-btn-plus jq-number__spin plus"
										   id="<?= $itemIds['QUANTITY_UP'] ?>"
										   href="javascript:void(0)" rel="nofollow">
										</a>
									</div>
								</div>
							</div>
                            <?
                        }
                    } elseif ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y') {
                        if ($arParams['USE_PRODUCT_QUANTITY']) {
                            ?>
							<div class="product-item-info-container" data-entity="quantity-block">
								<!--                product-item-hidden-->
								<div class="product-item-amount">
									<div class="product-item-amount-field-container jq-number qty">
										<a class="product-item-amount-field-btn-minus jq-number__spin minus"
										   id="<?= $itemIds['QUANTITY_DOWN'] ?>"
										   href="javascript:void(0)" rel="nofollow">
										</a>
										<div class="jq-number__field">
											<input class="product-item-amount-field" id="<?= $itemIds['QUANTITY'] ?>"
											       type="number"
											       name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>"
											       value="<?= $measureRatio ?>">
										</div>
										<a class="product-item-amount-field-btn-plus jq-number__spin plus"
										   id="<?= $itemIds['QUANTITY_UP'] ?>"
										   href="javascript:void(0)" rel="nofollow">
										</a>
									</div>
								</div>
							</div>
                            <?
                        }
                    }

                    break;

                case 'buttons':
                    ?>
					<div class="product-item-info-container buttons clearfix" data-entity="buttons-block">
						<!--            product-item-hidden-->
                        <?
                        if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y') {
                            ?>
	                        <div class="product-item-label-ring <?= $discountPositionClass ?>" id="<?= $itemIds['DSC_PERC'] ?>"
	                             style="<?= ($price['PERCENT'] > 0 ? '' : 'display: none') ?>;">
		                        <span><?= -$price['PERCENT'] ?>%</span>
	                        </div>
                            <?
                        }

                        if (!$haveOffers) {
                                ?>
								<div class="product-item-button-container palitra" id="<?= $itemIds['BASKET_ACTIONS'] ?>">
									<a class=" whiteBtn btn-link buyBtn btn <?= $buttonSizeClass ?>" href="#">
                                        <?= GetMessage('CT_BCI_TAKE');?><?/*= ($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET']) */?>
									</a>
								</div>
                                <?
                        } else {
                            if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y') {
                                ?>
								<div class="product-item-button-container">
                                    <?
                                    if ($showSubscribe) {
                                        $APPLICATION->IncludeComponent(
                                            'bitrix:catalog.product.subscribe',
                                            'itemtemplate',
                                            array(
                                                'PRODUCT_ID' => $item['ID'],
                                                'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                                'BUTTON_CLASS' => '' . $buttonSizeClass,
                                                'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                                'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                            ),
                                            $component,
                                            array('HIDE_ICONS' => 'Y')
                                        );
                                    }
                                    ?>
									<a class="btn btn-link <?= $buttonSizeClass ?>"
									   id="<?= $itemIds['NOT_AVAILABLE_MESS'] ?>" href="javascript:void(0)"
									   rel="nofollow"
									   style="<?= ($actualItem['CAN_BUY'] ? 'display: none' : '') ?>;">
                                        <?= $arParams['MESS_NOT_AVAILABLE'] ?>
									</a>
									<div id="<?= $itemIds['BASKET_ACTIONS'] ?>"
									     style="<?= ($actualItem['CAN_BUY'] ? '' : 'display: none') ?>;" class="buy-button">
										<!-- <a class=" whiteBtn btn-link buyBtn btn btn-sm <?= $buttonSizeClass ?>"
										   data-id="<?/*= $item['ID'] */?>" id="<?/*= $itemIds['BUY_LINK'] */?>"
										   href="#">
                                            <?= ($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET']) ?>
										</a> -->
									</div>
									<div style="<?= ($actualItem['CAN_BUY'] ? '' : 'display: none') ?>;" class="show-modal-select-props">
									<a href="<?= $item['DETAIL_PAGE_URL'] ?>">
										<span>Выбрать характеристики</span>
										</a>
									</div>
								</div>
                                <?
                            } else {
                                ?>
								<div class="product-item-button-container">
									<a class="btn btn-default <?= $buttonSizeClass ?>"
									   href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                        <?= $arParams['MESS_BTN_DETAIL'] ?>
									</a>
								</div>
                                <?
                            }
                        }
                        ?>
					</div>
                    <?
                    break;

                case 'props':
                    if (!$haveOffers) {
                        if (!empty($item['DISPLAY_PROPERTIES'])) {
                            ?>
                            <div class="title_props"><?= GetMessage('CT_BCI_TPL_MESS_COLOR');?></div>
							<div class="product-item-info-container" data-entity="props-block">
								<!-- <dl class="product-item-properties"> -->
                                    <?
                                    foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty) {
                                        ?>
										<!-- <dt<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                            <?= $displayProperty['NAME'] ?>
										</dt> -->
                                        <ul class="product-item-properties palitra"><?
                                        // var_export($item['PROPERTIES']);
                                        //селектор на jq рядом в скрипт.жс

                                        $counter = 0;
                                        while(count($displayProperty['DISPLAY_VALUE']) > $counter ){
                                            preg_match_all("/href=\"(.*?)\">/m", $displayProperty['DISPLAY_VALUE'][$counter], $propHrefs, PREG_SET_ORDER, 0);
                                            preg_match_all('|\d+|', $displayProperty['DESCRIPTION'][$counter], $propDescriptions);
                                            $ar_res = CCatalogProduct::GetByID($displayProperty['VALUE'][$counter]);
                                            $base_price = CCatalogProduct::GetOptimalPrice($displayProperty['VALUE'][$counter], 1, $USER->GetUserGroupArray())["PRICE"]["PRICE"];


                                            ?><li class="<?=($ar_res['QUANTITY'] <=0) ? 'not_active' : '' ?>"
                                            data-price="<?=$base_price?>"><a href="<?=$propHrefs[0][1]?>"><?=end($propDescriptions[0])?></a></li><?
                                            $counter++;
                                        }?></ul>
										<!-- <dd<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                            <?= (is_array($displayProperty['DISPLAY_VALUE'])
                                                ? implode(' / ', $displayProperty['DISPLAY_VALUE'])
                                                : $displayProperty['DISPLAY_VALUE']) ?>
										</dd> -->
                                        <?
                                    }
                                    ?>
								<!-- </dl> -->
							</div>
                            <?
                        }

                        if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES'])) {
                            ?>
							<div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
                                <?
                                if (!empty($item['PRODUCT_PROPERTIES_FILL'])) {
                                    foreach ($item['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                                        ?>
										<input type="hidden"
										       name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propID ?>]"
										       value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                                        <?
                                        unset($item['PRODUCT_PROPERTIES'][$propID]);
                                    }
                                }

                                if (!empty($item['PRODUCT_PROPERTIES'])) {
                                    ?>
									<table>
                                        <?
                                        foreach ($item['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                                            ?>
											<tr>
												<td><?= $item['PROPERTIES'][$propID]['NAME'] ?></td>
												<td>
                                                    <?
                                                    if (
                                                        $item['PROPERTIES'][$propID]['PROPERTY_TYPE'] === 'L'
                                                        && $item['PROPERTIES'][$propID]['LIST_TYPE'] === 'C'
                                                    ) {
                                                        foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                            ?>
															<label>
                                                                <? $checked = $valueID === $propInfo['SELECTED'] ? 'checked' : ''; ?>
																<input type="radio"
																       name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propID ?>]"
																       value="<?= $valueID ?>" <?= $checked ?>>
                                                                <?= $value ?>
															</label>
															<br/>
                                                            <?
                                                        }
                                                    } else {
                                                        ?>
														<select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propID ?>]">
                                                            <?
                                                            foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                                $selected = $valueID === $propInfo['SELECTED'] ? 'selected' : '';
                                                                ?>
																<option value="<?= $valueID ?>" <?= $selected ?>>
                                                                    <?= $value ?>
																</option>
                                                                <?
                                                            }
                                                            ?>
														</select>
                                                        <?
                                                    }
                                                    ?>
												</td>
											</tr>
                                            <?
                                        }
                                        ?>
									</table>
                                    <?
                                }
                                ?>
							</div>
                            <?
                        }
                    } else {
                        $showProductProps = !empty($item['DISPLAY_PROPERTIES']);
                        $showOfferProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];

                        if ($showProductProps || $showOfferProps) {
                            ?>
							<div class="product-item-info-container product-item-hidden" data-entity="props-block">
								<dl class="product-item-properties">
                                    <?
                                    if ($showProductProps) {
                                        foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty) {
                                            ?>
											<dt<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                                <?= $displayProperty['NAME'] ?>
											</dt>
											<dd<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                                <?= (is_array($displayProperty['DISPLAY_VALUE'])
                                                    ? implode(' / ', $displayProperty['DISPLAY_VALUE'])
                                                    : $displayProperty['DISPLAY_VALUE']) ?>
											</dd>
                                            <?
                                        }
                                    }

                                    if ($showOfferProps) {
                                        ?>
										<span id="<?= $itemIds['DISPLAY_PROP_DIV'] ?>" style="display: none;"></span>
                                        <?
                                    }
                                    ?>
								</dl>
							</div>
                            <?
                        }
                    }

                    break;

                case 'sku':
                    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP'])) {
                        ?>
						<div id="<?= $itemIds['PROP_DIV'] ?>">
                            <?
                            foreach ($arParams['SKU_PROPS'] as $skuProperty) {
                                $propertyId = $skuProperty['ID'];
                                $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
                                if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
                                    continue;
                                ?>
								<div class="product-item-info-container product-item-hidden" data-entity="sku-block">
									<div class="product-item-scu-container" data-entity="sku-line-block">
                                        <?= $skuProperty['NAME'] ?>
										<div class="product-item-scu-block">
											<div class="product-item-scu-list">
												<ul class="product-item-scu-item-list">
                                                    <?
                                                    foreach ($skuProperty['VALUES'] as $value) {
                                                        if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
                                                            continue;

                                                        $value['NAME'] = htmlspecialcharsbx($value['NAME']);

                                                        if ($skuProperty['SHOW_MODE'] === 'PICT') {
                                                            ?>
															<li class="product-item-scu-item-color-container"
															    title="<?= $value['NAME'] ?>"
															    data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
															    data-onevalue="<?= $value['ID'] ?>">
																<div class="product-item-scu-item-color-block">
																	<div class="product-item-scu-item-color"
																	     title="<?= $value['NAME'] ?>"
																	     style="background-image: url(<?= $value['PICT']['SRC'] ?>);">
																	</div>
																</div>
															</li>
                                                            <?
                                                        } else {
                                                            ?>
															<li class="product-item-scu-item-text-container"
															    title="<?= $value['NAME'] ?>"
															    data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
															    data-onevalue="<?= $value['ID'] ?>">
																<div class="product-item-scu-item-text-block">
																	<div class="product-item-scu-item-text"><?= $value['NAME'] ?></div>
																</div>
															</li>
                                                            <?
                                                        }
                                                    }
                                                    ?>
												</ul>
												<div style="clear: both;"></div>
											</div>
										</div>
									</div>
								</div>
                                <?
                            }
                            ?>
						</div>
                        <?
                        foreach ($arParams['SKU_PROPS'] as $skuProperty) {
                            if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
                                continue;

                            $skuProps[] = array(
                                'ID' => $skuProperty['ID'],
                                'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                                'VALUES' => $skuProperty['VALUES'],
                                'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
                            );
                        }

                        unset($skuProperty, $value);

                        if ($item['OFFERS_PROPS_DISPLAY']) {
                            foreach ($item['JS_OFFERS'] as $keyOffer => $jsOffer) {
                                $strProps = '';

                                if (!empty($jsOffer['DISPLAY_PROPERTIES'])) {
                                    foreach ($jsOffer['DISPLAY_PROPERTIES'] as $displayProperty) {
                                        $strProps .= '<dt>' . $displayProperty['NAME'] . '</dt><dd>'
                                            . (is_array($displayProperty['VALUE'])
                                                ? implode(' / ', $displayProperty['VALUE'])
                                                : $displayProperty['VALUE'])
                                            . '</dd>';
                                    }
                                }

                                $item['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                            }
                            unset($jsOffer, $strProps);
                        }
                    }

                    break;
            }
        }
    }

    if (
        $arParams['DISPLAY_COMPARE']
        && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
    ) {
        ?>
		<div class="product-item-compare-container">
			<div class="product-item-compare">
				<div class="checkbox">
					<label id="<?= $itemIds['COMPARE_LINK'] ?>">
						<input type="checkbox" data-entity="compare-checkbox">
						<span data-entity="compare-title"><?= $arParams['MESS_BTN_COMPARE'] ?></span>
					</label>
				</div>
			</div>
		</div>
        <?
    }
    ?>
</div>