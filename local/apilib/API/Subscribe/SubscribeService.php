<?php

namespace ApiLib\API\Subscribe;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\API\Product\ProductService;
use Bitrix\Catalog\Product\SubscribeManager;
use Bitrix\Catalog\ProductTable;
use Bitrix\Catalog\SubscribeTable;
use Bitrix\Main\Loader;
use Illuminate\Support\Collection;

class SubscribeService
{
    private int $userId = 0;

    private ProductService $prodictService;

    public function __construct()
    {
        Loader::includeModule('catalog');

        $this->prodictService = new ProductService();
    }

    public function ids()
    {
        $items = Collection::make($this->getItems());

        return $items->pluck('ITEM_ID')->toArray();
    }

    public function store(array $data)
    {
        global $USER;
        $subscribeManager = new SubscribeManager();
        $contactTypes = $subscribeManager->contactTypes;

        $subscribeData = [
            'USER_CONTACT' => $USER->GetEmail() ? $USER->GetEmail() : false,
            'ITEM_ID' => $data['product_id'],
            'SITE_ID' =>  SITE_ID,
            'CONTACT_TYPE' => SubscribeTable::CONTACT_TYPE_EMAIL,
            'USER_ID' => $USER->GetID() ? $USER->GetID() : false,
        ];

        $subscribeId = $subscribeManager->addSubscribe($subscribeData);

        if(!$subscribeId){
            $errorObject = current($subscribeManager->getErrors());
            $errors = array('error' => true);
            if($errorObject)
            {
                $errors['message'] = $errorObject->getMessage();

                return $errors;
            }
        }

        return $data['product_id'];
    }

    public function delete(int $id)
    {
        $items = $this->getItems();
        $subscribeId = false;

        foreach($items as $item) {
            if ($item['ITEM_ID'] == $id)
                $subscribeId = $item['ID'];
        }

        if ($subscribeId === false)
            AdvancedResponse::response(404, ['TYPE' => 'ERROR', 'MESSAGE' => 'Not found'], [['name' => 'Content-Type', 'value' => 'application/json']]);

        $subscribeManager = new SubscribeManager();
        if(!$subscribeManager->deleteManySubscriptions([$subscribeId], $id)) {
            $errorObject = current($subscribeManager->getErrors());
            if($errorObject) {
                $errors['message'] = $errorObject->getMessage();

                return $errors;
            }
        }

        return true;
    }

    private function getItems()
    {
        $items = [];
        global $USER, $DB;
        if(is_object($USER) && $USER->isAuthorized())
            $this->userId = $USER->getId();

        $filter = array(
            '=SITE_ID' => SITE_ID,
            array(
                'LOGIC' => 'OR',
                array('=DATE_TO' => false),
                array('>DATE_TO' => date($DB->dateFormatToPHP(\CLang::getDateFormat('FULL')), time()))
            )
        );
        if($this->userId)
        {
            $filter['USER_ID'] = $this->userId;
        }
        else
        {
            AdvancedResponse::response(401, ['TYPE' => 'ERROR', 'MESSAGE' => 'Unknown error'], [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        $resultObject = SubscribeTable::getList(
            array(
                'select' => array(
                    'ID',
                    'ITEM_ID',
                    'TYPE' => 'PRODUCT.TYPE',
                    'IBLOCK_ID' => 'IBLOCK_ELEMENT.IBLOCK_ID',
                ),
                'filter' => $filter,
            )
        );

        while($item = $resultObject->fetch())
        {
            $items[] = $item;
        }

        return $items;
    }

    public function index(array $data)
    {
        $items = $this->getItems();

        $productIds = [];
        $offersIds = [];
        foreach($items as $item)
        {
            if ($item['TYPE'] == ProductTable::TYPE_OFFER) {
                $offersIds[] = $item['ITEM_ID'];
            }
            if ($item['TYPE'] == ProductTable::TYPE_PRODUCT) {
                $productIds[] = $item['ITEM_ID'];
            }
        }

        if (count($offersIds)) {
            $offers = $this->prodictService->getProductSkuList(0, [], $offersIds);
            foreach ($offers as $item) {
                $productIds[] = $item['PARENT_ID'];
            }
        }

        $pagination = [];

        if (isset($data['limit']))
            $pagination['limit'] = $data['limit'];
        if (isset($data['offset']))
            $pagination['offset'] = $data['offset'];

        $products = $this->prodictService->getProductCard($productIds, [], [], $pagination);
        foreach ($products['items'] as &$item) {
            if (isset($item['SKU_LIST'])) {
                foreach($item['SKU_LIST'] as $skuKey => $skuItem) {
                    if (array_search($skuItem['ID'], $offersIds) === false) {
                        unset($item['SKU_LIST'][$skuKey]);
                    }
                }
            }
        }

        return $products;
    }
}
