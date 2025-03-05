<?php

namespace ApiLib\API\Order\Builder;

use ApiLib\API\Order\Builder\libs\OrderBasketBuilder;
use ApiLib\API\Order\Builder\libs\OrderDeliveryBuilder;
use ApiLib\API\Order\Builder\libs\OrderEntityCompanyBuilder;
use ApiLib\API\Order\Builder\libs\OrderEventBuilder;
use ApiLib\API\Order\Builder\libs\OrderFieldBuilder;
use ApiLib\API\Order\Builder\libs\OrderPaymentBuilder;
use ApiLib\API\Order\Builder\libs\OrderPropsBuilder;
use ApiLib\API\Order\Builder\libs\OrderResultBuilder;
use ApiLib\API\Order\Builder\libs\OrderSaveBuilder;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;
use Bitrix\Sale;

class OrderBuilder implements OrderBuilderInterface
{
    private Order $order;

    private HttpRequest $request;

    private array $arResult = [];

    private OrderPropsBuilder $propsBuilder;

    private OrderBasketBuilder $basketBuilder;

    private OrderDeliveryBuilder $deliveryBuilder;

    private OrderPaymentBuilder $paymentBuilder;

    private OrderEntityCompanyBuilder $entityCompanyBuilder;

    private OrderFieldBuilder $fieldBuilder;

    private OrderResultBuilder $orderResultBuilder;

    private OrderEventBuilder $orderEventBuilder;

    private OrderSaveBuilder $orderSaveBuilder;

    private $isOrderConfirmed;

    private $arUserResult;

    private $arDeliveryServiceAll=[];

    private $arPaySystemServiceAll=[];

    private $action = '';

    protected $useCatalog;

    protected $arActivePaySystems;

    // TODO: А как брать настройки компонента???
    private $arParams = [
        'SPOT_LOCATION_BY_GEOIP' => 'Y',
        'COUNT_DELIVERY_TAX' => 'N',
        'DELIVERY_TO_PAYSYSTEM' => 'd2p',
        'PRODUCT_COLUMNS_HIDDEN' => [],
        'PRODUCT_COLUMNS' => [],
        'USE_PHONE_NORMALIZATION' => 'Y',
        'SERVICES_IMAGES_SCALING' => 'standard',
        'USE_PRELOAD' => 'Y',
        "COMPATIBLE_MODE" => "Y",
        "USER_CONSENT" => "N",
        "BASKET_IMAGES_SCALING" => "standard",
        "SHOW_VAT_PRICE" => "Y",
        "ALLOW_AUTO_REGISTER" => "Y",
        "TEMPLATE_LOCATION" => "popup",
        "ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
        "PAY_FROM_ACCOUNT" => "N",
        "SHOW_NOT_CALCULATED_DELIVERIES" => "N",
        "DELIVERY_NO_AJAX" => "N",
    ];

    const PROPERTY_BLOCK = 'PROPERTY';

    const REGION_BLOCK = 'REGION';

    const DELIVERY_BLOCK = 'DELIVERY';

    const PAY_SYSTEM_BLOCK = 'PAY_SYSTEM';

    public function __construct()
    {
        $this->reset();
        $this->propsBuilder =new OrderPropsBuilder();
        $this->basketBuilder =new OrderBasketBuilder();
        $this->deliveryBuilder =new OrderDeliveryBuilder();
        $this->paymentBuilder =new OrderPaymentBuilder();
        $this->entityCompanyBuilder =new OrderEntityCompanyBuilder();
        $this->fieldBuilder =new OrderFieldBuilder();
        $this->orderResultBuilder =new OrderResultBuilder();
        $this->orderEventBuilder =new OrderEventBuilder();
        $this->orderSaveBuilder =new OrderSaveBuilder();
        $this->useCatalog = Loader::includeModule('catalog');
    }

