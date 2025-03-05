<?php

namespace Sprint\Migration;

class Version20190606000014 extends Version
{

    protected $description = "Добавляем инфоблок комментарии к новостям";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();

        $iblockId1 = $helper->Iblock()->saveIblock([
            'NAME' => 'Комментарии для новостей',
            'CODE' => 'newsreviews',
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

        $newsIB = $helper->Iblock()->getIblockIfExists('news');
        if (is_array($newsIB)) {
            $helper->Iblock()->saveProperty($iblockId1, [
                'NAME' => 'Новость комментария',
                'CODE' => 'REVIEW_NEWS',
                'PROPERTY_TYPE' => 'E',
                'LINK_IBLOCK_ID' => $newsIB['ID']
            ]);
        }

        $this->outSuccess('Инфоблок создан');

    }

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function down()
    {
        $helper = $this->getHelperManager();
        $ok = $helper->Iblock()->deleteIblockIfExists('newsreviews');

        if ($ok) {
            $this->outSuccess('Инфоблок удален');
        } else {
            $this->outError('Ошибка удаления инфоблока');
        }
    }

}
