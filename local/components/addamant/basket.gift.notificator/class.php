<?php


use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Error;
use Bitrix\Main\Errorable;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\EventManager;

class BasketGiftNotificator extends CBitrixComponent implements Controllerable, Errorable
{
    /** @var ErrorCollection */
    protected $errorCollection;

    /**
     * @inheritDoc
     */
    public function configureActions()
    {
        // TODO: Implement configureActions() method.
    }
    /**
     * change arparams
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams)
    {
        $this->errorCollection = new ErrorCollection();
        //Этот код **будет** выполняться при запуске аяксовых-действий
        return $arParams;
    }

    /**
     *
     * @return false
     */
    public function executeComponent()
    {
        $this->arResult = [];
        $this->initEvents();
        $this->includeComponentTemplate();

        return false;
    }

    /**
     *
     * @return false
     */
    private function initEvents()
    {
        EventManager::getInstance()->addEventHandler(
            'main',
            'OnBeforeEndBufferContent',
            function () {

                CJSCore::Init(
                    array(
                        'jquery', 'fx', 'ajax'
                    )
                );
            }
        );

        return false;
    }
    /**
     * @return array|Error[]
     */
    public function getErrors()
    {
        return $this->errorCollection->toArray();
    }

    /**
     * @param $code
     * @return Error|null
     */
    public function getErrorByCode($code)
    {
        return $this->errorCollection->getErrorByCode($code);
    }

    /**
     * params assigned with ajax (IBLOCK_ID, IBLOCK_CODE...)
     * @return array
     */
    protected function listKeysSignedParameters()
    {
        return [];
    }

}