    public function initProcessOrder()
    {

        $this->isOrderConfirmed = $this->request->isPost();
        global $USER;
        $userId = $USER->GetID();

        if (!$userId)
        {
            $userId = \CSaleUser::GetAnonymousUserID();
        }

        $this->order = $this->createOrder($userId);

        $this->orderResultBuilder->setOrderBuilder($this, $this->order);
        $this->orderResultBuilder->prepareResultArray();

        return $this->arResult;
    }

    public function refreshOrder()
    {
        global $USER;

        $this->order = $this->createOrder($USER->GetID() ? $USER->GetID() : \CSaleUser::GetAnonymousUserID());
        $this->orderResultBuilder->setOrderBuilder($this, $this->order);
        $this->orderResultBuilder->prepareResultArray();
        self::scaleImages($this->arResult['JS_DATA'], $this->arParams['SERVICES_IMAGES_SCALING']);


        return $this->arResult;
    }

    public function save()
    {
        global $USER;

        $arOrderRes = [];

            $this->isOrderConfirmed = true;
            $saveToSession = false;

            $userId = $USER->GetID() ? $USER->GetID() : \CSaleUser::GetAnonymousUserID();

            $this->order = $this->createOrder($userId);

            $isActiveUser = intval($userId) > 0 && $userId != \CSaleUser::GetAnonymousUserID();

            if ($isActiveUser && empty($this->arResult['ERROR']))
            {
                if (!$this->checkOrderConsistency($this->order))
                {
                    $r = new Sale\Result();
                    $r->addError(new Sale\ResultWarning(
                        Loc::getMessage('ORDER_CONSISTENCY_CHANGED'),
                        'SALE_ORDER_CONSISTENCY_CHANGED_ERROR'
                    ));

                    Sale\EntityMarker::addMarker($this->order, $this->order, $r);
                    $this->order->setField('MARKED', 'Y');
                }

                $this->orderSaveBuilder->setOrderBuilder($this);
                $this->orderSaveBuilder->saveOrder($saveToSession);
            }

            if (empty($this->arResult["ERROR"]))
            {
//                $arOrderRes["REDIRECT_URL"] = $this->arParams["~CURRENT_PAGE"]."?ORDER_ID=".urlencode($this->arResult["ACCOUNT_NUMBER"]);
                $arOrderRes["ID"] = $this->arResult["ORDER_ID"];
            }
            else
            {
                $arOrderRes['ERROR'] = $this->arResult['ERROR_SORTED'];
            }


//        $this->showAjaxAnswer(['order' => $arOrderRes]);

        return $arOrderRes;
    }

