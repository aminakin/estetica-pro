<?php

namespace ApiLib\API\Order\Builder\libs;

use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Bitrix\Sale\Services\Company;
use Bitrix\Sale;

class OrderEntityCompanyBuilder
{
    public function initEntityCompanyIds(Order $order)
    {
        $paymentCollection = $order->getPaymentCollection();
        if ($paymentCollection)
        {
            /** @var Payment $payment */
            foreach ($paymentCollection as $payment)
            {
                if ($payment->isInner())
                    continue;

                $payment->setField('COMPANY_ID', Company\Manager::getAvailableCompanyIdByEntity($payment));
                if ($payment->getField('COMPANY_ID') > 0)
                {
                    $responsibleGroups = Sale\Internals\CompanyResponsibleGroupTable::getCompanyGroups($payment->getField('COMPANY_ID'));
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

                            /** @var \Bitrix\Main\Entity\Event $event */
                            $event = new \Bitrix\Main\Event('sale', 'OnSaleComponentBeforePaymentSetResponsibleUserId', [
                                'ENTITY' => $payment,
                                'VALUE' => $responsibleUserId,
                            ]);
                            $event->send();

                            if ($event->getResults())
                            {
                                $result = new Sale\Result();
                                /** @var \Bitrix\Main\EventResult $eventResult */
                                foreach ($event->getResults() as $eventResult)
                                {
                                    if ($eventResult->getType() == \Bitrix\Main\EventResult::SUCCESS)
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

                            $payment->setField('RESPONSIBLE_ID', $responsibleUserId);
                        }
                    }
                }
            }
        }

        $shipmentCollection = $order->getShipmentCollection();
        if ($shipmentCollection)
        {
            /** @var Sale\Shipment $shipment */
            foreach ($shipmentCollection as $shipment)
            {
                if ($shipment->isSystem())
                    continue;

                $shipment->setField('COMPANY_ID', Company\Manager::getAvailableCompanyIdByEntity($shipment));

                if ($shipment->getField('COMPANY_ID') > 0)
                {
                    $responsibleGroups = Sale\Internals\CompanyResponsibleGroupTable::getCompanyGroups($shipment->getField('COMPANY_ID'));
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

                            /** @var \Bitrix\Main\Entity\Event $event */
                            $event = new \Bitrix\Main\Event('sale', 'OnSaleComponentBeforeShipmentSetResponsibleUserId', [
                                'ENTITY' => $shipment,
                                'VALUE' => $responsibleUserId,
                            ]);
                            $event->send();

                            if ($event->getResults())
                            {
                                $result = new Sale\Result();
                                /** @var \Bitrix\Main\EventResult $eventResult */
                                foreach ($event->getResults() as $eventResult)
                                {
                                    if ($eventResult->getType() == \Bitrix\Main\EventResult::SUCCESS)
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

                            $shipment->setField('RESPONSIBLE_ID', $responsibleUserId);
                        }
                    }
                }
            }
        }
    }
}