<?php

namespace ApiLib\API\Prop;

use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Illuminate\Support\Collection;

class PropService
{
    private int $ttl = 0;

    public function __construct()
    {
        $this->ttl = (int)BitrixSettings::getApiVarByName('TTL');
        \Bitrix\Main\Loader::includeModule('iblock');
    }

    public function getPropsValueByElements(array $elementIds): array
    {
        if (!count($elementIds))
            return [];

        $query = ElementPropertyTable::query();
        $query->addSelect('ID');
        $query->addSelect('*');
        $query->addFilter('=IBLOCK_ELEMENT_ID', $elementIds);
        $query->setCacheTtl($this->ttl);
        $arValues = $query->exec()->fetchAll();
        $values = new Collection($arValues);

        $propIds = $values->pluck('IBLOCK_PROPERTY_ID')->unique()->toArray();
        $enumIds = $values->where('VALUE_ENUM', '!==', null)->pluck('VALUE_ENUM')->unique()->toArray();
        $arEnumValues = $this->getEnumValue($enumIds);
        $enumValues = new Collection($arEnumValues);
        $values = $values->groupBy('IBLOCK_ELEMENT_ID');

        $arProps = $this->getProps(['propsIds' => $propIds]);
        $props = new Collection($arProps);

        $values = $values->map(function($item, $key) use($props, $enumValues) {
            $itemCollection = new Collection($item);
            $itemCollection = $itemCollection->groupBy('IBLOCK_PROPERTY_ID')
                ->map(function($val, $keyVal) use($props, $enumValues) {
                    $val = $val->map(function($el) use($enumValues) {
                        if (!empty($el['VALUE_ENUM'])) {
                            $el['VALUE'] = $enumValues->where('ID', $el['VALUE_ENUM'])->first()['VALUE'];
                        }

                        return $el;
                    });

                    $prop = $props->where('ID', $keyVal)->first();
                    $prop['VALUES'] = $val;

                    return $prop;
                });

            return $itemCollection;
        });

        return $values->toArray();
    }

    public function getProps(array $filter, $withEnums = false): array //$propsIds = [], $iblock = 0): array
    {
        $query = PropertyTable::query();
        $query->addSelect('ID');
        $query->addSelect('*');
        if (count($filter['propsIds']))
            $query->addFilter('=ID', $filter['propsIds']);
        if (!empty($filter['iblock']))
            $query->addFilter('IBLOCK_ID', $filter['iblock']);
        if (!empty($filter['code']))
            $query->addFilter('CODE', $filter['code']);
        $query->addFilter('=ACTIVE', 'Y');
        $query->setCacheTtl($this->ttl);

        if ($withEnums) {
            $props = new Collection($query->exec()->fetchAll());

            $props = $props->map(function($item, $key) {
                if ($item['PROPERTY_TYPE'] == PropertyTable::TYPE_LIST)
                    $item['VALUES'] = $this->getEnums($item['ID']);

                return $item;
            })->toArray();

            return $props;
        }

        return $query->exec()->fetchAll();
    }

    public function getEnumValue(array $enumIds): array
    {
        $query = PropertyEnumerationTable::query();
        $query->addSelect('ID');
        $query->addSelect('*');
        $query->addFilter('=ID', $enumIds);
        $query->setCacheTtl($this->ttl);

        return $query->exec()->fetchAll();
    }

    public function getEnums($propId): array
    {
        $query = PropertyEnumerationTable::query();
        $query->addSelect('ID');
        $query->addSelect('*');
        $query->addFilter('=PROPERTY_ID', $propId);
        $query->setCacheTtl($this->ttl);

        return $query->exec()->fetchAll();
    }
}