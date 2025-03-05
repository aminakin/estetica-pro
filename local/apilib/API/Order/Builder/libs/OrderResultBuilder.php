<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilder;
use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;
use Bitrix\Sale;

class OrderResultBuilder
{
    private OrderBuilderInterface $orderBuilder;

    private array $arParams;

    private array $arUserResult;

    private array $arResult;

    private HttpRequest $request;

    private $order;

    private $arActivePaySystems;

    private $arPaySystemServiceAll;

    private $arDeliveryServiceAll;

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder, Order $order)
    {
        /** @var  orderBuilder OrderBuilder*/
        $this->orderBuilder = $orderBuilder;
        $this->arUserResult = &$this->orderBuilder->getUserResult();
        $this->arResult = &$this->orderBuilder->getArResult();
        $this->request = $this->orderBuilder->getRequest();
        $this->arParams = &$this->orderBuilder->getParams();
        $this->order = $order;
        $this->arActivePaySystems = &$this->orderBuilder->getActivePaySystem();
        $this->arPaySystemServiceAll = &$this->orderBuilder->getPaySystemServiceAll();
        $this->arDeliveryServiceAll = &$this->orderBuilder->getDeliveryServiceAll();
    }

    public function prepareResultArray()
    {
        $this->initGrid();
        $this->obtainBasket();
        $this->obtainPropertiesForIbElements();

        if ($this->arParams['COMPATIBLE_MODE'] == 'Y')
        {
            $this->obtainFormattedProperties();
        }

        $this->obtainDelivery();
        $this->obtainPaySystem();
        $this->obtainTaxes();
        $this->obtainTotal();

        if ($this->arParams['USER_CONSENT'] === 'Y')
        {
            $this->obtainUserConsentInfo();
        }

        $this->getJsDataResult();

        if ($this->arParams['COMPATIBLE_MODE'] == 'Y')
        {
            $this->obtainRelatedProperties();
            $this->makeResultCompatible();
            $this->makeOrderDataArray();
        }

        $this->arResult['USER_VALS'] = $this->arUserResult;
        $this->executeEvent('OnSaleComponentOrderOneStepProcess', $this->order);
        $this->arResult['USER_VALS'] = $this->arUserResult;

        //try to avoid use "executeEvent" methods and use new events like this
        foreach (GetModuleEvents("sale", 'OnSaleComponentOrderResultPrepared', true) as $arEvent)
            ExecuteModuleEventEx($arEvent, [$this->order, &$this->arUserResult, $this->request, &$this->arParams, &$this->arResult]);
    }

    protected function makeOrderDataArray()
    {
        $orderData = $this->order->getFieldValues();
        $orderData['ORDER_PRICE'] = $this->arResult['ORDER_PRICE'];
        $orderData['ORDER_WEIGHT'] = $this->arResult['ORDER_WEIGHT'];
        $orderData['WEIGHT_UNIT'] = $this->arResult['WEIGHT_UNIT'];
        $orderData['WEIGHT_KOEF'] = $this->arResult['WEIGHT_KOEF'];
        $orderData['SITE_ID'] = $this->orderBuilder->getSiteId();
        $orderData['USE_VAT'] = $this->arResult["USE_VAT"];
        $orderData['VAT_RATE'] = $this->arResult["VAT_RATE"];
        $orderData['VAT_SUM'] = $this->arResult["VAT_SUM"];

        $this->arResult['ORDER_DATA'] = array_merge($orderData, $this->arUserResult);
    }

    public function initGrid()
    {
        $this->arResult["GRID"]["HEADERS"] = $this->getGridHeaders($this->arParams["PRODUCT_COLUMNS"]);
        $this->arResult["GRID"]["HEADERS_HIDDEN"] = $this->getGridHeaders($this->arParams["PRODUCT_COLUMNS_HIDDEN"]);
    }

    public function getGridHeaders($productColumns)
    {
        $arr = [];

        if (is_array($productColumns) && !empty($productColumns))
        {
            $arCodes = [];
            $iBlockProps = [];
            foreach ($productColumns as $key => $value) // making grid headers array
            {
                if (strncmp($value, "PROPERTY_", 9) == 0)
                {
                    $propCode = mb_substr($value, 9);

                    if ($propCode == '')
                        continue;

                    $arCodes[] = $propCode;
                }
            }

            if ($this->orderBuilder->getUseCatalog() && !empty($arCodes))
            {
                $iBlockList = [];
                $catalogIterator = \Bitrix\Catalog\CatalogIblockTable::getList([
                    'select' => ['IBLOCK_ID', 'PRODUCT_IBLOCK_ID', 'SITE_ID' => 'IBLOCK_SITE.SITE_ID'],
                    'filter' => ['SITE_ID' => $this->orderBuilder->getSiteId()],
                    'runtime' => [
                        'IBLOCK_SITE' => [
                            'data_type' => 'Bitrix\Iblock\IblockSiteTable',
                            'reference' => [
                                'ref.IBLOCK_ID' => 'this.IBLOCK_ID',
                            ],
                            'join_type' => 'inner',
                        ],
                    ],
                ]);
                while ($catalog = $catalogIterator->fetch())
                {
                    $iBlockList[$catalog['IBLOCK_ID']] = $catalog['IBLOCK_ID'];

                    if (intval($catalog['PRODUCT_IBLOCK_ID']) > 0)
                        $iBlockList[$catalog['PRODUCT_IBLOCK_ID']] = $catalog['PRODUCT_IBLOCK_ID'];
                }

                if (!empty($iBlockList))
                {
                    $propertyIterator = \Bitrix\Iblock\PropertyTable::getList([
                        'select' => ['ID', 'IBLOCK_ID', 'NAME', 'ACTIVE', 'SORT', 'CODE', 'TIMESTAMP_X',
                            'DEFAULT_VALUE', 'PROPERTY_TYPE', 'ROW_COUNT', 'COL_COUNT', 'LIST_TYPE',
                            'MULTIPLE', 'XML_ID', 'FILE_TYPE', 'MULTIPLE_CNT', 'LINK_IBLOCK_ID', 'WITH_DESCRIPTION',
                            'SEARCHABLE', 'FILTRABLE', 'IS_REQUIRED', 'VERSION', 'USER_TYPE', 'USER_TYPE_SETTINGS', 'HINT'],
                        'filter' => [
                            '@IBLOCK_ID' => array_keys($iBlockList),
                            '=ACTIVE' => 'Y',
                            '@CODE' => $arCodes,
                        ],
                        'order' => ['SORT' => 'ASC', 'ID' => 'ASC'],
                    ]);
                    while ($property = $propertyIterator->fetch())
                    {
                        $this->arIblockProps[$property['IBLOCK_ID']][$property['CODE']] = $property;

                        if (!isset($iBlockProps[$property['CODE']]))
                            $iBlockProps[$property['CODE']] = $property;
                    }
                }
            }

            // making grid headers array
            foreach ($productColumns as $key => $value)
            {
                // processing iblock properties
                if (strncmp($value, "PROPERTY_", 9) == 0)
                {
                    $propCode = mb_substr($value, 9);

                    if ($propCode == '')
                        continue;

                    // array of iblock properties to select
                    $this->arCustomSelectFields[] = $value;
                    $id = $value."_VALUE";
                    $name = $value;

                    if (array_key_exists($propCode, $iBlockProps))
                    {
                        $name = $iBlockProps[$propCode]["NAME"];
                    }
                }
                else
                {
                    $id = $key;
                    $name = $value;
                }

                $arColumn = [
                    "id" => $id,
                    "name" => $name,
                ];

                if ($key == "PRICE_FORMATED")
                {
                    $arColumn["align"] = "right";
                }

                $arr[] = $arColumn;
            }
        }

        return $arr;
    }

    protected function obtainBasket()
    {
        $arResult =& $this->arResult;

        $arResult["MAX_DIMENSIONS"] = $arResult["ITEMS_DIMENSIONS"] = [];
        $arResult["BASKET_ITEMS"] = [];

        $this->calculateBasket = $this->order->getBasket()->createClone();

        $discounts = $this->order->getDiscount();
        $showPrices = $discounts->getShowPrices();
        if (!empty($showPrices['BASKET']))
        {
            foreach ($showPrices['BASKET'] as $basketCode => $data)
            {
                $basketItem = $this->calculateBasket->getItemByBasketCode($basketCode);
                if ($basketItem instanceof \Bitrix\Sale\BasketItemBase)
                {
                    $basketItem->setFieldNoDemand('BASE_PRICE', $data['SHOW_BASE_PRICE']);
                    $basketItem->setFieldNoDemand('PRICE', $data['SHOW_PRICE']);
                    $basketItem->setFieldNoDemand('DISCOUNT_PRICE', $data['SHOW_DISCOUNT']);
                }
            }
        }
        unset($showPrices);

        /** @var Sale\BasketItem $basketItem */
        foreach ($this->calculateBasket as $basketItem)
        {
            $arBasketItem = $basketItem->getFieldValues();
            if ($basketItem->getVatRate() > 0)
            {
                $arResult["bUsingVat"] = "Y";
                $arBasketItem["VAT_VALUE"] = $basketItem->getVat();
            }
            $arBasketItem["QUANTITY"] = $basketItem->getQuantity();
            $arBasketItem["PRICE_FORMATED"] = SaleFormatCurrency($basketItem->getPrice(), $this->order->getCurrency());
            $arBasketItem["WEIGHT_FORMATED"] = roundEx(doubleval($basketItem->getWeight() / $arResult["WEIGHT_KOEF"]), SALE_WEIGHT_PRECISION)." ".$arResult["WEIGHT_UNIT"];
            $arBasketItem["DISCOUNT_PRICE"] = $basketItem->getDiscountPrice();

            $arBasketItem["DISCOUNT_PRICE_PERCENT"] = 0;
            if ($arBasketItem['CUSTOM_PRICE'] != 'Y')
            {
                $arBasketItem['DISCOUNT_PRICE_PERCENT'] = Sale\Discount::calculateDiscountPercent(
                    $arBasketItem['BASE_PRICE'],
                    $arBasketItem['DISCOUNT_PRICE']
                );
                if ($arBasketItem['DISCOUNT_PRICE_PERCENT'] === null)
                    $arBasketItem['DISCOUNT_PRICE_PERCENT'] = 0;
            }
            $arBasketItem["DISCOUNT_PRICE_PERCENT_FORMATED"] = $arBasketItem['DISCOUNT_PRICE_PERCENT'].'%';

            $arBasketItem["BASE_PRICE_FORMATED"] = SaleFormatCurrency($basketItem->getBasePrice(), $this->order->getCurrency());

            $arDim = $basketItem->getField('DIMENSIONS');

            if (is_string($arDim))
            {
                $arDim = unserialize($basketItem->getField('DIMENSIONS'), ['allowed_classes' => false]);
            }

            if (is_array($arDim))
            {
                $arResult["MAX_DIMENSIONS"] = \CSaleDeliveryHelper::getMaxDimensions(
                    [
                        $arDim["WIDTH"],
                        $arDim["HEIGHT"],
                        $arDim["LENGTH"],
                    ],
                    $arResult["MAX_DIMENSIONS"]);

                $arResult["ITEMS_DIMENSIONS"][] = $arDim;
            }

            $arBasketItem["PROPS"] = [];
            /** @var Sale\BasketPropertiesCollection $propertyCollection */
            $propertyCollection = $basketItem->getPropertyCollection();
            $propList = $propertyCollection->getPropertyValues();
            foreach ($propList as $key => &$prop)
            {
                if ($prop['CODE'] == 'CATALOG.XML_ID' || $prop['CODE'] == 'PRODUCT.XML_ID' || $prop['CODE'] == 'SUM_OF_CHARGE')
                    continue;

                $prop = array_filter($prop, ["CSaleBasketHelper", "filterFields"]);
                $arBasketItem["PROPS"][] = $prop;
            }

            $this->arElementId[] = $arBasketItem["PRODUCT_ID"];
            $arBasketItem["SUM_NUM"] = $basketItem->getPrice() * $basketItem->getQuantity();
            $arBasketItem["SUM"] = SaleFormatCurrency(
                $arBasketItem["SUM_NUM"],
                $this->order->getCurrency()
            );

            $arBasketItem["SUM_BASE"] = $basketItem->getBasePrice() * $basketItem->getQuantity();
            $arBasketItem["SUM_BASE_FORMATED"] = SaleFormatCurrency(
                $arBasketItem["SUM_BASE"],
                $this->order->getCurrency()
            );

            $arBasketItem["SUM_DISCOUNT_DIFF"] = $arBasketItem["SUM_BASE"] - $arBasketItem["SUM_NUM"];
            $arBasketItem["SUM_DISCOUNT_DIFF_FORMATED"] = SaleFormatCurrency(
                $arBasketItem["SUM_DISCOUNT_DIFF"],
                $this->order->getCurrency()
            );

            $arResult["BASKET_ITEMS"][] = $arBasketItem;
        }
    }

    protected function obtainPropertiesForIbElements()
    {
        if (empty($this->arElementId))
        {
            return;
        }

        $arResult =& $this->arResult;
        $arResult["GRID"]["ROWS"] = [];
        $arParents = [];

        if ($this->orderBuilder->getUseCatalog())
        {
            $arParents = \CCatalogSku::getProductList($this->arElementId);
            if (!empty($arParents))
            {
                foreach ($arParents as $productId => $arParent)
                {
                    $this->arElementId[] = $arParent["ID"];
                    $this->arSku2Parent[$productId] = $arParent["ID"];
                }
            }
        }

        $arElementData = [];
        $arProductData = [];
        $elementIndex = [];
        $res = \CIBlockElement::GetList(
            [],
            ["=ID" => array_unique($this->arElementId)],
            false,
            false,
            ["ID", "IBLOCK_ID", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PREVIEW_TEXT"]
        );
        while ($arElement = $res->Fetch())
        {
            $arElementData[$arElement["IBLOCK_ID"]][] = $arElement["ID"];
            $arProductData[$arElement["ID"]] = $arElement;
            $elementIndex[$arElement["ID"]] = [];
        }

        foreach ($arElementData as $iBlockId => $arElemId)
        {
            $arCodes = [];
            if (!empty($this->arIblockProps[$iBlockId]))
                $arCodes = array_keys($this->arIblockProps[$iBlockId]);

            $imageCode = $this->arParams['ADDITIONAL_PICT_PROP'][$iBlockId];

            if (!empty($imageCode) && !in_array($imageCode, $arCodes))
                $arCodes[] = $imageCode;

            if (!empty($arCodes))
            {
                \CIBlockElement::GetPropertyValuesArray($elementIndex, $iBlockId,
                    ["ID" => $arElemId],
                    ["CODE" => $arCodes]
                );
            }
        }
        unset($arElementData);

        $arAdditionalImages = [];
        foreach ($elementIndex as $productId => $productProperties)
        {
            if (!empty($productProperties) && is_array($productProperties))
            {
                foreach ($productProperties as $code => $property)
                {
                    if (
                        !empty($this->arParams['ADDITIONAL_PICT_PROP'])
                        && array_key_exists($arProductData[$productId]['IBLOCK_ID'], $this->arParams['ADDITIONAL_PICT_PROP'])
                    )
                    {
                        if ($this->arParams['ADDITIONAL_PICT_PROP'][$arProductData[$productId]['IBLOCK_ID']] == $code)
                        {
                            $arAdditionalImages[$productId] = is_array($property['VALUE']) ? current($property['VALUE']) : $property['VALUE'];
                        }
                    }

                    if (
                        !empty($this->arIblockProps[$arProductData[$productId]['IBLOCK_ID']])
                        && array_key_exists($code, $this->arIblockProps[$arProductData[$productId]['IBLOCK_ID']])
                    )
                    {
                        if (is_array($property['VALUE']))
                        {
                            $arProductData[$productId]['PROPERTY_'.$code.'_VALUE'] = implode(', ', $property['VALUE']);
                        }
                        else
                        {
                            $arProductData[$productId]['PROPERTY_'.$code.'_VALUE'] = $property['VALUE'];
                        }

                        if (is_array($property['PROPERTY_VALUE_ID']))
                        {
                            $arProductData[$productId]['PROPERTY_'.$code.'_VALUE_ID'] = implode(', ', $property['PROPERTY_VALUE_ID']);
                        }
                        else
                        {
                            $arProductData[$productId]['PROPERTY_'.$code.'_VALUE_ID'] = $property['PROPERTY_VALUE_ID'];
                        }

                        if ($property['PROPERTY_TYPE'] == 'L')
                        {
                            $arProductData[$productId]['PROPERTY_'.$code.'_ENUM_ID'] = $property['VALUE_ENUM_ID'];
                        }
                    }
                }
            }
        }
        unset($elementIndex);

        $currentProductProperties = [];

        $needToResizeProductImages = $this->arParams['COMPATIBLE_MODE'] === 'Y'
            || isset($this->arParams['PRODUCT_COLUMNS']['PREVIEW_PICTURE'])
            || isset($this->arParams['PRODUCT_COLUMNS']['DETAIL_PICTURE'])
            || isset($this->arParams['PRODUCT_COLUMNS_HIDDEN']['PREVIEW_PICTURE'])
            || isset($this->arParams['PRODUCT_COLUMNS_HIDDEN']['DETAIL_PICTURE']);

        foreach ($arResult["BASKET_ITEMS"] as &$arResultItem)
        {
            $productId = $arResultItem["PRODUCT_ID"];
            $arParent = $arParents[$productId];
            $itemIblockId = intval($arProductData[$productId]['IBLOCK_ID']);
            $currentProductProperties[$productId] = isset($this->arIblockProps[$itemIblockId])
                ? $this->arIblockProps[$itemIblockId]
                : [];

            if (
                (int)$arProductData[$productId]["PREVIEW_PICTURE"] <= 0
                && (int)$arProductData[$productId]["DETAIL_PICTURE"] <= 0
                && $arParent
            )
            {
                $productId = $arParent["ID"];
            }

            if ((int)$arProductData[$productId]["PREVIEW_PICTURE"] > 0)
            {
                $arResultItem["PREVIEW_PICTURE"] = $arProductData[$productId]["PREVIEW_PICTURE"];
            }

            if ((int)$arProductData[$productId]["DETAIL_PICTURE"] > 0)
            {
                $arResultItem["DETAIL_PICTURE"] = $arProductData[$productId]["DETAIL_PICTURE"];
            }

            if ($arProductData[$productId]["PREVIEW_TEXT"] != '')
            {
                $arResultItem["PREVIEW_TEXT"] = $arProductData[$productId]["PREVIEW_TEXT"];
                $arResultItem["PREVIEW_TEXT_TYPE"] = $arProductData[$productId]["PREVIEW_TEXT_TYPE"];
            }

            if (!empty($arProductData[$arResultItem["PRODUCT_ID"]]) && is_array($arProductData[$arResultItem["PRODUCT_ID"]]))
            {
                foreach ($arProductData[$arResultItem["PRODUCT_ID"]] as $key => $value)
                {
                    if (mb_strpos($key, "PROPERTY_") !== false)
                        $arResultItem[$key] = $value;
                }
            }

            // if sku element doesn't have some property value - we'll show parent element value instead
            if (isset($this->arSku2Parent[$arResultItem["PRODUCT_ID"]]))
            {
                $parentIblockId = $arProductData[$this->arSku2Parent[$arResultItem["PRODUCT_ID"]]]['IBLOCK_ID'];

                if (!empty($this->arIblockProps[$parentIblockId]))
                {
                    $currentProductProperties[$arResultItem["PRODUCT_ID"]] = array_merge(
                        $this->arIblockProps[$parentIblockId],
                        $currentProductProperties[$arResultItem["PRODUCT_ID"]]
                    );
                }

                foreach ($this->arCustomSelectFields as $field)
                {
                    $fieldVal = $field."_VALUE";
                    $parentId = $this->arSku2Parent[$arResultItem["PRODUCT_ID"]];

                    // can be array or string
                    if (
                        (!isset($arResultItem[$fieldVal]) || (isset($arResultItem[$fieldVal]) && $arResultItem[$fieldVal] == ''))
                        && (isset($arProductData[$parentId][$fieldVal]) && !empty($arProductData[$parentId][$fieldVal]))
                    )
                    {
                        $arResultItem[$fieldVal] = $arProductData[$parentId][$fieldVal];
                    }
                }
            }

            // replace PREVIEW_PICTURE with selected ADDITIONAL_PICT_PROP
            if (
                empty($arProductData[$arResultItem["PRODUCT_ID"]]["PREVIEW_PICTURE"])
                && empty($arProductData[$arResultItem["PRODUCT_ID"]]["DETAIL_PICTURE"])
                && $arAdditionalImages[$arResultItem["PRODUCT_ID"]]
            )
            {
                $arResultItem["PREVIEW_PICTURE"] = $arAdditionalImages[$arResultItem["PRODUCT_ID"]];
            }
            elseif (
                empty($arResultItem["PREVIEW_PICTURE"])
                && empty($arResultItem["DETAIL_PICTURE"])
                && $arAdditionalImages[$productId]
            )
            {
                $arResultItem["PREVIEW_PICTURE"] = $arAdditionalImages[$productId];
            }

            $arResultItem["PREVIEW_PICTURE_SRC"] = "";

            if (
                $needToResizeProductImages
                && isset($arResultItem["PREVIEW_PICTURE"])
                && (int)$arResultItem["PREVIEW_PICTURE"] > 0
            )
            {
                $arImage = \CFile::GetFileArray($arResultItem["PREVIEW_PICTURE"]);
                if (!empty($arImage))
                {
                    self::resizeImage($arResultItem, 'PREVIEW_PICTURE', $arImage,
                        ["width" => 320, "height" => 320],
                        ["width" => 110, "height" => 110],
                        $this->arParams['BASKET_IMAGES_SCALING']
                    );
                }
            }

            $arResultItem["DETAIL_PICTURE_SRC"] = "";

            if (
                $needToResizeProductImages
                && isset($arResultItem["DETAIL_PICTURE"])
                && (int)$arResultItem["DETAIL_PICTURE"] > 0
            )
            {
                $arImage = \CFile::GetFileArray($arResultItem["DETAIL_PICTURE"]);
                if (!empty($arImage))
                {
                    self::resizeImage($arResultItem, 'DETAIL_PICTURE', $arImage,
                        ["width" => 320, "height" => 320],
                        ["width" => 110, "height" => 110],
                        $this->arParams['BASKET_IMAGES_SCALING']
                    );
                }
            }
        }

        if (!empty($arResult["BASKET_ITEMS"]) && $this->orderBuilder->getUseCatalog())
        {
            $arResult["BASKET_ITEMS"] = getMeasures($arResult["BASKET_ITEMS"]);
        }

        foreach ($arResult["BASKET_ITEMS"] as $arBasketItem)
        {
            // prepare values for custom-looking columns
            $arCols = [
                "PROPS" => $this->getPropsInfo($arBasketItem),
            ];

            if ($this->arParams['COMPATIBLE_MODE'] === 'Y')
            {
                if (isset($arBasketItem["PREVIEW_PICTURE"]) && (int)$arBasketItem["PREVIEW_PICTURE"] > 0)
                {
                    $arCols["PREVIEW_PICTURE"] = \CSaleHelper::getFileInfo(
                        $arBasketItem["PREVIEW_PICTURE"],
                        ["WIDTH" => 110, "HEIGHT" => 110]
                    );
                }

                if (isset($arBasketItem["DETAIL_PICTURE"]) && (int)$arBasketItem["DETAIL_PICTURE"] > 0)
                {
                    $arCols["DETAIL_PICTURE"] = \CSaleHelper::getFileInfo(
                        $arBasketItem["DETAIL_PICTURE"],
                        ["WIDTH" => 110, "HEIGHT" => 110]
                    );
                }
            }

            if (!empty($arBasketItem["MEASURE_TEXT"]))
            {
                $arCols["QUANTITY"] = $arBasketItem["QUANTITY"]."&nbsp;".$arBasketItem["MEASURE_TEXT"];
            }

            foreach ($arBasketItem as $tmpKey => $value)
            {
                if ((mb_strpos($tmpKey, "PROPERTY_", 0) === 0) && (mb_strrpos($tmpKey, "_VALUE") == mb_strlen($tmpKey) - 6))
                {
                    $code = str_replace(["PROPERTY_", "_VALUE"], "", $tmpKey);
                    $propData = $currentProductProperties[$arBasketItem['PRODUCT_ID']][$code];

                    // display linked property type
                    if ($propData['PROPERTY_TYPE'] === 'E')
                    {
                        $propData['VALUE'] = $value;
                        $arCols[$tmpKey] = $this->getLinkedPropValue($arBasketItem, $propData);
                    }
                    elseif ($propData['PROPERTY_TYPE'] === 'S' && $propData['USER_TYPE'] === 'directory')
                    {
                        $arCols[$tmpKey] = $this->getDirectoryProperty($arBasketItem, $propData);
                    }
                    else
                    {
                        $arCols[$tmpKey] = $this->getIblockProps($value, $propData, ['WIDTH' => 110, 'HEIGHT' => 110]);
                    }
                }
            }

            $arResult["GRID"]["ROWS"][$arBasketItem["ID"]] = [
                "id" => $arBasketItem["ID"],
                "data" => $arBasketItem,
                "actions" => [],
                "columns" => $arCols,
                "editable" => true,
            ];
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
    public function getPropsInfo($source)
    {
        $resultHTML = "";

        foreach ($source["PROPS"] as $val)
        {
            $resultHTML .= str_replace(" ", "&nbsp;", $val["NAME"].": ".$val["VALUE"])."<br />";
        }

        return $resultHTML;
    }

    public function getLinkedPropValue($basketItem, $property)
    {
        $result = [];

        if ($property['MULTIPLE'] === 'Y')
            $property['VALUE'] = explode(',', $property['VALUE']);

        $formattedProperty = \CIBlockFormatProperties::GetDisplayValue($basketItem, $property, 'sale_out');
        if (!empty($formattedProperty['DISPLAY_VALUE']))
        {
            if (is_array($formattedProperty['DISPLAY_VALUE']))
            {
                foreach ($formattedProperty['DISPLAY_VALUE'] as $key => $formatValue)
                {
                    $result[] = [
                        'type' => 'linked',
                        'value' => $property['VALUE'][$key],
                        'value_format' => $formatValue,
                    ];
                }
            }
            else
            {
                $result[] = [
                    'type' => 'linked',
                    'value' => is_array($property['VALUE']) ? reset($property['VALUE']) : $property['VALUE'],
                    'value_format' => $formattedProperty['DISPLAY_VALUE'],
                ];
            }
        }

        return $result;
    }

    public function getDirectoryProperty($basketItem, $property)
    {
        $result = [];

        if ($property['MULTIPLE'] === 'Y')
        {
            $property['VALUE'] = explode(', ', $basketItem['PROPERTY_'.$property['CODE'].'_VALUE']);
        }
        else
        {
            $property['VALUE'] = $basketItem['PROPERTY_'.$property['CODE'].'_VALUE'];
        }

        $property['~VALUE'] = $property['VALUE'];

        if (CheckSerializedData($property['USER_TYPE_SETTINGS']))
        {
            $property['USER_TYPE_SETTINGS'] = unserialize($property['USER_TYPE_SETTINGS'], ['allowed_classes' => false]);
        }

        $formattedProperty = \CIBlockFormatProperties::GetDisplayValue($basketItem, $property, 'sale_out');
        if (!empty($formattedProperty['DISPLAY_VALUE']))
        {
            if (is_array($formattedProperty['DISPLAY_VALUE']))
            {
                foreach ($formattedProperty['DISPLAY_VALUE'] as $key => $formatValue)
                {
                    $result[] = [
                        'type' => 'value',
                        'value' => $formatValue,
                        'value_raw' => $property['VALUE'][$key],
                    ];
                }
            }
            else
            {
                $result[] = [
                    'type' => 'value',
                    'value' => $formattedProperty['DISPLAY_VALUE'],
                    'value_raw' => is_array($property['VALUE']) ? reset($property['VALUE']) : $property['VALUE'],
                ];
            }
        }

        return $result;
    }

    public function getIblockProps($value, $propData, $arSize = ["WIDTH" => 90, "HEIGHT" => 90], $orderId = 0)
    {
        $res = [];

        if ($propData["MULTIPLE"] == "Y")
        {
            $arVal = [];
            if (!is_array($value))
            {
                if (mb_strpos($value, ",") !== false)
                    $arVal = explode(",", $value);
                else
                    $arVal[] = $value;
            }
            else
                $arVal = $value;

            if (!empty($arVal))
            {
                foreach ($arVal as $key => $val)
                {
                    if ($propData["PROPERTY_TYPE"] == "F")
                        $res[] = $this->getFileData(trim($val), $orderId, $arSize);
                    else
                        $res[] = ["type" => "value", "value" => $val];
                }
            }
        }
        else
        {
            if ($propData["PROPERTY_TYPE"] == "F")
                $res[] = $this->getFileData($value, $orderId, $arSize);
            else
                $res[] = ["type" => "value", "value" => $value];
        }

        return $res;
    }

    public function getFileData($fileId, $orderId = 0, $arSize = ["WIDTH" => 90, "HEIGHT" => 90])
    {
        $res = "";
        $arFile = \CFile::GetFileArray($fileId);

        if ($arFile)
        {
            $is_image = \CFile::IsImage($arFile["FILE_NAME"], $arFile["CONTENT_TYPE"]);
            if ($is_image)
            {
                $arImgProduct = \CFile::ResizeImageGet($arFile, ["width" => $arSize["WIDTH"], "height" => $arSize["HEIGHT"]], BX_RESIZE_IMAGE_PROPORTIONAL, false, false);

                if (is_array($arImgProduct))
                    $res = ["type" => "image", "value" => $arImgProduct["src"], "source" => $arFile["SRC"]];
            }
            else
                $res = ["type" => "file", "value" => "<a href=".$arFile["SRC"].">".$arFile["ORIGINAL_NAME"]."</a>"];
        }

        return $res;
    }


    protected function obtainFormattedProperties()
    {
        $arResult =& $this->arResult;
        $arDeleteFieldLocation = [];
        $propIndex = [];
        $arOrderProps = $this->order->getPropertyCollection()->getArray();
        $propsSortedByGroup = [];
        foreach ($arOrderProps['groups'] as $group)
        {
            foreach ($arOrderProps['properties'] as $prop)
            {
                if ($prop['UTIL'] == 'Y' || !empty($prop['RELATION']))
                    continue;

                if ($group['ID'] == $prop['PROPS_GROUP_ID'])
                {
                    $prop['GROUP_NAME'] = $group['NAME'];
                    $propsSortedByGroup[] = $prop;
                }
            }
        }

        foreach ($propsSortedByGroup as $arProperty)
        {
            $arProperties = $this->getOrderPropFormatted($arProperty, $arDeleteFieldLocation);

            $flag = $arProperties["USER_PROPS"] == "Y" ? 'Y' : 'N';

            $arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]] = $arProperties;
            $propIndex[$arProperties["ID"]] =& $arResult["ORDER_PROP"]["USER_PROPS_".$flag][$arProperties["ID"]];

            $arResult["ORDER_PROP"]["PRINT"][$arProperties["ID"]] = [
                "ID" => $arProperties["ID"],
                "NAME" => $arProperties["NAME"],
                "VALUE" => $arProperties["VALUE_FORMATED"],
                "SHOW_GROUP_NAME" => $arProperties["SHOW_GROUP_NAME"],
            ];
        }

        // additional city property process
        foreach ($propIndex as $propId => $propDesc)
        {
            if (intval($propDesc['INPUT_FIELD_LOCATION']) && isset($propIndex[$propDesc['INPUT_FIELD_LOCATION']]))
            {
                $propIndex[$propDesc['INPUT_FIELD_LOCATION']]['IS_ALTERNATE_LOCATION_FOR'] = $propId;
                $propIndex[$propId]['CAN_HAVE_ALTERNATE_LOCATION'] = $propDesc['INPUT_FIELD_LOCATION']; // more strict condition rather INPUT_FIELD_LOCATION, check if the property really exists
            }
        }

        //delete prop for text location (town)
        if (count($arDeleteFieldLocation) > 0)
        {
            foreach ($arDeleteFieldLocation as $fieldId)
                unset($arResult["ORDER_PROP"]["USER_PROPS_Y"][$fieldId]);
        }

        $this->executeEvent('OnSaleComponentOrderOneStepOrderProps', $this->order);
    }

    protected function executeEvent($eventName = '', $order = null)
    {
        $arModifiedResult = $this->arUserResult;

        foreach (GetModuleEvents("sale", $eventName, true) as $arEvent)
            ExecuteModuleEventEx($arEvent, [&$this->arResult, &$arModifiedResult, &$this->arParams, true]);

        if (!empty($order))
            $this->synchronize($arModifiedResult, $order);
    }

    protected function synchronize($arModifiedResult, Order $order)
    {
        $modifiedFields = self::arrayDiffRecursive($arModifiedResult, $this->arUserResult);

        if (!empty($modifiedFields))
            $this->synchronizeOrder($modifiedFields, $order);
    }

    protected function synchronizeOrder($modifiedFields, Order $order)
    {
        if (!empty($modifiedFields) && is_array($modifiedFields))
        {
            $recalculatePayment = $modifiedFields['CALCULATE_PAYMENT'] === true;
            unset($modifiedFields['CALCULATE_PAYMENT']);
            $recalculateDelivery = false;

            if (!empty($modifiedFields['PERSON_TYPE_ID']))
            {
                $order->setPersonTypeId($modifiedFields['PERSON_TYPE_ID']);
            }

            $propertyCollection = $order->getPropertyCollection();

            foreach ($modifiedFields as $field => $value)
            {
                switch ($field)
                {
                    case 'PAY_SYSTEM_ID':
                        $recalculatePayment = true;
                        break;
                    case 'PAY_CURRENT_ACCOUNT':
                        $recalculatePayment = true;
                        break;
                    case 'DELIVERY_ID':
                        $recalculateDelivery = true;
                        break;
                    case 'ORDER_PROP':
                        if (is_array($value))
                        {
                            /** @var Sale\PropertyValue $property */
                            foreach ($propertyCollection as $property)
                            {
                                if (array_key_exists($property->getPropertyId(), $value))
                                {
                                    $property->setValue($value[$property->getPropertyId()]);
                                    $arProperty = $property->getProperty();
                                    if ($arProperty['IS_LOCATION'] === 'Y' || $arProperty['IS_ZIP'] === 'Y')
                                    {
                                        $recalculateDelivery = true;
                                    }
                                }
                            }
                        }

                        break;
                    case 'ORDER_DESCRIPTION':
                        $order->setField('USER_DESCRIPTION', $value);
                        break;
                    case 'DELIVERY_LOCATION':
                        $codeValue = \CSaleLocation::getLocationCODEbyID($value);
                        if ($property = $propertyCollection->getDeliveryLocation())
                        {
                            $property->setValue($codeValue);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $codeValue;
                        }

                        $recalculateDelivery = true;
                        break;
                    case 'DELIVERY_LOCATION_BCODE':
                        if ($property = $propertyCollection->getDeliveryLocation())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        $recalculateDelivery = true;
                        break;
                    case 'DELIVERY_LOCATION_ZIP':
                        if ($property = $propertyCollection->getDeliveryLocationZip())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        $recalculateDelivery = true;
                        break;
                    case 'TAX_LOCATION':
                        $codeValue = \CSaleLocation::getLocationCODEbyID($value);
                        if ($property = $propertyCollection->getTaxLocation())
                        {
                            $property->setValue($codeValue);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $codeValue;
                        }

                        break;
                    case 'TAX_LOCATION_BCODE':
                        if ($property = $propertyCollection->getTaxLocation())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                    case 'PAYER_NAME':
                        if ($property = $propertyCollection->getPayerName())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                    case 'USER_EMAIL':
                        if ($property = $propertyCollection->getUserEmail())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                    case 'PROFILE_NAME':
                        if ($property = $propertyCollection->getProfileName())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                }

                $this->arUserResult[$field] = $value;
            }

            if ($recalculateDelivery)
            {
                if ($shipment = $this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($order))
                {
                    $this->orderBuilder->getDeliveryBuilder()->initDelivery($shipment);
                    $recalculatePayment = true;
                }
            }

            if ($recalculatePayment)
            {
                $this->orderBuilder->getPaymentBuilder()->recalculatePayment($order);
            }
        }
    }

    public static function arrayDiffRecursive($arr1, $arr2)
    {
        $modified = [];

        foreach ($arr1 as $key => $value)
        {
            if (array_key_exists($key, $arr2))
            {
                if (is_array($value) && is_array($arr2[$key]))
                {
                    $arDiff = self::arrayDiffRecursive($value, $arr2[$key]);
                    if (!empty($arDiff))
                    {
                        $modified[$key] = $arDiff;
                    }
                }
                elseif ($value != $arr2[$key])
                {
                    $modified[$key] = $value;
                }
            }
            else
            {
                $modified[$key] = $value;
            }
        }

        return $modified;
    }

    protected function getOrderPropFormatted($arProperty, &$arDeleteFieldLocation = [])
    {
        static $propertyGroupID = 0;
        static $propertyUSER_PROPS = '';

        $arProperty['FIELD_NAME'] = 'ORDER_PROP_'.$arProperty['ID'];

        if ($arProperty['CODE'] != '')
        {
            $arProperty['FIELD_ID'] = 'ORDER_PROP_'.$arProperty['CODE'];
        }
        else
        {
            $arProperty['FIELD_ID'] = 'ORDER_PROP_'.$arProperty['ID'];
        }

        if (intval($arProperty['PROPS_GROUP_ID']) != $propertyGroupID || $propertyUSER_PROPS != $arProperty['USER_PROPS'])
        {
            $arProperty['SHOW_GROUP_NAME'] = 'Y';
        }

        $propertyGroupID = $arProperty['PROPS_GROUP_ID'];
        $propertyUSER_PROPS = $arProperty['USER_PROPS'];

        if ($arProperty['REQUIRED'] === 'Y' || $arProperty['IS_PROFILE_NAME'] === 'Y'
            || $arProperty['IS_LOCATION'] === 'Y' || $arProperty['IS_LOCATION4TAX'] === 'Y'
            || $arProperty['IS_PAYER'] === 'Y' || $arProperty['IS_ZIP'] === 'Y')
        {
            $arProperty['REQUIED'] = 'Y';
            $arProperty['REQUIED_FORMATED'] = 'Y';
        }

        if ($arProperty['IS_LOCATION'] === 'Y')
        {
            $deliveryId = \CSaleLocation::getLocationIDbyCODE(current($arProperty['VALUE']));
            $this->arUserResult['DELIVERY_LOCATION'] = $deliveryId;
            $this->arUserResult['DELIVERY_LOCATION_BCODE'] = current($arProperty['VALUE']);
        }

        if ($arProperty['IS_ZIP'] === 'Y')
        {
            $this->arUserResult['DELIVERY_LOCATION_ZIP'] = current($arProperty['VALUE']);
        }

        if ($arProperty['IS_LOCATION4TAX'] === 'Y')
        {
            $taxId = \CSaleLocation::getLocationIDbyCODE(current($arProperty['VALUE']));
            $this->arUserResult['TAX_LOCATION'] = $taxId;
            $this->arUserResult['TAX_LOCATION_BCODE'] = current($arProperty['VALUE']);
        }

        if ($arProperty['IS_PAYER'] === 'Y')
        {
            $this->arUserResult['PAYER_NAME'] = current($arProperty['VALUE']);
        }

        if ($arProperty['IS_EMAIL'] === 'Y')
        {
            $this->arUserResult['USER_EMAIL'] = current($arProperty['VALUE']);
        }

        if ($arProperty['IS_PROFILE_NAME'] === 'Y')
        {
            $this->arUserResult['PROFILE_NAME'] = current($arProperty['VALUE']);
        }

        switch ($arProperty['TYPE'])
        {
            case 'Y/N':
                self::formatYN($arProperty);
                break;
            case 'STRING':
                self::formatString($arProperty);
                break;
            case 'NUMBER':
                self::formatNumber($arProperty);
                break;
            case 'ENUM':
                self::formatEnum($arProperty);
                break;
            case 'LOCATION':
                self::formatLocation($arProperty, $arDeleteFieldLocation, $this->arResult['LOCATION_ALT_PROP_DISPLAY_MANUAL']);
                break;
            case 'FILE':
                self::formatFile($arProperty);
                break;
            case 'DATE':
                self::formatDate($arProperty);
                break;
        }

        return $arProperty;
    }

    public static function formatYN(array &$arProperty)
    {
        $curVal = $arProperty['VALUE'];

        if (current($curVal) == "Y")
        {
            $arProperty["CHECKED"] = "Y";
            $arProperty["VALUE_FORMATED"] = Loc::getMessage("SOA_Y");
        }
        else
            $arProperty["VALUE_FORMATED"] = Loc::getMessage("SOA_N");

        $arProperty["SIZE1"] = (intval($arProperty["SIZE1"]) > 0) ? $arProperty["SIZE1"] : 30;

        $arProperty["VALUE"] = current($curVal);
        $arProperty["TYPE"] = 'CHECKBOX';
    }

    public static function formatString(array &$arProperty)
    {
        $curVal = $arProperty['VALUE'];

        if (!empty($arProperty["MULTILINE"]) && $arProperty["MULTILINE"] == 'Y')
        {
            $arProperty["TYPE"] = 'TEXTAREA';
            $arProperty["SIZE2"] = (intval($arProperty["ROWS"]) > 0) ? $arProperty["ROWS"] : 4;
            $arProperty["SIZE1"] = (intval($arProperty["COLS"]) > 0) ? $arProperty["COLS"] : 40;
        }
        else
            $arProperty["TYPE"] = 'TEXT';

        $arProperty["SOURCE"] = current($curVal) == $arProperty['DEFAULT_VALUE'] ? 'DEFAULT' : 'FORM';
        $arProperty["VALUE"] = current($curVal);
        $arProperty["VALUE_FORMATED"] = $arProperty["VALUE"];
    }

    public static function formatNumber(array &$arProperty)
    {
        $curVal = $arProperty['VALUE'];
        $arProperty["TYPE"] = 'TEXT';
        $arProperty["VALUE"] = current($curVal);
        $arProperty["VALUE_FORMATED"] = $arProperty["VALUE"];
    }

    public static function formatEnum(array &$arProperty)
    {
        $curVal = $arProperty['VALUE'];

        if ($arProperty["MULTIELEMENT"] == 'Y')
        {
            if ($arProperty["MULTIPLE"] == 'Y')
            {
                $setValue = [];
                $arProperty["FIELD_NAME"] = "ORDER_PROP_".$arProperty["ID"].'[]';
                $arProperty["SIZE1"] = (intval($arProperty["SIZE1"]) > 0) ? $arProperty["SIZE1"] : 5;

                $i = 0;
                foreach ($arProperty["OPTIONS"] as $val => $name)
                {
                    $arVariants = [
                        'VALUE' => $val,
                        'NAME' => $name,
                    ];
                    if ((is_array($curVal) && in_array($arVariants["VALUE"], $curVal)))
                    {
                        $arVariants["SELECTED"] = "Y";
                        if ($i > 0)
                            $arProperty["VALUE_FORMATED"] .= ", ";
                        $arProperty["VALUE_FORMATED"] .= $arVariants["NAME"];
                        $setValue[] = $arVariants["VALUE"];
                        $i++;
                    }
                    $arProperty["VARIANTS"][] = $arVariants;
                }

                $arProperty["TYPE"] = 'MULTISELECT';
            }
            else
            {
                foreach ($arProperty['OPTIONS'] as $val => $name)
                {
                    $arVariants = [
                        'VALUE' => $val,
                        'NAME' => $name,
                    ];
                    if ($arVariants["VALUE"] == current($curVal))
                    {
                        $arVariants["CHECKED"] = "Y";
                        $arProperty["VALUE_FORMATED"] = $arVariants["NAME"];
                    }

                    $arProperty["VARIANTS"][] = $arVariants;
                }
                $arProperty["TYPE"] = 'RADIO';
            }
        }
        else
        {
            if ($arProperty["MULTIPLE"] == 'Y')
            {
                $setValue = [];
                $arProperty["FIELD_NAME"] = "ORDER_PROP_".$arProperty["ID"].'[]';
                $arProperty["SIZE1"] = ((intval($arProperty["SIZE1"]) > 0) ? $arProperty["SIZE1"] : 5);

                $i = 0;
                foreach ($arProperty["OPTIONS"] as $val => $name)
                {
                    $arVariants = [
                        'VALUE' => $val,
                        'NAME' => $name,
                    ];
                    if (is_array($curVal) && in_array($arVariants["VALUE"], $curVal))
                    {
                        $arVariants["SELECTED"] = "Y";
                        if ($i > 0)
                            $arProperty["VALUE_FORMATED"] .= ", ";
                        $arProperty["VALUE_FORMATED"] .= $arVariants["NAME"];
                        $setValue[] = $arVariants["VALUE"];
                        $i++;
                    }
                    $arProperty["VARIANTS"][] = $arVariants;
                }

                $arProperty["TYPE"] = 'MULTISELECT';
            }
            else
            {
                $arProperty["SIZE1"] = ((intval($arProperty["SIZE1"]) > 0) ? $arProperty["SIZE1"] : 1);
                $flagDefault = "N";
                $nameProperty = "";
                foreach ($arProperty["OPTIONS"] as $val => $name)
                {
                    $arVariants = [
                        'VALUE' => $val,
                        'NAME' => $name,
                    ];
                    if ($flagDefault == "N" && $nameProperty == "")
                    {
                        $nameProperty = $arVariants["NAME"];
                    }
                    if ($arVariants["VALUE"] == current($curVal))
                    {
                        $arVariants["SELECTED"] = "Y";
                        $arProperty["VALUE_FORMATED"] = $arVariants["NAME"];
                        $flagDefault = "Y";
                    }
                    $arProperty["VARIANTS"][] = $arVariants;
                }
                if ($flagDefault == "N")
                {
                    $arProperty["VARIANTS"][0]["SELECTED"] = "Y";
                    $arProperty["VARIANTS"][0]["VALUE_FORMATED"] = $nameProperty;
                }
                $arProperty["TYPE"] = 'SELECT';
            }
        }
    }

    public static function formatLocation(array &$arProperty, array &$arDeleteFieldLocation, $locationAltPropDisplayManual = null)
    {
        $curVal = \CSaleLocation::getLocationIDbyCODE(current($arProperty['VALUE']));
        $arProperty["VALUE"] = $curVal;

        $locationFound = false;
        //todo select via D7
        $dbVariants = \CSaleLocation::GetList(
            ["SORT" => "ASC", "COUNTRY_NAME_LANG" => "ASC", "CITY_NAME_LANG" => "ASC"],
            ["LID" => LANGUAGE_ID],
            false,
            false,
            ["ID", "COUNTRY_NAME", "CITY_NAME", "SORT", "COUNTRY_NAME_LANG", "CITY_NAME_LANG", "CITY_ID", "CODE"]
        );
        while ($arVariants = $dbVariants->GetNext())
        {
            $city = !empty($arVariants['CITY_NAME']) ? ' - '.$arVariants['CITY_NAME'] : '';

            if ($arVariants['ID'] === $curVal)
            {
                // set formatted value
                $locationFound = $arVariants;
                $arVariants['SELECTED'] = 'Y';
                $arProperty['VALUE_FORMATED'] = $arVariants['COUNTRY_NAME'].$city;
            }

            $arVariants['NAME'] = $arVariants['COUNTRY_NAME'].$city;
            // save to variants
            $arProperty['VARIANTS'][] = $arVariants;
        }

        if (!$locationFound && intval($curVal))
        {
            $item = \CSaleLocation::GetById($curVal);
            if ($item)
            {
                // set formatted value
                $locationFound = $item;
                $arProperty["VALUE_FORMATED"] = $item["COUNTRY_NAME"].(($item["CITY_NAME"] <> '') ? " - " : "").$item["CITY_NAME"];
                $item['SELECTED'] = 'Y';
                $item['NAME'] = $item["COUNTRY_NAME"].(($item["CITY_NAME"] <> '') ? " - " : "").$item["CITY_NAME"];

                // save to variants
                $arProperty["VARIANTS"][] = $item;
            }
        }

        if ($locationFound)
        {
            // enable location town text
            if (isset($locationAltPropDisplayManual)) // its an ajax-hit and sale.location.selector.steps is used
            {
                if (intval($locationAltPropDisplayManual[$arProperty["ID"]])) // user MANUALLY selected "Other location" in the selector
                    unset($arDeleteFieldLocation[$arProperty["ID"]]);
                else
                    $arDeleteFieldLocation[$arProperty["ID"]] = $arProperty["INPUT_FIELD_LOCATION"];
            }
            else
            {
                if ($arProperty["IS_LOCATION"] == "Y" && intval($arProperty["INPUT_FIELD_LOCATION"]) > 0)
                {
                    if (intval($locationFound["CITY_ID"]) <= 0)
                        unset($arDeleteFieldLocation[$arProperty["ID"]]);
                    else
                        $arDeleteFieldLocation[$arProperty["ID"]] = $arProperty["INPUT_FIELD_LOCATION"];
                }
            }
        }
        else
        {
            // nothing found, may be it is the first load - hide
            $arDeleteFieldLocation[$arProperty["ID"]] = $arProperty["INPUT_FIELD_LOCATION"];
        }
    }

    public static function formatFile(array &$arProperty)
    {
        $curVal = $arProperty['VALUE'];

        $arProperty["SIZE1"] = intval($arProperty["SIZE1"]);
        if ($arProperty['MULTIPLE'] == 'Y')
        {
            $arr = [];
            $curVal = isset($curVal) ? $curVal : $arProperty["DEFAULT_VALUE"];
            foreach ($curVal as $file)
            {
                $arr[] = $file['ID'];
            }
            $arProperty["VALUE"] = serialize($arr);
        }
        else
        {
            $arFile = isset($curVal) && is_array($curVal) ? current($curVal) : $arProperty["DEFAULT_VALUE"];
            if (is_array($arFile))
                $arProperty["VALUE"] = $arFile['ID'];
        }
    }

    public static function formatDate(array &$arProperty)
    {
        $arProperty["VALUE"] = current($arProperty['VALUE']);
        $arProperty["VALUE_FORMATED"] = $arProperty["VALUE"];
    }



    /////////////////
    ///
    ///
    ///
    private function sanitize(string $html): string
    {
        static $sanitizer = null;

        if ($sanitizer === null)
        {
            $sanitizer = new \CBXSanitizer;
            $sanitizer->setLevel(\CBXSanitizer::SECURE_LEVEL_LOW);
        }

        return $sanitizer->sanitizeHtml($html);
    }

    protected function obtainDelivery()
    {
        $arResult =& $this->arResult;

        $arStoreId = [];
        /** @var Sale\Shipment $shipment */
        $shipment = $this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($this->order);

        if ($shipment && !empty($this->arDeliveryServiceAll))
        {
            foreach ($this->arDeliveryServiceAll as $deliveryObj)
            {
                $arDelivery =& $this->arResult["DELIVERY"][$deliveryObj->getId()];

                $arDelivery['ID'] = $deliveryObj->getId();
                $arDelivery['NAME'] = $deliveryObj->isProfile() ? $deliveryObj->getNameWithParent() : $deliveryObj->getName();
                $arDelivery['OWN_NAME'] = $deliveryObj->getName();
                $arDelivery['DESCRIPTION'] = $this->sanitize($deliveryObj->getDescription());
                $arDelivery['FIELD_NAME'] = 'DELIVERY_ID';
                $arDelivery["CURRENCY"] = $this->order->getCurrency();
                $arDelivery['SORT'] = $deliveryObj->getSort();
                $arDelivery['EXTRA_SERVICES'] = $deliveryObj->getExtraServices()->getItems();
                $arDelivery['STORE'] = \Bitrix\Sale\Delivery\ExtraServices\Manager::getStoresList($deliveryObj->getId());

                if (intval($deliveryObj->getLogotip()) > 0)
                    $arDelivery["LOGOTIP"] = \CFile::GetFileArray($deliveryObj->getLogotip());

                if (!empty($arDelivery['STORE']) && is_array($arDelivery['STORE']))
                {
                    foreach ($arDelivery['STORE'] as $val)
                        $arStoreId[$val] = $val;
                }

                $buyerStore = $this->request->get('BUYER_STORE');
                if (!empty($buyerStore) && !empty($arDelivery['STORE']) && is_array($arDelivery['STORE']) && in_array($buyerStore, $arDelivery['STORE']))
                {
                    $this->arUserResult['DELIVERY_STORE'] = $arDelivery["ID"];
                }
            }

            $arResult["BUYER_STORE"] = $shipment->getStoreId();
        }

        $arStore = [];
        $dbList = \CCatalogStore::GetList(
            ["SORT" => "DESC", "ID" => "DESC"],
            ["ACTIVE" => "Y", "ID" => $arStoreId, "ISSUING_CENTER" => "Y", "+SITE_ID" => $this->orderBuilder->getSiteId()],
            false,
            false,
            ["ID", "TITLE", "ADDRESS", "DESCRIPTION", "IMAGE_ID", "PHONE", "SCHEDULE", "GPS_N", "GPS_S", "ISSUING_CENTER", "SITE_ID"]
        );
        while ($arStoreTmp = $dbList->Fetch())
        {
            if ($arStoreTmp["IMAGE_ID"] > 0)
                $arStoreTmp["IMAGE_ID"] = \CFile::GetFileArray($arStoreTmp["IMAGE_ID"]);
            else
                $arStoreTmp["IMAGE_ID"] = null;

            $arStore[$arStoreTmp["ID"]] = $arStoreTmp;
        }

        $arResult["STORE_LIST"] = $arStore;

        $arResult["DELIVERY_EXTRA"] = [];
        $deliveryExtra = $this->request->get('DELIVERY_EXTRA');
        if (is_array($deliveryExtra) && !empty($deliveryExtra[$this->arUserResult["DELIVERY_ID"]]))
            $arResult["DELIVERY_EXTRA"] = $deliveryExtra[$this->arUserResult["DELIVERY_ID"]];

        $this->executeEvent('OnSaleComponentOrderOneStepDelivery', $this->order);
    }

    /**
     * Set pay system data from inner/external payment object and pay system services object to $this->arResult
     * Execution of 'OnSaleComponentOrderOneStepPaySystem' event
     */
    protected function obtainPaySystem()
    {
        $arResult =& $this->arResult;

        $innerPayment = $this->orderBuilder->getPaymentBuilder()->getInnerPayment($this->order);
        if (!empty($innerPayment) && $innerPayment->getSum() > 0)
        {
            $arResult['PAYED_FROM_ACCOUNT_FORMATED'] = SaleFormatCurrency($innerPayment->getSum(), $this->order->getCurrency());
            $arResult['ORDER_TOTAL_LEFT_TO_PAY'] = $this->order->getPrice() - $innerPayment->getSum();
            $arResult['ORDER_TOTAL_LEFT_TO_PAY_FORMATED'] = SaleFormatCurrency($this->order->getPrice() - $innerPayment->getSum(), $this->order->getCurrency());
        }

        $paySystemList = $this->arParams['DELIVERY_TO_PAYSYSTEM'] === 'p2d' ? $this->arActivePaySystems : $this->arPaySystemServiceAll;
        if (!empty($paySystemList))
        {
            $innerPaySystemId = \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId();

            if (!empty($paySystemList[$innerPaySystemId]))
            {
                $innerPaySystem = $paySystemList[$innerPaySystemId];

                if ($innerPaySystem['LOGOTIP'] > 0)
                {
                    $innerPaySystem['LOGOTIP'] = \CFile::GetFileArray($innerPaySystem['LOGOTIP']);
                }

                $innerPaySystem['DESCRIPTION'] = $this->sanitize((string)$innerPaySystem['DESCRIPTION']);

                $arResult['INNER_PAY_SYSTEM'] = $innerPaySystem;
                unset($paySystemList[$innerPaySystemId]);
            }

            $extPayment = $this->orderBuilder->getPaymentBuilder()->getExternalPayment($this->order);
            $paymentId = !empty($extPayment) ? $extPayment->getPaymentSystemId() : null;
//dd($extPayment->getPaymentSystemId());
            foreach ($paySystemList as $paySystem)
            {
                $paySystem['PSA_ID'] = $paySystem['ID'];

                if ((string)$paySystem['PSA_NAME'] === '')
                {
                    $paySystem['PSA_NAME'] = $paySystem['NAME'];
                }

                $paySystem['PSA_NAME'] = htmlspecialcharsEx($paySystem['PSA_NAME']);

                $keyMap = [
                    'ACTION_FILE', 'RESULT_FILE', 'NEW_WINDOW', 'PERSON_TYPE_ID', 'PARAMS', 'TARIF', 'HAVE_PAYMENT',
                    'HAVE_ACTION', 'HAVE_RESULT', 'HAVE_PREPAY', 'HAVE_RESULT_RECEIVE', 'ENCODING',
                ];
                foreach ($keyMap as $key)
                {
                    $paySystem["PSA_{$key}"] = $paySystem[$key];
                    unset($paySystem[$key]);
                }

                if ($paySystem['LOGOTIP'] > 0)
                {
                    $paySystem['PSA_LOGOTIP'] = \CFile::GetFileArray($paySystem['LOGOTIP']);
                }
                unset($paySystem['LOGOTIP']);

                if ($paymentId == $paySystem['ID'])
                {
                    $paySystem['CHECKED'] = 'Y';
                }

                $paySystem['PRICE'] = 0;
                if ($paySystem['HAVE_PRICE'] === 'Y' && !empty($extPayment))
                {
                    $service = \Bitrix\Sale\PaySystem\Manager::getObjectById($paySystem['ID']);
                    if ($service !== null)
                    {
                        $paySystem['PRICE'] = $service->getPaymentPrice($extPayment);
                        $paySystem['PRICE_FORMATTED'] = SaleFormatCurrency($paySystem['PRICE'], $this->order->getCurrency());

                        if ($paymentId == $paySystem['ID'])
                        {
                            $arResult['PAY_SYSTEM_PRICE'] = $extPayment->getField('PRICE_COD');
                            $arResult['PAY_SYSTEM_PRICE_FORMATTED'] = SaleFormatCurrency($arResult['PAY_SYSTEM_PRICE'], $this->order->getCurrency());
                        }
                    }
                }

                $paySystem['DESCRIPTION'] = $this->sanitize((string)$paySystem['DESCRIPTION']);

                $arResult['PAY_SYSTEM'][] = $paySystem;
            }
        }

        $this->executeEvent('OnSaleComponentOrderOneStepPaySystem', $this->order);
    }

    /**
     * Set related to payment/delivery order properties data from order object to $this->arResult
     *
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    protected function obtainRelatedProperties()
    {
        $arRes = [];
        $arProps = $this->order->getPropertyCollection()->getArray();

        foreach ($arProps['properties'] as $key => $property)
        {
            if ($property['UTIL'] == 'Y')
                continue;

            if (!empty($property['RELATION']))
            {
                if (!empty($this->arResult['PERSON_TYPE'][$property['PERSON_TYPE_ID']]))
                {
                    $personType = $this->arResult['PERSON_TYPE'][$property['PERSON_TYPE_ID']];
                    $property['PERSON_TYPE_LID'] = $personType['ID'];
                    $property['PERSON_TYPE_NAME'] = $personType['NAME'];
                    $property['PERSON_TYPE_SORT'] = $personType['SORT'];
                    $property['PERSON_TYPE_ACTIVE'] = $personType['ACTIVE'];
                }

                foreach ($arProps['groups'] as $group)
                {
                    if ($group['ID'] == $property['PROPS_GROUP_ID'])
                    {
                        $property['GROUP_NAME'] = $group['NAME'];
                        $property['GROUP_ID'] = $group['ID'];
                        $property['GROUP_PERSON_TYPE_ID'] = $group['PERSON_TYPE_ID'];
                        break;
                    }
                }

                $property['SETTINGS'] = [
                    'MINLENGTH' => $property['MINLENGTH'],
                    'MAXLENGTH' => $property['MAXLENGTH'],
                    'PATTERN' => $property['PATTERN'],
                    'MULTILINE' => $property['MULTILINE'],
                    'SIZE' => $property['SIZE'],
                ];
                $property['PAYSYSTEM_ID'] = $property['ID'];
                $property['DELIVERY_ID'] = $property['ID'];

                $arRes[] = $this->getOrderPropFormatted($property);
            }
        }

        $this->arResult["ORDER_PROP"]["RELATED"] = $arRes;
    }

    /**
     * Set taxes data from order object to $this->arResult
     */
    protected function obtainTaxes()
    {
        $arResult =& $this->arResult;

        $arResult["USE_VAT"] = $this->order->isUsedVat();
        $arResult["VAT_RATE"] = $this->order->getVatRate();
        $arResult["VAT_SUM"] = $this->order->getVatSum();

        if ($arResult["VAT_SUM"] === null)
            $arResult["VAT_SUM"] = 0;

        $arResult["VAT_SUM_FORMATED"] = SaleFormatCurrency($arResult["VAT_SUM"], $this->order->getCurrency());

        $taxes = $this->order->getTax();
        $taxes->refreshData();

        if ($this->order->isUsedVat())
        {
            if ($this->arParams['SHOW_VAT_PRICE'] === 'Y')
            {
                $arResult['TAX_LIST'] = $taxes->getAvailableList();
            }
        }
        else
        {
            $arResult['TAX_LIST'] = $taxes->getTaxList();
            if (is_array($arResult['TAX_LIST']) && !empty($arResult['TAX_LIST']))
            {
                foreach ($arResult['TAX_LIST'] as $key => &$tax)
                {
                    if ($tax['VALUE_MONEY'])
                        $tax['VALUE_MONEY_FORMATED'] = SaleFormatCurrency($tax['VALUE_MONEY'], $this->order->getCurrency());
                }
            }
        }

        $arResult['TAX_PRICE'] = $this->order->getTaxPrice();
    }

    /**
     * Set order total prices data from order object to $this->arResult
     */
    protected function obtainTotal()
    {
        $arResult =& $this->arResult;

        $locationAltPropDisplayManual = $this->request->get('LOCATION_ALT_PROP_DISPLAY_MANUAL');
        if (!empty($locationAltPropDisplayManual) && is_array($locationAltPropDisplayManual))
        {
            foreach ($locationAltPropDisplayManual as $propId => $switch)
            {
                if (intval($propId))
                {
                    $arResult['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($propId)] = !!$switch;
                }
            }
        }

        $basket = $this->calculateBasket;

        $arResult['BASKET_POSITIONS'] = $basket->count();

        $arResult['ORDER_PRICE'] = $basket->getPrice();
        $arResult['ORDER_PRICE_FORMATED'] = SaleFormatCurrency($arResult['ORDER_PRICE'], $this->order->getCurrency());

        $arResult['ORDER_WEIGHT'] = $basket->getWeight();
        $arResult['ORDER_WEIGHT_FORMATED'] = roundEx(floatval($arResult['ORDER_WEIGHT'] / $arResult['WEIGHT_KOEF']), SALE_WEIGHT_PRECISION).' '.$arResult['WEIGHT_UNIT'];

        $arResult['PRICE_WITHOUT_DISCOUNT_VALUE'] = $basket->getBasePrice();
        $arResult['PRICE_WITHOUT_DISCOUNT'] = SaleFormatCurrency($arResult['PRICE_WITHOUT_DISCOUNT_VALUE'], $this->order->getCurrency());

        $arResult['BASKET_PRICE_DISCOUNT_DIFF_VALUE'] = $basket->getBasePrice() - $basket->getPrice();
        $arResult['BASKET_PRICE_DISCOUNT_DIFF'] = SaleFormatCurrency($arResult['BASKET_PRICE_DISCOUNT_DIFF_VALUE'], $this->order->getCurrency());

        $arResult['DISCOUNT_PRICE'] = Sale\PriceMaths::roundPrecision(
            $this->order->getDiscountPrice() + ($arResult['PRICE_WITHOUT_DISCOUNT_VALUE'] - $arResult['ORDER_PRICE'])
        );
        $arResult['DISCOUNT_PRICE_FORMATED'] = SaleFormatCurrency($arResult['DISCOUNT_PRICE'], $this->order->getCurrency());

        $arResult['DELIVERY_PRICE'] = Sale\PriceMaths::roundPrecision($this->order->getDeliveryPrice());
        $arResult['DELIVERY_PRICE_FORMATED'] = SaleFormatCurrency($arResult['DELIVERY_PRICE'], $this->order->getCurrency());

        $arResult['ORDER_TOTAL_PRICE'] = Sale\PriceMaths::roundPrecision($this->order->getPrice());
        $arResult['ORDER_TOTAL_PRICE_FORMATED'] = SaleFormatCurrency($arResult['ORDER_TOTAL_PRICE'], $this->order->getCurrency());
    }

    /**
     * Obtains all order fields filled by user.
     */
    protected function obtainUserConsentInfo()
    {
        $propertyNames = [];

        $propertyIterator = Sale\Property::getList([
            'select' => ['NAME'],
            'filter' => [
                'ACTIVE' => 'Y',
                'UTIL' => 'N',
                'PERSON_TYPE_SITE.SITE_ID' => $this->orderBuilder->getSiteId(),
            ],
            'order' => [
                'SORT' => 'ASC',
                'ID' => 'ASC',
            ],
            'runtime' => [
                new \Bitrix\Main\Entity\ReferenceField(
                    'PERSON_TYPE_SITE',
                    'Bitrix\Sale\Internals\PersonTypeSiteTable',
                    ['=this.PERSON_TYPE_ID' => 'ref.PERSON_TYPE_ID']
                ),
            ],
        ]);
        while ($property = $propertyIterator->fetch())
        {
            $propertyNames[] = $property['NAME'];
        }

        $this->arResult['USER_CONSENT_PROPERTY_DATA'] = $propertyNames;
    }

    /**
     * Make $arResult compatible ('~' prefixes and htmlspecialcharsEx)
     */
    protected function makeResultCompatible()
    {
        $arResult =& $this->arResult;

        if (is_array($arResult['PERSON_TYPE']) && !empty($arResult['PERSON_TYPE']))
            foreach ($arResult['PERSON_TYPE'] as &$item)
                self::makeCompatibleArray($item);

        if (is_array($arResult['ORDER_PROP']['RELATED']) && !empty($arResult['ORDER_PROP']['RELATED']))
            foreach ($arResult['ORDER_PROP']['RELATED'] as &$item)
                self::makeCompatibleArray($item);

        if (is_array($arResult['ORDER_PROP']['USER_PROPS_Y']) && !empty($arResult['ORDER_PROP']['USER_PROPS_Y']))
            foreach ($arResult['ORDER_PROP']['USER_PROPS_Y'] as &$item)
                self::makeCompatibleArray($item);

        if (is_array($arResult['ORDER_PROP']['USER_PROPS_N']) && !empty($arResult['ORDER_PROP']['USER_PROPS_N']))
            foreach ($arResult['ORDER_PROP']['USER_PROPS_N'] as &$item)
                self::makeCompatibleArray($item);

        if (is_array($arResult['BASKET_ITEMS']) && !empty($arResult['BASKET_ITEMS']))
            foreach ($arResult['BASKET_ITEMS'] as &$item)
                self::makeCompatibleArray($item);

        if (is_array($arResult['GRID']['ROWS']) && !empty($arResult['GRID']['ROWS']))
            foreach ($arResult['GRID']['ROWS'] as &$item)
                self::makeCompatibleArray($item['data']);

        if (is_array($arResult['USER_ACCOUNT']) && !empty($arResult['USER_ACCOUNT']))
            self::makeCompatibleArray($arResult['USER_ACCOUNT']);
    }

    public static function makeCompatibleArray(&$array)
    {
        if (empty($array) || !is_array($array))
            return;

        $arr = [];
        foreach ($array as $key => $value)
        {
            if (is_array($value) || preg_match("/[;&<>\"]/", $value))
            {
                $arr[$key] = htmlspecialcharsEx($value);
            }
            else
            {
                $arr[$key] = $value;
            }

            $arr["~{$key}"] = $value;
        }

        $array = $arr;
    }

    protected function getJsDataResult()
    {
        global $USER;
        $arResult =& $this->arResult;
        $result =& $this->arResult['JS_DATA'];

        $result['IS_AUTHORIZED'] = $USER->IsAuthorized();
        $result['LAST_ORDER_DATA'] = [];

        if (
            ($this->request->getRequestMethod() === 'GET' || $this->request->get('do_authorize') === 'Y' || $this->request->get('do_register') === 'Y')
            && $this->arUserResult['USE_PRELOAD']
            && $result['IS_AUTHORIZED']
        )
        {
            $lastOrder =& $this->arUserResult['LAST_ORDER_DATA'];

            if (!empty($lastOrder))
            {
                $status = false;
                if (!empty($lastOrder['PERSON_TYPE_ID']))
                {
                    $status = $this->order->getPersonTypeId() == $lastOrder['PERSON_TYPE_ID'];
                }

                $result['LAST_ORDER_DATA']['PERSON_TYPE'] = $status;

                $status = false;
                if (!empty($lastOrder['DELIVERY_ID']) && $shipment = $this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($this->order))
                {
                    if (empty($lastOrder['DELIVERY_EXTRA_SERVICES'][$lastOrder['DELIVERY_ID']]))
                    {
                        $status = $shipment->getDeliveryId() == $lastOrder['DELIVERY_ID'];
                    }
                }

                $result['LAST_ORDER_DATA']['DELIVERY'] = $status;

                $status = false;
                if (empty($lastOrder['PAY_CURRENT_ACCOUNT']) && !empty($lastOrder['PAY_SYSTEM_ID']) && $payment = $this->orderBuilder->getPaymentBuilder()->getExternalPayment($this->order))
                {
                    $status = $payment->getPaymentSystemId() == $lastOrder['PAY_SYSTEM_ID'];
                }

                $result['LAST_ORDER_DATA']['PAY_SYSTEM'] = $status;

                $status = false;
                if (!empty($lastOrder['BUYER_STORE']) && $shipment = $this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($this->order))
                {
                    $status = $shipment->getStoreId() == $lastOrder['BUYER_STORE'];
                }

                $result['LAST_ORDER_DATA']['PICK_UP'] = $status;
            }
            else
            {
                // last order data cannot initialize
                $result['LAST_ORDER_DATA']['FAIL'] = true;
            }
        }
        else
        {
            // last order data not initialized
            $result['LAST_ORDER_DATA']['FAIL'] = false;
        }

        $result['ZIP_PROPERTY_CHANGED'] = $this->arUserResult['ZIP_PROPERTY_CHANGED'];
        $result['ORDER_DESCRIPTION'] = $this->arUserResult['ORDER_DESCRIPTION'];
        $result['SHOW_AUTH'] = !$USER->IsAuthorized() && $this->arParams['ALLOW_AUTO_REGISTER'] === 'N';
        $result['SHOW_EMPTY_BASKET'] = $arResult['SHOW_EMPTY_BASKET'];
        $result['AUTH'] = $arResult['AUTH'];
        $result['SMS_AUTH'] = $arResult['SMS_AUTH'];
        $result['OK_MESSAGE'] = $arResult['OK_MESSAGE'];
        $result['GRID'] = $arResult['GRID'];
        $result['PERSON_TYPE'] = $arResult["PERSON_TYPE"];
        $result['PAY_SYSTEM'] = $arResult["PAY_SYSTEM"];
        $result['INNER_PAY_SYSTEM'] = $arResult["INNER_PAY_SYSTEM"];
        $result['DELIVERY'] = $arResult["DELIVERY"];

        foreach ($result['DELIVERY'] as &$delivery)
        {
            if (!empty($delivery['EXTRA_SERVICES']))
            {
                $arExtraService = [];
                /** @var \Bitrix\Sale\Delivery\ExtraServices\Base $extraService */
                foreach ($delivery['EXTRA_SERVICES'] as $extraServiceId => $extraService)
                {
                    if ($extraService->canUserEditValue())
                    {
                        $arr = [];
                        $arr['id'] = $extraServiceId;
                        $arr['name'] = $extraService->getName();
                        $arr['value'] = $extraService->getValue();
                        $arr['price'] = $extraService->getPriceShipment($this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($this->order));
                        $arr['priceFormatted'] = SaleFormatCurrency($extraService->getPriceShipment($this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($this->order)), $this->order->getCurrency());
                        $arr['description'] = $extraService->getDescription();
                        $arr['canUserEditValue'] = $extraService->canUserEditValue();
                        $arr['editControl'] = $extraService->getEditControl('DELIVERY_EXTRA_SERVICES['.$delivery['ID'].']['.$extraServiceId.']');
                        $arr['viewControl'] = $extraService->getViewControl();
                        $arExtraService[] = $arr;
                    }
                }

                $delivery['EXTRA_SERVICES'] = $arExtraService;
            }
        }

        $result["USER_PROFILES"] = $arResult["ORDER_PROP"]['USER_PROFILES'];

        $arr = $this->order->getPropertyCollection()->getArray();

        foreach ($arr['properties'] as $key => $property)
        {
            if ($property['UTIL'] === 'Y')
            {
                unset($arr['properties'][$key]);
            }
        }

        if (!empty($arr['groups']) && !empty($arr['properties']))
        {
            $arr['groups'] = array_values($arr['groups']);
            $arr['properties'] = array_values($arr['properties']);

            $groupIndexList = [];

            foreach ($arr['groups'] as $groupData)
            {
                $groupIndexList[] = (int)$groupData['ID'];
            }

            if (!empty($groupIndexList))
            {
                foreach ($arr['properties'] as $index => $propertyData)
                {
                    if (array_key_exists('PROPS_GROUP_ID', $propertyData))
                    {
                        if (!in_array($propertyData['PROPS_GROUP_ID'], $groupIndexList))
                        {
                            $arr['properties'][$index]['PROPS_GROUP_ID'] = 0;
                        }
                    }

                    if ($propertyData['TYPE'] === 'ENUM' && is_array($propertyData['OPTIONS']))
                    {
                        $arr['properties'][$index]['OPTIONS_SORT'] = array_keys($propertyData['OPTIONS']);
                    }
                }
            }

        }

        $result["ORDER_PROP"] = $arr;
        $result['STORE_LIST'] = $arResult['STORE_LIST'];
        $result['BUYER_STORE'] = $arResult['BUYER_STORE'];

        $result['COUPON_LIST'] = [];
        $arCoupons = Sale\DiscountCouponsManager::get(true, [], true, true);
        if (!empty($arCoupons))
        {
            foreach ($arCoupons as &$oneCoupon)
            {
                if ($oneCoupon['STATUS'] == Sale\DiscountCouponsManager::STATUS_NOT_FOUND || $oneCoupon['STATUS'] == Sale\DiscountCouponsManager::STATUS_FREEZE)
                {
                    $oneCoupon['JS_STATUS'] = 'BAD';
                }
                elseif ($oneCoupon['STATUS'] == Sale\DiscountCouponsManager::STATUS_NOT_APPLYED || $oneCoupon['STATUS'] == Sale\DiscountCouponsManager::STATUS_ENTERED)
                {
                    $oneCoupon['JS_STATUS'] = 'ENTERED';
                }
                else
                {
                    $oneCoupon['JS_STATUS'] = 'APPLIED';
                }

                $oneCoupon['JS_CHECK_CODE'] = '';
                if (isset($oneCoupon['CHECK_CODE_TEXT']))
                {
                    $oneCoupon['JS_CHECK_CODE'] = is_array($oneCoupon['CHECK_CODE_TEXT'])
                        ? implode(', ', $oneCoupon['CHECK_CODE_TEXT'])
                        : $oneCoupon['CHECK_CODE_TEXT'];
                }

                $result['COUPON_LIST'][] = $oneCoupon;
            }

            unset($oneCoupon);
            $result['COUPON_LIST'] = array_values($arCoupons);
        }
        unset($arCoupons);

        $result['PAY_CURRENT_ACCOUNT'] = 'N';
        if ($innerPaySystem = $this->order->getPaymentCollection()->getInnerPayment())
        {
            if ($innerPaySystem->getSum() > 0)
            {
                $result['PAY_CURRENT_ACCOUNT'] = 'Y';
            }
        }

        $result['PAY_FROM_ACCOUNT'] = $arResult["PAY_FROM_ACCOUNT"];
        $result['CURRENT_BUDGET_FORMATED'] = $arResult["CURRENT_BUDGET_FORMATED"];

        $result['TOTAL'] = [
            'BASKET_POSITIONS' => $arResult["BASKET_POSITIONS"],
            'PRICE_WITHOUT_DISCOUNT_VALUE' => $arResult["PRICE_WITHOUT_DISCOUNT_VALUE"],
            'PRICE_WITHOUT_DISCOUNT' => $arResult["PRICE_WITHOUT_DISCOUNT"],
            'BASKET_PRICE_DISCOUNT_DIFF_VALUE' => $arResult["BASKET_PRICE_DISCOUNT_DIFF_VALUE"],
            'BASKET_PRICE_DISCOUNT_DIFF' => $arResult["BASKET_PRICE_DISCOUNT_DIFF"],
            'PAYED_FROM_ACCOUNT_FORMATED' => $arResult["PAYED_FROM_ACCOUNT_FORMATED"],
            'ORDER_TOTAL_PRICE' => $arResult["ORDER_TOTAL_PRICE"],
            'ORDER_TOTAL_PRICE_FORMATED' => $arResult["ORDER_TOTAL_PRICE_FORMATED"],
            'ORDER_TOTAL_LEFT_TO_PAY' => $arResult["ORDER_TOTAL_LEFT_TO_PAY"],
            'ORDER_TOTAL_LEFT_TO_PAY_FORMATED' => $arResult["ORDER_TOTAL_LEFT_TO_PAY_FORMATED"],
            'ORDER_WEIGHT' => $arResult["ORDER_WEIGHT"],
            'ORDER_WEIGHT_FORMATED' => $arResult["ORDER_WEIGHT_FORMATED"],
            'ORDER_PRICE' => $arResult["ORDER_PRICE"],
            'ORDER_PRICE_FORMATED' => $arResult["ORDER_PRICE_FORMATED"],
            'USE_VAT' => $arResult["USE_VAT"],
            'VAT_RATE' => $arResult["VAT_RATE"],
            'VAT_SUM' => $arResult["VAT_SUM"],
            'VAT_SUM_FORMATED' => $arResult["VAT_SUM_FORMATED"],
            'TAX_PRICE' => $arResult["TAX_PRICE"],
            'TAX_LIST' => $arResult["TAX_LIST"],
            'DISCOUNT_PRICE' => $arResult["DISCOUNT_PRICE"],
            'DISCOUNT_PRICE_FORMATED' => $arResult["DISCOUNT_PRICE_FORMATED"],
            'DELIVERY_PRICE' => $arResult["DELIVERY_PRICE"],
            'DELIVERY_PRICE_FORMATED' => $arResult["DELIVERY_PRICE_FORMATED"],
            'PAY_SYSTEM_PRICE' => $arResult["PAY_SYSTEM_PRICE"],
            'PAY_SYSTEM_PRICE_FORMATTED' => $arResult["PAY_SYSTEM_PRICE_FORMATTED"],
        ];

        $result['ERROR'] = $arResult["ERROR_SORTED"];
        $result['WARNING'] = $arResult["WARNING"];

        $arResult['LOCATIONS'] = $this->getLocationsResult();

        foreach (GetModuleEvents("sale", 'OnSaleComponentOrderJsData', true) as $arEvent)
        {
            ExecuteModuleEventEx($arEvent, [&$this->arResult, &$this->arParams]);
        }
    }

    protected function getLocationsResult()
    {
        $locations = [];
        $propertyCollection = $this->order->getPropertyCollection();
        $properties = $propertyCollection->getArray();

        foreach ($properties['properties'] as $property)
        {
            if ($property['UTIL'] == 'Y')
                continue;

            if ($property['TYPE'] == 'LOCATION')
            {
                $locationTemplateP = $this->arParams['TEMPLATE_LOCATION'] == '.default' ? 'steps' : 'search';
                $locationTemplate = $this->request->get('PERMANENT_MODE_STEPS') == 1 ? 'steps' : $locationTemplateP;

                $locations[$property['ID']]['template'] = $locationTemplate;
                $locations[$property['ID']]['output'] = $this->getLocationHtml($property, $locationTemplate);
                $locations[$property['ID']]['showAlt'] = isset($this->arUserResult['ORDER_PROP'][$property['INPUT_FIELD_LOCATION']]);
                $locations[$property['ID']]['lastValue'] = reset($property['VALUE']);

                if ($property['IS_LOCATION'] === 'Y')
                {
                    $locations[$property['ID']]['coordinates'] = Sale\Location\LocationTable::getRow([
                        'select' => ['LONGITUDE', 'LATITUDE'],
                        'filter' => ['=CODE' => reset($property['VALUE'])],
                    ]);
                }
            }
        }

        return $locations;
    }

    protected function getLocationHtml($property, $locationTemplate)
    {
        global $APPLICATION;

        $locationOutput = [];
        $showDefault = true;

        $propertyId = (int)$property['ID'];
        $isMultiple = $property['MULTIPLE'] == 'Y' && $property['IS_LOCATION'] != 'Y';

        $locationAltPropDisplayManual = $this->request->get('LOCATION_ALT_PROP_DISPLAY_MANUAL');
        $altPropManual = isset($locationAltPropDisplayManual[$propertyId]) && (bool)$locationAltPropDisplayManual[$propertyId];

        $location = $this->order->getPropertyCollection()->getItemByOrderPropertyId($propertyId);
        $actualValues = $location->getValue();

        if (!is_array($actualValues))
        {
            $actualValues = [$actualValues];
        }

        if (!empty($actualValues) && is_array($actualValues))
        {
            foreach ($actualValues as $key => $value)
            {
                $parameters = [
                    'CODE' => $value,
                    'INPUT_NAME' => 'ORDER_PROP_'.$propertyId.($isMultiple ? '['.$key.']' : ''),
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => '36000000',
                    'SEARCH_BY_PRIMARY' => 'N',
                    'SHOW_DEFAULT_LOCATIONS' => $showDefault ? 'Y' : 'N',
                    'PROVIDE_LINK_BY' => 'code',
                    'JS_CALLBACK' => 'submitFormProxy',
                    'JS_CONTROL_DEFERRED_INIT' => $propertyId.($isMultiple ? '_'.$key : ''),
                    'JS_CONTROL_GLOBAL_ID' => $propertyId.($isMultiple ? '_'.$key : ''),
                    'DISABLE_KEYBOARD_INPUT' => 'Y',
                    'PRECACHE_LAST_LEVEL' => 'N',
                    'PRESELECT_TREE_TRUNK' => 'Y',
                    'SUPPRESS_ERRORS' => 'Y',
                    'FILTER_BY_SITE' => 'Y',
                    'FILTER_SITE_ID' => $this->orderBuilder->getSiteId(),
                ];

                ob_start();

                if ($locationTemplate == 'steps')
                {
                    echo '<input type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL['.$propertyId
                        .']" name="LOCATION_ALT_PROP_DISPLAY_MANUAL['.$propertyId.']" value="'
                        .($altPropManual ? '1' : '0').'" />';
                }

                $APPLICATION->IncludeComponent(
                    'bitrix:sale.location.selector.'.$locationTemplate,
                    '',
                    $parameters,
                    null,
                    ['HIDE_ICONS' => 'Y']
                );

                $locationOutput[] = ob_get_contents();
                ob_end_clean();

                $showDefault = false;
            }
        }

        if ($isMultiple)
        {
            $parameters = [
                'CODE' => '',
                'INPUT_NAME' => 'ORDER_PROP_'.$propertyId.'[#key#]',
                'CACHE_TYPE' => 'A',
                'CACHE_TIME' => '36000000',
                'SEARCH_BY_PRIMARY' => 'N',
                'SHOW_DEFAULT_LOCATIONS' => 'N',
                'PROVIDE_LINK_BY' => 'code',
                'JS_CALLBACK' => 'submitFormProxy',
                'JS_CONTROL_DEFERRED_INIT' => $propertyId.'_key__',
                'JS_CONTROL_GLOBAL_ID' => $propertyId.'_key__',
                'DISABLE_KEYBOARD_INPUT' => 'Y',
                'PRECACHE_LAST_LEVEL' => 'N',
                'PRESELECT_TREE_TRUNK' => 'Y',
                'SUPPRESS_ERRORS' => 'Y',
                'FILTER_BY_SITE' => 'Y',
                'FILTER_SITE_ID' => $this->orderBuilder->getSiteId(),
            ];

            ob_start();

            $APPLICATION->IncludeComponent(
                'bitrix:sale.location.selector.'.$locationTemplate,
                '',
                $parameters,
                null,
                ['HIDE_ICONS' => 'Y']
            );

            $locationOutput['clean'] = ob_get_contents();
            ob_end_clean();
        }

        return $locationOutput;
    }

}