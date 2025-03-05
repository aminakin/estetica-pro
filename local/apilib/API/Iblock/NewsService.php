<?php

namespace ApiLib\API\Iblock;

use ApiLib\API\Iblock\NewsReviewsService;
use ApiLib\API\Prop\PropService;
use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Iblock\ElementPropertyTable;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;

class NewsService
{
    /**
     * Переменные для работы запросов
     * @var array
     */
    private array $const = [];

    function __construct()
    {
        Loader::includeModule('iblock');

        if (!isset($this->const['IBLOCK_ID']))
            $this->const['IBLOCK_ID'] = BitrixSettings::getApiVarByName('NEWS_ID');

        if (!isset($this->const['TTL']))
            $this->const['TTL'] = (int)BitrixSettings::getApiVarByName('TTL');;
    }

    /**
     * Получение списка новостей с комментариями
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getNews()
    {
        $iblock = Iblock::wakeUp($this->const['IBLOCK_ID'])->getEntityDataClass();

        $elements = $iblock::getList([
            'select' => ['ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'TIMESTAMP_X', 'ACTIVE_FROM', 'ACTIVE_TO', 'SORT'],
            'filter' => [
                'IBLOCK_ID' => $this->const['IBLOCK_ID'],
                '=ACTIVE' => 'Y',
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
            ],
            'order' => ['ACTIVE_FROM' => 'DESC', 'SORT' => 'ASC'],
            'cache' => [
                'ttl' => $this->const['TTL'],
            ]
        ])->fetchAll();

        $NewsReviewsService = new NewsReviewsService([]);
        foreach ($elements as &$element) {
            $propRes = ElementPropertyTable::getList(array(
                'select' => ['ID', '*'],
                'filter' => ['IBLOCK_ELEMENT_ID' => $element['ID']],
                'order'  => ['ID' => 'ASC']
            ));
            while($prop = $propRes->Fetch()) {
                $element['PROPERTIES'][$prop['IBLOCK_PROPERTY_ID']][] = $prop['VALUE'];
            }

            $element['PREVIEW_PICTURE'] = \CFile::GetPath($element['PREVIEW_PICTURE']);
            $element['COMMENTS'] = $NewsReviewsService->getNewsReviews($element['ID']);
        }
        unset($element);

        return $elements;
    }

}
