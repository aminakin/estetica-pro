<?php

namespace ApiLib\API\Iblock;


use Bitrix\Iblock\Iblock;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

class SliderService
{
    /**
     * Переменные для работы запросов
     * @var array
     */
    private array $const = [];

    public function __construct(int $IBLOCK_ID, array $properties)
    {
        Loader::includeModule('iblock');

        if (!isset($this->const['IBLOCK_ID']))
            $this->const['IBLOCK_ID'] = $IBLOCK_ID;

        if (!isset($this->const['PROPERTIES']))
            $this->const['PROPERTIES'] = $properties;

        if (!isset($this->const['TTL']))
            $this->const['TTL'] = 86400;
    }

    /**
     * Получение слайдера
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getSlider()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $iblock = Iblock::wakeUp($this->const['IBLOCK_ID'])->getEntityDataClass();

        $selectArr = [
            'ID', 'NAME', 'PREVIEW_PICTURE', 'ACTIVE_FROM', 'ACTIVE_TO', 'SORT'
        ];

        foreach ($this->const['PROPERTIES'] as $prop) {
            $selectArr[$prop . '_'] = $prop;
        }

        $elements = $iblock::getList([
            'select' => $selectArr,
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

        foreach ($elements as &$element) {
            $element['PREVIEW_PICTURE'] = \CFile::GetPath($element['PREVIEW_PICTURE']);
        }
        unset($element);

        return $elements;
    }

}
