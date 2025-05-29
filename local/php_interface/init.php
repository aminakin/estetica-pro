<? //demo
class classBuildSCG
{
  public static function buildSVG(string $svg_name, string $base = 'local/templates/main/images/svg/')
  {
    return file_get_contents($_SERVER["DOCUMENT_ROOT"] . '/' . $base . '/' . $svg_name . '.svg');
  } 
}

function file_get_contents_alt($url)
{

  $ch = curl_init();

  curl_setopt($ch, CURLOPT_USERAGENT, filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW));
  curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

  $data = curl_exec($ch);

  curl_close($ch);

  return $data;
}

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/log.txt");
define("LOG_FILENAME_ADD", $_SERVER["DOCUMENT_ROOT"] . "/estetica_add.txt");

require_once 'include/mobile-detect/Mobile_Detect.php';
$detect = new Mobile_Detect;

use Bitrix\Main;
use Bitrix\Sender;
use Bitrix\Main\Event;
use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Main\Context;
use Bitrix\Sale\Delivery;
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Sale\DiscountCouponsManager;

CModule::AddAutoloadClasses(
  '', // не указываем имя модуля
  array(
    'Page' => '/local/classes/Page.php',
    'ChangePriceShipment' => '/local/classes/ChangePriceShipment.php',
    'IblockHelper' => '/local/classes/IblockHelper.php',
    'IblockHelperInterface' => '/local/classes/IblockHelperInterface.php',
  )
);

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("AfterElement", "OnBeforeIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("AfterElement", "OnAfterIBlockElementUpdateHandler"));

//Main\EventManager::getInstance()->addEventHandler("catalog", "Bitrix\Catalog\Model\Product::OnBeforeUpdate", [\ApiLib\Events\ProductEvents::class, "OnBeforeProductUpdateHandler"]);
//Main\EventManager::getInstance()->addEventHandler("catalog", "Bitrix\Catalog\Model\Product::OnAfterUpdate", [\ApiLib\Events\ProductEvents::class, "OnAfterProductUpdateHandler"]);
Main\EventManager::getInstance()->addEventHandler('sale', 'onSaleDeliveryServiceCalculate', [ChangePriceShipment::class, 'calcPriceShipment'], false, 1000);


AddEventHandler('catalog', 'OnSuccessCatalogImport1C', 'customCatalogImportStep');
if ($_REQUEST['type'] == 'sale' || $_REQUEST['type'] == 'catalog') {
  COption::SetOptionString("catalog", "DEFAULT_SKIP_SOURCE_CHECK", "Y");
  COption::SetOptionString("sale", "secure_1c_exchange", "N");
}

/*--- Запуск агента для обработки бонусов прешедших из файла из 1с ---*/
function agentPrescalerBonusLoader()
{
  userBonusLoader::load_bonus_data();
  return 'agentPrescalerBonusLoader();';
}

class userBonusLoader
{
  public static function load_bonus_data()
  {
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/from1c/bonus.xml")) {
      return;
    }
    $z = new XMLReader;
    copy($_SERVER['DOCUMENT_ROOT'] . "/upload/from1c/bonus.xml", $_SERVER['DOCUMENT_ROOT'] . "/upload/from1c/log/bonus.xml" . date('YmdHis'));

    $yt = $z->open($_SERVER['DOCUMENT_ROOT'] . "/upload/from1c/bonus.xml");

    while ($z->nodeType != XMLReader::ELEMENT || $z->localName != 'data') {
      if (!$z->read()) {
        $z->close();
        return;
      }
    }
    if ($z->localName == 'data')
      do {
        if ($z->localName == 'data' && $z->nodeType == XMLReader::ELEMENT) {
          $str = $z->readOuterXML();
          $sxml = simplexml_load_string($str);
          self::load_bonus_dataxml($sxml);
        }
      } while ($z->next());

    unlink($_SERVER['DOCUMENT_ROOT'] . "/upload/from1c/bonus.xml");
  }

  public static function load_bonus_dataxml($sxml)
  {
    global $USER_FIELD_MANAGER;
    $arData = (array)$sxml;
    $startPhone = preg_replace('/^(8|7)/', '', $arData['PhoneNumber']);

    $filter = array("PERSONAL_PHONE" => "%" . $startPhone, "ACTIVE" => "Y");
    $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter);
    if ($arUser = $rsUsers->Fetch()) {
      $user_id = $arUser['ID'];
    }
    if (!$user_id) return;

    $currentBonusBalance = intval($arData['BonusBalance']);
    foreach ($arData['BonusOffest']->Bonus as $key => $value) {
      $bonusOffersValue .= $value->BonusOff . '/' . $value->DateOff . ';';
    }
    $bonusOffersValue = substr($bonusOffersValue, 0, -1);

    if ($rsUser = CUser::GetByID($user_id)) {
      $USER_FIELD_MANAGER->Update("USER", $user_id, [
        'UF_BONUS_CART' => $arData['CardNumber'],
        'UF_CURPERCENT' => $arData['CurrentPercentage'],
        'UF_PURCHVOLUME' => $arData['PurchaseVolume'],
        'UF_NEXTLEVEL' => $arData['NextLevel'],
        'UF_LOGICTIM_BONUS' => $currentBonusBalance,
        'UF_QUANTITY_DATE' => $bonusOffersValue
      ]);
    }
  }
}

class userBonusLoaderCustom
{
  public static function load_bonus_data()
  {
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/from1c/bonus1.xml")) {
      return;
    }
    $z = new XMLReader;

    $yt = $z->open($_SERVER['DOCUMENT_ROOT'] . "/upload/from1c/bonus1.xml");

    while ($z->nodeType != XMLReader::ELEMENT || $z->localName != 'data') {
      if (!$z->read()) {
        $z->close();
        return;
      }
    }
    if ($z->localName == 'data')
      do {
        if ($z->localName == 'data' && $z->nodeType == XMLReader::ELEMENT) {
          $str = $z->readOuterXML();
          $sxml = simplexml_load_string($str);
          self::load_bonus_dataxml($sxml);
        }
      } while ($z->next());
  }

  public static function load_bonus_dataxml($sxml)
  {
    global $USER_FIELD_MANAGER;
    $arData = (array)$sxml;
    $startPhone = preg_replace('/^(8|7)/', '', $arData['PhoneNumber']);

    $filter = array("PERSONAL_PHONE" => "%" . $startPhone, "ACTIVE" => "Y");
    $rsUsers = CUser::GetList(($by = "id"), ($order = "desc"), $filter);
    if ($arUser = $rsUsers->Fetch()) {
      $user_id = $arUser['ID'];
    }
    if (!$user_id) return;


    $currentBonusBalance = intval($arData['BonusBalance']);
    foreach ($arData['BonusOffest']->Bonus as $key => $value) {
      $bonusOffersValue .= $value->BonusOff . '/' . $value->DateOff . ';';
    }
    $bonusOffersValue = substr($bonusOffersValue, 0, -1);

    if ($rsUser = CUser::GetByID($user_id)) {
      $USER_FIELD_MANAGER->Update("USER", $user_id, [
        'UF_BONUS_CART' => $arData['CardNumber'],
        'UF_CURPERCENT' => $arData['CurrentPercentage'],
        'UF_PURCHVOLUME' => $arData['PurchaseVolume'],
        'UF_NEXTLEVEL' => $arData['NextLevel'],
        'UF_LOGICTIM_BONUS' => $currentBonusBalance,
        'UF_QUANTITY_DATE' => $bonusOffersValue
      ]);
    }
  }
}

//define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/upload/1c_exchange/logOrderSave.txt");
function customCatalogImportStep()
{
  $stepInterval = (int)COption::GetOptionString("catalog", "1C_INTERVAL", "-");
  $startTime = time();
  // Флаг импорта файла торговых предложений
  $isOffers = strpos($_REQUEST['filename'], 'offers') !== false;
  $NS = &$_SESSION["BX_CML2_IMPORT"]["NS"];

  if (!isset($NS['custom']['lastId'])) {
    // Последний отработанный элемент для пошаговости.
    $NS['custom']['lastId'] = 0;
    $NS['custom']['counter'] = 0;
  }

  // Условия выборки элементов для обработки
  $arFilter = array(
    'IBLOCK_ID' => 1024,
    'ACTIVE' => 'Y',
  );

  $res = CIBlockElement::GetList(array('ID' => 'ASC'), array_merge($arFilter, array('>ID' => $NS['custom']['lastId'])));
  $errorMessage = null;

  while ($arItem = $res->Fetch()) {

    if ($error === true) {
      $errorMessage = 'Что-то случилось.';
      break;
    }

    $NS['custom']['lastId'] = $arItem['ID'];
    $NS['custom']['counter']++;

    // Прерывание по времени шага
    if ($stepInterval > 0 && (time() - $startTime) > $stepInterval) {
      break;
    }
  }

  if ($arItem != false) {
    if ($errorMessage === null) {
      print "progress\n";
      print "Обработано " . $NS['custom']['counter'] . ' элементов, осталось ' . $res->SelectedRowsCount();
    } else {
      print "failure\n" . $errorMessage;
    }

    $contents = ob_get_contents();
    ob_end_clean();

    if (toUpper(LANG_CHARSET) != "WINDOWS-1251") {
      $contents = $GLOBALS['APPLICATION']->ConvertCharset($contents, LANG_CHARSET, "windows-1251");
    }

    header("Content-Type: text/html; charset=windows-1251");
    print $contents;
    exit;
  }
}

