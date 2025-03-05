<?php

namespace ApiLib\API\Basket;


use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\API\Product\ProductService;
use Bitrix\Sale\BasketBase;
use Illuminate\Support\Collection;

class BasketService{

    /**
     * Объект корзины текущего пользователя
     *
     * @var \Bitrix\Sale\BasketBase
     */
    private BasketBase $basket;

    private DiscountService $discountService;

    function __construct(){
        \Bitrix\Main\Loader::includeModule('sale');
        $this->basket = \Bitrix\Sale\Basket::loadItemsForFUser(
            \Bitrix\Sale\Fuser::getId(),
            \Bitrix\Main\Context::getCurrent()->getSite()
        );

        $this->discountService = new DiscountService($this->basket);
    }

    private function isExistProduct(int $productId)
    {
        $productByBasketItem = false;

        $basketItems = $this->basket->getBasketItems();

        if($basketItems) {
            foreach($basketItems as $basketItem) {
                if($basketItem->getField('PRODUCT_ID') == $productId) {
                    $productByBasketItem = $basketItem->getId();
                    break;
                }
            }
        }

        return $productByBasketItem;
    }

    /**
     * Добавление товара в корзину
     *
     * @param Int $productId
     * @param Int $quantity
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function store(int $productId, int $quantity)
    {
        if ($itemId = $this->isExistProduct($productId)) {
            return $this->update($itemId, $quantity);
        }

        $basketItem = $this->basket->createItem('catalog', $productId, null);
        $basketItem->setFields([
            'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
            'LID' => 's1',
            'PRODUCT_PROVIDER_CLASS' => '\Bitrix\Catalog\Product\CatalogProvider',
            'QUANTITY' => $quantity
        ]);
        $result = $this->basket->save();

        if ($result->isSuccess()){
            return [
                'success' => true,
                'id' => $basketItem->getId(),
                'message' => 'Product №' . $productId . ' added to basket at quantity ' . $quantity
            ];
        }

        AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result->getErrorMessages()],  [['name' => 'Content-Type', 'value' => 'application/json']]);

        return [
            'success' => false,
            'message' => $result->getErrorMessages()
        ];
    }

    public function update(Int $itemId, Int $quantity)
    {
        $item = $this->basket->getItemById($itemId);
        $item->setField('QUANTITY', $quantity);

        $result = $this->basket->save();

        if ($result->isSuccess()){
            return [
                'success' => true,
                'id' => $item->getId(),
                'message' => 'Item id ' . $itemId . ' added to basket at quantity ' . $quantity
            ];
        }

        AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result->getErrorMessages()],  [['name' => 'Content-Type', 'value' => 'application/json']]);

        return [];
    }

    /**
     * Получение содержимого корзины
     *
     * @return array
     */
    public function index()
    {
        $result = [];
        $this->discountService->appleDiscount();
        foreach ($this->basket->getBasketItems() as $basketItem){
            $result[] = [
                'ID' => $basketItem->getId(),
                'NAME' => $basketItem->getField('NAME'),
                'QUANTITY' => $basketItem->getField('QUANTITY'),
                'PRICE' => $basketItem->getField('PRICE'),
                'BASE_PRICE' => $basketItem->getField('BASE_PRICE'),
                'DISCOUNT_PRICE' => $basketItem->getField('DISCOUNT_PRICE'),
                'PRODUCT_ID' => $basketItem->getField('PRODUCT_ID'),
                'DELAY' => $basketItem->getField('DELAY'),
                'CAN_BUY' => $basketItem->getField('CAN_BUY'),
                'WEIGHT' => $basketItem->getField('WEIGHT'),
                'NOTES' => $basketItem->getField('NOTES'),
            ];
        }

        return $this->appendMaxQuantiqy($this->appendPictures($result));
    }

    private function appendPictures($baskets): array
    {
        $colBasket = new Collection($baskets);
        $ids = $colBasket->pluck('PRODUCT_ID')->toArray();
        $sProduct = new ProductService();
        $product = $sProduct->indexParentImgByIds($ids)['items'];
        $picture = (new Collection($product))->pluck('PREVIEW_PICTURE','ID')->toArray();

        foreach($baskets as &$item) {
            $item['preview_src'] = \CFile::GetPath($picture[$item['PRODUCT_ID']]);
        }

        return $baskets;
    }

    private function appendMaxQuantiqy($baskets): array
    {
        $colBasket = new Collection($baskets);
        $ids = $colBasket->pluck('PRODUCT_ID')->toArray();
        $sProduct = new ProductService();
        $product = $sProduct->indexByIds($ids)['items'];
        $quantity = (new Collection($product))->pluck('QUANTITY','ID')->toArray();

        foreach($baskets as &$item) {
            $item['max_quantity'] = $quantity[$item['PRODUCT_ID']];
        }

        return $baskets;
    }

    public function destroy($itemId)
    {
        if ($item = $this->basket->getItemById($itemId)) {
            $item->delete();

            $result = $this->basket->save();

            if ($result->isSuccess()) {
                return [
                    'success' => true,
                    'message' => 'Item id ' . $itemId . ' destroyed',
                ];
            }

            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result->getErrorMessages()],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => 'Unknown error'],  [['name' => 'Content-Type', 'value' => 'application/json']]);

        return [];
    }

}