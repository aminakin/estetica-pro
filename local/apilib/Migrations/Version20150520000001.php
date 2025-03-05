<?php

namespace Sprint\Migration;

class Version20150520000001 extends Version
{

    protected $description = "Добавляем инфоблок новости";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();

        $helper->Iblock()->saveIblockType([
            'ID' => 'mobile_app',
            'LANG' => [
                'en' => [
                    'NAME' => 'For mobile app',
                    'SECTION_NAME' => 'Sections',
                    'ELEMENT_NAME' => 'Elements',
                ],
                'ru' => [
                    'NAME' => 'Для мобильного приложения',
                    'SECTION_NAME' => 'Разделы',
                    'ELEMENT_NAME' => 'Элементы',
                ],
            ],
        ]);

        $iblockId1 = $helper->Iblock()->saveIblock([
            'NAME' => 'Лента новостей',
            'CODE' => 'news',
            'LID' => ['s1'],
            'IBLOCK_TYPE_ID' => 'mobile_app',
            'LIST_PAGE_URL' => '',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/#IBOCK_CODE#/#ELEMENT_CODE#',
        ]);

        $helper->Iblock()->saveIblockFields($iblockId1, [
            'CODE' => [
                'DEFAULT_VALUE' => [
                    'TRANSLITERATION' => 'Y',
                    'UNIQUE' => 'Y',
                ],
            ],
        ]);


        $this->outSuccess('Инфоблок создан');

    }

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function down()
    {
        $helper = $this->getHelperManager();
        $ok = $helper->Iblock()->deleteIblockIfExists('news');

        if ($ok) {
            $this->outSuccess('Инфоблок удален');
        } else {
            $this->outError('Ошибка удаления инфоблока');
        }
    }

}
