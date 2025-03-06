<?
use Bitrix\Main;

class ChangePriceShipment{ 

    static function calcPriceShipment(\Bitrix\Main\Event $event){
        $baseResult = $event->getParameter('RESULT');
        $shipment = $event->getParameter('SHIPMENT');
        $deliveryId = $event->getParameter('DELIVERY_ID');
        $minDeliveryPrice = 10000;

        //Почтa России: Доставка в отделение или почтомат [46]
        if($deliveryId == 46){

            $order = $shipment->getShipmentItemCollection()->getShipment()->getCollection()->getOrder();
            $collection = $order->getPropertyCollection();
            $propertyValue = $collection->getItemByOrderPropertyId(38);

            $fullBasketPrice = $order->getBasket()->getPrice(); // стоимость товаров
            
            $bonusQuantityCheck = $propertyValue->getValue(); // количество бонусов
            if($bonusQuantityCheck !== '-'){
                $bonusQuantity = $bonusQuantityCheck;
            }else{
                $bonusQuantity = 0;
            }

            $curBasketPrice =  intval($fullBasketPrice) - intval($bonusQuantity);

            // #102969
            // #104302
            $startDate = 1704650399; // 08.01.2024
	        $endDate = 1705255199; // 14.01.2024
	        $objDateTime = new DateTime();
	        $todayDate = $objDateTime->getTimestamp();

	        if($startDate < $todayDate && $endDate > $todayDate){
	        	$minDeliveryPrice = 5000;
	        }

            if(isset($curBasketPrice) && $curBasketPrice >= $minDeliveryPrice){
                $baseResult->setDeliveryPrice(0);
            }

        }

        //Служба доставки Ozon: Самовывоз [54]
        if($deliveryId == 54){

            $order = $shipment->getShipmentItemCollection()->getShipment()->getCollection()->getOrder();
            $collection = $order->getPropertyCollection();
            $propertyValue = $collection->getItemByOrderPropertyId(38);

            $fullBasketPrice = $order->getBasket()->getPrice(); // стоимость товаров
            
            $bonusQuantityCheck = $propertyValue->getValue(); // количество бонусов
            if($bonusQuantityCheck !== '-'){
                $bonusQuantity = $bonusQuantityCheck;
            }else{
                $bonusQuantity = 0;
            }

            $curBasketPrice =  intval($fullBasketPrice) - intval($bonusQuantity);

            if(isset($curBasketPrice) && $curBasketPrice >= 5000){
                $baseResult->setDeliveryPrice(0);
            }

        }
        //Служба доставки Ozon: Постамат [55]
        if($deliveryId == 55){

            $order = $shipment->getShipmentItemCollection()->getShipment()->getCollection()->getOrder();
            $collection = $order->getPropertyCollection();
            $propertyValue = $collection->getItemByOrderPropertyId(38);

            $fullBasketPrice = $order->getBasket()->getPrice(); // стоимость товаров
            
            $bonusQuantityCheck = $propertyValue->getValue(); // количество бонусов
            if($bonusQuantityCheck !== '-'){
                $bonusQuantity = $bonusQuantityCheck;
            }else{
                $bonusQuantity = 0;
            }

            $curBasketPrice =  intval($fullBasketPrice) - intval($bonusQuantity);

            if(isset($curBasketPrice) && $curBasketPrice >= 5000){
                $baseResult->setDeliveryPrice(0);
            }

        }

        $event->addResult(
            new \Bitrix\Main\EventResult(
                \Bitrix\Main\EventResult::SUCCESS, array('RESULT' => $baseResult)
            )
        );
    }
}