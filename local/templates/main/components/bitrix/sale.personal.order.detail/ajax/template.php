<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Page\Asset;


CJSCore::Init(array('clipboard', 'fx'));

$APPLICATION->SetTitle("");

if (!empty($arResult['ERRORS']['FATAL'])) {
    foreach ($arResult['ERRORS']['FATAL'] as $error) {
        ShowError($error);
    }

    $component = $this->__component;

    if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED])) {
        $APPLICATION->AuthForm('', false, false, 'N', false);
    }
} else {
    if (!empty($arResult['ERRORS']['NONFATAL'])) {
        foreach ($arResult['ERRORS']['NONFATAL'] as $error) {
            ShowError($error);
        }
    }
    ?>
    <div class="sale-order-detail">
        <div class="sale-order-detail__close" onclick="BX.Sale.PersonalOrderComponent.PersonalOrderList.closeDetailOrder()">
             <?= classBuildSCG::buildSVG('big-cross'); ?>
        </div>
        <div class="sale-order-detail__header">
            <h1 class="sale-order-detail__title">
                <?= Loc::getMessage('ORDER_TITLE', [
                    '#ORDER_NUMBER#' => $arResult["ACCOUNT_NUMBER"],
                    '#ORDER_DATE#' => $arResult['ORDER_DAY_CREATED'] . ' ' . Loc::getMessage('MONTH_' . $arResult['ORDER_MONTH_CREATED']),
                ]) ?>
            </h1>
            <div class="sale-order-detail__status">
                <? if ($arResult['CANCELED'] == 'Y') { ?>
                    <div class="order-status-cancel">
                        <?= Loc::getMessage('SPOD_ORDER_CANCELED') ?>
                    </div>
                <? } else { ?>
                    <div class="order-status-<?= $arResult['STATUS']['ID'] ?>">
                        <?= $arResult['STATUS']['NAME'] ?>
                    </div>
                <? } ?>
            </div>
        </div>

        <div class="sale-order-detail__body order-detail-body<?= $arResult['CANCELED'] == 'Y' ? ' order--cancelled' : '' ?>">
            <div class="sale-order-detail__info order-detail-info">
                <div class="order-detail-body__line">
                    <div class="order-detail-info__part"><?= Loc::getMessage('BUYER') ?></div>
                    <div class="order-detail-info__part">
                        <?= $arResult['USER_ORDER_PROPS']['NAME'] ?> <?= $arResult['USER_ORDER_PROPS']['SURNAME'] ?> <?= $arResult['USER_ORDER_PROPS']['PATRONYMIC'] ?>
                        <div class="order-detail-info__desc">
                            <?= $arResult['USER_ORDER_PROPS']['PHONE'] ?>
                        </div>
                        <div class="order-detail-info__desc">
                            <?= $arResult['USER_ORDER_PROPS']['EMAIL'] ?>
                        </div>
                    </div>
                </div>
                <div class="order-detail-body__line">
                    <div class="order-detail-info__part"><?= Loc::getMessage('DELIVERY') ?></div>
                    <div class="order-detail-info__part">
                        <?= $arResult['ORDER_DELIVERY']['NAME'] ?>
                        <? if (false) {//Вдруг заходят вывести описание доставки ?>
                            <div class="order-detail-info__desc"><?= $arResult['ORDER_DELIVERY']['DESCRIPTION'] ?></div>
                        <? } ?>
                    </div>
                </div>
                <div class="order-detail-body__line">
                    <div class="order-detail-info__part"><?= Loc::getMessage('ADDRESS_DELIVERY') ?></div>
                    <div class="order-detail-info__part"><?= $arResult['ORDER_ADDRESS'] ?></div>
                </div>
                <div class="order-detail-body__line">
                    <div class="order-detail-info__part"><?= Loc::getMessage('COAST_DELIVERY') ?></div>
                    <div class="order-detail-info__part"><?= $arResult['PRICE_DELIVERY_FORMATED'] ?></div>
                </div>
                <div class="order-detail-body__line">
                    <div class="order-detail-info__part"><?= Loc::getMessage('PAYMENT') ?></div>
                    <div class="order-detail-info__part"><?= $arResult['ORDER_PAYMENT'] ?></div>
                </div>
                <div class="order-detail-body__line">
                    <div class="order-detail-info__part"><?= Loc::getMessage('TOTAL_PRICE') ?></div>
                    <div class="order-detail-info__part">
                        <div class="order-detail-info__payment order-pay">
                            <?= $arResult['SUM_REST_FORMATED'] ?>
                            <? if ($arResult['CANCELED'] != 'Y' && $arResult['STATUS']['ID'] != 'F') { ?>
                                <div class="order-pay__toggle"><?= Loc::getMessage('SPOD_ORDER_PAY') ?></div>
                            <? } ?>
                        </div>
                    </div>
                    <? /*if (count($arResult['PAY'])) { ?>
                        <div class="order-pay__buttons">
                            <? foreach ($arResult['PAY'] as $pay) {
                                echo $pay;
                            } ?>
                        </div>
                    <? } */?>
                </div>
            </div>
            <div class="order-detail-products">
                <div class="order-detail-products__header">
                    <div class="order-detail-products__line">
                        <div class="order-detail-products__part">
                            <?= declOfNum(count($arResult['BASKET']), ['Товар заказан:', 'Товара заказано:', 'Товаров заказано:']) ?>
                        </div>
                        <div class="order-detail-products__part">
                            <div class="order-detail-products__count"><?= Loc::getMessage('COUNT') ?></div>
                            <div class="order-detail-products__price"><?= Loc::getMessage('SPOD_PRICE') ?></div>
                        </div>
                    </div>
                </div>
                <div class="order-detail-products__body">
                    <? foreach ($arResult['BASKET'] as $basketItem) { ?>
                        <div class="order-detail-products__line">
                            <div class="order-detail-products__part">
                                <div class="order-detail-products__image">
                                    <? if ($basketItem['PICTURE']['SRC'] <> '') {
                                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $basketItem['PICTURE']['SRC'])) {
                                            $imageSrc = $basketItem['PICTURE']['SRC'];
                                        } else {
                                            $imageSrc = $this->GetFolder() . '/images/no_photo.png';
                                        }
                                    } else {
                                        $imageSrc = $this->GetFolder() . '/images/no_photo.png';
                                    } ?>
                                    <img src="<?= $imageSrc ?>" alt="">
                                </div>
                                <div class="order-detail-products__name">
                                    <a href="<?= $basketItem['DETAIL_PAGE_URL'] ?>" class="order-detail-products__link">
                                        <?= htmlspecialcharsbx($basketItem['NAME']) ?>
                                    </a>
                                    <div class="order-detail-products__count">
                                    <?= $basketItem['QUANTITY'] ?>
                                </div>
                                <div class="order-detail-products__price">
                                    <?= $basketItem['PRICE_FORMATED'] ?>
                                </div>
                                </div>
                               
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
    </div>
<?}?>