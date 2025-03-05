<?php

namespace ApiLib\API\NewProduct;

use ApiLib\API\Product\ProductService;
use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Catalog\PriceTable;
use Bitrix\Iblock\ElementTable;
use Illuminate\Support\Collection;

class NewProductService extends ProductService
{
    private int $newCatalogIblockId;

    private int $propId;

    public function __construct(int $ttl = 86400)
    {
        parent::__construct($ttl);

        $this->newCatalogIblockId = (int)BitrixSettings::getApiVarByName('NEW_CATALOG_ID'); //26
        $this->propId = 228;
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

        // где то тут внедряемся в код для проверки на section_id и выуживания IDS товаров
        if ($useFilter) {
            $sectionId = 0;
            if (isset($filter['section_id'])) {
                $sectionId = $filter['section_id'];
                unset($filter['section_id']);
            }

            if ($sectionId !== 0) {
                $newProductId = $this->getProductIdsByNewCatalogSection($sectionId);
                $productId = array_merge($productId, $newProductId);
                if (count($productId) == 0)
                    $productId = [0];
//                var_export($productId); die();
            }

            $productId = array_merge($productId, $this->buildFilterProductArr($query, $filter));
        }

        $query->addFilter('IBLOCK_ELEMENT.ACTIVE', 'Y');
        if (!empty($productId))
            $query->addFilter('ID', $productId);

        $query->registerRuntimeField('PRODUCT_PRICE', [
            'data_type' => PriceTable::class,
            'reference' => ['=this.ID' => 'ref.PRODUCT_ID'],
            'join_type' => "inner"
        ]);

        $query->setOrder(['IBLOCK_ELEMENT.SORT' => 'ASC', 'IBLOCK_ELEMENT.ID' => 'DESC']);

        $this->appendPagination($query, $pagination);

        if (isset($sort['sort']))
            $this->appendSort($query, $sort);

        $query->setCacheTtl($this->getTtl());

        $exec = $query->exec();

        if (count($pagination))
            $pagination['count'] = $exec->getCount();

        $res = $exec->fetchAll();

        if (count($pagination)) {
            return ['items' => $res, 'pagination' => $pagination];
        }

        return ['items' => $res];
    }

    private function getProductIdsByNewCatalogSection($sectionId): array
    {
        $newElement = [];
        $query = ElementTable::query();
        $query->setSelect(['ID']);
        if ($sectionId != 0)
            $query->addFilter('IBLOCK_SECTION.ID', $sectionId);
        $query->addFilter('ACTIVE', 'Y');
        $query->addFilter('IBLOCK_ID', $this->newCatalogIblockId);
        $elements = $query->exec()->fetchAll();

        $elements = $this->attachProps($elements);
        foreach ($elements as $element) {
            $propProp = $element['PROPS'][$this->propId];
            /** @var Collection $col */
            $col = $propProp['VALUES'];
            if ($col !== null)
                $newElement[] = $col->first()['VALUE'];
        }

        return $newElement;
    }



//    private function buildFilterProductArr(&$query, $filter)
//    {
//        $newFilter = $this->getFilterByIblockId($filter, $this->productIblockId);
//        $this->appleFilter($query, $newFilter, $this->productIblockId);
//        unset($filter['section_id']);
//        $newOfferFilter = $this->getFilterByIblockId($filter, $this->offerIblockId);
//
//        if (count($newOfferFilter)) {
//            $offers = new Collection($this->getProductSkuList(0, $newOfferFilter));
//
//            if (count($offers)) {
//                return $offers->pluck('PARENT_ID')->unique()->toArray();
//            }
//        }
//
//        return [];
//    }
}
