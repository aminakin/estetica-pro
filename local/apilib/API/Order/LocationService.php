<?php

namespace ApiLib\API\Order;

use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\HttpRequest;
use Bitrix\Sale\Location\LocationTable;
use Bitrix\Sale\Location\Search\Finder;
use Bitrix\Main\Loader;
use Illuminate\Support\Collection;

class LocationService
{
    private Cache $cache;

    private int $ttl;

    public function __construct()
    {
        Loader::includeModule('sale');
        $this->cache = Cache::createInstance();
        $this->ttl = (int)BitrixSettings::getApiVarByName('TTL');
    }

    public function index(HttpRequest $request)
    {
        $locations = [];
        $uniqStr = '';
        if (!empty($request->get('q'))) {
            $parameters['filter']['=PHRASE'] = $request->get('q');
            $uniqStr .= $parameters['filter']['=PHRASE'];
        }
        if (!empty($request->get('code'))) {
            $parameters['filter']['=CODE'] = $request->get('code');
            $uniqStr .= $parameters['filter']['=CODE'];
        }
        if (!empty($request->get('id'))) {
            $parameters['filter']['=ID'] = $request->get('id');
            $uniqStr .= $parameters['filter']['=CODE'];
        }
        $parameters['limit'] = $request->get('limit') ?? 10 ;
        $parameters['offset'] = $request->get('offset') ?? 0;

        $countParams = $parameters;
        unset($countParams['limit']);
        $resultCount = Finder::find($countParams);
        $allElementIds = $resultCount->fetchAll();
        $elementCount = count($allElementIds);

        if ($this->cache->initCache($this->ttl, $uniqStr . $parameters['filter']['=PHRASE'] . $parameters['limit'] . $parameters['offset'], '/')) {
            $locations = $this->cache->getVars();
        }
        elseif ($this->cache->startDataCache()) {
            $result = Finder::find($parameters);

            $locationIds = [];
            while ($item = $result->fetch()) {
                // hack to repair ORM
                if (!isset($item['ID']))
                    $item['ID'] = $item['VALUE'];
                $locationIds[] = $item['ID'];
            }

            $locations = $this->getLocationById($locationIds);

            $parents = $this->getLocationParents($locations);
            foreach ($locations as &$location) {
                $location['PARENTS'] = $parents[$location['ID']];
            }
            $this->cache->endDataCache($locations);
        }

        return ['items' => $locations, 'pagination' => ['limit' => $parameters['limit'], 'offset' => $parameters['offset'], 'count' => $elementCount]];
    }

    private function getLocationById($id)
    {
        $query = LocationTable::query();
        $query->whereIn('ID', $id);
        $query->where('NAME.LANGUAGE_ID', LANGUAGE_ID);
        $query->setSelect(['*', 'NAME_RU' => 'NAME.NAME', 'IS_PARENT',]);

        return $query->exec()->fetchAll();
    }

    private function getLocationParents($list)
    {
        $res = LocationTable::getPathToMultipleNodes(
            $list,
            array(
                'select' => ['*', 'NAME_RU' => 'NAME.NAME'],
                'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID)
            )
        );

        $items = [];

        while($item = $res->fetch()) {
            $parentNames = [];
            foreach($item['PATH'] as $id => $pathItem) {
                if ($id != $item['ID'])
                    $parentNames[] = $pathItem['NAME_RU'];
            }
            $items[$item['ID']] = implode(', ', $parentNames);
        }

        return $items;
    }
}