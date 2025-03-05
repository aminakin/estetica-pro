<?php

namespace ApiLib\API\Order;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\API\Order\Builder\OrderBuilder;
use ApiLib\API\Order\Builder\OrderBuilderInterface;
use ApiLib\API\Order\Builder\OrderDirector;
use ApiLib\API\Product\ProductService;
use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Sale\Internals\BasketTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\OrderTable;
use Illuminate\Support\Collection;

class OrderService
{
    private OrderDirector $orderDirector;

    private OrderBuilderInterface $builder;

    private array $pagination = [];
    private int $ttl = 0;

    public function __construct()
    {
        Loader::includeModule('sale');
        $this->builder = new OrderBuilder();
        $this->orderDirector = new OrderDirector();
        $this->orderDirector->setBuilder($this->builder);

        $this->pagination['limit'] = 30;
        $this->pagination['offset'] = 0;

        $this->ttl = (int)BitrixSettings::getApiVarByName('TTL');
    }

    public function show(HttpRequest $request)
    {
        $this->requestTransformUpper($request);
        $result_message = $this->orderDirector->buildVirtualOrder($request);
        if (isset($result_message['ERROR'])) {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result_message],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }
        if (isset($result_message['WARNING'])) {
            AdvancedResponse::response(400, ['TYPE' => 'WARNING', 'MESSAGE' => $result_message],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return $result_message;
    }

    public function refresh(HttpRequest $request)
    {
        $this->requestTransformUpper($request);
        $result_message = $this->orderDirector->refreshVirtualOrder($request);
        if (isset($result_message['ERROR'])) {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result_message],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }
        if (isset($result_message['WARNING'])) {
            AdvancedResponse::response(400, ['TYPE' => 'WARNING', 'MESSAGE' => $result_message],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return $result_message;
    }

    public function save(HttpRequest $request)
    {
        $this->requestTransformUpper($request);
        $result_message = $this->orderDirector->save($request);
        file_put_contents('/var/www/esteticapro/data/www/estetica-pro.ru/req.log', var_export($result_message, true), FILE_APPEND|LOCK_EX);
        if (isset($result_message['ERROR'])) {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result_message],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }
        if (isset($result_message['WARNING'])) {
            AdvancedResponse::response(400, ['TYPE' => 'WARNING', 'MESSAGE' => $result_message],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return $result_message;
    }

    private function requestTransformUpper(HttpRequest &$request)
    {
        $vals = $request->toArray();
file_put_contents('/var/www/esteticapro/data/www/estetica-pro.ru/req.log', var_export($vals, true), FILE_APPEND|LOCK_EX);
        foreach ($vals as $key => $val) {
            $request->set(mb_strtoupper($key), $val);
        }
    }

    public function index(HttpRequest $request)
    {
        $query = OrderTable::query();

        $query->addSelect('*');

        $this->buildFilter($query, $request);
        $this->buildSort($query, $request);
        $this->buildPagination($query, $request);

        $query->setCacheTtl($this->ttl);

        $exec = $query->exec();

        $this->pagination['count'] = $exec->getCount();

        $res = $exec->fetchAll();
        $this->orderListAppendData($res);

        return ['items' => $res, 'pagination' => $this->pagination];
    }

    private function orderListAppendData(&$orderList)
    {
        $orderCollection = new Collection($orderList);
        $orderIds = $orderCollection->pluck('ID')->toArray();
        $query = BasketTable::query();
        $query->setSelect([
            "ID", "LID", "MODULE", "PRODUCT_ID", "QUANTITY", "WEIGHT",
            "DELAY", "CAN_BUY", "PRICE", "CUSTOM_PRICE", "BASE_PRICE",
            'PRODUCT_PRICE_ID', 'PRICE_TYPE_ID', "CURRENCY", 'BARCODE_MULTI',
            "RESERVED", "RESERVE_QUANTITY", "NAME", "CATALOG_XML_ID",
            "VAT_RATE", "NOTES", "DISCOUNT_PRICE","PRODUCT_PROVIDER_CLASS",
            "CALLBACK_FUNC", "ORDER_CALLBACK_FUNC", "PAY_CALLBACK_FUNC",
            "CANCEL_CALLBACK_FUNC", "DIMENSIONS", "TYPE", "SET_PARENT_ID",
            "DETAIL_PAGE_URL", "FUSER_ID", 'MEASURE_CODE', 'MEASURE_NAME',
            'ORDER_ID', 'DATE_INSERT', 'DATE_UPDATE', 'PRODUCT_XML_ID',
            'SUBSCRIBE', 'RECOMMENDATION', 'VAT_INCLUDED', 'SORT',
            'DATE_REFRESH', 'DISCOUNT_NAME', 'DISCOUNT_VALUE', 'DISCOUNT_COUPON',
            'XML_ID', 'MARKING_CODE_GROUP'
        ]);
        $query->addFilter('ORDER_ID', $orderIds);
        $exec = $query->exec();
        $baskets = $exec->fetchAll();

        $basketsCollection = new Collection($baskets);
        $propductIds = $basketsCollection->pluck('PRODUCT_ID')->toArray();
        $products = (new ProductService())->index(['id' => $propductIds, 'limit' => 0]);
        $productCollestion = new Collection($products['items']);

        foreach($orderList as &$item) {
            $bItems = $basketsCollection->where('ORDER_ID', $item['ID']);

            $bItems = $bItems->map(function($item, $key) use ($productCollestion) {
                $item['preview_src'] = \CFile::ResizeImageGet($productCollestion->where('ID', $item['PRODUCT_ID'])->first()['PREVIEW_PICTURE'],['height' => 180, 'width' => 180]);
                return $item;
            });
            $item['items'] = $bItems->toArray();
        }
    }

    private function buildFilter(Query &$query, HttpRequest $request)
    {
        global $USER;

        $filterStatus = $request->get('filter_status');
        $filterCanceled = $request->get('filter_canceled');

        $query->addFilter("USER_ID", $USER->GetID());

        if (!empty($filterStatus))
            $query->addFilter('STATUS_ID', $filterStatus);

        if (!empty($filterCanceled))
            $query->addFilter('CANCELED', $filterCanceled);
    }

    private function buildSort(Query &$query, HttpRequest $request)
    {
        $query->addOrder('ID', 'DESC');
    }

    private function buildPagination(Query &$query, HttpRequest $request): void
    {
        $pagination_limit = !empty($request->get('limit')) ? $request->get('limit') : $this->pagination['limit'];
        $pagination_offset = !empty($request->get('offset')) ? $request->get('offset') : $this->pagination['offset'];

        $query->setLimit($pagination_limit);
        $query->setOffset($pagination_offset);
        $query->countTotal(true);
    }

    public function updateCanceled(HttpRequest $request, int $orderId)
    {
        $order = Order::load($orderId);
        $order->setField('CANCELED', 'Y');
        $result = $order->save();
        if ($result->isSuccess()) {
            return 'OK';
        }
        else {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => 'Don\'t save'], [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return [];
    }
}