/**
 * Хелпер для быстрого форматирования цены
 *
 * @param float $price
 * @param bool|string $currency
 * @return string
 * @example
 * format_price(45689.95); // 45 689.95
 * format_price(45689.0); // 45 689
 * format_price(45689.95, ' руб.'); // 45 689.95 руб.
 */
function format_price($price, $currency = false)
{
  $price = number_format($price, 2, '.', ' ');
  if (substr($price, -3) === '.00') {
    $price = substr($price, 0, -3);
  }
  if ($currency) {
    $price .= $currency;
  }
  return $price;
}


//при подтверждении регистрации пользователем, отправляем письмо ему и администратору
AddEventHandler("main", "OnAfterUserRegister", "OnAfterUserRegisterHandler");
function OnAfterUserRegisterHandler($arFields)
{
  // если регистрация успешна то
  if ($arFields["USER_ID"] > 0) {
    $arEventFields = array(
      "NAME" => $arFields['NAME'],
      "LAST_NAME" => $arFields['LAST_NAME'],
      "EMAIL" => $arFields['EMAIL'],
      "PASSWORD" => $arFields['PASSWORD']
    );
    CEvent::Send("USER_INFO_CUSTOM", SITE_ID, $arEventFields); //письма зарегистрировавшемуся

    $arEventFields2 = array(
      "NAME" => $arFields['NAME'],
      "LAST_NAME" => $arFields['LAST_NAME'],
      "EMAIL" => $arFields['EMAIL'],
      "PHONE" => $arFields['PERSONAL_PHONE'],
      "USER_ID" => $arFields['USER_ID']
    );
    CEvent::Send("NEW_USER_CUSTOM", SITE_ID, $arEventFields2); //письма администратору
  }
}

//передача свойств заказа в почтовое событие создания заказа
AddEventHandler("sale", "OnOrderNewSendEmail", "bxModifySaleMails");
function bxModifySaleMails($orderID, &$eventName, &$arFields)
{
  $order_props = CSaleOrderPropsValue::GetOrderProps($orderID);
  $couponList = \Bitrix\Sale\Internals\OrderCouponsTable::getList(array(
    'select' => array('COUPON'),
    'filter' => array('=ORDER_ID' => $orderID)
  ));
  $usecoupon = '';
  while ($coupon = $couponList->fetch()) {
    $usecoupon = $coupon['COUPON'];
  }
  $dbRes = \Bitrix\Sale\ShipmentCollection::getList([
    'select' => ['DELIVERY_NAME'],
    'filter' => ['=ORDER_ID' => $orderID]
  ]);
  if ($item = $dbRes->fetch()) {
    $curDelivery = $item['DELIVERY_NAME'];
  }
  $arFields['DELIV'] = $curDelivery;

  while ($arProps = $order_props->Fetch()) {
    if ($arProps['CODE'] == 'FIO') {
      $arFields['FIO'] = $arProps['VALUE'];
    }
    if ($arProps['CODE'] == 'PHONE') {
      $arFields['PHONE'] = '+' . $arProps['VALUE'];
    }
    if ($delivery_id != 2) {
      if ($arProps["CODE"] == "FULL_ADRESS") {
        $arFields['CITY'] = 'местоположение:  ' . $arProps["VALUE"];
      }
      if ($arProps['CODE'] == 'STREET') {
        $arFields['ADDRESS'] = 'адрес: ул. ' . $arProps['VALUE'];
      }
      if ($arProps['CODE'] == 'HOUSE') {
        $arFields['ADDRESS'] .= ' д. ' . $arProps['VALUE'];
      }
      if ($arProps['CODE'] == 'CORPUSE') {
        $arFields['ADDRESS'] .= ' корп. ' . $arProps['VALUE'];
      }
      if ($arProps['CODE'] == 'APARTMENT') {
        $arFields['ADDRESS'] .= ' кв. ' . $arProps['VALUE'];
      }
    }
    if ($arProps['CODE'] == 'DISCONCART') {
      $arFields['DISCONCART'] = $usecoupon;
    }
    if ($arProps['CODE'] == 'DELIVERY') {
      $arFields['PICK_POINT'] = 'Пункт выдачи: ' . $arProps['VALUE'];
    }
    if ($arProps['CODE'] == 'RUSPOSTTYPE') {
      $arFields['RUSPOSTTYPE'] = !empty($arProps['VALUE']) ? "<b>Доставка в:</b> " . $arProps['VALUE'] : "";
    }
    if ($arProps['CODE'] == 'MANAGER_CALL') {
      if ($arProps["VALUE"] == 'N') {
        $arFields['MANAGER_CALL'] = 'Нет';
      } else {
        $arFields['MANAGER_CALL'] = 'Да';
      }
    }
  }

  // комментарий к заказу
  $order = Order::load($orderID);
  $arFields["ORDER_DESCRIPTION"] = $order->getField('USER_DESCRIPTION');
}


AddEventHandler("sale", "OnOrderSave", "SaleComponentOrderCompleteHandler");

function SaleComponentOrderCompleteHandler($ID, $arFields, $arOrder, $isNew)
{
  //нужно плдключать или нет не знаю не проверял. Но подключил
  if (!CModule::IncludeModule('sale')) {
    return true;
  }
  if ($isNew) {
    $order = Bitrix\Sale\Order::load($ID);
    $couponList = \Bitrix\Sale\Internals\OrderCouponsTable::getList(array(
      'select' => array('COUPON'),
      'filter' => array('=ORDER_ID' => $ID)
    ));
    /*$usecoupon = '';
    while ($coupon = $couponList->fetch()) {
        $usecoupon = $coupon['COUPON'];
    }*/
    $propertyCollection = $order->getPropertyCollection();
    $resFullName = $propertyCollection->getItemByOrderPropertyId(1);
    $resCity = $propertyCollection->getItemByOrderPropertyId(5);
    $arLocs = CSaleLocation::GetByID($resCity->getvalue());
    $res = \Bitrix\Sale\Location\LocationTable::getList(array(
      'filter' => array(
        '=ID' => $arLocs['ID'],
        '=PARENTS.NAME.LANGUAGE_ID' => LANGUAGE_ID,
        '=PARENTS.TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
      ),
      'select' => array(
        'I_ID' => 'PARENTS.ID',
        'I_NAME_RU' => 'PARENTS.NAME.NAME',
        'I_TYPE_CODE' => 'PARENTS.TYPE.CODE',
        'I_TYPE_NAME_RU' => 'PARENTS.TYPE.NAME.NAME'
      ),
      'order' => array(
        'PARENTS.DEPTH_LEVEL' => 'asc'
      )
    ));
    while ($item = $res->fetch()) {
      if ($item['I_TYPE_CODE'] == 'SUBREGION') {
        $arLocs['SUBREGION'] .= $item["I_NAME_RU"];
      }
    }
    /*$User_dersc = $order->getField('USER_DESCRIPTION');
    $User_dersc .= ' // Номер дисконтной карты: ' . $usecoupon;*/
    $resStreet = $propertyCollection->getItemByOrderPropertyId(6);
    $resHouse = $propertyCollection->getItemByOrderPropertyId(7);
    //$resCorpuse = $propertyCollection->getItemByOrderPropertyId(8);
    $resApartment = $propertyCollection->getItemByOrderPropertyId(9);
    $resFullAdress = $propertyCollection->getItemByOrderPropertyId(29);
    $resusecoupon = $propertyCollection->getItemByOrderPropertyId(31);
    $resName = $propertyCollection->getItemByOrderPropertyId(34);
    $resSurname = $propertyCollection->getItemByOrderPropertyId(35);
    $resMiddleName = $propertyCollection->getItemByOrderPropertyId(36);
    $resPersonalPhone = $propertyCollection->getItemByOrderPropertyId(37);
    $resIfAuth = $propertyCollection->getItemByOrderPropertyId(43);


    $City = $arLocs['COUNTRY_NAME_ORIG'] . ' ' . $arLocs['REGION_NAME_ORIG'] . ' ' . $arLocs['SUBREGION'] . ' ' . $arLocs['CITY_NAME_ORIG'];
    $Street = $resStreet->getValue();
    $House = $resHouse->getValue();
    $Apartment = $resApartment->getValue();
    $IfAuth = $resIfAuth->getValue();

    global $USER;
    $userID = $USER->GetID();
    $rsUser = CUser::GetByID($USER->GetID($userID));
    $arUser = $rsUser->Fetch();
    if ($IfAuth == 'Y') {
      $personalPhone = $arUser['PERSONAL_PHONE'];
    } else {
      $personalPhone = '';
    }
    $usecoupon = $arUser['UF_BONUS_CART'];
    $resPersonalPhone->setValue($personalPhone);
    $resusecoupon->setValue($usecoupon);
    $resFullAdress->setValue($City . ', ул. ' . $Street . ', д. ' . $House . ', кв. ' . $Apartment);
    $name = $resName->getValue();
    $surname = $resSurname->getValue();
    $middleName = $resMiddleName->getValue();
    $resFullName->setValue($name . ' ' . $surname . ' ' . $middleName);
    $order->save();
  }
}

