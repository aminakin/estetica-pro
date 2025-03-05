<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Main\HttpRequest;
use Bitrix\Sale\Order;
use Bitrix\Sale;

class OrderBasketBuilder
{
    private OrderBuilderInterface $orderBuilder;

    private HttpRequest $request;

    private array $arParams;

    private $arUserResult;

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder)
    {
        $this->orderBuilder = $orderBuilder;
        $this->request = $this->orderBuilder->getRequest();
        $this->arParams = &$this->orderBuilder->getParams();
        $this->arUserResult = &$this->orderBuilder->getUserResult();
    }

    public function initBasket(Order $order)
    {
        $basket = $this->loadBasket();

        $this->arUserResult['QUANTITY_LIST'] = $this->getActualQuantityList($basket);

        $result = $basket->refresh();
        if ($result->isSuccess())
        {
            $basket->save();
        }

        // right NOW we decide to work only with available basket
        // full basket won't update anymore
        $availableBasket = $basket->getOrderableItems();
        if ($availableBasket->isEmpty())
        {
            $this->showEmptyBasket();
        }

        $order->appendBasket($availableBasket);
    }

    private function loadBasket()
    {
        $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);

        /** @var Sale\Basket $basketClassName */
        $basketClassName = $registry->getBasketClassName();

        return $basketClassName::loadItemsForFUser(Sale\Fuser::getId(), $this->orderBuilder->getSiteId());
    }

    protected function getActualQuantityList(Sale\BasketBase $basket)
    {
        $quantityList = [];

        if (!$basket->isEmpty())
        {
            /** @var Sale\BasketItemBase $basketItem */
            foreach ($basket as $basketItem)
            {
                if ($basketItem->canBuy() && !$basketItem->isDelay())
                {
                    $quantityList[$basketItem->getBasketCode()] = $basketItem->getQuantity();
                }
            }
        }

        return $quantityList;
    }

    protected function showEmptyBasket()
    {
        global $APPLICATION;

        $APPLICATION->RestartBuffer();
        echo json_encode([
            'success' => 'N',
            'redirect' => 'Empty basket',
        ]);
        die();
    }

}