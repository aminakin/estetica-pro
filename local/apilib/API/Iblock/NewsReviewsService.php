<?php

namespace ApiLib\API\Iblock;

use ApiLib\API\Settings\BitrixSettings;
use \Bitrix\Iblock\ElementPropertyTable;

use Bitrix\Iblock\Iblock;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;

class NewsReviewsService
{
    /**
     * Переменные для работы запросов
     * @var array
     */
    private array $const = [];


    function __construct(array $parameters = [])
    {
        Loader::includeModule('iblock');

        if (!isset($this->const['IBLOCK_ID']))
            $this->const['IBLOCK_ID'] = (int)BitrixSettings::getApiVarByName('NEWS_COMMENTS_ID');

        if (isset($parameters['linkPropId']))
            $this->propReviewsToNewsLinkId = $parameters['linkPropId'];
    }

    /**
     * Получение комментариев к указанной новости
     *
     * @param int $newsId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getNewsReviews(int $newsId = 0)
    {
        if (empty($newsId)) return [];

        $iblock = Iblock::wakeUp($this->const['IBLOCK_ID'])->getEntityDataClass();

        $elements = $iblock::getList([
            'select' => ['ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'TIMESTAMP_X', 'NEWS_' => 'NEWS'],
            'filter' => [
                'NEWS_IBLOCK_GENERIC_VALUE' => $newsId,
                'IBLOCK_ID' => $this->const['IBLOCK_ID'],
                '=ACTIVE' => 'Y'
            ]
        ])->fetchAll();

        foreach ($elements as &$element) {
            $element['PREVIEW_PICTURE'] = \CFile::GetPath($element['PREVIEW_PICTURE']);
        }
        unset($element);

        return $elements;
    }

    /**
     * Добавление комментария к указанной новости
     *
     * @param HttpRequest $request
     * @return \Bitrix\Main\ORM\Data\AddResult|false
     */
    public function addNewsReview(HttpRequest $request)
    {
        if (empty($request->get('news_id'))) return false;

        $iblock = Iblock::wakeUp($this->const['IBLOCK_ID'])->getEntityDataClass();
        try {
            $element = $iblock::add([
                "fields" => array(
                    "NAME" => $request->get('comment_title'),
                    "PREVIEW_TEXT" => $request->get('comment_text'),
                ),
            ]);
            ElementPropertyTable::add([
                'IBLOCK_PROPERTY_ID' => $this->propReviewsToNewsLinkId,
                'IBLOCK_ELEMENT_ID' => $element->getId(),
                'VALUE' => $request->get('news_id')
            ]);
            return $element;
        } catch (\Exception $e) {
            return false;
        }
    }
}