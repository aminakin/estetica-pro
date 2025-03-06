<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 */
?>
<script id="basket-total-template" type="text/html">
	<a href="javascript:void(0)" class="basket-items-list-header-filter-item active"
								data-entity="basket-items-count" data-filter="all" style="display: none;"></a>
	<?/* if (!$USER->IsAuthorized()){?>
		<div class="basket-checkout-container auth" data-entity="basket-checkout-aligner">
				<div class="basket-items-block__title">
		          <?= GetMessage('SBB_BASKET_AUTH');?>
		      </div>
		      <div class="basket-items-block__info">
		      	<?= GetMessage('SBB_BASKET_AUTH_INFO');?>
		      </div>
		      <a href="#auth" class="enterLink linkOpen menu-links">
		      	<?= GetMessage('SBB_BASKET_AUTH_ENTER');?>
		      </a>
		</div>	
	<?}*/?>				
	<div class="basket-checkout-container" data-entity="basket-checkout-aligner">
        <div class="basket-items-block__title">
            Ваш заказ
        </div>
		<?
		if ($arParams['HIDE_COUPON'] !== 'Y')
		{
			?>
			<div class="basket-coupon-section">
				<div class="basket-coupon-block-field">
					<div class="basket-coupon-block-field-description">
						<?=Loc::getMessage('SBB_COUPON_ENTER')?>:
					</div>
					<div class="form">
						<div class="form-group" style="position: relative;">
							<input type="text" class="form-control" id="" placeholder="" data-entity="basket-coupon-input">
							<span class="basket-coupon-block-coupon-btn"></span>
						</div>
					</div>
				</div>
			</div>
			<?
		}
		?>
        <div class="basket-items-block__sum">
            <div class="basket-items-block__row">
                <div class="basket-items-block__name">
                    заказ на сумму
                </div>
                <div class="basket-items-block__value" data-entity="basket-total-price">
                    <span style="display: none !important;">{{{PRICE}}} ₽</span>
                    {{{PRICE}}} ₽
                </div>
            </div>
            <div class="basket-items-block__row">
                <div class="basket-items-block__name">
                    доставка
                </div>
                <div class="basket-items-block__value">
                    <?
                    $arPrices = array();
                    $db_dtype = CSaleDelivery::GetList(
                        array(
                            "SORT" => "ASC",
                            "NAME" => "ASC"
                        ),
                        array(
                            "LID" => SITE_ID,
                            "ACTIVE" => "Y",
                        ),
                        false,
                        false,
                        array()
                    );
                    while ($ar_dtype = $db_dtype->Fetch())
                    {
                        $arPrices[] = $ar_dtype['PRICE'];
                    }

                    ?>
                    от&nbsp;<?=min($arPrices)?>&nbsp;₽
                    <a class="basket-items-block__delivery linkOpen" href="#delivery">
                        i
                    </a>
                </div>
            </div>
        </div>
		<div class="basket-checkout-section">
			<div class="basket-checkout-section-inner">
				<div class="basket-checkout-block basket-checkout-block-total">
					<div class="basket-checkout-block-total-inner">
                        {{#DISCOUNT_PRICE_FORMATED}}
                            <div class="basket-checkout-block-total-overhead">Сумма без учёта скидок</div>
                        {{/DISCOUNT_PRICE_FORMATED}}
						<div class="basket-checkout-block-total-title">Всего</div>
						<div class="basket-checkout-block-total-description">
							{{#WEIGHT_FORMATED}}
								<?=Loc::getMessage('SBB_WEIGHT')?>: {{{WEIGHT_FORMATED}}}
								{{#SHOW_VAT}}<br>{{/SHOW_VAT}}
							{{/WEIGHT_FORMATED}}
							{{#SHOW_VAT}}
								<?=Loc::getMessage('SBB_VAT')?>: {{{VAT_SUM_FORMATED}}}
							{{/SHOW_VAT}}
						</div>
					</div>
				</div>
				<?/*<div class="basket-coupon-block-total-item-count">
							{{{BASKET_ITEMS_COUNT}}}
				</div>*/?>
				<div class="basket-checkout-block basket-checkout-block-total-price">
					<div class="basket-checkout-block-total-price-inner">
						{{#DISCOUNT_PRICE_FORMATED}}
							<div class="basket-coupon-block-total-price-old">
								{{{PRICE_WITHOUT_DISCOUNT_FORMATED}}}
							</div>
						{{/DISCOUNT_PRICE_FORMATED}}

						<div class="basket-coupon-block-total-price-current" data-entity="basket-total-price">
							{{{PRICE_FORMATED}}}
						</div>
						

						{{#DISCOUNT_PRICE_FORMATED}}
							<div class="basket-coupon-block-total-price-difference">
								<?=Loc::getMessage('SBB_BASKET_ITEM_ECONOMY')?>
								<span style="white-space: nowrap;">{{{DISCOUNT_PRICE_FORMATED}}}</span>
							</div>
						{{/DISCOUNT_PRICE_FORMATED}}
					</div>
				</div>
				
			</div>
            
				<? if (!$USER->IsAuthorized()){?>
					<div class="basket-checkout-block basket-checkout-block-btn">
					<a href="#auth" class="enterLink linkOpen menu-links">
							<?= GetMessage('SBB_BASKET_AUTH_ENTER');?>
						</a>
					</div>	
					<div class="basket-checkout-block basket-checkout-block-btn">
					<a href="#register" class="enterLink linkOpen menu-links">
							<?= GetMessage('SBB_REGISTRATION');?>
						</a>
					</div>
				<?}?>
				<? if (!$USER->IsAuthorized()){?>
					<div class="basket-checkout-block basket-checkout-block-btn">
						{{#MINSUM}}
							<button class="button_transparent">
								<?=Loc::getMessage('SBB_WITHOUTREG_ORDER')?>
							</button>
						{{/MINSUM}}

						{{^MINSUM}}
						<button class="button_filled {{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}"
								data-entity="basket-checkout-button">
							<?=Loc::getMessage('SBB_WITHOUTREG_ORDER')?>
						</button>
						{{/MINSUM}}
					</div>
				<?}else{?>
					<div class="basket-checkout-block basket-checkout-block-btn">
						{{#MINSUM}}
							<button class="button_transparent">
								<?=Loc::getMessage('SBB_ORDER')?>
							</button>
						{{/MINSUM}}

						{{^MINSUM}}
						<button class="button_filled {{#DISABLE_CHECKOUT}} disabled{{/DISABLE_CHECKOUT}}"
								data-entity="basket-checkout-button">
							<?=Loc::getMessage('SBB_ORDER')?>
						</button>
						{{/MINSUM}}
					</div>
				<?}?>
				
					<div class="basket-checkout-section-share">
					<?if ($USER->IsAdmin()){?>
						<div id="share" class="button_filled"><?=Loc::getMessage('SBB_SHARE')?></div>
						<?}?>
					</div>
				
			<div class="basket-checkout-section-payment">
                <img src="/local/templates/main/components/bitrix/sale.basket.basket/estetica_new/images/visacard.svg">
                <img src="/local/templates/main/components/bitrix/sale.basket.basket/estetica_new/images/mastercard.svg">
                <img src="/local/templates/main/components/bitrix/sale.basket.basket/estetica_new/images/mircard.svg">
                <img src="/local/templates/main/components/bitrix/sale.basket.basket/estetica_new/images/applepay.svg">
                <img src="/local/templates/main/components/bitrix/sale.basket.basket/estetica_new/images/googlepay.svg">
            </div>	
            {{#MINSUM}}
            <div class="basket-checkout-section-minsum minsum">
                <div class="minsum__text"></div>
                <div class="minsum__text">
                    <?= GetMessage('SBB_BASKET_MINPRICE');?>
                    <span class="minsum__value">
                        {{MINSUM}}
                    </span>
                    ₽
                </div>
            </div>
            {{/MINSUM}}
            	
            {{#MINSUM}}
            	<div class="basket-checkout-continue-buy minsum">
            		<a href="/catalog/"><?= GetMessage('SBB_BASKET_CONTINE');?></a>
            	</div>
            {{/MINSUM}}
            {{^MINSUM}}
			<div class="basket-checkout-section-privacy">
                <?=GetMessage('SBB_BASKET_PRICE_DEVILERY_INFO_NOT_AUTH') ?>
            </div>
            {{/MINSUM}}
		</div>

		<?
		if ($arParams['HIDE_COUPON'] !== 'Y')
		{
		?>
			<div class="basket-coupon-alert-section">
				<div class="basket-coupon-alert-inner">
					{{#COUPON_LIST}}
					<div class="basket-coupon-alert text-{{CLASS}}">
						<span class="basket-coupon-text">
							<strong>{{COUPON}}</strong> - <?=Loc::getMessage('SBB_COUPON')?> {{JS_CHECK_CODE}}
							{{#DISCOUNT_NAME}}({{{DISCOUNT_NAME}}}){{/DISCOUNT_NAME}}
						</span>
						<span class="close-link" data-entity="basket-coupon-delete" data-coupon="{{COUPON}}">
							<?=Loc::getMessage('SBB_DELETE')?>
						</span>
					</div>
					{{/COUPON_LIST}}
				</div>
			</div>
			<?
		}
		?>
	</div>
	<? //$APPLICATION->IncludeFile('/include/bonus-delivery.php'); ?>
	<? $APPLICATION->IncludeFile('/include/bonus-delivery_a.php'); ?>
</script>