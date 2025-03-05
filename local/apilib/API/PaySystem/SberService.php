<?php

namespace ApiLib\API\PaySystem;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Request;
use Bitrix\Sale\Order;
use Bitrix\Sale\Payment;
use Sale\Handlers\PaySystem\sberbank_ecom2Handler;

class SberService extends sberbank_ecom2Handler
{
    public function initiatePay(Payment $payment, Request $request = null)
    {
        $moduleId = 'sberbank.ecom2';

        $RBS_Gateway = new \Sberbank\Payments\Gateway;


        // module settings
        $RBS_Gateway->setOptions(array(
            'module_id' => Option::get($moduleId, 'MODULE_ID'),
            'gate_url_prod' => Option::get($moduleId, 'SBERBANK_PROD_URL'),
            'gate_url_test' => Option::get($moduleId, 'SBERBANK_TEST_URL'),
            'module_version' => Option::get($moduleId, 'MODULE_VERSION'),
            'iso' => unserialize(Option::get($moduleId, 'ISO')),
            'cms_version' => 'Bitrix ' . SM_VERSION,
            'language' => 'ru',
            'default_cartItem_tax' => Option::get($moduleId, 'TAX_DEFAULT'),
        ));

        // handler settings
        $RBS_Gateway->setOptions(array(
            'ofd_tax' => $this->getBusinessValue($payment, 'SBERBANK_OFD_TAX_SYSTEM') == 0 ? 0 : $this->getBusinessValue($payment, 'SBERBANK_OFD_TAX_SYSTEM'),
            'ofd_enabled' => $this->getBusinessValue($payment, 'SBERBANK_OFD_RECIEPT')  == 'Y' ? 1 : 0,
            'ffd_version' => $this->getBusinessValue($payment, 'SBERBANK_FFD_VERSION'),
            'ffd_payment_object' => $this->getBusinessValue($payment, 'SBERBANK_FFD_PAYMENT_OBJECT'),
            'ffd_payment_object_delivery' => $this->getBusinessValue($payment, 'SBERBANK_FFD_PAYMENT_OBJECT_DELIVERY'),
            'ffd_payment_method' => $this->getBusinessValue($payment, 'SBERBANK_FFD_PAYMENT_METHOD'),
            'ffd_payment_method_delivery' => $this->getBusinessValue($payment, 'SBERBANK_FFD_PAYMENT_METHOD_DELIVERY'),
            'test_mode' => $this->getBusinessValue($payment, 'SBERBANK_GATE_TEST_MODE') == 'Y' ? 1 : 0,
            'handler_logging' => $this->getBusinessValue($payment, 'SBERBANK_HANDLER_LOGGING') == 'Y' ? 1 : 0,
            'handler_two_stage' => $this->getBusinessValue($payment, 'SBERBANK_HANDLER_TWO_STAGE') == 'Y' ? 1 : 0,
        ));

        $RBS_Gateway->buildData(array(
            'orderNumber' => $this->getBusinessValue($payment, 'SBERBANK_ORDER_NUMBER') . '_' . $payment->getField('ID'),
            'amount' => $this->getBusinessValue($payment, 'SBERBANK_ORDER_AMOUNT'),
            'userName' => $this->getBusinessValue($payment, 'SBERBANK_GATE_LOGIN'),
            'password' => $this->getBusinessValue($payment, 'SBERBANK_GATE_PASSWORD'),
            'description' => $this->getOrderDescription($payment,'SBERBANK_ORDER_DESCRIPTION'),
        ));

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== "off" ? 'https://' : 'http://';
        $domain_name = strtok($_SERVER['HTTP_HOST'], ":");

        if(strlen($domain_name) < 3) {
            $domain_name = $_SERVER['SERVER_NAME'];
        }
        if(strlen($domain_name) < 3) {
            $domain_name = Option::get($moduleId, 'NOTIFY_URL', '');
        }

        if(strlen($domain_name) > 3) {
            $RBS_Gateway->setOptions(
                array(
                    'domain_finded' => true,
                    'callback_url' => html_entity_decode($protocol . $domain_name . '/bitrix/tools/sale_ps_result.php?PAYMENT=SBERBANK&OPERATION_CALLBACK=SBER')
                )
            );
        }

        $RBS_Gateway->buildData(array(
            'returnUrl' => $protocol . $domain_name . '/bitrix/tools/sale_ps_result.php' . '?PAYMENT=SBERBANK&ORDER_ID=' . $payment->getField('ORDER_ID') . '&PAYMENT_ID=' . $payment->getField('ID')
        ));

        $Order = Order::load($payment->getOrderId());
        $propertyCollection = $Order->getPropertyCollection();

        $phone_key = strlen(Option::get($moduleId, 'OPTION_PHONE')) > 0 ? Option::get($moduleId, 'OPTION_PHONE') : 'PHONE';
        $email_key = strlen(Option::get($moduleId, 'OPTION_EMAIL')) > 0 ? Option::get($moduleId, 'OPTION_EMAIL') : 'EMAIL';
        $fio_key = strlen(Option::get($moduleId, 'OPTION_FIO')) > 0 ? Option::get($moduleId, 'OPTION_FIO') : 'FIO';

        $phone = preg_replace('/\D+/', '', $this->getPropertyValueByCode($propertyCollection, $phone_key));

        if(substr($phone, 0, 1) == '7') {
            $phone = '+'.$phone;
        }
        if(substr($phone, 0, 1) == '8') {
            $phone[0] = '7';
            $phone = '+' . $phone;
        }

        $RBS_Gateway->setOptions(array(
            'customer_name' => $this->getPropertyValueByCode($propertyCollection, $fio_key),
            'customer_email' => $this->getPropertyValueByCode($propertyCollection, $email_key),
            'customer_phone' => $phone,
        ));

        if ($RBS_Gateway->ofdEnable()) {


            $Basket = $Order->getBasket();
            $basketItems = $Basket->getBasketItems();


            $lastIndex = 0;
            foreach ($basketItems as $key => $BasketItem) {
                $lastIndex = $key + 1;
                $position = array(
                    'positionId' => $key,
                    'itemCode' => $BasketItem->getProductId(),
                    'name' => str_replace("\n", "", mb_substr($BasketItem->getField('NAME'), 0, 120)),
                    'itemAmount' => $BasketItem->getFinalPrice(),
                    'itemPrice' => $BasketItem->getPrice(),
                    'quantity' => array(
                        'value' => $BasketItem->getQuantity(),
                        'measure' => $BasketItem->getField('MEASURE_NAME') ? $BasketItem->getField('MEASURE_NAME') : Loc::getMessage('SBERBANK_PAYMENT_FIELD_MEASURE'),
                    ),
                    'tax' => array(
                        'taxType' =>  $RBS_Gateway->getTaxCode( $BasketItem->getField('VAT_RATE') * 100 ),
                    ),
                );


                $RBS_Gateway->setPosition($position);
            }

            if($Order->getField('PRICE_DELIVERY') > 0) {

                Loader::includeModule('catalog');
                $deliveryInfo = \Bitrix\Sale\Delivery\Services\Manager::getById($Order->getField('DELIVERY_ID'));

                $deliveryVatItem = \CCatalogVat::GetByID($deliveryInfo['VAT_ID'])->Fetch();
                $RBS_Gateway->setOptions(array(
                    'delivery' => true,
                ));
                $RBS_Gateway->setPosition(array(
                    'positionId' => $lastIndex + 1,
                    'itemCode' => 'DELIVERY_' . $Order->getField('DELIVERY_ID'),
                    'name' => Loc::getMessage('SBERBANK_PAYMENT_FIRLD_DELIVERY'),
                    'itemAmount' => $Order->getField('PRICE_DELIVERY'),
                    'itemPrice' => $Order->getField('PRICE_DELIVERY'),
                    'quantity' => array(
                        'value' => 1,
                        'measure' => Loc::getMessage('SBERBANK_PAYMENT_FIELD_MEASURE'),
                    ),
                    'tax' => array(
                        'taxType' => $RBS_Gateway->getTaxCodeDelivery($deliveryVatItem['RATE']),
                    ),
                ));
            }
        }

        $gateResponse = $RBS_Gateway->registerOrder();

        return $RBS_Gateway->getPaymentLink();
    }


    private function getPropertyValueByCode($propertyCollection, $code) {
        $property = '';
        foreach ($propertyCollection as $property)
        {
            if($property->getField('CODE') == $code)
                return $property->getValue();
        }
    }
}