<?php

namespace Local\Discounts;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\BasketBase;
use Bitrix\Sale\DiscountCouponsManager;
use Bitrix\Sale\Fuser;
use Bitrix\Sale\Order;
use CAllIBlock;
use CCatalogDiscount;
use CCatalogProduct;
use CCatalogSku;
use CCurrencyRates;
use CIBlockElement;
use CIBlockSection;
use CModule;
use CSaleUser;
use CUser;

class DiscountCore
{

    protected static $isAdmin = false;
    protected static $userGroups = [];
    protected static $basket = [];


    protected static $currentFuser = null;
    protected static $giftModuleIsWorkig = false;
    protected static $disconts = [];
    protected static $discountPrices = [];
    protected static $fullBasketPrice = 0;


    protected static function getFinalPriceInCurrency($item_id, $sale_currency = 'RUB')
    {
        global $USER;
        $currency_code = 'RUB';

        // Простой товар, без торговых предложений (для количества равному 1)
        $price = CCatalogProduct::GetOptimalPrice($item_id, 1, $USER->GetUserGroupArray(), 'N');

        if (!$price || !isset($price['PRICE'])) {
            return false;
        }
        if (isset($price['CURRENCY'])) {
            $currency_code = $price['CURRENCY'];
        }
        if (isset($price['PRICE']['CURRENCY'])) {
            $currency_code = $price['PRICE']['CURRENCY'];
        }

        $final_price = $price['PRICE']['PRICE'];
        $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), 'N', 2);
        if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
            $final_price = CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
        }
        if ($currency_code != $sale_currency) {
            $final_price = CCurrencyRates::ConvertCurrency($final_price, $currency_code, $sale_currency);
        }

        return $final_price;
    }


    /**
     * @param $product_id
     */
    private static function getCatalogQuantityByProduct($product_id)
    {
        $iterator = \Bitrix\Catalog\Model\Product::getList([
            'select' => [
                'QUANTITY'
            ],
            'filter' => ['=ID' => $product_id]
        ]);
        $result = $iterator->fetch();
        unset($iterator);
        if (empty($result)) {
            return false;
        }

        return (int)$result['QUANTITY'];
    }

    /**
     * Add gifts universal
     * @param $prodId
     * @param $quantity
     * @return array|void
     */
    protected static function addGift($prodId, $quantity)
    {

        global $USER;
        $productByBasketItem = null;
        $bProductInBasket = false;

        /* сть ли такой товар в корзине */
        if (is_array($prodId)) {
            foreach (self::$basket as $basketItem) {
                if (in_array($basketItem->getField('PRODUCT_ID'), $prodId)) {
                    $productByBasketItem = $basketItem;
                    $bProductInBasket = true;
                }
            }
        } else {
            foreach (self::$basket as $basketItem) {
                if ($basketItem->getField('PRODUCT_ID') == $prodId) {
                    $productByBasketItem = $basketItem;
                    $bProductInBasket = true;
                }
            }
        }

        //check real quantity
        $realProductQuantity = self::getCatalogQuantityByProduct($prodId);
        if ($realProductQuantity < $quantity) {
            if ($bProductInBasket) {
                $quantity = $realProductQuantity;
                $quantity = $quantity - (int)$productByBasketItem->getQuantity();
            } else {
                $quantity = $realProductQuantity;
            }
        }
        //

        if ($bProductInBasket) {
            /* если в корзине был такой товар - добавить ему подарков */
            $basketPropertyCollection = $productByBasketItem->getPropertyCollection();
            $basketPropertyCollection->getPropertyValues();

            $isAlredyAdded = false;
            $addedValue = 0;
            foreach ($basketPropertyCollection as $propertyItem) {
                if ($propertyItem->getField('CODE') == 'INFO') {
                    if ($propertyItem->getField('VALUE')) {
                        $addedValue = (int)$propertyItem->getField('VALUE');
                        $isAlredyAdded = true;
                    }
                }
            }
            /* basket real price */
            $itemPrice = ceil((float)self::getFinalPriceInCurrency($productByBasketItem->getProductId()));
            if ((float)self::$discountPrices[$productByBasketItem->getBasketCode()]['PRICE'] < $itemPrice) {
                $itemPrice = ceil((float)self::$discountPrices[$productByBasketItem->getBasketCode()]['PRICE']);
            }
            if (!$isAlredyAdded) {
                /* в корзине был подобный товар - НЕ подарок */
                $basketPropertyCollection->setProperty([
                    'INFO' => [
                        'NAME' => 'Подарков:',
                        'CODE' => 'INFO',
                        'VALUE' => $quantity,
                        'SORT' => 100
                    ]
                ]);
                $basketPropertyCollection->save();

                $productByBasketItem->setField('CUSTOM_PRICE', 'Y');
                $productByBasketItem->setField('QUANTITY', $productByBasketItem->getQuantity() + (int)$quantity);

                //кол-во_изначальное * изначальную_цену + колво_подарков * цена+подарков / новое_количеств (округлить до наименьшего если меньше чем Х.5)
                $productByBasketItem->setField('PRICE', ceil((((int)$productByBasketItem->getQuantity() - (int)$quantity) * $itemPrice + (int)$quantity * 0) / (int)$productByBasketItem->getQuantity()));
            } else {
                /* В корзине уже был подобный товар с пометкой ПОДАРКА  и ИЗМЕНЕН в количестве, подарки не добавляем, а делаем слияние */
                if ($productByBasketItem->getField('CUSTOM_PRICE') != 'Y') {
                    $productByBasketItem->setField('CUSTOM_PRICE', 'Y');
                    $productByBasketItem->setField('PRICE', ceil((((int)$productByBasketItem->getQuantity() - (int)$quantity) * $itemPrice + (int)$addedValue * 0) / (int)$productByBasketItem->getQuantity()));
                }
            }
            $productByBasketItem->save();
            /* for export */
            $prodId = $productByBasketItem->getField('PRODUCT_ID');
        } else {
            if (is_array($prodId)) {
                $prodId = $prodId[array_rand($prodId)];
            }

            /* в корзине не было товаров like подарок */
            $item = self::$basket->createItem('catalog', $prodId);
            $elem = self::getInfoElement($prodId);
            if ($elem['ACTIVE'] != 'Y' && $elem['QUANTITY'] < $quantity) {
                return;
            }
            $item->setFields(array(
                'PRICE' => 1,
                'CUSTOM_PRICE' => 'Y',
                'QUANTITY' => $quantity,
                'CATALOG_XML_ID' => $elem['CATALOG_XML_ID'],
                'PRODUCT_XML_ID' => $elem['EXTERNAL_ID'],
                'CURRENCY' => Bitrix\Currency\CurrencyManager::getBaseCurrency(),
                'LID' => Bitrix\Main\Context::getCurrent()->getSite(),
                'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
            ));

            /* before property add */
            $item->save();
            /**/
            $basketPropertyCollection = $item->getPropertyCollection();
            $basketPropertyCollection->setProperty(['INFO' => [
                'NAME' => 'Информация подарков',
                'CODE' => 'INFO',
                'VALUE' => $quantity,
                'SORT' => 100
            ]]);
            $basketPropertyCollection->save();
        }

        return ['ID' => $prodId, 'QUANTITY' => $quantity];
    }


    /**
     * remove Gifts from basket bi ID
     * @param $prodId - mixed
     */
    protected static function removeGift($prodId)
    {
        if (is_array($prodId)) {
            foreach (self::$basket as $basketItem) {
                if (in_array($basketItem->getField('PRODUCT_ID'), $prodId) && $basketItem->getField('CUSTOM_PRICE') == 'Y') {
                    self::removeGiftItem($basketItem);
                }
            }
        } else {
            foreach (self::$basket as $basketItem) {
                if ($basketItem->getField('PRODUCT_ID') == $prodId && $basketItem->getField('CUSTOM_PRICE') == 'Y') {
                    self::removeGiftItem($basketItem);
                }
            }
        }
    }
    /**
     * single remove
     * @param $basketItem
     */
    private static function removeGiftItem($basketItem)
    {
        $basketPropertyCollection = $basketItem->getPropertyCollection();
        $basketPropertyCollection->getPropertyValues();

        $addedValue = 0;
        foreach ($basketPropertyCollection as $propertyItem) {
            if ($propertyItem->getField('CODE') == 'INFO') {
                if ($propertyItem->getField('VALUE')) {
                    $addedValue = (int)$propertyItem->getField('VALUE');
                }
                $propertyItem->delete();
                break;
            }
        }
        $basketPropertyCollection->save();

        if ($basketItem->getField('CUSTOM_PRICE') == 'Y') {
            $basketItem->setField('CUSTOM_PRICE', 'N');
            $basketItem->setField('PRICE', $basketItem->getBasePrice());
            if ($addedValue > 0) {
                $newQuantity = $basketItem->getQuantity() - (int)$addedValue;

                if ($newQuantity > 0) {
                    $basketItem->setField('QUANTITY', $newQuantity);
                } else {
                    $basketItem->delete();
                }
            }
        }
        $basketItem->save();
    }

    protected function saveBasket()
    {
        self::$basket->save();
        self::getBasket();
    }

    public static function getInfoElement($offerId)
    {
        CModule::IncludeModule('iblock');
        $elementInfo = CIBlockElement::GetByID($offerId)->fetch();
        $productInfo = CCatalogProduct::GetByID($offerId);
        $url = CAllIBlock::ReplaceDetailUrl($elementInfo['DETAIL_PAGE_URL'], $elementInfo, false, 'E');
        $info = array(
            'QUANTITY' => $productInfo['QUANTITY'],
            'NAME' => $elementInfo['NAME'],
            'URL' => $url,
            'CATALOG_XML_ID' => $elementInfo['IBLOCK_EXTERNAL_ID'],
            'EXTERNAL_ID' => $elementInfo['EXTERNAL_ID'],
            'ACTIVE' => $elementInfo['ACTIVE'],
        );
        return $info;
    }

    /**
     * is offer
     * @param $prod_id
     * @return mixed - main prod id
     */
    private static function getProductByOffer($prod_id)
    {
        $arRealProd = CCatalogSku::GetProductInfo($prod_id);
        if (is_array($arRealProd)) {
            return self::$PRODUCT_ID = $arRealProd['ID'];
        } else {
            return self::$PRODUCT_ID = $prod_id;
        }
    }


    protected static function setItemNewPrice($arItems, $isSimpleMessage = false)
    {
        foreach ($arItems as $item) {
            $productByBasketItem =  self::$basket->getItemById($item['BASKET_ID']);
            if( $item['NEW_PRICE'] > 0){
                $productByBasketItem->setFields([
                    'CUSTOM_PRICE' => 'Y',
                    'DISCOUNT_PRICE' => $item['PRICE'] - $item['NEW_PRICE'],
                    'PRICE' => $item['NEW_PRICE']
                ]);

                $basketPropertyCollection = $productByBasketItem->getPropertyCollection();
                $basketPropertyCollection->getPropertyValues();
                $basketPropertyCollection->setProperty([
                    'OLDPRICE' => [
                        'NAME' => 'Цена до скидки:',
                        'CODE' => 'OLDPRICE',
                        'VALUE' => $item['PRICE'],
                        'SORT' => 100
                    ]
                ]);
                $basketPropertyCollection->save();
            }

        }

        self::$basket->save();
    }


    private static function getProductSections($prod_id)
    {
        CModule::IncludeModule('iblock');
        CModule::IncludeModule('catalog');

        $arRealProd = CCatalogSku::GetProductInfo($prod_id);
        if (is_array($arRealProd)) {
            self::$PRODUCT_ID = $arRealProd['ID'];
        } else {
            self::$PRODUCT_ID = $prod_id;
        }

        $db_old_groups = CIBlockElement::GetElementGroups(self::$PRODUCT_ID, true);
        while ($ar_group = $db_old_groups->Fetch()) {
            $ar_new_groups[] = $ar_group['ID'];
        }
        $nav = CIBlockSection::GetNavChain(false, $ar_new_groups[0]);
        while ($arSectionPath = $nav->GetNext()) {
            $ar_new_groups[] = $arSectionPath['ID'];
        }

        return $ar_new_groups;
    }


    /**
     * init all sales
     */
    public function init(
        &$arUserResult,
        $request,
        &$arParams,
        &$arResult
    ) {

        if (self::$giftModuleIsWorkig) {
            return;
        }
        self::$giftModuleIsWorkig = true;

        self::init_vars();
        self::clearGiftInBasket();
        /* disounts here */

        $discountsSales = new DiscountSales();

        $discountsSales->moduleGifts();

        self::$giftModuleIsWorkig = false;
    }



    public static function init_vars()
    {
        CModule::IncludeModule('iblock');
        CModule::IncludeModule('sale');
        CModule::IncludeModule('catalog');


        self::getUser();
        self::getBasket();
        self::getBasketDiscounts();
        self::getBasketPrice();
    }


    /**
     *
     */
    private static function getUser()
    {
        global $USER;
        self::$userGroups = CUser::GetUserGroup($USER->GetID());
        self::$currentFuser = Fuser::getId();

        /* isAdmin */
        if ($USER->isAdmin()) {
            self::$isAdmin = true;
        } else {
            self::$isAdmin = false;
        }
    }


    /**
     * get current basket
     */
    public static function getBasket()
    {
        Loader::includeModule('sale');
        return self::$basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
    }


    /**
     * get basket discounts
     */
    private static function getBasketDiscounts()
    {
        Loader::includeModule('sale');
        $virtualBasket = self::$basket->createClone();
        if (!$virtualBasket->getOrder()) {
            global $USER;
            $resUs = $USER instanceof CUser ? $USER->GetID() : null;
            $userId = $resUs ?: CSaleUser::GetAnonymousUserID();
            $order = Order::create('s1', $userId);
            $order->appendBasket($virtualBasket);
        } else {
            $order = $virtualBasket->getOrder();
        }
        $discounts = $order->getDiscount();
        $discounts->setExecuteModuleFilter(array('all', 'catalog', 'sale'));
        $discounts->calculate();
        // $order->doFinalAction(true);
        self::$disconts = $discounts->getApplyResult(true);
        self::$discountPrices = self::$disconts['PRICES']['BASKET'];

    }

    /**
     * get full basket price
     */
    private static function getBasketPrice()
    {
        foreach (self::$basket as $basketItem) {
            if ($basketItem->getPrice() > 1 && $basketItem->getQuantity() > 0 && !$basketItem->isDelay() && $basketItem->canBuy()) {
                self::$fullBasketPrice += self::getDiscountPriceByElement($basketItem->getProductId(), (int)$basketItem->getQuantity())['DISCOUNT_PRICE'] * $basketItem->getQuantity();
            }
        }
    }

    /**
     * @param $productId
     * @param $productQuantity
     * @return array|bool
     */
    private static function getDiscountPriceByElement($productId, $productQuantity)
    {
        global $USER;
        $arPrice = CCatalogProduct::GetOptimalPrice($productId, $productQuantity, $USER->GetUserGroupArray(), 'N');
        if (!$arPrice || count($arPrice) <= 0) {
            if (CCatalogProduct::GetNearestQuantityPrice($productId, $productQuantity, $USER->GetUserGroupArray())) {
                $arPrice = CCatalogProduct::GetOptimalPrice($productId, $productQuantity, $USER->GetUserGroupArray(), 'N');
            }
        }
        return $arPrice;
    }

    /**
     * @param $ignoringProdIDs array
     */
    private static function clearGiftInBasket(array $ignoringProdIDs = [])
    {
        foreach (self::$basket as $basketItem) {
            if (in_array($basketItem->getProductId(), $ignoringProdIDs)) {
                continue;
            }

            $isGift = false;
            $isSaleProd = false;
            $basketPropertyCollection = $basketItem->getPropertyCollection();
            $basketPropertyCollection->getPropertyValues();


            foreach ($basketPropertyCollection as $propertyItem) {
                if ($propertyItem->getField('CODE') == 'INFO') {
                    if ($propertyItem->getField('VALUE')) {
                        $isGift = true;
                        $addedValue = (int)$propertyItem->getField('VALUE');
                    }
                    $propertyItem->delete();
                    break;
                }
                if ($propertyItem->getField('CODE') == 'OLDPRICE') {
                    if ($propertyItem->getField('VALUE')) {
                        $isSaleProd = true;
                        $saleValue = (int)$propertyItem->getField('VALUE');
                    }
                    $propertyItem->delete();
                    break;
                }
            }
            $basketPropertyCollection->save();

            if ($isGift) {
                $basketItem->setField('CUSTOM_PRICE', 'N');
                if ($addedValue > 0) {
                    $newQuantity = $basketItem->getQuantity() - (int)$addedValue;
                    if ($newQuantity > 0) {
                        $basketItem->setField('QUANTITY', $newQuantity);
                    } else {
                        $basketItem->delete();
                    }
                }
                $basketItem->save();
            }else if($isSaleProd){
                $basketItem->setField('CUSTOM_PRICE', 'N');
                //             $basketItem->setField('PRICE', $basketItem->getBasePrice());
                if ($saleValue > 0) {
                    if ($newQuantity > 0) {
                        $basketItem->setField('PRICE', $saleValue);
                    }
                }
                $basketItem->save();
            }
        }

        self::saveBasket();
        /**/
    }




}