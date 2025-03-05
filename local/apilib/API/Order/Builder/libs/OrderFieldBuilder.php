<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Sale\Order;
use Bitrix\Sale\Services\Company;
use Bitrix\Sale;
use Bitrix\Main;

class OrderFieldBuilder
{
    private $arUserResult;

    private OrderBuilderInterface $orderBuilder;

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder)
    {
        $this->orderBuilder = $orderBuilder;
        $this->arUserResult = &$this->orderBuilder->getUserResult();
    }

    public function initOrderFields(Order $order)
    {
        $order->setField("USER_DESCRIPTION", $this->arUserResult['ORDER_DESCRIPTION']);
        $order->setField('COMPANY_ID', Company\Manager::getAvailableCompanyIdByEntity($order));

        if ($order->getField('COMPANY_ID') > 0)
        {
            $responsibleGroups = Sale\Internals\CompanyResponsibleGroupTable::getCompanyGroups($order->getField('COMPANY_ID'));
            if (!empty($responsibleGroups) && is_array($responsibleGroups))
            {
                $usersList = [];

                foreach ($responsibleGroups as $groupId)
                {
                    $usersList[] = \CGroup::GetGroupUser($groupId);
                }

                $usersList = array_merge(...$usersList);

                if (!empty($usersList) && is_array($usersList))
                {
                    $usersList = array_unique($usersList);
                    $responsibleUserId = $usersList[array_rand($usersList)];

                    /** @var Main\Entity\Event $event */
                    $event = new Main\Event('sale', 'OnSaleComponentBeforeOrderSetResponsibleUserId', [
                        'ENTITY' => $order,
                        'VALUE' => $responsibleUserId,
                    ]);
                    $event->send();

                    if ($event->getResults())
                    {
                        $result = new Result();
                        /** @var Main\EventResult $eventResult */
                        foreach ($event->getResults() as $eventResult)
                        {
                            if ($eventResult->getType() == Main\EventResult::SUCCESS)
                            {
                                if ($eventResultData = $eventResult->getParameters())
                                {
                                    if (isset($eventResultData['VALUE']) && $eventResultData['VALUE'] != $responsibleUserId)
                                    {
                                        $responsibleUserId = $eventResultData['VALUE'];
                                    }
                                }
                            }
                        }
                    }

                    $order->setField('RESPONSIBLE_ID', $responsibleUserId);
                }

            }
        }

    }
}