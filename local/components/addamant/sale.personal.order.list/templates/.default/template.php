<?
/**
 * @var array $arResult
 * @var array $arParams ;
 * @global array $APPLICATION ;
 * @var array $templateFolder ;
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs($templateFolder . '/script.js');
CJSCore::Init(array('clipboard', 'fx'));

Loc::loadMessages(__FILE__);

$javascriptParams = array(
    "url" => CUtil::JSEscape($this->__component->GetPath() . '/ajax.php'),
    "templateFolder" => CUtil::JSEscape($templateFolder),
    "paymentList" => $paymentChangeData
);
$javascriptParams = CUtil::PhpToJSObject($javascriptParams);

if (!empty($arResult['ERRORS']['FATAL'])) {
    foreach ($arResult['ERRORS']['FATAL'] as $error) {
        ShowError($error);
    }
    $component = $this->__component;
    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }

} else {
    if (count($arResult['ORDERS']) == 0) {
        $this->SetViewTarget("empty_orders");
        echo 'main-profile-orders--empty-orders';
        $this->EndViewTarget(); ?>
        <div class="main-profile-orders__empty-orders empty-orders">
            <div class="empty-orders__title">У вас пока нет ни одного заказа.</div>
            <div class="empty-orders__subtitle">Тут будут отображаться все ваши заказы.</div>
            <a class="empty-orders__link" href="/catalog">В каталог</a>
        </div>
    <?php } else {
        foreach ($arResult['ORDERS'] as $key => $order) { ?>
            <?php
                global  $USER;
                if ($USER->IsAdmin()) {
                    //echo "<pre>"; var_dump($order); echo "</pre>";
                }
            ?>
            <div class="main-profile-orders__item order-item">
                <div class="order-item__header">
                    <div class="order-item__header-col order-item__col--3 order-item__col--left order-item__header-order_number">
                        <a href="javascript:void(0);"
                           onclick="BX.Sale.PersonalOrderComponent.PersonalOrderList.showDetailOrder(<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>, <?= $javascriptParams ?>)">
                            <?= Loc::getMessage('SPOL_TPL_ORDER') ?>
                            №<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>
                        </a>
                    </div>
                    <div class="order-item__header-col order-item__col--5 order-item__header-info">
                        <div class="order-item__header-col order-item__col--center">
                            <?= declOfNum(count($order['BASKET_ITEMS']),
                                array(
                                    Loc::getMessage('SPOL_TPL_GOOD'),
                                    Loc::getMessage('SPOL_TPL_TWO_GOODS'),
                                    Loc::getMessage('SPOL_TPL_GOODS')
                                )
                            ) ?>
                        </div>
                        <div class="order-item__header-col order-item__col--center">
                            <?= $order['ORDER']['DATE_INSERT_FORMATED'] ?>
                        </div>
                        <div class="order-item__header-col order-item__col--center">
                            <?= $order['ORDER']['FORMATED_PRICE'] ?>
                        </div>
                    </div>
                    <div class="order-item__header-col order-item__col--3 order-item__col--right order-item__header-status">
                        <? if ($order['ORDER']['CANCELED'] == 'Y') { ?>
                            <div class="order-status-cancel">
                                <?= Loc::getMessage('SPOL_TPL_ORDER_CANCELED') ?>
                            </div>
                        <? } else { ?>
                            <div class="order-status-<?= $order['ORDER']['STATUS_ID'] ?>">
                                <? if ($status = CSaleStatus::GetByID($order['ORDER']['STATUS_ID'])) {
                                    echo $status['NAME'];
                                } ?>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <div class="order-item__content<?= $order['ORDER']['CANCELED'] == 'Y' ? ' order--cancelled' : '' ?>">
                    <div class="order-item__content-col order-item__col--7">
                        <div class="order-item__products">
                            <? $k = 0 ?>
                            <? foreach ($order['BASKET_ITEMS'] as $item) { ?>
                                <? $k++ ?>
                                <? if ($k > 3) continue ?>
                                <div class="order-item__products-item">
                                    <a href="<?= $item['DETAIL_PAGE_URL'] ?>" class="order-item__products-link">
                                        <? $CIBlockElement = CIBlockElement::GetByID($item['PRODUCT_ID']);

                                        $image = SITE_TEMPLATE_PATH . '/images/icons/no_photo.png';

                                        if ($arResultElement = $CIBlockElement->GetNext()) {
                                            if ($arResultElement['PREVIEW_PICTURE']) {
                                                $image = CFile::GetPath($arResultElement['PREVIEW_PICTURE']);
                                            } elseif ($arResultElement['DETAIL_PICTURE']) {
                                                $image = CFile::GetPath($arResultElement['DETAIL_PICTURE']);
                                            }

                                            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $image)) {
                                                $image = SITE_TEMPLATE_PATH . '/images/icons/no_photo.png';
                                            }
                                        } ?>
                                        <img src="<?= $image ?>" alt="<?= $order['BASKET_ITEMS']['NAME'] ?>"
                                             title="<?= $order['BASKET_ITEMS']['NAME'] ?>"
                                             class="order-item__products-image">
                                    </a>
                                </div>
                            <? } ?>
                            <? if (count($order['BASKET_ITEMS']) > 3) { ?>
                                <div class="order-item__products-item order-item__other-products order-item__other-products--full">
                                    <a href="javascript:void(0);"
                                       onclick="BX.Sale.PersonalOrderComponent.PersonalOrderList.showDetailOrder(<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>, <?= $javascriptParams ?>)">
                                        +<?= count($order['BASKET_ITEMS']) - 3 ?>
                                    </a>
                                </div>
                            <? } ?>
                            <div class="order-item__products-item order-item__other-products order-item__other-products--middle">
                                <a href="javascript:void(0);"
                                   onclick="BX.Sale.PersonalOrderComponent.PersonalOrderList.showDetailOrder(<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>, <?= $javascriptParams ?>)">
                                    +<?= count($order['BASKET_ITEMS']) - 2 ?>
                                </a>
                            </div>
                            <div class="order-item__products-item order-item__other-products order-item__other-products--small">
                                <a href="javascript:void(0);"
                                   onclick="BX.Sale.PersonalOrderComponent.PersonalOrderList.showDetailOrder(<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>, <?= $javascriptParams ?>)">
                                    +<?= count($order['BASKET_ITEMS']) - 1 ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="order-item__content-col order-item__col--4">
                        <div class="order-item__action">
                            <? if ($order['ORDER']['CANCELED'] != 'Y' && $order['ORDER']['CAN_CANCEL'] == 'Y' && $order['ORDER']['STATUS_ID'] != 'F') { ?>
                                <a class="order-item__link"
                                   href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_CANCEL"]) ?>">
                                    <?= Loc::getMessage('SPOL_TPL_CANCEL_ORDER') ?>
                                </a>
                            <? } else { ?>
                                <a class="order-item__link"
                                   href="javascript:void(0);">
                                </a>
                            <? } ?>
                            <? if (false) { ?>
                                <a class="order-item__link"
                                   href="<?= htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"]) ?>">
                                    <?= Loc::getMessage('SPOL_TPL_REPEAT_ORDER') ?>
                                </a>
                            <? } ?>
                            <a class="order-item__link"
                               href="javascript:void(0);"
                               onclick="BX.Sale.PersonalOrderComponent.PersonalOrderList.showDetailOrder(<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER']) ?>, <?= $javascriptParams ?>)">
                                <?= Loc::getMessage('SPOL_TPL_MORE_ON_ORDER') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <? }
        echo str_replace('/local/ajax/order_list.php', '/personal/orders/', $arResult["NAV_STRING"]);
        if ($arParams['AJAX'] != 'Y') {
            if ($_REQUEST["filter_history"] !== 'Y') {
                ?>
                <script>
                    BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
                </script>
            <? }
        }
    }
}
?>