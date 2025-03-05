<?php

namespace ApiLib\API\Section;

use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;

class SectionTreeService
{
    private SectionTable $sectionTable;

    private array $const = [];

    public function setConstValue($key, $value)
    {
        $this->const[$key] = $value;
    }

    public function getConstValue($key, $default = '')
    {
        return empty($this->const[$key]) ? $default : $this->const[$key];
    }

    public function getSectionTable()
    {
        return $this->sectionTable;
    }

    public function __construct(array $const = [])
    {
        Loader::includeModule('iblock');
        $this->sectionTable = new SectionTable();

        $this->const = $const;
        if (!isset($this->const['IBLOCK_ID']))
            $this->const['IBLOCK_ID'] = (int)BitrixSettings::getApiVarByName('CATALOG_ID');
        if (!isset($this->const['TTL']))
            $this->const['TTL'] = (int)BitrixSettings::getApiVarByName('TTL');

    }

    public function index(int $parent = 0)
    {
        $result = $this->sectionTable->getList([
            'select' => ['ID', 'NAME', 'PICTURE', 'SORT', 'DESCRIPTION', 'DESCRIPTION_TYPE', 'PARENT_SECTION.ID'],
            'filter' => [
                '=PARENT_SECTION.ID' => $parent,
                '=IBLOCK_ID' => $this->const['IBLOCK_ID'],
                '=ACTIVE' => 'Y',
                '=GLOBAL_ACTIVE' => 'Y',
            ],
            'order' => ['SORT' => 'ASC'],
            'cache' => [
                'ttl' => $this->const['TTL'],
            ],
        ]);

        $sections = [];
        while($section = $result->fetch()) {
            $section['CHILDREN'] = $this->index($section['ID']);
            $section['PICTURE'] = \CFile::GetPath($section['PICTURE']);
            $sections[] = $section;
        }

        return $sections;
    }
}
