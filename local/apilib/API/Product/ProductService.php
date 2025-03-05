<?php

namespace ApiLib\API\Product;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\API\Prop\PropService;
use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Catalog\EO_Product_Query;
use Bitrix\Catalog\PriceTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\ORM\Query\Query;
use Illuminate\Support\Collection;

class ProductService{

    private $optionsPropertyId = 204; // ИД свойства "Характеристики" у предложений

    private PropService $propsService;

    private int $ttl = 0;

    private int $productIblockId;

    private int $offerIblockId;

    private int $skuLinkId;

    private bool $withProps = false;

    private bool $withOffers = false;

    private array $pagenation = [];

    private string $sortType = '';

    public function setTtl(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function setProductIblockId(int $productIblockId): void
    {
        $this->productIblockId = $productIblockId;
    }

    public function setOfferIblockId(int $offerIblockId): void
    {
        $this->offerIblockId = $offerIblockId;
    }

    public function setWithProps(bool $withProps): void
    {
        $this->withProps = $withProps;
    }

    public function setWithOffers(bool $withOffers): void
    {
        $this->withOffers = $withOffers;
    }

    function __construct(int $ttl = 86400){
        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('catalog');
        $this->propsService = new PropService();
        $this->ttl = (int)BitrixSettings::getApiVarByName('TTL');
        $this->productIblockId = (int)BitrixSettings::getApiVarByName('CATALOG_ID'); //14

        $this->offerIblockId = (int)BitrixSettings::getApiVarByName('OFFER_IBLOCK_ID');//15;
        $props = $this->propsService->getProps(['code' => 'CML2_LINK', 'iblock' => $this->offerIblockId]);
        if (count($props))
            $this->skuLinkId = current($props)['ID'];

        $this->withProps = (bool)BitrixSettings::getApiVarByName('WITH_PROPS');//true;
        $this->withOffers = (bool)BitrixSettings::getApiVarByName('WITH_OFFERS');//true;

        $this->pagenation['limit'] = 30;
        $this->pagenation['offset'] = 0;
    }

    public function setSortType($type)
    {
        $this->sortType = $type;
    }

    public function index(array $data)
    {
        $sort = $this->buildDataSort($data);
        $this->buildDataPagination($data);
        $ids = [];

        if (isset($data['id']))
            $ids = $data['id'];

        return $this->getProductCard($ids, $data, $sort, $this->pagenation);
    }

    /**
     * Получение полной карточки товара с предложениями или без
     *
     * @param $productId
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getProductCard($productId = 0, $filter = [], $sort = [], $pagination = []): array
    {
        $elements = $this->getProductArr($productId, $filter, $sort, $pagination);

        $productWithOffersIds = [];
        foreach($elements['items'] as $element) {
            if ($element['TYPE'] == 3) {
                $productWithOffersIds[] = $element['ID'];
            }
        }

        if ($this->withProps) {
            $elements['items'] = $this->attachProps($elements['items']);
        }

        if (count($productWithOffersIds) && $this->withOffers){
            $offers = $this->getProductSkuList($productWithOffersIds);

            foreach($offers as $offer) {
                foreach($elements['items'] as &$element) {
                    if ($element['ID'] == $offer['PARENT_ID']) {
                        $element['SKU_LIST'][] = $offer;
                    }

                }
            }
        }

        return $elements;
    }

    /**
     * Добавление/удаление избранного товара
     *
     * @param $productId
     * @param $userId
     * @return array
     */
    public function toggleFavorites($productId = 0){
        if (empty($productId)) {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => 'Parameters is invalid!'],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        global $USER;

        if (!$USER->IsAuthorized()) {
            AdvancedResponse::response(401, ['TYPE' => 'ERROR', 'MESSAGE' => '401 Unauthorized'],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        if ($USER->GetID()){
            return $this->toggleFavoriteAuthorized($productId, $USER->GetID());
        }

        return $this->toggleFavoriteGuest($productId);
    }

    protected function getFilterByIblockId($filter, $iblockId)
    {
        $ibFilter = [];

        $props = new Collection($this->propsService->getProps(['iblock' => $iblockId]));

        foreach($filter as $propCode => $propValue) {
            $arPropCode = explode('-', $propCode);
            if ($arPropCode[0] == 'prop' && $props->where('ID', $arPropCode[1])->count()) {
                $ibFilter[$propCode] = $propValue;
            }

            if ($propCode == 'section_id')
                $ibFilter[$propCode] = $propValue;
        }

        return $ibFilter;
    }

    protected function buildFilterProductArr(&$query, $filter)
    {
        $newFilter = $this->getFilterByIblockId($filter, $this->productIblockId);
        $this->appleFilter($query, $newFilter, $this->productIblockId);
        unset($filter['section_id']);
        $newOfferFilter = $this->getFilterByIblockId($filter, $this->offerIblockId);

        if (count($newOfferFilter)) {
            $offers = new Collection($this->getProductSkuList(0, $newOfferFilter));

            if (count($offers)) {
                return $offers->pluck('PARENT_ID')->unique()->toArray();
            }
        }

        return [];
    }

    /**
     * Получение данных об основном товаре
     *
     * @param Int $productId
     * @return array|false
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getProductArr($productId, $filter, $sort, $pagination, $useFilter = true){
        $query = \Bitrix\Catalog\ProductTable::query();
        $query->setSelect([
            'ID',
            'NAME' => 'IBLOCK_ELEMENT.NAME',
            'DETAIL_PICTURE' => 'IBLOCK_ELEMENT.DETAIL_PICTURE',
            'DETAIL_TEXT' => 'IBLOCK_ELEMENT.DETAIL_TEXT',
            'PREVIEW_TEXT' => 'IBLOCK_ELEMENT.PREVIEW_TEXT',
            'PREVIEW_PICTURE' => 'IBLOCK_ELEMENT.PREVIEW_PICTURE',
            'PRICE' => 'PRODUCT_PRICE.PRICE',
            'AVAILABLE',
            'QUANTITY',
            'TYPE',
        ]);

        if (!is_array($productId) && !empty($productId))
            $productId = [$productId];

        if ($useFilter)
            $productId = array_merge($productId,$this->buildFilterProductArr($query, $filter));

        $query->addFilter('IBLOCK_ELEMENT.ACTIVE', 'Y');
        if (!empty($productId))
            $query->addFilter('ID', $productId);

        $query->registerRuntimeField('PRODUCT_PRICE', [
            'data_type' => PriceTable::class,
            'reference' => ['=this.ID' => 'ref.PRODUCT_ID'],
            'join_type' => "inner"
        ]);

        $this->appendPagination($query, $pagination);

        $this->setSortQuery($query, $sort, $productId);

        $query->setCacheTtl($this->ttl);

        $exec = $query->exec();

        if (count($pagination))
            $pagination['count'] = $exec->getCount();

        $res = $exec->fetchAll();

        if (count($pagination)) {
            return ['items' => $res, 'pagination' => $pagination];
        }

        return ['items' => $res];
    }

    /**
     * @param $query EO_Product_Query
     * @throws \Bitrix\Main\SystemException
     */
    protected function setSortQuery(&$query,$sort, $productId)
    {
        if (isset($sort['sort'])) {
            $this->appendSort($query, $sort);

            return;
        }

        switch ($this->sortType) {
            case '':
                $this->appendSort($query, ['sort' => 'prop-133', 'order' => 'DESC']);
                $this->appendSort($query, ['sort' => 'NAME', 'order' => 'DESC']);
                $this->appendSort($query, ['sort' => 'ID', 'order' => 'DESC']);

                break;

            case 'search':
                if (is_array($productId)) {
                    $this->appendSort($query, ['sort' => 'ID', 'order' => 'ASC']);
//                    $query->setOrder('IBLOCK_ELEMENT.ID', implode(', ', $productId));
                }
                break;
        }
    }

    public function indexByIds($ids) {
        return $this->getProductArr($ids, [], [], [], false);
    }

    public function indexParentImgByIds($ids) {
        $elements = $this->getProductArr($ids, [], [], [], false);

        $offersIds = [];
        foreach($elements['items'] as $element) {
            if ($element['TYPE'] == 4) {
                $offersIds[] = $element['ID'];
            }
        }

        if (count($offersIds)) {
            $parentIds = (new Collection($this->getProductSkuList(0, [], $offersIds)))->pluck('PARENT_ID', 'ID')->toArray();
            $parents = new Collection($this->getProductArr($parentIds, [], [], [], false)['items']);
            foreach($elements['items'] as &$element) {
                if ($element['TYPE'] == 4) {
                    $element['PREVIEW_PICTURE'] = $parents->where('ID', $parentIds[$element['ID']])->first()['PREVIEW_PICTURE'];
                }
            }
        }

        return $elements;
    }


    private function getParentIds($ids) {

    }

    protected function appendPagination(Query &$query, $pagination): void
    {
        if (isset($pagination['limit']) && $pagination['limit'] !== null)
            $query->setLimit($pagination['limit']);
        if (isset($pagination['offset']))
            $query->setOffset($pagination['offset']);
        $query->countTotal(true);
    }

    protected function appendSort(Query &$query, $sort): void
    {
        $arSort = explode('-', $sort['sort']);
        if ($arSort[0] == 'prop') {
            $props = $this->propsService->getProps(['propsIds' => $arSort[1]]);
            $prop = array_shift($props);

            $query->registerRuntimeField('PRODUCT_PROPS_VALUE_SORT', [
                'data_type' => ElementPropertyTable::class,
                'reference' => ['=this.ID' => 'ref.IBLOCK_ELEMENT_ID', 'ref.IBLOCK_PROPERTY_ID' => new SqlExpression($arSort[1])],
                'join_type' => "LEFT"
            ]);

            if ($prop['PROPERTY_TYPE'] == PropertyTable::TYPE_LIST) {
                $query->registerRuntimeField('PRODUCT_PROPS_VALUE_ENUM_SORT', [
                    'data_type' => PropertyEnumerationTable::class,
                    'reference' => ['=ref.ID' => new SqlExpression('catalog_product_product_props_value_sort.VALUE_ENUM')],
                    'join_type' => "LEFT"
                ]);
                $query->addOrder('PRODUCT_PROPS_VALUE_ENUM_SORT.VALUE', $sort['order']);
            } elseif ($prop['PROPERTY_TYPE'] == PropertyTable::TYPE_NUMBER)
                $query->addOrder('PRODUCT_PROPS_VALUE_SORT.VALUE_NUM', $sort['order']);
            else
                $query->addOrder('PRODUCT_PROPS_VALUE_SORT.VALUE', $sort['order']);
        }
        else {
            $query->addOrder(mb_strtoupper($sort['sort']), $sort['order']);
        }
    }

    protected function appleFilter(Query &$query, array $filter, $iblock): void
    {
        if (isset($filter['section_id'])) {
            $query->addFilter('IBLOCK_ELEMENT.IBLOCK_SECTION.ID', $filter['section_id']);
        }

        $query->addFilter('IBLOCK_ELEMENT.ACTIVE', 'Y');
        $query->addFilter('IBLOCK_ELEMENT.IBLOCK_ID', $iblock);

        $props = new Collection($this->propsService->getProps(['iblock' => $iblock]));

        foreach($filter as $propCode => $propValue) {
            $arPropCode = explode('-', $propCode);
            if ($arPropCode[0] == 'prop' && $props->where('ID', $arPropCode[1])->count()) {

                $queryValue = 'AND catalog_product_product_props_value.VALUE = "' . $propValue . '"';
                if (is_array($propValue)) {
                    $queryValue = 'AND catalog_product_product_props_value.VALUE IN ("' . implode('","', $propValue) . '")';
                } elseif (isset($arPropCode[2])) {
                    switch($arPropCode[2]) {
                        case 'lt':
                            $queryValue = 'AND catalog_product_product_props_value.VALUE < ' . $propValue . '';
                            break;
                        case 'le':
                            $queryValue = 'AND catalog_product_product_props_value.VALUE <= ' . $propValue . '';
                            break;
                        case 'ne':
                            $queryValue = 'AND catalog_product_product_props_value.VALUE <> ' . $propValue . '';
                            break;
                        case 'ge':
                            $queryValue = 'AND catalog_product_product_props_value.VALUE >= ' . $propValue . '';
                            break;
                        case 'gt':
                            $queryValue = 'AND catalog_product_product_props_value.VALUE > ' . $propValue . '';
                            break;
                    }
                }

                $sqlExp = new SqlExpression('
                    SELECT * 
                    FROM b_iblock_element_property as catalog_product_product_props_value
                    WHERE `catalog_product`.`ID` = `catalog_product_product_props_value`.`IBLOCK_ELEMENT_ID` 
                      AND catalog_product_product_props_value.IBLOCK_PROPERTY_ID = "' . $arPropCode[1] . '"' .
                    $queryValue . '
                      '
                );
                $query->whereExists($sqlExp);
            }
        }
    }

    /**
     * получение списка товарных предложений для основного товара
     *
     * @param Int $productId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getProductSkuList($productId = 0, $filter = [], $offerIds = []){
        $query = ProductTable::query();
        $query->setSelect([
            'ID',
            'NAME' => 'IBLOCK_ELEMENT.NAME',
            'DETAIL_PICTURE' => 'IBLOCK_ELEMENT.DETAIL_PICTURE',
            'DETAIL_TEXT' => 'IBLOCK_ELEMENT.DETAIL_TEXT',
            'PREVIEW_TEXT' => 'IBLOCK_ELEMENT.PREVIEW_TEXT',
            'PREVIEW_PICTURE' => 'IBLOCK_ELEMENT.PREVIEW_PICTURE',
            'PRICE' => 'PRODUCT_PRICE.PRICE',
            'AVAILABLE',
            'QUANTITY',
            'SKU_NAME' => 'IBLOCK_ELEMENT.NAME',
            'PARENT_ID' => 'PRODUCT_SKU_LINK.VALUE',
        ]);

        if (count($filter))
            $this->appleFilter($query, $filter, $this->offerIblockId);

        if (count($offerIds))
            $query->addFilter('ID', $offerIds);

        if (!empty($productId)) {
            $query->addFilter('PRODUCT_SKU_LINK.VALUE', $productId);
        }

        $query->setCacheTtl($this->ttl);

        if (!empty($this->skuLinkId)) {
            $query->registerRuntimeField('PRODUCT_SKU_LINK', [
                'data_type' => ElementPropertyTable::class,
                'reference' => ['=this.ID' => 'ref.IBLOCK_ELEMENT_ID', 'ref.IBLOCK_PROPERTY_ID' => new SqlExpression($this->skuLinkId)],
                'join_type' => "LEFT"
            ]);
        }
        $query->registerRuntimeField('PRODUCT_PRICE', [
            'data_type' => PriceTable::class,
            'reference' => ['=this.ID' => 'ref.PRODUCT_ID'],
            'join_type' => "LEFT"
        ]);
        $offers = $query->exec()->fetchAll();

        if ($this->withProps) {
            $offers = $this->attachProps($offers);
        }

        return $offers;
    }

    protected function attachProps($elements)
    {
        $elementIds = (new Collection($elements))->pluck('ID')->toArray();
        $props = $this->propsService->getPropsValueByElements($elementIds);

        foreach($elements as &$element) {
            $element['PROPS'] = $props[$element['ID']];
        }

        return $elements;
    }

    private function buildDataSort(array $data): array
    {
        $sort = [];
        if (isset($data['sort'])) {
            $sort['sort'] = $data['sort'];
            unset($data['sort']);
        }
        if (isset($data['order'])) {
            $sort['order'] = $data['order'];
            unset($data['order']);
        }

        return $sort;
    }

    private function buildDataPagination(array $data): void
    {
        if (isset($data['limit'])) {
            $this->pagenation['limit'] = $data['limit'];
            unset($data['limit']);
        }
        if (isset($data['offset'])) {
            $this->pagenation['offset'] = $data['offset'];
            unset($data['offset']);
        }
    }

    /**
     * Добавление/удаление избранного товара для авторизованного пользователя
     *
     * @param Int $productId
     * @param int $userId
     * @return array
     */
    private function toggleFavoriteAuthorized(Int $productId, int $userId){

        global $USER;
        $rsUser = \CUser::GetByID($userId);
        $arUser = $rsUser->Fetch();
        $arElements = $arUser['UF_FAVORITES'];

        if (!in_array($productId, $arElements))
        {
            $arElements[] = $productId;
            $USER->Update($userId, Array("UF_FAVORITES" => $arElements));

            return [
                'success' => true,
                'message' => 'Product №' . $productId . ' added to favorites!'
            ];
        }

        $key = array_search($productId, $arElements);
        unset($arElements[$key]);
        $USER->Update($userId, Array("UF_FAVORITES" => $arElements));

        return [
            'success' => true,
            'message' => 'Product №' . $productId . ' removed from favorites!'
        ];
    }

    /**
     * Добавление/удаление избранного товара для не авторизованного пользователя
     *
     * @param Int $productId
     * @return array
     */
    private function toggleFavoriteGuest(Int $productId){
        $session = \Bitrix\Main\Application::getInstance()->getSession();
        if (!$session->has('favorites'))
        {
            $session->set('favorites', ['products' => [$productId]]);
            return [
                'success' => true,
                'message' => 'Product №' . $productId . ' added to favorites!'
            ];
        }
        $favorites = $session->get('favorites');
        if (!in_array($productId, $favorites['products'])){
            $favorites['products'][] = $productId;
            $session->set('favorites', $favorites);
            return [
                'success' => true,
                'message' => 'Product №' . $productId . ' added to favorites!'
            ];
        }

        $key = array_search($productId, $favorites['products']);
        unset($favorites['products'][$key]);
        $session->set('favorites', $favorites);

        return [
            'success' => true,
            'message' => 'Product №' . $productId . ' removed from favorites!'
        ];
    }

    public function favoriteIndex(HttpRequest $request)
    {
        $arElements = $this->getFavoriteIds();
        $values = $request->getValues();
        $values = array_merge($values, ['id' => $arElements]);

        if (count($arElements)) {
            return (new ProductService)->index($values);
        }

        return [];
    }

    public function favoriteIndexIds()
    {
        return $this->getFavoriteIds();
    }

    public function getFavoriteIds(): array
    {
        global $USER;

        $session = \Bitrix\Main\Application::getInstance()->getSession();
        $arElements = [];

        if ($USER->GetID()) {
            $rsUser = \CUser::GetByID($USER->GetID());
            $arUser = $rsUser->Fetch();
            $arElements = $arUser['UF_FAVORITES'];
        } elseif ($session->has('favorites')) {
            $favorites = $session->get('favorites');
            $arElements = $favorites['products'];
        }

        return $arElements;
    }
}