AddEventHandler("main", "OnBeforeUserRegister", "OnBeforeUserUpdateHandler");
AddEventHandler("main", "OnBeforeUserUpdate", "OnBeforeUserUpdateHandler");
function OnBeforeUserUpdateHandler(&$arFields)
{
  $arFields["LOGIN"] = $arFields["EMAIL"];
}

AddEventHandler("main", "OnBeforeUserLogin", "beforeLogin");
/* Авторизация по телефону и логину */
function beforeLogin(&$arFields)
{
  $firstChar = substr($arFields['LOGIN'], 0, 1);

  if ($firstChar == '7' || $firstChar == '8' || $firstChar == '+') {
    $phone = preg_replace('/[^\d]/', '', $arFields['LOGIN']);
    $phone = substr($phone, 1);

    $filter = [
      'PERSONAL_PHONE' => $phone
    ];

    $dbUsers = CUser::GetList(($by = "id"), ($order = "asc"), $filter);
    if ($dbUsers->is_filtered) {
      if ($arUser = $dbUsers->Fetch()) {
        $arFields['LOGIN'] = $arUser['LOGIN'];
      } else {

        $filter = [
          'PERSONAL_PHONE' => '%' . $phone
        ];

        unset($dbUsers);
        $dbUsers = CUser::GetList(($by = "id"), ($order = "asc"), $filter);

        if ($dbUsers->is_filtered) {
          if ($arUser = $dbUsers->Fetch()) {
            $arFields['LOGIN'] = $arUser['LOGIN'];
          }
        }
      }
    }
    //file_put_contents($_SERVER["DOCUMENT_ROOT"].'/test.txt', var_export($arFields['LOGIN'], true) );
  } elseif (filter_var($arFields['LOGIN'], FILTER_VALIDATE_EMAIL)) {
    $filter = [
      'EMAIL' => $arFields['LOGIN']
    ];

    $dbUsers = CUser::GetList(($by = "id"), ($order = "asc"), $filter);

    if ($dbUsers->is_filtered) {
      if ($arUser = $dbUsers->Fetch()) {
        $arFields['LOGIN'] = $arUser['LOGIN'];
      }
    }
  }
}

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "DoIBlockAfterSave");
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "DoIBlockAfterSave");
AddEventHandler("catalog", "OnPriceAdd", "DoIBlockAfterSave");
AddEventHandler("catalog", "OnPriceUpdate", "DoIBlockAfterSave");
function DoIBlockAfterSave($arg1, $arg2 = false)
{
  $ELEMENT_ID = false;
  $IBLOCK_ID = false;
  $OFFERS_IBLOCK_ID = false;
  $OFFERS_PROPERTY_ID = false;
  if (CModule::IncludeModule('currency'))
    $strDefaultCurrency = CCurrency::GetBaseCurrency();

  //Check for catalog event
  if (is_array($arg2) && $arg2["PRODUCT_ID"] > 0) {
    //Get iblock element
    $rsPriceElement = CIBlockElement::GetList(
      array(),
      array(
        "ID" => $arg2["PRODUCT_ID"],
      ),
      false,
      false,
      array("ID", "IBLOCK_ID")
    );
    if ($arPriceElement = $rsPriceElement->Fetch()) {
      $arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
      if (is_array($arCatalog)) {
        //Check if it is offers iblock
        if ($arCatalog["OFFERS"] == "Y") {
          //Find product element
          $rsElement = CIBlockElement::GetProperty(
            $arPriceElement["IBLOCK_ID"],
            $arPriceElement["ID"],
            "sort",
            "asc",
            array("ID" => $arCatalog["SKU_PROPERTY_ID"])
          );
          $arElement = $rsElement->Fetch();
          if ($arElement && $arElement["VALUE"] > 0) {
            $ELEMENT_ID = $arElement["VALUE"];
            $IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
            $OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
            $OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
          }
        } //or iblock which has offers
        elseif ($arCatalog["OFFERS_IBLOCK_ID"] > 0) {
          $ELEMENT_ID = $arPriceElement["ID"];
          $IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
          $OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
          $OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
        } //or it's regular catalog
        else {
          $ELEMENT_ID = $arPriceElement["ID"];
          $IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
          $OFFERS_IBLOCK_ID = false;
          $OFFERS_PROPERTY_ID = false;
        }
      }
    }
  } //Check for iblock event
  elseif (is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0) {
    //Check if iblock has offers
    $arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
    if (is_array($arOffers)) {
      $ELEMENT_ID = $arg1["ID"];
      $IBLOCK_ID = $arg1["IBLOCK_ID"];
      $OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
      $OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
    }
  }

  if ($ELEMENT_ID) {
    static $arPropCache = array();
    if (!array_key_exists($IBLOCK_ID, $arPropCache)) {
      //Check for MINIMAL_PRICE property
      $rsProperty = CIBlockProperty::GetByID("MINIMUM_PRICE", $IBLOCK_ID);
      $arProperty = $rsProperty->Fetch();
      if ($arProperty)
        $arPropCache[$IBLOCK_ID] = $arProperty["ID"];
      else
        $arPropCache[$IBLOCK_ID] = false;
    }

    if ($arPropCache[$IBLOCK_ID]) {
      //Compose elements filter
      if ($OFFERS_IBLOCK_ID) {
        $rsOffers = CIBlockElement::GetList(
          array(),
          array(
            "IBLOCK_ID" => $OFFERS_IBLOCK_ID,
            "PROPERTY_" . $OFFERS_PROPERTY_ID => $ELEMENT_ID,
          ),
          false,
          false,
          array("ID")
        );
        while ($arOffer = $rsOffers->Fetch())
          $arProductID[] = $arOffer["ID"];

        if (!is_array($arProductID))
          $arProductID = array($ELEMENT_ID);
      } else
        $arProductID = array($ELEMENT_ID);

      $minPrice = false;
      $maxPrice = false;
      //Get prices
      $rsPrices = CPrice::GetList(
        array(),
        array(
          "PRODUCT_ID" => $arProductID,
        )
      );
      while ($arPrice = $rsPrices->Fetch()) {
        if (CModule::IncludeModule('currency') && $strDefaultCurrency != $arPrice['CURRENCY'])
          $arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $strDefaultCurrency);

        $PRICE = $arPrice["PRICE"];

        if ($minPrice === false || $minPrice > $PRICE)
          $minPrice = $PRICE;

        if ($maxPrice === false || $maxPrice < $PRICE)
          $maxPrice = $PRICE;
      }

      //Save found minimal price into property
      if ($minPrice !== false) {
        CIBlockElement::SetPropertyValuesEx(
          $ELEMENT_ID,
          $IBLOCK_ID,
          array(
            "MINIMUM_PRICE" => $minPrice,
            "MAXIMUM_PRICE" => $maxPrice,
          )
        );
      }
    }
  }
}

//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("loadElementFrom1C", "UpdateProps"));
//AddEventHandler("iblock", "OnBeforeIBlockElementAdd", array("loadElementFrom1C", "UpdateProps"));
//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", array("loadElementFrom1C", "UpdateProps"));

//AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "UpdatePropsNew");
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("elementLoadFrom1C", "UpdatePropsNew"));

//AddEventHandler("iblock", "OnIBlockElementUpdate", "UpdatePropsNew");
class elementLoadFrom1C
{
  static public $elementsId = [];

