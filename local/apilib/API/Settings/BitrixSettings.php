<?php

namespace ApiLib\API\Settings;

class BitrixSettings
{
    /**
     * Возвращает все настройки из .settings по имени
     * @return array
     */
    public static function getApiVars(){
        return \Bitrix\Main\Config\Configuration::getValue('apiVars');
    }

    /**
     * Переменная из настроек по имени
     * @param $name
     * @return mixed
     */
    public static function getApiVarByName($name){
        $config = \Bitrix\Main\Config\Configuration::getValue('apiVars');
        return $config[$name];
    }
}