    protected function checkOrderConsistency(Order $order)
    {
        return $this->getActualQuantityList($order->getBasket()) === $this->arUserResult['QUANTITY_LIST'];
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

    public static function scaleImages(&$result, $scale = '')
    {
        if (!empty($result) && is_array($result))
        {
            if (!empty($result['DELIVERY']) && is_array($result['DELIVERY']))
            {
                foreach ($result['DELIVERY'] as $key => $delivery)
                {
                    if (!empty($delivery["LOGOTIP"]))
                    {
                        self::resizeImage($delivery, 'LOGOTIP', $delivery["LOGOTIP"],
                            ["width" => 600, "height" => 600],
                            ["width" => 95, "height" => 55],
                            $scale
                        );
                        $result["DELIVERY"][$key] = $delivery;
                    }

                }
                unset($logotype, $delivery);
            }

            if (!empty($result['PAY_SYSTEM']) && is_array($result['PAY_SYSTEM']))
            {
                foreach ($result['PAY_SYSTEM'] as $key => $paySystem)
                {
                    if (!empty($paySystem["PSA_LOGOTIP"]))
                    {
                        self::resizeImage($paySystem, 'PSA_LOGOTIP', $paySystem["PSA_LOGOTIP"],
                            ["width" => 600, "height" => 600],
                            ["width" => 95, "height" => 55],
                            $scale
                        );
                        $result["PAY_SYSTEM"][$key] = $paySystem;
                    }
                }
                unset($logotype, $paySystem);
            }

            if (!empty($result['INNER_PAY_SYSTEM']) && is_array($result['INNER_PAY_SYSTEM']) && !empty($result['INNER_PAY_SYSTEM']["LOGOTIP"]))
            {
                self::resizeImage($result['INNER_PAY_SYSTEM'], 'LOGOTIP', $result['INNER_PAY_SYSTEM']["LOGOTIP"],
                    ["width" => 600, "height" => 600],
                    ["width" => 95, "height" => 55],
                    $scale
                );
            }

            if (!empty($result['STORE_LIST']) && is_array($result['STORE_LIST']))
            {
                foreach ($result['STORE_LIST'] as $key => $store)
                {
                    if (!empty($store["IMAGE_ID"]))
                    {
                        self::resizeImage($store, 'IMAGE_ID', $store["IMAGE_ID"],
                            ["width" => 320, "height" => 320],
                            ["width" => 115, "height" => 115],
                            $scale
                        );
                        $result["STORE_LIST"][$key] = $store;
                    }
                }
                unset($logotype, $store);
            }
        }
    }

    public static function resizeImage(array &$item, $imageKey, array $arImage, array $sizeAdaptive, array $sizeStandard, $scale = '')
    {
        if ($scale == '')
        {
            $scale = 'adaptive';
        }

        if ($scale === 'no_scale')
        {
            $item[$imageKey.'_SRC'] = $arImage['SRC'];
            $item[$imageKey.'_SRC_ORIGINAL'] = $arImage['SRC'];
        }
        elseif ($scale === 'adaptive')
        {
            $arFileTmp = \CFile::ResizeImageGet(
                $arImage,
                ['width' => $sizeAdaptive['width'] / 2, 'height' => $sizeAdaptive['height'] / 2],
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            $item[$imageKey.'_SRC'] = $arFileTmp['src'];

            $arFileTmp = \CFile::ResizeImageGet(
                $arImage,
                $sizeAdaptive,
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
            $item[$imageKey.'_SRC_2X'] = $arFileTmp['src'];

            $item[$imageKey.'_SRC_ORIGINAL'] = $arImage['SRC'];
        }
        else
        {
            $arFileTmp = \CFile::ResizeImageGet($arImage, $sizeStandard, BX_RESIZE_IMAGE_PROPORTIONAL, true);
            $item[$imageKey.'_SRC'] = $arFileTmp['src'];

            $item[$imageKey.'_SRC_ORIGINAL'] = $arImage['SRC'];
        }
    }

    public function &getResult(): array
    {
        return $this->arResult;
    }

    public function setRequest(HttpRequest $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
    public function getUseCatalog()
    {
        return $this->useCatalog;
    }

    public function getDeliveryBuilder() {
        return $this->deliveryBuilder;
    }

    public function getPaymentBuilder() {
        return $this->paymentBuilder;
    }

    public function getIsOrderConfirmed()
    {
        return $this->isOrderConfirmed;
    }

    public function &getParams()
    {
        return $this->arParams;
    }

    public function reset()
    {
        $this->order = Order::create($this->getSiteId());
    }

    public function &getEntryOrder()
    {
        return $this->order;
    }

    public function getItem()
    {
        $result = $this->order;
        $this->reset();

        return $result;
    }

    protected function createOrder($userId)
    {
        $this->makeUserResultArray();

        // TODO: Купоны нам нужны?
//        DiscountCouponsManager::init(DiscountCouponsManager::MODE_CLIENT, ['userId' => $userId]);
//        $this->executeEvent('OnSaleComponentOrderOneStepDiscountBefore');

        /** @var Order $order */
        $order = $this->getOrder($userId);


        // TODO: Тоже потом посмотреть что тут надо. Это все уже выполняется в функции выше вроде же
//        if ($this->arUserResult['RECREATE_ORDER'])
//            $order = $this->getOrder($userId);
//
//        if ($this->arUserResult['CALCULATE_PAYMENT'])
//            $this->recalculatePayment($order);

        return $order;
    }

    protected function getOrder($userId)
    {
        $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
        /** @var Order $orderClassName */
        $orderClassName = $registry->getOrderClassName();

        $order = $orderClassName::create($this->getSiteId(), $userId);
        $order->isStartField();

        // TODO: наверно надо будет подключить подгрузку данных с последнего заказа
        $this->initLastOrderData($order);

        $order->setField('STATUS_ID', Sale\OrderStatus::getInitialStatus());

        // Оно нам кажется не надо
//        Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
//        if ($this->arParams['USE_PREPAYMENT'] === 'Y')
//        {
//            $this->usePrepayment($order);
//        }

        $isPersonTypeChanged = $this->initPersonType($order);

        // На текущем сайте не используется
//        $this->initTradeBinding($order);
        $this->propsBuilder->setOrderBuilder($this);
        $this->propsBuilder->initProperties($order, $isPersonTypeChanged);
//        $this->initProperties($order, $isPersonTypeChanged);
        $this->basketBuilder->setOrderBuilder($this);
        $this->basketBuilder->initBasket($order);
//        $this->initBasket($order);

        $taxes = $order->getTax();
        $taxes->setDeliveryCalculate($this->arParams['COUNT_DELIVERY_TAX'] === 'Y');

        $shipment = $this->initShipment($order);

        $order->doFinalAction(true);

        if ($this->arParams['DELIVERY_TO_PAYSYSTEM'] === 'd2p')
        {
            $this->deliveryBuilder->setOrderBuilder($this);
            $this->deliveryBuilder->initDelivery($shipment);
            $this->paymentBuilder->setOrderBuilder($this);
            $this->paymentBuilder->initPayment($order);
//            $this->initDelivery($shipment);
//            $this->initPayment($order);
        }
        else
        {
            $this->paymentBuilder->setOrderBuilder($this);
            $this->paymentBuilder->initPayment($order);
            $this->deliveryBuilder->setOrderBuilder($this);
            $this->deliveryBuilder->initDelivery($shipment);
//            $this->initPayment($order);
//            $this->initDelivery($shipment);
        }


        $this->entityCompanyBuilder->initEntityCompanyIds($order);
        $this->fieldBuilder->setOrderBuilder($this);
        $this->fieldBuilder->initOrderFields($order);

//        $this->initEntityCompanyIds($order);
//        $this->initOrderFields($order);

        // initialization of related properties
        $this->setOrderProperties($order);
        $this->paymentBuilder->recalculatePayment($order);
//        $this->recalculatePayment($order);

        $eventParameters = [
            $order, &$this->arUserResult, $this->request,
            &$this->arParams, &$this->arResult, &$this->arDeliveryServiceAll, &$this->arPaySystemServiceAll,
        ];
        foreach (GetModuleEvents('sale', 'OnSaleComponentOrderCreated', true) as $arEvent)
        {
            ExecuteModuleEventEx($arEvent, $eventParameters);
        }

        // no need to calculate deliveries when order is saving
        if ($this->action !== 'saveOrderAjax')
        {
            $this->deliveryBuilder->calculateDeliveries($order);
        }

        return $order;
    }

    protected function initLastOrderData(Order $order)
    {
        global $USER;

        if (
            ($this->request->getRequestMethod() === 'GET' || $this->request->get('do_authorize') === 'Y' || $this->request->get('do_register') === 'Y')
            && $this->arUserResult['USE_PRELOAD']
            && $USER->IsAuthorized()
        )
        {
            $showData = [];
            $lastOrderData = $this->getLastOrderData($order);

            if (!empty($lastOrderData))
            {
                if (!empty($lastOrderData['PERSON_TYPE_ID']))
                    $this->arUserResult['PERSON_TYPE_ID'] = $showData['PERSON_TYPE_ID'] = $lastOrderData['PERSON_TYPE_ID'];

                if (!empty($lastOrderData['PAY_CURRENT_ACCOUNT']))
                    $this->arUserResult['PAY_CURRENT_ACCOUNT'] = $showData['PAY_CURRENT_ACCOUNT'] = $lastOrderData['PAY_CURRENT_ACCOUNT'];

                if (!empty($lastOrderData['PAY_SYSTEM_ID']))
                    $this->arUserResult['PAY_SYSTEM_ID'] = $showData['PAY_SYSTEM_ID'] = $lastOrderData['PAY_SYSTEM_ID'];

                if (!empty($lastOrderData['DELIVERY_ID']))
                    $this->arUserResult['DELIVERY_ID'] = $showData['DELIVERY_ID'] = $lastOrderData['DELIVERY_ID'];

                if (!empty($lastOrderData['DELIVERY_EXTRA_SERVICES']))
                    $this->arUserResult['DELIVERY_EXTRA_SERVICES'] = $showData['DELIVERY_EXTRA_SERVICES'] = $lastOrderData['DELIVERY_EXTRA_SERVICES'];

                if (!empty($lastOrderData['BUYER_STORE']))
                    $this->arUserResult['BUYER_STORE'] = $showData['BUYER_STORE'] = $lastOrderData['BUYER_STORE'];

                $this->arUserResult['LAST_ORDER_DATA'] = $showData;
            }
        }
    }

    protected function getLastOrderData(Order $order)
    {
        $lastOrderData = [];

        $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
        /** @var Order $orderClassName */
        $orderClassName = $registry->getOrderClassName();

        $filter = [
            'filter' => [
                'USER_ID' => $order->getUserId(),
                'LID' => $order->getSiteId(),
            ],
            'select' => ['ID'],
            'order' => ['ID' => 'DESC'],
            'limit' => 1,
        ];

        if ($arOrder = $orderClassName::getList($filter)->fetch())
        {
            /** @var Order $lastOrder */
            $lastOrder = $orderClassName::load($arOrder['ID']);
            $lastOrderData['PERSON_TYPE_ID'] = $lastOrder->getPersonTypeId();

            if ($payment = $this->paymentBuilder->getInnerPayment($lastOrder))
                $lastOrderData['PAY_CURRENT_ACCOUNT'] = 'Y';

            if ($payment = $this->paymentBuilder->getExternalPayment($lastOrder))
                $lastOrderData['PAY_SYSTEM_ID'] = $payment->getPaymentSystemId();

            if ($shipment = $this->deliveryBuilder->getCurrentShipment($lastOrder))
            {
                $lastOrderData['DELIVERY_ID'] = $shipment->getDeliveryId();
                $lastOrderData['BUYER_STORE'] = $shipment->getStoreId();
                $lastOrderData['DELIVERY_EXTRA_SERVICES'][$shipment->getDeliveryId()] = $shipment->getExtraServices();
                if ($storeFields = \Bitrix\Sale\Delivery\ExtraServices\Manager::getStoresFields($lastOrderData['DELIVERY_ID'], false))
                    unset($lastOrderData['DELIVERY_EXTRA_SERVICES'][$shipment->getDeliveryId()][$storeFields['ID']]);
            }
        }

        return $lastOrderData;
    }

    protected function makeUserResultArray()
    {
        $request =& $this->request;

        $arUserResult = [
            "PERSON_TYPE_ID" => false,
            "PERSON_TYPE_OLD" => false,
            "PAY_SYSTEM_ID" => false,
            "DELIVERY_ID" => false,
            "ORDER_PROP" => [],
            "DELIVERY_LOCATION" => false,
            "TAX_LOCATION" => false,
            "PAYER_NAME" => false,
            "USER_EMAIL" => false,
            "PROFILE_NAME" => false,
            "PAY_CURRENT_ACCOUNT" => false,
            "CONFIRM_ORDER" => false,
            "FINAL_STEP" => false,
            "ORDER_DESCRIPTION" => false,
            "PROFILE_ID" => false,
            "PROFILE_CHANGE" => false,
            "DELIVERY_LOCATION_ZIP" => false,
            "ZIP_PROPERTY_CHANGED" => 'N',
            "QUANTITY_LIST" => [],
            "USE_PRELOAD" => $this->arParams['USE_PRELOAD'] === 'Y',
        ];

        if ($request->isPost())
        {
            if (intval($request->get('PERSON_TYPE')) > 0)
                $arUserResult["PERSON_TYPE_ID"] = intval($request->get('PERSON_TYPE'));

            if (intval($request->get('PERSON_TYPE_OLD')) > 0)
                $arUserResult["PERSON_TYPE_OLD"] = intval($request->get('PERSON_TYPE_OLD'));
            if (empty($arUserResult["PERSON_TYPE_OLD"]) || $arUserResult["PERSON_TYPE_OLD"] == $arUserResult["PERSON_TYPE_ID"])
            {
                $profileId = $request->get('PROFILE_ID');

                if ($profileId !== null)
                {
                    $arUserResult['PROFILE_ID'] = (int)$profileId;
                }

                $paySystemId = $request->get('PAY_SYSTEM_ID');
                if (!empty($paySystemId))
                    $arUserResult["PAY_SYSTEM_ID"] = intval($paySystemId);
                $deliveryId = $request->get('DELIVERY_ID');
                if (!empty($deliveryId))
                    $arUserResult["DELIVERY_ID"] = $deliveryId;

                $buyerStore = $request->get('BUYER_STORE');
                if (!empty($buyerStore))
                    $arUserResult["BUYER_STORE"] = intval($buyerStore);

                $deliveryExtraServices = $request->get('DELIVERY_EXTRA_SERVICES');
                if (!empty($deliveryExtraServices))
                    $arUserResult["DELIVERY_EXTRA_SERVICES"] = $deliveryExtraServices;

                if ($request->get('ORDER_DESCRIPTION') <> '')
                {
                    $arUserResult["~ORDER_DESCRIPTION"] = $request->get('ORDER_DESCRIPTION');
                    $arUserResult["ORDER_DESCRIPTION"] = htmlspecialcharsbx($request->get('ORDER_DESCRIPTION'));
                }

                if ($request->get('PAY_CURRENT_ACCOUNT') == "Y")
                    $arUserResult["PAY_CURRENT_ACCOUNT"] = "Y";

                if ($request->get('confirmorder') == "Y")
                {
                    $arUserResult["CONFIRM_ORDER"] = "Y";
                    $arUserResult["FINAL_STEP"] = "Y";
                }

                $arUserResult["PROFILE_CHANGE"] = $request->get('profile_change') == "Y" ? "Y" : "N";
            }

            $arUserResult['ZIP_PROPERTY_CHANGED'] = $this->request->get('ZIP_PROPERTY_CHANGED') === 'Y' ? 'Y' : 'N';
        }

        foreach (GetModuleEvents("sale", 'OnSaleComponentOrderUserResult', true) as $arEvent)
            ExecuteModuleEventEx($arEvent, [&$arUserResult, $this->request, &$this->arParams]);

        $this->arUserResult = $arUserResult;
    }

    protected function initPersonType(Order $order)
    {
        $arResult =& $this->arResult;
        $personTypeId = intval($this->arUserResult['PERSON_TYPE_ID']);
        $personTypeIdOld = intval($this->arUserResult['PERSON_TYPE_OLD']);

        $personTypes = Sale\PersonType::load($this->getSiteId());
        foreach ($personTypes as $personType)
        {
            if ($personTypeId === intval($personType["ID"]) || !array_key_exists($personTypeId, $personTypes))
            {
                $personTypeId = intval($personType["ID"]);
                $order->setPersonTypeId($personTypeId);
                $this->arUserResult['PERSON_TYPE_ID'] = $personTypeId;
                $personType["CHECKED"] = "Y";
            }
            $arResult["PERSON_TYPE"][$personType["ID"]] = $personType;
        }
        Loc::setCurrentLang('ru');
        Loc::loadMessages(__FILE__);

        if ($personTypeId == 0)
            $this->addError(Loc::getMessage("SOA_ERROR_PERSON_TYPE"), self::REGION_BLOCK);

        $this->orderEventBuilder->setOrderBuilder($this);
        $this->orderEventBuilder->executeEvent('OnSaleComponentOrderOneStepPersonType', $order);
//        $this->executeEvent('OnSaleComponentOrderOneStepPersonType', $order);

        return count($arResult["PERSON_TYPE"]) > 1 && ($personTypeId !== $personTypeIdOld);
    }

    public function addError($res, $type = 'MAIN')
    {
        if ($res instanceof Sale\Result)
        {
            $errors = $res->getErrorMessages();
        }
        else
        {
            $errors = [$res];
        }

        foreach ($errors as $error)
        {
            if (!in_array($error, $this->arResult['ERROR'], true))
            {
                $this->arResult['ERROR'][] = $error;
            }

            if (
                empty($this->arResult['ERROR_SORTED'][$type])
                || !in_array($error, $this->arResult['ERROR_SORTED'][$type], true)
            )
            {
                $this->arResult['ERROR_SORTED'][$type][] = $error;
            }
        }
    }

    public function addWarning($res, $type)
    {
        if (
            !empty($type)
            && (
                empty($this->arResult['WARNING'][$type])
                || (!empty($this->arResult['WARNING'][$type]) && !in_array($res, $this->arResult['WARNING'][$type]))
            )
        )
        {
            $this->arResult['WARNING'][$type][] = $res;
        }
    }

    public function getSiteId()
    {
        return SITE_ID;
    }

    public function &getUserResult()
    {
        return $this->arUserResult;
    }
    public function &getActivePaySystem()
    {
        return $this->arActivePaySystems;
    }

    public function &getArResult()
    {
        return $this->arResult;
    }

    public function &getDeliveryServiceAll()
    {
        return $this->arDeliveryServiceAll;
    }

    public function &getPaySystemServiceAll()
    {
        return $this->arPaySystemServiceAll;
    }

    public function initShipment(Order $order)
    {
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipment->setField('CURRENCY', $order->getCurrency());

        /** @var Sale\BasketItem $item */
        foreach ($order->getBasket() as $item)
        {
            /** @var Sale\ShipmentItem $shipmentItem */
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }

        return $shipment;
    }

    public function loadUserAccount(Order $order)
    {
        if (!isset($this->arResult["USER_ACCOUNT"]))
        {
            $dbUserAccount = \CSaleUserAccount::GetList(
                [],
                [
                    "USER_ID" => $order->getUserId(),
                    "CURRENCY" => $order->getCurrency(),
                ]
            );
            $this->arResult["USER_ACCOUNT"] = $dbUserAccount->Fetch();
        }
    }

    public function setOrderProperties(Order $order)
    {
        $propertyCollection = $order->getPropertyCollection();

        $res = $propertyCollection->setValuesFromPost(['PROPERTIES' => $this->arUserResult['ORDER_PROP']], []);

        if ($this->isOrderConfirmed)
        {
            if (!$res->isSuccess())
            {
                $this->addError($res, self::PROPERTY_BLOCK);
            }

            /** @var Sale\PropertyValue $propertyValue */
            foreach ($propertyCollection as $propertyValue)
            {
                if ($propertyValue->isUtil())
                {
                    continue;
                }

                $res = $propertyValue->verify();
                if (!$res->isSuccess())
                {
                    $this->addError($res, self::PROPERTY_BLOCK);
                }

                $res = $propertyValue->checkRequiredValue($propertyValue->getPropertyId(), $propertyValue->getValue());
                if (!$res->isSuccess())
                {
                    $this->addError($res, self::PROPERTY_BLOCK);
                }
            }
        }
    }

    public function getOrderClone(Order $order)
    {
        /** @var Order $orderClone */
        $orderClone = $order->createClone();

        $clonedShipment = $this->deliveryBuilder->getCurrentShipment($orderClone);
        if (!empty($clonedShipment))
        {
            $clonedShipment->setField('CUSTOM_PRICE_DELIVERY', 'N');
        }

        return $orderClone;
    }
}