  static function UpdatePropsNew(&$arFields)
  {
    if (!isset(self::$elementsId[$arField['ID']])) {
      if (($arFields['PROPERTY_VALUES']['313'][0]['VALUE'] == 707 || $arFields['PROPERTY_VALUES']['313']['n0']['VALUE'] == 707) ||
        ($arFields['PROPERTY_VALUES']['312'][0]['VALUE'] == 706 || $arFields['PROPERTY_VALUES']['312']['n0']['VALUE'] == 706)
      ) {
        $PROPERTY_VALUE = array(
          0 => array("VALUE" => "361", "DESCRIPTION" => "")
        );
        CIBlockElement::SetPropertyValuesEx($arFields['ID'], 14, array("NEW" => $PROPERTY_VALUE));
      }

      if (($arFields['PROPERTY_VALUES']['313'][0]['VALUE'] == 710 || $arFields['PROPERTY_VALUES']['313']['n0']['VALUE'] == 710) ||
        ($arFields['PROPERTY_VALUES']['312'][0]['VALUE'] == 709 || $arFields['PROPERTY_VALUES']['312']['n0']['VALUE'] == 709) ||
        ($arFields['PROPERTY_VALUES']['312'][0]['VALUE'] == "" && $arFields['PROPERTY_VALUES']['312']['n0']['VALUE'] == "")
      ) {
        $PROPERTY_VALUE = array(
          0 => array("VALUE" => "", "DESCRIPTION" => "")
        );
        CIBlockElement::SetPropertyValuesEx($arFields['ID'], 14, array("NEW" => $PROPERTY_VALUE));
      }
    }
    self::$elementsId[$arField['ID']] = $arField['ID'];
  }
}


class loadElementFrom1C
{
  public static function idProp($description)
  {
    $arIdPropByDesc = array(
      'Длина:' => array(
        'prop_id' => '104',
      ),
      'Толщина:' => array(
        'prop_id' => '105',
      ),
      'Изгиб:' => array(
        'prop_id' => '106',
      ),
    );

    if ($arIdPropByDesc[$description]["prop_id"] == "") return false;

    $res = CIBlockProperty::GetByID($arIdPropByDesc[$description]["prop_id"]);
    if ($ar_res = $res->GetNext()) {
      return $ar_res;
    } else {
      return false;
    }
  }

  public static function convertValue($value, $prop)
  {
    if ($prop["PROPERTY_TYPE"] == "S") {
      return $value;
    } elseif ($prop["PROPERTY_TYPE"] == "L") {
      //$new_value = $value;
      $db_enum_list = CIBlockProperty::GetPropertyEnum($prop["ID"], array("value" => "asc", "id" => "asc"));
      $propValues = array();
      while ($ar_enum_list = $db_enum_list->GetNext()) {
        $propValues[$ar_enum_list["ID"]] = $ar_enum_list;
        if ($ar_enum_list["VALUE"] == $value) {
          //xlog(array("NAME"=> "test convertValue", "VAL" => array($value,$prop)));
          return $ar_enum_list["ID"];
        }
      }

      $ibpenum = new CIBlockPropertyEnum;
      if ($PropID = $ibpenum->Add(array('PROPERTY_ID' => $prop["ID"], 'VALUE' => $value))) {
        return $PropID;
      }
    }
  }
}


/*Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnSaleComponentOrderCreated',
    array('recalcBasket', 'recalcDelivery')
);*/

Main\EventManager::getInstance()->addEventHandler(
  "logictim.balls",
  "AfterCalculateOrderBonus",
  array('recalcBasket', 'BonusesRecalc')
);

class recalcBasket
{
  public static $flagAddProcess = false;
  public static $freeDelivery = true;
  public static $usedBonuses = false;
  public static $IBLOCK_ID = 14;
  public static $IBLOCK_OFFERS_ID = 15;

  /*public static function recalcDelivery($order)
  {
      if ($order->getId())
          return;
      if (self::$flagAddProcess)
          return;
      self::$flagAddProcess = true;

      CModule::IncludeModule("iblock");
      CModule::IncludeModule("sale");
      CModule::IncludeModule("catalog");

      $basket = Basket::loadItemsForFUser(Bitrix\Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

      $basketItems = $basket->getBasketItems();

      // Если есть акционные товары, то доставка не бесплатна
      foreach ($basketItems as $basketItem) {
          $prodId = $basketItem->getProductId();
          $mxResult = CCatalogSku::GetProductInfo($basketItem->getProductId());
          if (is_array($mxResult)) {
              $prodId = $mxResult['ID'];
          }
          $prodProps = CIBlockElement::GetProperty(self::$IBLOCK_ID, $prodId, array("sort" => "asc"), array("CODE" => "AKTSIONNYY"));
          if ($res = $prodProps->GetNext()) {
              if (strtolower($res['VALUE_ENUM']) == 'да') {
                  self::$freeDelivery = false;
              }
          }
      }

      $couponsManager = new DiscountCouponsManager;

      // Если применены купоны, то доставка не бесплатна
      if (count($couponsManager->get()) > 0) {
          self::$freeDelivery = false;
      }


      // Сумма заказа меньше 4000, значит доставка не бесплатна
      if ($basket->getPrice() < 4000) {
          self::$freeDelivery = false;
      }

      // Если применены баллы, то доставка не бесплатна
      if (self::$usedBonuses) {
          self::$freeDelivery = false;
      }

      if (self::$freeDelivery) {
          self::freeDelivery($order);
      }

  }*/

  public static function BonusesRecalc(Main\Event $event)
  {
    $arBonus = $event->getParameters();
    /* Если применены баллы, то доставка не бесплатна */
    if (isset($arBonus['ORDER_INFO']['PAY_BONUS']) && $arBonus['ORDER_INFO']['PAY_BONUS'] > 0) {
      self::$usedBonuses = true;
    }

    /* Не применять баллы к акционным товарам */
    foreach ($arBonus['ITEMS'] as $item) {
      $prodProps = CIBlockElement::GetProperty(self::$IBLOCK_ID, $item['ID'], array("sort" => "asc"), array("CODE" => "AKTSIONNYY"));
      if ($res = $prodProps->GetNext()) {
        if (strtolower($res['VALUE_ENUM']) == 'да') {
          $arBonus['ALL_BONUS'] -= $arBonus['ITEMS'][$item['ID']]['ADD_BONUS'];
          $arBonus['ITEMS'][$item['ID']]['ADD_BONUS'] = 0;
          $arBonus['ITEMS'][$item['ID']]['PRICE']['DISCOUNT_PRICE'] = 0;
        }
      }
    }

    $couponsManager = new DiscountCouponsManager;

    /* Если применены купоны, то баллы не начисляются */
    if (count($couponsManager->get()) > 0) {
      $arBonus['ALL_BONUS'] = 0;
    }

    //debug($couponsManager->get());
    $result = new Bitrix\Main\EventResult($event->getEventType(), $arBonus);
    return $result;
  }

  public static function freeDelivery($order)
  {
    $deliveryPrice = 0;
    $shipmentCollection = $order->getShipmentCollection();
    foreach ($shipmentCollection as $shIt) {
      if (!$shIt->isSystem())
        $shIt->setFields(array('PRICE_DELIVERY' => $deliveryPrice, 'BASE_PRICE_DELIVERY' => $deliveryPrice));
    }
  }
}

/*function agentAddamantCoupons()
{
    CModule::IncludeModule("sale");
    include $_SERVER['DOCUMENT_ROOT'] . "/local/php_interface/include/coupons/include.php";
    $xmlCoupons = new couponsImport();
    $xmlCoupons->start();
    return 'agentAddamantCoupons();';
}*/


/* поиск только по заголовкам */
AddEventHandler("search", "BeforeIndex", array("SearchHandlers", "BeforeIndexHandler"));

class SearchHandlers
{
  public static function BeforeIndexHandler($arFields)
  {
    if ($arFields["MODULE_ID"] == "iblock") {
      if (array_key_exists("BODY", $arFields) && substr($arFields["ITEM_ID"], 0, 1) != "S") // Только для элементов
      {
        $arFields["BODY"] = " ";
      }

      if (substr($arFields["ITEM_ID"], 0, 1) == "S") // Только для разделов
      {
        $arFields['TITLE'] = "";
        $arFields["BODY"] = "";
        $arFields['TAGS'] = "";
      }
    }

    return $arFields;
  }
}

