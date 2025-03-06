<?php

namespace Sprint\Migration;


class Version20250303134350 extends Version
{
    protected $author = "techaddamant@addamant.ru";

    protected $description = "Учебные курсы | Учебные курсы | ИБ категории";

    protected $moduleVersion = "4.18.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();

        $iblockId = $helper->Iblock()->getIblockIdIfExists(
            'courses',
            'courses'
        );

        $helper->Iblock()->addSectionsFromTree(
            $iblockId,
            array (
  0 => 
  array (
    'NAME' => 'Курсы маникюра',
    'CODE' => 'kursy-manikyura',
    'SORT' => '100',
    'ACTIVE' => 'Y',
    'XML_ID' => '1',
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
  ),
  1 => 
  array (
    'NAME' => 'Мастер-классы по маникюру',
    'CODE' => 'master-klassy-po-manikyuru',
    'SORT' => '200',
    'ACTIVE' => 'Y',
    'XML_ID' => '',
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
  ),
  2 => 
  array (
    'NAME' => 'Курсы педикюра',
    'CODE' => 'kursy-pedikyura',
    'SORT' => '300',
    'ACTIVE' => 'Y',
    'XML_ID' => '',
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
  ),
  3 => 
  array (
    'NAME' => 'Курсы наращивания и ламинирования ресниц',
    'CODE' => 'modelirovaniye-resnits',
    'SORT' => '400',
    'ACTIVE' => 'Y',
    'XML_ID' => '2',
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
  ),
  4 => 
  array (
    'NAME' => 'Курсы оформления бровей',
    'CODE' => 'kursy-oformleniya-brovey',
    'SORT' => '500',
    'ACTIVE' => 'Y',
    'XML_ID' => '',
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
  ),
  5 => 
  array (
    'NAME' => 'Мастер-классы по наращиванию и ламинированию ресниц, оформлению бровей',
    'CODE' => 'master-klassy-po-narashchivaniyu-i-laminirovaniyu-resnits-oformleniyu-brovey',
    'SORT' => '600',
    'ACTIVE' => 'Y',
    'XML_ID' => '',
    'DESCRIPTION' => '',
    'DESCRIPTION_TYPE' => 'text',
  ),
  6 => 
  array (
    'NAME' => 'Расписание',
    'CODE' => 'raspisanie',
    'SORT' => '1000',
    'ACTIVE' => 'Y',
    'XML_ID' => '1216',
    'DESCRIPTION' => NULL,
    'DESCRIPTION_TYPE' => 'text',
  ),
)        );
    }
}
