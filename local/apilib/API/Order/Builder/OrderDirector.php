<?php

namespace ApiLib\API\Order\Builder;

use Bitrix\Main\HttpRequest;

class OrderDirector
{
    private OrderBuilderInterface $builder;

    public function setBuilder(OrderBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function buildVirtualOrder(HttpRequest $request)
    {
        $this->builder->setRequest($request);
        $this->builder->initProcessOrder();

        return $this->builder->getResult();
    }

    public function refreshVirtualOrder(HttpRequest $request)
    {
        $this->builder->setRequest($request);
        $this->builder->refreshOrder();

        return $this->builder->getResult();
    }

    public function save(HttpRequest $request)
    {
        $this->builder->setRequest($request);

        return $this->builder->save();
    }

    /**
     * example
     * @return void
     */
    public function buildSimpleOrder()
    {
        $this->builder->produceDelivery();
    }

    /**
     * example
     * @return void
     */
    public function buildFullOrder()
    {
        $this->builder->produceDelivery();
        $this->builder->producePayment();
    }
}