/* при сохранении удалять спец символы *

/**
 * У свойства есть только 2 переменные Значение - описание.
 * Потому если нам нужно добавить 3,4,5 то лучше это сделать через сериализацию массива описани
 * В вывод добавляем еще input, например:
 * <input type="text" id="meas" name="'.$strHTMLControlName["DESCRIPTION"].'[1]" value="'.htmlspecialcharsex($arValue["DESCRIPTION"][1]).'" />
 * Ну я думаю понятно как ключи массива работают
 *
 * //dev.1c-bitrix.ru/api_help/iblock/classes/user_properties/index.php - тут очень хорошо расписано о полз. свойствах
 * Примеры стандартных свойств лежат тут (/bitrix/modules/iblock/classes/general)
 */

AddEventHandler('iblock', 'OnIBlockPropertyBuildList', array('CustomTableProp', 'GetUserTypeDescription')); //построение списка свойств инфоблока

AddEventHandler('iblock', 'OnIBlockPropertyBuildList', array('CustomTableProp', 'DateRange')); //построение списка свойств инфоблока

class CustomTableProp
{
  //описываем поведение пользовательского свойства
  public static function GetUserTypeDescription()
  {
    return array(
      'PROPERTY_TYPE' => 'E',
      'USER_TYPE' => 'palitra',
      'DESCRIPTION' => 'Таблица цветов', //именно это будет выведено в списке типов свойств во вкладке редактирования свойств ИБ
      //указываем необходимые функции, используемые в создаваемом типе
      "GetPropertyFieldHtml" => array(__CLASS__, "GetPropertyFieldHtml"),
      "ConvertToDB" => array(__CLASS__, "ConvertToDB"),
      "ConvertFromDB" => array(__CLASS__, "ConvertFromDB")
    );
  }

  public static function DateRange()
  {
    return array(
      'PROPERTY_TYPE' => 'S',
      'USER_TYPE' => 'date_range',
      'DESCRIPTION' => 'Диапазон дат', //именно это будет выведено в списке типов свойств во вкладке редактирования свойств ИБ
      //указываем необходимые функции, используемые в создаваемом типе
      "GetPropertyFieldHtml" => array(__CLASS__, "GetPropertyFieldDateHtml"),
      "ConvertToDB" => array(__CLASS__, "ConvertToDB"),
      "ConvertFromDB" => array(__CLASS__, "ConvertFromDB")
    );
  }

  //формируем пару полей для создаваемого свойства

  public static function GetPropertyFieldHtml($arProperty, $arValue, $strHTMLControlName)
  {

    // Описание превращаем обратно в массив ( в шаблоне также )
    $arValue["DESCRIPTION"] = unserialize($arValue["DESCRIPTION"]);
    if (intval($arValue["VALUE"]) > 0) {
      $arFilter = array(
        "ID" => intval($arValue["VALUE"]),
        "IBLOCK_ID" => $arProperty["LINK_IBLOCK_ID"],
      );
      //Получаем информацию о элементах по ID
      $rsItem = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "IBLOCK_ID", "NAME"));
      $arItem = $rsItem->GetNext();
    }
    // Что будет выведено:
    // jsUtils.OpenWindow... - открывает окно с выбором элемента инфоблока
    return '
              <table border="0" cellspacing="0" cellpadding="0" width="100%" class="internal">
                <tbody>
                  <tr class="heading">
                    <td>Элемент</td>
                    <td>Номер цвета</td>
                  </tr>
                  <tr>
                    <td align="center" width="50%">
                      <input name="' . $strHTMLControlName["VALUE"] . '" id="' . $strHTMLControlName["VALUE"] . '" value="' . htmlspecialcharsex($arValue["VALUE"]) . '" size="5" type="text">
                      <input type="button" value="Выбрать" onClick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?lang=' . LANG . '&IBLOCK_ID=' . $arProperty["LINK_IBLOCK_ID"] . '&n=' . $strHTMLControlName["VALUE"] . '\', 600, 500);">
                      <br><span id="sp_' . md5($strHTMLControlName["VALUE"]) . '_' . $key . '" >' . $arItem["NAME"] . '</span>
                    </td>
                    <td align="center">
                      <input type="text" id="meas" name="' . $strHTMLControlName["DESCRIPTION"] . '[0]" value="' . htmlspecialcharsex($arValue["DESCRIPTION"][0]) . '" />
                    </td>
                  </tr>
                </tbody>
              </table>
            ';
  }

  public static function GetPropertyFieldDateHtml($arProperty, $arValue, $strHTMLControlName)
  {

    // Описание превращаем обратно в массив ( в шаблоне также )
    $arValue["DESCRIPTION"] = unserialize($arValue["DESCRIPTION"]);
    if (intval($arValue["VALUE"]) > 0) {
      $arFilter = array(
        "ID" => intval($arValue["VALUE"]),
        "IBLOCK_ID" => $arProperty["LINK_IBLOCK_ID"],
      );
      //Получаем информацию о элементах по ID
      $rsItem = CIBlockElement::GetList(array(), $arFilter, false, false, array("ID", "IBLOCK_ID", "NAME"));
      $arItem = $rsItem->GetNext();
    }
    // Что будет выведено:
    // jsUtils.OpenWindow... - открывает окно с выбором элемента инфоблока
    /* echo '<pre>';
     var_export($strHTMLControlName);
     echo '<br>';
     var_export($arValue);
     echo '<br>';
     var_export('PROP[' . $arProperty['ID'] . '][n0][DESCRIPTION][0]');
     echo '<br>';
     echo '<br>';
     echo '</pre>';*/
    return '
              <table border="0" cellspacing="0" cellpadding="0" class="internal">
                <tbody>
                  <tr class="heading">
                    <td>Дата начала</td>
                    <td>Дата окончания</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="adm-input-wrap adm-input-wrap-calendar">
                        <input class="adm-input adm-input-calendar" name="' . $strHTMLControlName["VALUE"] . '" id="' . $strHTMLControlName["VALUE"] . '" value="' . htmlspecialcharsex($arValue["VALUE"]) . '" type="text">
                        <span class="adm-calendar-icon" title="Нажмите для выбора даты" onclick="BX.calendar({node:this, field:\'' . $strHTMLControlName["VALUE"] . '\', form: \'\', bTime: false, bHideTime: false});"></span>
                      </div>
                    </td>
                    <td>
                      <div class="adm-input-wrap adm-input-wrap-calendar">
                        <input type="text" id="meas" name="' . $strHTMLControlName["DESCRIPTION"] . '[0]" value="' . htmlspecialcharsex($arValue["DESCRIPTION"][0]) . '" />
                        <span class="adm-calendar-icon" title="Нажмите для выбора даты" onclick="BX.calendar({node:this, field:\'' . $strHTMLControlName["DESCRIPTION"] . '[0]\', form: \'\', bTime: false, bHideTime: false});"></span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            ';
  }

  // При сохранении свойства в БД
  public static function ConvertToDB($arProperty, $arValue)
  {
    //Если значение или описание массив, то сериализуем
    if (is_array($arValue) && array_key_exists("VALUE", $arValue) && !empty($arValue['VALUE'])) {
      $arValue["VALUE"] = serialize($arValue["VALUE"]);
      $arValue['DESCRIPTION'] = serialize($arValue["DESCRIPTION"]);
    }
    return $arValue;
  }

  // При извлечении значения из БД (по странным причинам сюда не попадает описание)
  public static function ConvertFromDB($arProperty, $arValue)
  {
    if (is_array($arValue) && array_key_exists("VALUE", $arValue) && !empty($arValue['VALUE'])) {
      $arValue["VALUE"] = unserialize($arValue["VALUE"]);
    }
    //unserialize для описания проводим в выводе шаблона т.к функция передаст тооько строку
    return $arValue;
  }
}

class Courses
{
  private static function months(): array
  {
    return ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
  }

  private static function unSerializeStr($str): array
  {
    $str = utf8_encode($str);
    $str = html_entity_decode($str);
    return unserialize($str);
  }

  public static function getDateRange($fromDates, $toDates, $prefix = ""): string
  {

    $datePeriodsList = [];
    foreach ($fromDates as $key => $item) {
      $to = null;
      $from = $item;
      $dataForCompare = $from;
      $dateArray = self::unSerializeStr($toDates[$key]);
      if (strlen($dateArray[0])) {
        $to = $dateArray[0];
        $dataForCompare = $to;
      }


      $currentDate = date('d.m.Y');
      if (strtotime($dataForCompare) > strtotime($currentDate)) {
        $fromTimeStamp = strtotime($from);
        $dayFrom = date("j", $fromTimeStamp);
        $monthFrom = date("n", $fromTimeStamp);
        $dayTo = null;
        $monthTo = null;

        if ($to) {
          $toTimeStamp = strtotime($to);
          $dayTo = date("j", $toTimeStamp);
          $monthTo = date("n", $toTimeStamp);
        }

        $months = self::months();
        $monthFromIndex = $monthFrom - 1;
        $datePeriod = "$dayFrom $months[$monthFromIndex]";

        if ($monthTo) {
          $monthToIndex = $monthTo - 1;
          $datePeriod = "$dayFrom $months[$monthFromIndex] - $dayTo $months[$monthToIndex]";

          if ($monthFrom === $monthTo) {
            $monthIndex = $monthTo - 1;
            $datePeriod = "$dayFrom - $dayTo $months[$monthIndex]";
          }
        }
        $datePeriodsList[] = $datePeriod;
      }
    }
    return count($datePeriodsList) ? $prefix . " " . trim(implode(", ", $datePeriodsList)) : "";
  }
}

