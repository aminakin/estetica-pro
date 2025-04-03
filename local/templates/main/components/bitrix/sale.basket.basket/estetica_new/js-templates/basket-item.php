<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/**
 * @var array $mobileColumns
 * @var array $arParams
 * @var string $templateFolder
 */

$usePriceInAdditionalColumn = in_array('PRICE', $arParams['COLUMNS_LIST']) && $arParams['PRICE_DISPLAY_MODE'] === 'Y';
$useSumColumn = in_array('SUM', $arParams['COLUMNS_LIST']);
$useActionColumn = in_array('DELETE', $arParams['COLUMNS_LIST']);

$restoreColSpan = 2 + $usePriceInAdditionalColumn + $useSumColumn + $useActionColumn;

$positionClassMap = array(
    'left' => 'basket-item-label-left',
    'center' => 'basket-item-label-center',
    'right' => 'basket-item-label-right',
    'bottom' => 'basket-item-label-bottom',
    'middle' => 'basket-item-label-middle',
    'top' => 'basket-item-label-top'
);

$discountPositionClass = '';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION'])) {
    foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos) {
        $discountPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}

$labelPositionClass = '';
if (!empty($arParams['LABEL_PROP_POSITION'])) {
    foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos) {
        $labelPositionClass .= isset($positionClassMap[$pos]) ? ' ' . $positionClassMap[$pos] : '';
    }
}
?>
<script id="basket-item-template" type="text/html">
    <div class="basket-items-list-item-container{{#SHOW_RESTORE}} basket-items-list-item-container-expend{{/SHOW_RESTORE}}"
        id="basket-item-{{ID}}" data-entity="basket-item" data-id="{{ID}}">
        <div class="basket-item-content">
            {{#SHOW_RESTORE}}
                <div class="basket-items-list-item-notification" colspan="<?= $restoreColSpan ?>">
                    <div class="basket-items-list-item-notification-inner basket-items-list-item-notification-removed" id="basket-item-height-aligner-{{ID}}">
                        {{#SHOW_LOADING}}
                            <div class="basket-items-list-item-overlay"></div>
                        {{/SHOW_LOADING}}
                        <div class="basket-items-list-item-removed-container">
                            <div>
                                Товар удалён
                            </div>
                            <div class="basket-items-list-item-removed-block">
                                <a href="javascript:void(0)" data-entity="basket-item-restore-button">
                                    Отменить
                                </a>
                                <!--							<span class="basket-items-list-item-clear-btn" data-entity="basket-item-close-restore-button"></span>-->
                            </div>
                        </div>
                    </div>
                </div>
            {{/SHOW_RESTORE}}
            {{^SHOW_RESTORE}}
                <div class="basket-item-top-block">
                    <div class="basket-item-top_toggle-switch basket-toggle-switch checked" data-item-switcher-id="{{ID}}" data-entity="basket-item-toggle-delayed">
                        <div class="basket-toggle-switch__circle"></div>
                    </div>
                </div>
                <?
                if (in_array('PREVIEW_PICTURE', $arParams['COLUMNS_LIST'])) {
                ?>
                    <div class="basket-item-block-image__wrapper">
                        <div class="basket-item-block-image<?= (!isset($mobileColumns['PREVIEW_PICTURE']) ? ' hidden-xs' : '') ?>">
                            {{#DETAIL_PAGE_URL}}
                                <a href="{{DETAIL_PAGE_URL}}" class="basket-item-image-link">
                            {{/DETAIL_PAGE_URL}}

                            <img class="basket-item-image" alt="{{NAME}}"
                                src="{{{IMAGE_URL}}}{{^IMAGE_URL}}<?= $templateFolder ?>/images/no_photo.png{{/IMAGE_URL}}">

                            {{#SHOW_LABEL}}
                                <div class="basket-item-label-text basket-item-label-big <?= $labelPositionClass ?>">
                                    {{#LABEL_VALUES}}
                                        <div{{#HIDE_MOBILE}} class="hidden-xs" {{/HIDE_MOBILE}}>
                                            <span title="{{NAME}}">{{NAME}}</span>
                                </div>
                                {{/LABEL_VALUES}}
                        </div>
                        {{/SHOW_LABEL}}

                        <?
                        if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y') {
                        ?>
                            {{#DISCOUNT_PRICE_PERCENT}}
                                <div class="basket-item-label-ring basket-item-label-small <?= $discountPositionClass ?>">
                                    -{{DISCOUNT_PRICE_PERCENT_FORMATED}}
                                </div>
                            {{/DISCOUNT_PRICE_PERCENT}}
                        <?
                        }
                        ?>

                        {{#DETAIL_PAGE_URL}}
                            </a>
                        {{/DETAIL_PAGE_URL}}
                    </div>
        </div>
    <?
                }
    ?>
    <div class="basket-items-list-cont">

        <div class="basket-items-list-item-column">
            <div class="basket-items-list-item-descriptions">
                <div class="basket-items-list-item-descriptions-inner" id="basket-item-height-aligner-{{ID}}">

                    <div class="basket-item-block-info">
                        <?
                        if (isset($mobileColumns['DELETE'])) {
                        ?>
                            <span class="basket-item-actions-remove visible-xs" data-entity="basket-item-delete">Удалить</span>
                        <?
                        }
                        ?>
                        <div class="basket-item-info-name">
                            {{#DETAIL_PAGE_URL}}
                                <a href="{{DETAIL_PAGE_URL}}" class="basket-item-info-name-link">
                            {{/DETAIL_PAGE_URL}}

                            <span data-entity="basket-item-name">{{NAME}}</span>

                            {{#DETAIL_PAGE_URL}}
                                </a>
                            {{/DETAIL_PAGE_URL}}
                        </div>
                        {{#NOT_AVAILABLE}}
                            <div class="basket-items-list-item-warning-container">
                                <div class="alert alert-warning text-center">
                                    <?= Loc::getMessage('SBB_BASKET_ITEM_NOT_AVAILABLE') ?>.
                                </div>
                            </div>
                        {{/NOT_AVAILABLE}}
                        {{#WARNINGS.length}}
                            <div class="basket-items-list-item-warning-container">
                                <div class="alert alert-warning alert-dismissable" data-entity="basket-item-warning-node">
                                    <span class="close" data-entity="basket-item-warning-close">&times;</span>
                                    {{#WARNINGS}}
                                        <div data-entity="basket-item-warning-text">{{{.}}}</div>
                                    {{/WARNINGS}}
                                </div>
                            </div>
                        {{/WARNINGS.length}}
                        <div class="basket-item-block-properties">
                            <?
                            if (!empty($arParams['PRODUCT_BLOCKS_ORDER'])) {
                                foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName) {
                                    switch (trim((string)$blockName)) {
                                        case 'props':
                                            if (in_array('PROPS', $arParams['COLUMNS_LIST'])) {
                            ?>
                                                {{#PROPS}}
                                                    <div class="basket-item-property<?= (!isset($mobileColumns['PROPS']) ? ' hidden-xs' : '') ?>">
                                                        <div class="basket-item-property-name">
                                                            {{{NAME}}}
                                                        </div>
                                                        <div class="basket-item-property-value"
                                                            data-entity="basket-item-property-value" data-property-code="{{CODE}}">
                                                            {{{VALUE}}}
                                                        </div>
                                                    </div>
                                                {{/PROPS}}
                                            <?
                                            }

                                            break;
                                        case 'sku':
                                            ?>
                                            {{#SKU_BLOCK_LIST}}
                                                {{#IS_IMAGE}}
                                                    <div class="basket-item-property basket-item-property-scu-image"
                                                        data-entity="basket-item-sku-block">
                                                        <div class="basket-item-property-name">{{NAME}}</div>
                                                        <div class="basket-item-property-value">
                                                            <ul class="basket-item-scu-list">
                                                                {{#SKU_VALUES_LIST}}
                                                                    <li class="basket-item-scu-item{{#SELECTED}} selected{{/SELECTED}}
																		{{#NOT_AVAILABLE_OFFER}} not-available{{/NOT_AVAILABLE_OFFER}}"
                                                                        title="{{NAME}}"
                                                                        data-entity="basket-item-sku-field"
                                                                        data-initial="{{#SELECTED}}true{{/SELECTED}}{{^SELECTED}}false{{/SELECTED}}"
                                                                        data-value-id="{{VALUE_ID}}"
                                                                        data-sku-name="{{NAME}}"
                                                                        data-property="{{PROP_CODE}}">
                                                                        <span class="basket-item-scu-item-inner"
                                                                            style="background-image: url({{PICT}});"></span>
                                                                    </li>
                                                                {{/SKU_VALUES_LIST}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                {{/IS_IMAGE}}

                                                {{^IS_IMAGE}}
                                                    <div class="basket-item-property basket-item-property-scu-text"
                                                        data-entity="basket-item-sku-block">
                                                        <div class="basket-item-property-name">{{NAME}}</div>
                                                        <div class="basket-item-property-value">
                                                            <ul class="basket-item-scu-list">
                                                                {{#SKU_VALUES_LIST}}
                                                                    <li class="basket-item-scu-item{{#SELECTED}} selected{{/SELECTED}}
																		{{#NOT_AVAILABLE_OFFER}} not-available{{/NOT_AVAILABLE_OFFER}}"
                                                                        title="{{NAME}}"
                                                                        data-entity="basket-item-sku-field"
                                                                        data-initial="{{#SELECTED}}true{{/SELECTED}}{{^SELECTED}}false{{/SELECTED}}"
                                                                        data-value-id="{{VALUE_ID}}"
                                                                        data-sku-name="{{NAME}}"
                                                                        data-property="{{PROP_CODE}}">
                                                                        <span class="basket-item-scu-item-inner">{{NAME}}</span>
                                                                    </li>
                                                                {{/SKU_VALUES_LIST}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                {{/IS_IMAGE}}
                                            {{/SKU_BLOCK_LIST}}

                                            {{#HAS_SIMILAR_ITEMS}}
                                                <div class="basket-items-list-item-double" data-entity="basket-item-sku-notification">
                                                    <div class="alert alert-info alert-dismissable text-center">
                                                        {{#USE_FILTER}}
                                                            <a href="javascript:void(0)"
                                                                class="basket-items-list-item-double-anchor"
                                                                data-entity="basket-item-show-similar-link">
                                                        {{/USE_FILTER}}
                                                        <?= Loc::getMessage('SBB_BASKET_ITEM_SIMILAR_P1') ?>{{#USE_FILTER}}</a>{{/USE_FILTER}}
                                                        <?= Loc::getMessage('SBB_BASKET_ITEM_SIMILAR_P2') ?>
                                                        {{SIMILAR_ITEMS_QUANTITY}} {{MEASURE_TEXT}}
                                                        <br>
                                                        <a href="javascript:void(0)" class="basket-items-list-item-double-anchor"
                                                            data-entity="basket-item-merge-sku-link">
                                                            <?= Loc::getMessage('SBB_BASKET_ITEM_SIMILAR_P3') ?>
                                                            {{TOTAL_SIMILAR_ITEMS_QUANTITY}} {{MEASURE_TEXT}}?
                                                        </a>
                                                    </div>
                                                </div>
                                            {{/HAS_SIMILAR_ITEMS}}
                                        <?
                                            break;
                                        case 'columns':
                                        ?>
                                            {{#COLUMN_LIST}}
                                                {{#IS_IMAGE}}
                                                    <div class="basket-item-property-custom basket-item-property-custom-photo
														{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                                        data-entity="basket-item-property">
                                                        <div class="basket-item-property-custom-name">{{NAME}}</div>
                                                        <div class="basket-item-property-custom-value">
                                                            {{#VALUE}}
                                                                <span>
                                                                    <img class="basket-item-custom-block-photo-item"
                                                                        src="{{{IMAGE_SRC}}}" data-image-index="{{INDEX}}"
                                                                        data-column-property-code="{{CODE}}">
                                                                </span>
                                                            {{/VALUE}}
                                                        </div>
                                                    </div>
                                                {{/IS_IMAGE}}

                                                {{#IS_TEXT}}
                                                    <div class="basket-item-property-custom basket-item-property-custom-text
														{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                                        data-entity="basket-item-property">
                                                        <div class="basket-item-property-custom-name">{{NAME}}</div>
                                                        <div class="basket-item-property-custom-value"
                                                            data-column-property-code="{{CODE}}"
                                                            data-entity="basket-item-property-column-value">
                                                            {{VALUE}}
                                                        </div>
                                                    </div>
                                                {{/IS_TEXT}}

                                                {{#IS_HTML}}
                                                    <div class="basket-item-property-custom basket-item-property-custom-text
														{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                                        data-entity="basket-item-property">
                                                        <div class="basket-item-property-custom-name">{{NAME}}</div>
                                                        <div class="basket-item-property-custom-value"
                                                            data-column-property-code="{{CODE}}"
                                                            data-entity="basket-item-property-column-value">
                                                            {{{VALUE}}}
                                                        </div>
                                                    </div>
                                                {{/IS_HTML}}

                                                {{#IS_LINK}}
                                                    <div class="basket-item-property-custom basket-item-property-custom-text
														{{#HIDE_MOBILE}}hidden-xs{{/HIDE_MOBILE}}"
                                                        data-entity="basket-item-property">
                                                        <div class="basket-item-property-custom-name">{{NAME}}</div>
                                                        <div class="basket-item-property-custom-value"
                                                            data-column-property-code="{{CODE}}"
                                                            data-entity="basket-item-property-column-value">
                                                            {{#VALUE}}
                                                                {{{LINK}}}{{^IS_LAST}}<br>{{/IS_LAST}}
                                                            {{/VALUE}}
                                                        </div>
                                                    </div>
                                                {{/IS_LINK}}
                                            {{/COLUMN_LIST}}
                            <?
                                            break;
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    {{#SHOW_LOADING}}
                        <div class="basket-items-list-item-overlay"></div>
                    {{/SHOW_LOADING}}
                </div>
            </div>
            <?
            if ($usePriceInAdditionalColumn) {
            ?>
                <div class="basket-items-list-item-price basket-items-list-item-price-for-one<?= (!isset($mobileColumns['PRICE']) ? ' hidden-xs' : '') ?>">
                    <div class="basket-item-block-price">
                        {{#SHOW_DISCOUNT_PRICE}}
                            <div class="basket-item-price-old">
                                <span class="basket-item-price-old-text">
                                    {{{FULL_PRICE_FORMATED}}}
                                </span>
                            </div>
                        {{/SHOW_DISCOUNT_PRICE}}

                        <div class="basket-item-price-current">
                            <span class="basket-item-price-current-text" id="basket-item-price-{{ID}}">
                                {{{PRICE_FORMATED}}}
                            </span>
                        </div>

                        <div class="basket-item-price-title">
                            <?= Loc::getMessage('SBB_BASKET_ITEM_PRICE_FOR') ?> {{MEASURE_RATIO}} {{MEASURE_TEXT}}
                        </div>
                        {{#SHOW_LOADING}}
                            <div class="basket-items-list-item-overlay"></div>
                        {{/SHOW_LOADING}}
                    </div>
                </div>
            <?
            }
            ?>
            <div class="basket-items-list-item-amount">
                <div class="basket-item-block-amount{{#NOT_AVAILABLE}} disabled{{/NOT_AVAILABLE}}"
                    data-entity="basket-item-quantity-block">
                    <span class="basket-item-amount-btn-minus" data-entity="basket-item-quantity-minus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4 12C4 11.4477 4.44772 11 5 11H19C19.5523 11 20 11.4477 20 12C20 12.5523 19.5523 13 19 13H5C4.44772 13 4 12.5523 4 12Z" fill="black" />
                        </svg>
                    </span>
                    <div class="basket-item-amount-filed-block">
                        <input type="text" class="basket-item-amount-filed" value="{{QUANTITY}}"
                            {{#NOT_AVAILABLE}} disabled="disabled" {{/NOT_AVAILABLE}}
                            data-value="{{QUANTITY}}" data-entity="basket-item-quantity-field"
                            id="basket-item-quantity-{{ID}}">
                    </div>
                    <span class="basket-item-amount-btn-plus" data-entity="basket-item-quantity-plus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5V11H5C4.44772 11 4 11.4477 4 12C4 12.5523 4.44772 13 5 13H11V19C11 19.5523 11.4477 20 12 20C12.5523 20 13 19.5523 13 19V13H19C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11H13V5Z" fill="black" />
                        </svg>
                    </span>
                    <div class="basket-item-amount-field-description">
                        <?
                        if ($arParams['PRICE_DISPLAY_MODE'] === 'Y') {
                        ?>
                            {{MEASURE_TEXT}}
                        <?
                        } else {
                        ?>
                            {{#SHOW_PRICE_FOR}}
                                {{MEASURE_RATIO}} {{MEASURE_TEXT}} =
                                <span id="basket-item-price-{{ID}}">{{{PRICE_FORMATED}}}</span>
                            {{/SHOW_PRICE_FOR}}
                            {{^SHOW_PRICE_FOR}}
                                {{MEASURE_TEXT}}
                            {{/SHOW_PRICE_FOR}}
                        <?
                        }
                        ?>
                    </div>
                    {{#SHOW_LOADING}}
                        <div class="basket-items-list-item-overlay"></div>
                    {{/SHOW_LOADING}}
                </div>
            </div>
            <?
            if ($useSumColumn) {
            ?>
                <div class="basket-items-list-item-price<?= (!isset($mobileColumns['SUM']) ? ' hidden-xs' : '') ?>">
                    <div class="basket-item-block-price">
                        {{#SHOW_DISCOUNT_PRICE}}
                            <div class="basket-item-price-old">
                                <span class="basket-item-price-old-text" id="basket-item-sum-price-old-{{ID}}">
                                    {{{SUM_FULL_PRICE_FORMATED}}}
                                </span>
                            </div>
                        {{/SHOW_DISCOUNT_PRICE}}

                        <div class="basket-item-price-current">
                            <span class="basket-item-price-current-text" id="basket-item-sum-price-{{ID}}">
                                {{{SUM_PRICE_FORMATED}}}
                            </span>
                        </div>

                        {{#SHOW_DISCOUNT_PRICE}}
                            <div class="basket-item-price-difference">
                                <?= Loc::getMessage('SBB_BASKET_ITEM_ECONOMY') ?>
                                <span id="basket-item-sum-price-difference-{{ID}}" style="white-space: nowrap;">
                                    {{{SUM_DISCOUNT_PRICE_FORMATED}}}
                                </span>
                            </div>
                        {{/SHOW_DISCOUNT_PRICE}}
                        {{#SHOW_LOADING}}
                            <div class="basket-items-list-item-overlay"></div>
                        {{/SHOW_LOADING}}
                    </div>
                </div>
            <?
            }
            ?>
        </div>
        <div class="basket-items-list-item-column">
            <? if ($useActionColumn) { ?>
                <div class="basket-items-list-item-remove">
                    <? global $USER; ?>
                    <? if ($USER->IsAuthorized()): ?>
                        <div class="product-item-favorite-image-block" data-item="{{PRODUCT_ID_FAVITEM}}">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16 7.59966C14.0166 4.98053 10.7626 4.13565 7.91273 4.80143C4.69478 5.55321 1.91669 8.24491 1.91669 12.4996C1.91669 16.2825 3.88872 19.622 6.373 22.2906C8.86341 24.9657 11.9402 27.0462 14.329 28.3275C15.3791 28.8908 16.6209 28.8908 17.6711 28.3275C20.0598 27.0462 23.1366 24.9657 25.627 22.2906C28.1113 19.622 30.0834 16.2825 30.0834 12.4996C30.0834 8.24492 27.3053 5.55321 24.0873 4.80143C21.2375 4.13566 17.9835 4.98053 16 7.59966ZM8.25397 6.2621C5.6386 6.8731 3.41669 9.01713 3.41669 12.4996C3.41669 15.7484 5.11554 18.7384 7.4709 21.2685C9.82014 23.792 12.7517 25.7793 15.038 27.0057C15.6453 27.3314 16.3547 27.3314 16.962 27.0057C19.2483 25.7793 22.1799 23.792 24.5291 21.2685C26.8845 18.7384 28.5834 15.7484 28.5834 12.4996C28.5834 9.01714 26.3614 6.87311 23.7461 6.2621C21.1064 5.64542 18.141 6.60451 16.6595 9.33996C16.5284 9.582 16.2753 9.73278 16 9.73278C15.7248 9.73278 15.4716 9.582 15.3405 9.33996C13.859 6.60451 10.8937 5.64542 8.25397 6.2621Z" fill="#F781BF"></path>
                            </svg>
                        </div>
                    <? else: ?>
                        <a class="product-item-favorite-image-block no_auth_fav linkOpen" href="#no_fav_popup" data-item="{{PRODUCT_ID_FAVITEM}}">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M16 7.59966C14.0166 4.98053 10.7626 4.13565 7.91273 4.80143C4.69478 5.55321 1.91669 8.24491 1.91669 12.4996C1.91669 16.2825 3.88872 19.622 6.373 22.2906C8.86341 24.9657 11.9402 27.0462 14.329 28.3275C15.3791 28.8908 16.6209 28.8908 17.6711 28.3275C20.0598 27.0462 23.1366 24.9657 25.627 22.2906C28.1113 19.622 30.0834 16.2825 30.0834 12.4996C30.0834 8.24492 27.3053 5.55321 24.0873 4.80143C21.2375 4.13566 17.9835 4.98053 16 7.59966ZM8.25397 6.2621C5.6386 6.8731 3.41669 9.01713 3.41669 12.4996C3.41669 15.7484 5.11554 18.7384 7.4709 21.2685C9.82014 23.792 12.7517 25.7793 15.038 27.0057C15.6453 27.3314 16.3547 27.3314 16.962 27.0057C19.2483 25.7793 22.1799 23.792 24.5291 21.2685C26.8845 18.7384 28.5834 15.7484 28.5834 12.4996C28.5834 9.01714 26.3614 6.87311 23.7461 6.2621C21.1064 5.64542 18.141 6.60451 16.6595 9.33996C16.5284 9.582 16.2753 9.73278 16 9.73278C15.7248 9.73278 15.4716 9.582 15.3405 9.33996C13.859 6.60451 10.8937 5.64542 8.25397 6.2621Z" fill="#F781BF"></path>
                            </svg>
                        </a>
                    <? endif; ?>
                    <div class="basket-item-block-actions">
                        <span class="basket-item-actions-remove" data-entity="basket-item-delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.29289 3.29289C9.48043 3.10536 9.73478 3 10 3H14C14.2652 3 14.5196 3.10536 14.7071 3.29289C14.8946 3.48043 15 3.73478 15 4V5H9V4C9 3.73478 9.10536 3.48043 9.29289 3.29289ZM7 5V4C7 3.20435 7.31607 2.44129 7.87868 1.87868C8.44129 1.31607 9.20435 1 10 1H14C14.7957 1 15.5587 1.31607 16.1213 1.87868C16.6839 2.44129 17 3.20435 17 4V5H19H21C21.5523 5 22 5.44772 22 6C22 6.55228 21.5523 7 21 7H20V20C20 20.7957 19.6839 21.5587 19.1213 22.1213C18.5587 22.6839 17.7957 23 17 23H7C6.20435 23 5.44129 22.6839 4.87868 22.1213C4.31607 21.5587 4 20.7957 4 20V7H3C2.44772 7 2 6.55228 2 6C2 5.44772 2.44772 5 3 5H5H7ZM6 7V20C6 20.2652 6.10536 20.5196 6.29289 20.7071C6.48043 20.8946 6.73478 21 7 21H17C17.2652 21 17.5196 20.8946 17.7071 20.7071C17.8946 20.5196 18 20.2652 18 20V7H16H8H6Z" fill="#999999" />
                            </svg>
                        </span>
                        {{#SHOW_LOADING}}
                            <div class="basket-items-list-item-overlay"></div>
                        {{/SHOW_LOADING}}
                    </div>
                </div>
            <? } ?>

        </div>
    </div>
    </div>
    {{/SHOW_RESTORE}}
    </div>
</script>