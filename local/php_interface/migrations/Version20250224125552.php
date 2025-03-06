<?php

namespace Sprint\Migration;


class Version20250224125552 extends Version
{
    protected $author = "techaddamant@addamant.ru";

    protected $description = "О нас | Преимущества | ИБ разделы";

    protected $moduleVersion = "4.18.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();

        $iblockId = $helper->Iblock()->getIblockIdIfExists(
            'aboutAsAdvantages',
            'about'
        );

        $helper->Iblock()->addSectionsFromTree(
            $iblockId,
            array (
  0 => 
  array (
    'NAME' => 'Преимущества',
    'CODE' => NULL,
    'SORT' => '500',
    'ACTIVE' => 'Y',
    'XML_ID' => '1827',
    'DESCRIPTION' => NULL,
    'DESCRIPTION_TYPE' => 'text',
  ),
)        );
    }
}