/* логирование изменения товаров (поле картинка)*/
/*class BeforeElement{
    // создаем обработчик события "OnBeforeIBlockElementUpdate"
    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        AddMessage2Log(var_export($arFields, true));
    }
}*/

/*Если было удаление картинки */

class AfterElement
{
  static function AddMessage2LogAdd($sText, $sModule = "", $traceDepth = 6, $bShowArgs = false)
  {
    if (defined("LOG_FILENAME_ADD") && strlen(LOG_FILENAME_ADD) > 0) {
      if (!is_string($sText)) {
        $sText = var_export($sText, true);
      }
      if (strlen($sText) > 0) {
        ignore_user_abort(true);
        if ($fp = @fopen(LOG_FILENAME_ADD, "ab")) {
          if (flock($fp, LOCK_EX)) {
            @fwrite($fp, "Host: " . $_SERVER["HTTP_HOST"] . "\nDate: " . date("Y-m-d H:i:s") . "\nModule: " . $sModule . "\n" . $sText . "\n");
            $arBacktrace = Bitrix\Main\Diag\Helper::getBackTrace($traceDepth, ($bShowArgs ? null : DEBUG_BACKTRACE_IGNORE_ARGS));
            $strFunctionStack = "";
            $strFilesStack = "";
            $firstFrame = (count($arBacktrace) == 1 ? 0 : 1);
            $iterationsCount = min(count($arBacktrace), $traceDepth);
            for ($i = $firstFrame; $i < $iterationsCount; $i++) {
              if (strlen($strFunctionStack) > 0)
                $strFunctionStack .= " < ";

              if (isset($arBacktrace[$i]["class"]))
                $strFunctionStack .= $arBacktrace[$i]["class"] . "::";

              $strFunctionStack .= $arBacktrace[$i]["function"];

              if (isset($arBacktrace[$i]["file"]))
                $strFilesStack .= "\t" . $arBacktrace[$i]["file"] . ":" . $arBacktrace[$i]["line"] . "\n";
              if ($bShowArgs && isset($arBacktrace[$i]["args"])) {
                $strFilesStack .= "\t\t";
                if (isset($arBacktrace[$i]["class"]))
                  $strFilesStack .= $arBacktrace[$i]["class"] . "::";
                $strFilesStack .= $arBacktrace[$i]["function"];
                $strFilesStack .= "(\n";
                foreach ($arBacktrace[$i]["args"] as $value)
                  $strFilesStack .= "\t\t\t" . $value . "\n";
                $strFilesStack .= "\t\t)\n";
              }
            }

            if (strlen($strFunctionStack) > 0) {
              @fwrite($fp, "    " . $strFunctionStack . "\n" . $strFilesStack);
            }

            @fwrite($fp, "----------\n");
            @fflush($fp);
            @flock($fp, LOCK_UN);
            @fclose($fp);
          }
        }
        ignore_user_abort(false);
      }
    }
  }

  // создаем обработчик события "OnAfterIBlockElementUpdate"
  static function OnBeforeIBlockElementUpdateHandler(&$arFields)
  {
    if (!empty($arFields['PROPERTY_VALUES']['113'])) {
      if (!CModule::IncludeModule("iblock"))
        return;
      $res = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], "sort", "asc", array("ID" => "113"));
      $VALUES = [];
      while ($ob = $res->GetNext()) {
        $img = CFile::GetByID($ob['VALUE']);
        $VALUES[] = $img->arResult;
      }
      //self::AddMessage2LogAdd(" ID " . var_export($arFields['ID'], true) . "\n BEFORE " . "\n Изображение свойства картинки удалено - " . var_export($arFields['PROPERTY_VALUES']['113'], true) . "\n ID изменившего данный товар " . var_export($arFields['MODIFIED_BY'], true) . " \n " . var_export($VALUES, true));
    }
    if (!empty($arFields['DETAIL_PICTURE'])) {
      if (!CModule::IncludeModule("iblock"))
        return;
      $res = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], "sort", "asc", array("ID" => "113"));
      $VALUES = [];
      while ($ob = $res->GetNext()) {
        $img = CFile::GetByID($ob['VALUE']);
        $VALUES[] = $img->arResult;
      }

     // self::AddMessage2LogAdd(" ID " . var_export($arFields['ID'], true) . "\n BEFORE " . "\n Изображение детальное удалено - " . var_export($arFields['DETAIL_PICTURE'], true) . "\n ID изменившего данный товар " . var_export($arFields['MODIFIED_BY'], true) . " \n " . var_export($VALUES, true));
    }
    if (!empty($arFields['PREVIEW_PICTURE'])) {
      if (!CModule::IncludeModule("iblock"))
        return;
      $res = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], "sort", "asc", array("ID" => "113"));
      $VALUES = [];
      while ($ob = $res->GetNext()) {
        $img = CFile::GetByID($ob['VALUE']);
        $VALUES[] = $img->arResult;
      }

      //self::AddMessage2LogAdd(" ID " . var_export($arFields['ID'], true) . "\n BEFORE " . "\n Изображение анонс удалено - " . var_export($arFields['PREVIEW_PICTURE'], true) . "\n ID изменившего данный товар " . var_export($arFields['MODIFIED_BY'], true) . " \n " . var_export($VALUES, true));
    }
  }

  static function OnAfterIBlockElementUpdateHandler(&$arFields)
  {
    if (!empty($arFields['PROPERTY_VALUES']['113'] || $arFields['DETAIL_PICTURE'])) {
      //self::AddMessage2LogAdd(var_export($arFields, true));
      //self::AddMessage2LogAdd(" ID " . var_export($arFields['ID'], true) . "\n AFTER " . "\n Изображение свойства картинки удалено - " . var_export($arFields['PROPERTY_VALUES']['113'], true) . "\n ID изменившего данный товар " . var_export($arFields['MODIFIED_BY'], true));
    }
    if (!empty($arFields['DETAIL_PICTURE'])) {
     // self::AddMessage2LogAdd(" ID " . var_export($arFields['ID'], true) . "\n AFTER " . "\n Изображение детальное удалено - " . var_export($arFields['DETAIL_PICTURE'], true) . "\n ID изменившего данный товар " . var_export($arFields['MODIFIED_BY'], true));
    }
    if (!empty($arFields['PREVIEW_PICTURE'])) {
      //self::AddMessage2LogAdd(" ID " . var_export($arFields['ID'], true) . "\n AFTER " . "\n Изображение анонс удалено - " . var_export($arFields['PREVIEW_PICTURE'], true) . "\n ID изменившего данный товар " . var_export($arFields['MODIFIED_BY'], true));
    }
  }
}

/*
 * Если нужно при обновлении товара, не обновлять какие-либо поля
 */
//AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "DoNotUpdate");
function DoNotUpdate(&$arFields)
{
  if ($_REQUEST['mode'] == 'import') {
    /*unset($arFields['PREVIEW_PICTURE']);
    unset($arFields['DETAIL_PICTURE']);
    unset($arFields['PREVIEW_TEXT']);
    unset($arFields['DETAIL_TEXT']);*/
    unset($arFields["IBLOCK_SECTION"]);
    unset($arFields['IBLOCK_SECTIONS']);
  }
}

function declOfNum($num, $titles)
{
  $cases = array(2, 0, 1, 1, 1, 2);
  return $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]] . " " . $num;
}

function declOfNumLevel($num, $titles)
{
  $cases = array(2, 0, 1, 1, 1, 2);
  return $num.' '.$titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
}

function declOfNumCountCatalog($number, $titles)
{
  $cases = array(2, 0, 1, 1, 1, 2);
  $format = $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
  return sprintf($format, $number);
}

/// Подписка при регистрации
AddEventHandler("main", "OnAfterUserRegister", array("ClassAfterRegister", "OnAfterUserRegisterHandler"));

