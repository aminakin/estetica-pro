<?php

namespace ApiLib\API\Search;

use ApiLib\API\Product\ProductService;
use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Main\HttpRequest;
use Illuminate\Support\Collection;

class SearchService
{
    private int $ttl = 0;

    private int $iblockId;


    private ProductService $service;

    public function __construct()
    {
        $this->ttl = (int)BitrixSettings::getApiVarByName('TTL');
        \CModule::IncludeModule("search");
        $this->iblockId = (int)BitrixSettings::getApiVarByName('CATALOG_ID');
        $this->service = new ProductService();
    }

    public function setIblockId(int $id)
    {
        $this->iblockId = $id;
    }

    public function index(array $request)
    {
        $querySearch = new \CSearch();
        $querySearch->SetOptions(array( // чтобы не ругался на форматирование запроса
            'ERROR_ON_EMPTY_STEM' => false,
        ));
        $querySearch->Search(array(
            'QUERY' => $request['q'],
            'SITE_ID' => SITE_ID,
            'MODULE_ID' => 'iblock',
            'PARAM2' => $this->iblockId
        ));
        if (!$querySearch->selectedRowsCount()) { // и делаем резапрос, если не найдено с морфологией...
            $querySearch->Search(array(
                'QUERY' => $request['q'],
                'SITE_ID' => SITE_ID,
                'MODULE_ID' => 'iblock',
                'PARAM2' => $this->iblockId
            ), array(), array('STEMMING' => false));// уже с отключенной морфологией
        }

        while ($row = $querySearch->fetch()) {
            $resultId[] = $row['ITEM_ID'];
        }

        if (count($resultId))
            $request['id'] = $resultId;
        else
            $request['id'] = [0];

        global $APPLICATION;
        $component = new \CBitrixComponent();

        $arElements = $APPLICATION->IncludeComponent(
            "bitrix:search.page",
            "",
            array (
                'RESTART' => 'Y',
                'NO_WORD_LOGIC' => 'N',
                'USE_LANGUAGE_GUESS' => 'N',
                'CHECK_DATES' => 'N',
                'arrFILTER' =>
                    array (
                        0 => 'iblock_catalog',
                    ),
                'arrFILTER_iblock_catalog' =>
                    array (
                        0 => '14',
                    ),
                'USE_TITLE_RANK' => 'N',
                'DEFAULT_SORT' => 'rank',
                'FILTER_NAME' => '',
                'SHOW_WHERE' => 'N',
                'arrWHERE' =>
                    array (
                    ),
                'SHOW_WHEN' => 'N',
                'PAGE_RESULT_COUNT' => 999,
                'DISPLAY_TOP_PAGER' => 'N',
                'DISPLAY_BOTTOM_PAGER' => 'N',
                'PAGER_TITLE' => '',
                'PAGER_SHOW_ALWAYS' => 'N',
                'PAGER_TEMPLATE' => 'N',
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
        ob_clean();

        $arElements = array_values($arElements);

        $colElements = Collection::make($arElements);

        if (!$colElements->count()) {
            return [
                "items" => [],
                "pagination" => [
                    "limit" => $request['limit'] ?? 30,
                    "offset" => $request['offset'] ?? 0,
                    "count" => 0
                ]
            ];
        }

        $limit = $request['limit'];
        $offset = $request['offset'];
        $sliceElements = $colElements->slice($offset, $limit)->toArray();
        $request['id'] = $sliceElements;
        $request['limit'] = false;
        $request['offset'] = false;
        $this->service->setSortType('search');

        $products = $this->service->index($request);
        $colProducts = Collection::make($products['items']);



        $colProducts = $colProducts->sortBy(function($product, $key) use ($sliceElements) {
            return array_search($product['ID'], $sliceElements);
        });

        $products['items'] = $colProducts->slice($offset, $limit)->toArray();
        $products['pagination']['limit'] = $limit;
        $products['pagination']['offset'] = $offset;
        $products['pagination']['count'] = $colElements->count();

        return $products;
    }
}
