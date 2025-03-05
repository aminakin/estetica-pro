<?php

namespace ApiLib\API\NewSection;

use ApiLib\API\Section\SectionService;
use ApiLib\API\Settings\BitrixSettings;

class NewSectionService extends SectionService
{
    public function __construct(array $const = [])
    {
        parent::__construct($const);

        $this->setConstValue('IBLOCK_ID', (int)BitrixSettings::getApiVarByName('NEW_CATALOG_ID'));
    }

    public function index(int $parent)
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

        $sections = $result->fetchAll();

        foreach($sections as &$section) {
            $section['PICTURE'] = \CFile::GetPath($section['PICTURE']);
        }

        return $sections;
    }
}
