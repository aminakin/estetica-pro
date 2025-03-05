<?php

namespace ApiLib\API\NewSection;

use ApiLib\API\Section\SectionTreeService;
use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Loader;

class NewSectionTreeService extends SectionTreeService
{
    public function __construct(array $const = [])
    {
        parent::__construct($const);

        $this->setConstValue('IBLOCK_ID', (int)BitrixSettings::getApiVarByName('NEW_CATALOG_ID'));
    }

    public function index(int $parent = 0)
    {
        $result = $this->getSectionTable()->getList([
            'select' => ['ID', 'NAME', 'PICTURE', 'SORT', 'DESCRIPTION', 'DESCRIPTION_TYPE', 'PARENT_SECTION.ID'],
            'filter' => [
                '=PARENT_SECTION.ID' => $parent,
                '=IBLOCK_ID' => $this->getConstValue('IBLOCK_ID'),
                '=ACTIVE' => 'Y',
                '=GLOBAL_ACTIVE' => 'Y',
            ],
            'order' => ['NAME' => 'ASC'],
            'cache' => [
                'ttl' => $this->getConstValue('TTL'),
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