class ClassAfterRegister
{
  public static function OnAfterUserRegisterHandler($arUser)
  {

    global $USER;
    \Bitrix\Main\Loader::includeModule('sender');
    // условие атоподписки, если пользователь подтверждает регистрацию или если admin редактирует пользователя
    $res = \Bitrix\Sender\Subscription::getMailingList([]);

    $subscribeList = [];
    $uEmail = $USER->getEmail();

    foreach ($res as $mailList) {
      $subscribeList[] = $mailList['ID'];
    }

    $contactId = \Bitrix\Sender\ContactTable::addIfNotExist(['EMAIL' => $uEmail]);
    $contact = new \Bitrix\Sender\Entity\Contact($contactId);

    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();

    if ($arUser['UF_SUBSCRIBES'] == '1') {
      foreach ($subscribeList as $item) {
        $contact->subscribe($item);
      }
    }
  }
}

AddEventHandler("main", "OnBeforeUserAdd", array("beforeUserAdd", "OnBeforeUserAddHandler"));

class beforeUserAdd
{
  // создаем обработчик события "OnBeforeUserAdd"
  public static function OnBeforeUserAddHandler(&$arFields)
  {
    //AddMessage2Log(var_export($arFields, true));
    $arFields['PERSONAL_PHONE'] = preg_replace('~\D+~', '', $arFields['PERSONAL_PHONE']);
  }
}

//отправка смс после регистрации
AddEventHandler("main", "OnAfterUserAdd", array("afterRegistration", "OnAfterUserAddHandler"));

class SendsmsAfterRegistration
{
  public static function OnAfterUserAddHandler(&$arFields)
  {
    $BXMAKER_MODULE_ID = 'bxmaker.smsnotice';
    \Bitrix\Main\Loader::includeModule($BXMAKER_MODULE_ID);
    $oManager = \BXmaker\SmsNotice\Manager::getInstance();
    $arFields['PERSONAL_PHONE'] = preg_replace('~\D+~', '', $arFields['PERSONAL_PHONE']);
    $result = $oManager->sendTemplate('REGISTRATION_SMS', array(
      'PHONE' => preg_replace('/[^0-9]/', '', $arFields['PERSONAL_PHONE']),
    ));
  }
}

AddEventHandler("main", "OnBeforeUserUpdate", ["AddamantUser", "beforeUpdate"]);
AddEventHandler("main", "OnBeforeUserAdd", ["AddamantUser", "beforeAdd"]);
AddEventHandler("main", "OnBeforeUserRegister", ["AddamantUser", "beforeRegister"]);
AddEventHandler("main", "OnAfterUserRegister", ["AddamantUser", "afterRegister"]);
AddEventHandler("bxmaker.authuserphone", "BXmakerAuthUserPhoneEnterComponentAjax", ["AddamantUser", "ajaxRegister"]);


class AddamantUser
{
  public static function beforeAuthPhoneRegister(&$arFields)
  {
    $req = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $arExpandData = $req->getPost('expandData');

    if (is_array($arExpandData) && isset($arExpandData['lastName'])) {
      $arFields['LAST_NAME'] = trim(htmlentities($arExpandData['lastName'], ENT_QUOTES));
    } else {
      throw new \BXmaker\AuthUserPhone\Exception\BaseException(
        'Не заполнено поле обязательное - Фамилия',
        'ERROR_LAST_NAME'
      );
    }

    if (is_array($arExpandData) && isset($arExpandData['firstName'])) {
      $arFields['FIRST_NAME'] = trim(htmlentities($arExpandData['firstName'], ENT_QUOTES));
    } else {
      throw new \BXmaker\AuthUserPhone\Exception\BaseException(
        'Не заполнено поле обязательное - Имя',
        'ERROR_FIRST_NAME'
      );
    }

    if (is_array($arExpandData) && isset($arExpandData['secondName'])) {
      $arFields['SECOND_NAME'] = trim(htmlentities($arExpandData['secondName'], ENT_QUOTES));
    } else {
      throw new \BXmaker\AuthUserPhone\Exception\BaseException(
        'Не заполнено поле обязательное - Отчество',
        'ERROR_SECOND_NAME'
      );
    }

    if (!(is_array($arExpandData) && isset($arExpandData['passwordConfirm']) && $arFields['PASSWORD'] == $arExpandData['passwordConfirm'])) {
      throw new \BXmaker\AuthUserPhone\Exception\BaseException(
        'Пароль и подтвеждение пароля должны быть одинаковыми',
        'ERROR_CONFIRM_PASSWORD'
      );
    }


    if (is_array($arExpandData) && isset($arExpandData['day']) && isset($arExpandData['month']) && isset($arExpandData['year'])) {
      $day = trim(htmlentities($arExpandData['day'], ENT_QUOTES));
      $month = trim(htmlentities($arExpandData['month'], ENT_QUOTES));
      $year = trim(htmlentities($arExpandData['year'], ENT_QUOTES));
      $arFields['PERSONAL_BIRTHDAY'] = Main\Type\DateTime::createFromText($day . '.' . $month . '.' . $year);
    }

    if (is_array($arExpandData) && isset($arExpandData['subscribeNewsConsent'])) {
      $arFields['UF_SUBSCRIBES'] = $arExpandData['subscribeNewsConsent'];
    }
  }
  public static function beforeUpdate(&$arFields)
  {
    if (strlen($arFields['PERSONAL_PHONE']) > 0) {
      self::unifyPhone($arFields['PERSONAL_PHONE']);
      $arFields['PHONE_NUMBER'] = '+' . $arFields['PERSONAL_PHONE'];
    }
  }

  public static function beforeAdd(&$arFields)
  {
    self::unifyPhone($arFields['PERSONAL_PHONE']);
    $arFields['PHONE_NUMBER'] = '+' . $arFields['PERSONAL_PHONE'];
  }

  public static function afterRegister($arFields)
  {
    self::beforeUpdate($arFields);
  }

