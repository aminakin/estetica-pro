<?php

namespace ApiLib\API\Brand;

use ApiLib\API\Prop\PropService;
use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Type\DateTime;
use Illuminate\Support\Collection;

class BrandService
{
    private PropService $propsService;

    public function __construct()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $this->propsService = new PropService();
    }

    public function index()
    {
        $query = ElementTable::query();

        $query->setSelect(['ID', 'NAME', 'CODE', 'ACTIVE_TO', 'ACTIVE_FROM', 'SORT']);
        $query->setFilter([
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => (int)BitrixSettings::getApiVarByName('BRAND_ID'),
            [
                'LOGIC' => 'OR',
                'ACTIVE_TO' => null,
                '>=ACTIVE_TO' => new DateTime(),
            ],
            [
                'LOGIC' => 'OR',
                'ACTIVE_FROM' => null,
                '<=ACTIVE_FROM' => new DateTime(),
            ]
        ]);
        $query->setOrder([
            'ACTIVE_FROM' => "ASC"
        ]);

        $exec = $query->exec();
        $brands = $exec->FetchAll();
        $brands = $this->attachProps($brands);

        return $brands;
    }

    public function attachProps($brands)
    {
        $brandIds = (new Collection($brands))->pluck('ID')->toArray();
        $props = $this->propsService->getPropsValueByElements($brandIds);

        foreach($brands as &$element) {
            $element['PROPS'] = $props[$element['ID']];
        }

        return $brands;
    }
}