  public static function ajaxRegister($event)
  {
    $errors = [];
    $component = $event['component'];
    $method = $component->request()->getPost('method');

    if ($method != 'register') {
      return new \Bitrix\Main\EventResult(\Bitrix\Main\EventResult::SUCCESS, null);
    }

    $expandData = $component->request()->getPost('expandData');
    $lastName = $expandData['lastName'];
    $firstName = $expandData['firstName'];
    $secondName = $expandData['secondName'];
    $phone = \trim((string) $component->request()->getPost('phone'));

    if (empty(trim($lastName)))
      $errors[] = [
        'msg' => 'Не заполнено поле обязательное - Фамилия',
        'code' => 'ERROR_INVALID_LAST_NAME',
        'more' => [
          'error_fields' => 'lastName'
        ],
      ];

    if (empty(trim($firstName)))
      $errors[] = [
        'msg' => 'Не заполнено поле обязательное - Имя',
        'code' => 'ERROR_INVALID_FIRST_NAME',
        'more' => [
          'error_fields' => 'firstName'
        ],
      ];

    if (empty(trim($secondName)))
      $errors[] = [
        'msg' => 'Не заполнено поле обязательное - Отчество',
        'code' => 'ERROR_INVALID_SECOD_NAME',
        'more' => [
          'error_fields' => 'secondName'
        ],
      ];

    $phone = $component->manager()->getPreparedPhone($phone);
    if (!$component->manager()->isValidPhone($phone)) {
      $errors[] = [
        'msg' => 'Номер телефона не указан или указан с ошибками',
        'code' => 'ERROR_INVALID_PHONE',
        'more' => [
          'error_fields' => 'phone'
        ],
      ];
    }

    $email = \trim((string) $component->request()->getPost('email'));
    if ($component->arParams['IS_ENABLED_REGISTER_EMAIL'] == 'Y' && $component->arParams['IS_ENABLED_REGISTER_EMAIL_REQUIRED'] == 'Y' && empty($email)) {
      $errors[] = [
        'msg' => $component->getMessage('REGISTER_ACTION.ERROR_REGISTRATION_EMAIL_REQUIRED'),
        'code' => 'ERROR_REGISTRATION_EMAIL_REQUIRED',
        'more' => [
          'error_fields' => 'email'
        ],
      ];
    }
    if ($component->arParams['IS_ENABLED_REGISTER_EMAIL'] == 'Y' && !empty($email)) {
      if (!$component->manager()->isValidEmail($email)) {
        $errors[] = [
          'msg' => $component->getMessage('REGISTER_ACTION.ERROR_EMAIL_NOT_SET'),
          'code' => 'ERROR_EMAIL_NOT_SET',
          'more' => [
            'error_fields' => 'email'
          ],
        ];
      }
      if ($component->manager()->param()->isEnabledUniqueEmail() && $component->manager()->isExistEmail($email)) {
        $errors[] = [
          'msg' => $component->getMessage('REGISTER_ACTION.ERROR_EMAIL_USED'),
          'code' => 'ERROR_EMAIL_USED',
          'more' => [
            'error_fields' => 'email'
          ],
        ];
      }
      $arUserFields['EMAIL'] = $email;
    }

    $password = \trim((string) $component->request()->getPost('password'));
    if ($component->arParams['IS_ENABLED_REGISTER_PASSWORD'] == 'Y' && $component->arParams['IS_ENABLED_REGISTER_PASSWORD_REQUIRED'] == 'Y' && empty($password)) {
      $errors[] = [
        'msg' => $component->getMessage('REGISTER_ACTION.ERROR_REGISTRATION_PASSWORD_REQUIRED'),
        'code' => 'ERROR_REGISTRATION_PASSWORD_REQUIRED',
        'more' => [
          'error_fields' => 'password'
        ],
      ];
    }

    $passwordConfirm = \trim((string) $expandData['passwordConfirm']);
    if (
      $component->arParams['IS_ENABLED_REGISTER_PASSWORD'] == 'Y' &&
      $component->arParams['IS_ENABLED_REGISTER_PASSWORD_REQUIRED'] == 'Y' &&
      empty($passwordConfirm)
    ) {
      $errors[] = [
        'msg' => 'Поле Подтверждение пароля обязательно для заполнения',
        'code' => 'ERROR_REGISTRATION_PASSWORD_REQUIRED',
        'more' => [
          'error_fields' => 'passwordConfirm'
        ],
      ];
    }
    if (
      $component->arParams['IS_ENABLED_REGISTER_PASSWORD'] == 'Y' &&
      $component->arParams['IS_ENABLED_REGISTER_PASSWORD_REQUIRED'] == 'Y' &&
      !empty($passwordConfirm) &&
      $passwordConfirm !== $password
    ) {
      $errors[] = [
        'msg' => 'Значение полей Пароль и Подтверждение пароля должны быть одинаковыми',
        'code' => 'ERROR_REGISTRATION_PASSWORD_CONFIRM',
        'more' => [
          'error_fields' => 'passwordConfirm'
        ],
      ];
    }
    if ($component->arParams['IS_ENABLED_REGISTER_PASSWORD'] == 'Y' && !empty($password)) {
      $arUserFields['PASSWORD'] = $password;
      $arUserFields['CONFIRM_PASSWORD'] = $passwordConfirm;
    }

    if (count($errors)) {
      throw new \BXmaker\AuthUserPhone\Exception\BaseException(
        json_encode($errors),
        'COMPLEX_ERROR'
      );
    }
  }
  public static function beforeRegister(&$arFields)
  {
    $errors = [];
    $req = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    $arExpandData = $req->getPost('expandData');

    if (is_array($arExpandData) && isset($arExpandData['lastName']) && mb_strlen($arExpandData['lastName']) > 0) {
      $arFields['LAST_NAME'] = $arExpandData['lastName'];
    } else {
      $errors['lastName'][] = 'Не заполнено поле обязательное - Фамилия';
    }

    if (is_array($arExpandData) && isset($arExpandData['firstName']) && mb_strlen($arExpandData['firstName']) > 0) {
      $arFields['NAME'] = $arExpandData['firstName'];
    } else {
      $errors['firstName'][] = 'Не заполнено поле обязательное - Имя';
    }

    if (is_array($arExpandData) && isset($arExpandData['secondName']) && mb_strlen($arExpandData['secondName']) > 0) {
      $arFields['SECOND_NAME'] = $arExpandData['secondName'];
    } else {
      $errors['secondName'][] = 'Не заполнено поле обязательное - Отчество';
    }

    if (!(is_array($arExpandData) && isset($arExpandData['passwordConfirm']) && $arFields['PASSWORD'] == $arExpandData['passwordConfirm'])) {
      $errors['passwordConfirm'][] = 'Пароль и подтвеждение пароля должны быть одинаковыми';
    }


    if (is_array($arExpandData) && isset($arExpandData['day']) && isset($arExpandData['month']) && isset($arExpandData['year'])) {
      $day = trim(htmlentities($arExpandData['day'], ENT_QUOTES));
      $month = trim(htmlentities($arExpandData['month'], ENT_QUOTES));
      $year = trim(htmlentities($arExpandData['year'], ENT_QUOTES));
      $arFields['PERSONAL_BIRTHDAY'] = Main\Type\DateTime::createFromText($day . '.' . $month . '.' . $year);
    }

    if (is_array($arExpandData) && isset($arExpandData['subscribeNewsConsent'])) {
      $arFields['UF_SUBSCRIBES'] = $arExpandData['subscribeNewsConsent'];
    }

    if (count($errors)) {
      $arSegmentErrors = [];
      foreach ($errors as $error) {
        $arSegmentErrors[] = implode('<br/>', $error);
      }

      throw new \BXmaker\AuthUserPhone\Exception\BaseException(
        implode('<br/>', $arSegmentErrors),
        'ERROR_USER_TYPE',
        ['error_fields' => array_keys($errors)]
      );
    }

    self::unifyPhone($arFields['PERSONAL_PHONE']);
    $arFields['PHONE_NUMBER'] = '+' . $arFields['PERSONAL_PHONE'];
  }

  public static function unifyPhone(&$phone)
  {
    if (strlen($phone) > 0) {
      if ($phone[0] == "8") {
        $phone[0] = 7;
      }

      $phone = preg_replace('/[\D]/', '', $phone);
    }
  }
}

// Бесплатная доставка от 4000 тыс, кроме Кирова, до 20.02.2022. Задача №78505
/*Bitrix\Main\EventManager::getInstance()->addEventHandler('sale', 'OnSaleDeliveryServiceCalculate', 'setFreeDelivery');

function setFreeDelivery(Bitrix\Main\Event $event){
  $params = $event->getParameters(); 
  if ($params['SHIPMENT']):
    $baseResult = $event->getParameter('RESULT');
    $shipment = $event->getParameter('SHIPMENT');
    $orderPrice = $shipment->getShipmentItemCollection()->getShipment()->getCollection()->getOrder()->getPrice();
    $order = $shipment->getCollection()->getOrder();
    $property = $order->getPropertyCollection()->getItemByOrderPropertyId(5);
    if ($property !== null) {
      $propertyValue = CSaleLocation::GetByID($property->getvalue());
      if($orderPrice >= 4000 && $propertyValue['REGION_NAME'] != 'Кировская область') {
        $baseResult->setDeliveryPrice(0);
        $event->addResult(
          new Main\EventResult(
              Main\EventResult::SUCCESS, array('RESULT' => $baseResult)
          )
        );
      }
    }
  endif;
}*/

AddEventHandler('main', 'OnBeforeEventAdd', array('OnBeforeEventAdd', 'OnProductNotify'));

class OnBeforeEventAdd
{
  static function OnProductNotify(&$event, &$lid, &$arFields, &$message_id)
  {
    if ($event == 'CATALOG_PRODUCT_SUBSCRIBE_NOTIFY') {
      $unsubscribeData = [
        'unSubscribe' => "Y",
        'userContact' => $arFields['USER_CONTACT'],
        'subscribeId' => $arFields['ID'],
        'productId' => $arFields['PRODUCT_ID']
      ];

      $subscribeManager = new \Bitrix\Catalog\Product\SubscribeManager;
      $subscribeManager->unSubscribe($unsubscribeData);
    }
  }
}

function num_word($value, $words, $show = true)
{
  $num = $value % 100;
  if ($num > 19) {
    $num = $num % 10;
  }

  $out = ($show) ?  $value . ' ' : '';
  switch ($num) {
    case 1:
      $out .= $words[0];
      break;
    case 2:
    case 3:
    case 4:
      $out .= $words[1];
      break;
    default:
      $out .= $words[2];
      break;
  }

  return $out;
}

function clearCart()
{
  $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
  global $APPLICATION;
  if ($request->get('clear') !== null && Loader::includeModule("sale")) {
    CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());

    $redirect = $APPLICATION->GetCurPageParam('', ['clear']);

    LocalRedirect($redirect);
  }
}
// Подсчет количества бонусов получаемых с товаров 116942
function infoBonus($price)
{
  global $USER;
  if ($USER->IsAuthorized()) {
    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    $percent = $arUser['UF_CURPERCENT'];
    if (!empty($arUser['UF_CURPERCENT'])) {
      $bonus = floor($price * ($percent / 100));
      $bonusFormated = "+" . $bonus . " " . declOfNumCountCatalog($bonus, ['бонус', 'бонуса', 'бонусов']);

      $arBonus = [
        'PERCENT_VALUE' => $percent,
        'BONUS_VALUE' => $bonusFormated,
        'BONUS_NUM' => (int) $bonus,
      ];

      return $arBonus;
    } else {
      return false;
    }
  }
}
