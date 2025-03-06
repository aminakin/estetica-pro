<?php
/**
 * Created by PhpStorm.
 * User: l0rd
 * Date: 19.10.18
 * Time: 15:24
 */

class siteDiscount
{
    public function rebuildDiscount($arrDiscountFilter)
    {
        $resultDiscount = [];
        $resultDiscount = $this->filterDiscountByName();
        $resultDiscount = $this->compareDiscount($resultDiscount, $arrDiscountFilter);
        if (isset($resultDiscount['del'])) {
            $this->delDicount($resultDiscount['del']);
        }
        if (isset($resultDiscount['add'])) {
            $newDiscount = $this->addDicount($resultDiscount['add']);
        }
        $resultDiscount = $this->filterDiscountByName();
        return $resultDiscount;
    }

    private function delDicount($arDelDiscount)
    {
        foreach ($arDelDiscount as $idDel)
            CSaleDiscount::Delete($idDel);
    }

    private function addDicount($arAddDiscount)
    {
        $resultNewDiscount = [];
        foreach ($arAddDiscount as $discount) {
            $arFields = array(
                'LID' => 's1',
                'ACTIVE' => 'Y',
                'NAME' => 'Скидка по карте ' . $discount[0] . '%' . $discount[1],
                //'VALUE' => $discount[0],
                'VALUE_TYPE' => 'P',
                'PRIORITY' => 1000,
                'LAST_DISCOUNT' => 'Y',
                'CONDITIONS' => array(
                    'CLASS_ID' => 'CondGroup',
                    'DATA' =>
                        array(
                            'All' => 'AND',
                            'True' => 'True',
                        ),
                    'CHILDREN' =>
                        array(),
                ),
                'ACTIONS' => array(
                    'CLASS_ID' => 'CondGroup',
                    'DATA' =>
                        array(
                            'All' => 'AND',
                        ),
                    'CHILDREN' =>
                        array(
                            0 =>
                                array(
                                    'CLASS_ID' => 'ActSaleBsktGrp',
                                    'DATA' =>
                                        array(
                                            'Type' => 'Discount',
                                            'Value' => $discount[0],
                                            'Unit' => 'Perc',
                                            'Max' => 999999999,
                                            'All' => 'AND',
                                            'True' => 'True',
                                        ),
                                    'CHILDREN' =>
                                        array(
                                            0 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBProp:14:135',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 363,
                                                        ),
                                                ),
                                            1 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1087,
                                                        ),
                                                ),
                                            2 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1090,
                                                        ),
                                                ),
                                            3 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1088,
                                                        ),
                                                ),
                                            4 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1092,
                                                        ),
                                                ),
                                            5 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1081,
                                                        ),
                                                ),
                                            6 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1082,
                                                        ),
                                                ),
                                            7 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1119,
                                                        ),
                                                ),
                                            8 =>
                                                array(
                                                    'CLASS_ID' => 'CondIBSection',
                                                    'DATA' =>
                                                        array(
                                                            'logic' => 'Not',
                                                            'value' => 1106,
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                ),
                'USER_GROUPS' => [2],


            );
            $ID = CSaleDiscount::Add($arFields);
            $resultNewDiscount[$ID] = [$ID, $discount];
        }
        return $resultNewDiscount;
    }

    private function compareDiscount($resultDiscount, $arrDiscountFilter)
    {
        $result = [];
        foreach ($resultDiscount as $discount) {
            if (!is_array($discount) || count($discount) == 0)
                continue;
            //$discount=  array_shift($discount);
            $notOnDel = false;
            foreach ($arrDiscountFilter as $discountFilter) {
                if ($discount[0] == $discountFilter) {
                    $notOnDel = true;
                }
            }
            if (!$notOnDel) {
                $result['del'][] = $discount[1];
            }
        }

        foreach ($arrDiscountFilter as $procent => $discountFilter) {
            $notOnAdd = false;
            foreach ($resultDiscount as $discount) {
                if (!is_array($discount) || count($discount) == 0)
                    continue;
                //$discount=  array_shift($discount);
                if ($discount[0] == $discountFilter) {
                    $notOnAdd = true;
                }
            }
            if (!$notOnAdd) {
                $result['add'][] = [$procent, $discountFilter];
            }
        }
        return $result;
    }

    private function filterDiscountByName(/*$name*/)
    {
        /*
         * array ( 'NAME' => 'Скидка по карте 3%|k3', 'ID' => '5', 'CONDITIONS' => 'a:3:{s:8:"CLASS_ID";s:9:"CondGroup";s:4:"DATA";a:2:{s:3:"All";s:3:"AND";s:4:"True";s:4:"True";}s:8:"CHILDREN";a:0:{}}', 'UNPACK' => 'function($arOrder){return ((1 == 1)); };', 'APPLICATION' => 'function (&$arOrder){\\Bitrix\\Sale\\Discount\\Actions::applyToBasket($arOrder, array ( \'VALUE\' => -3.0, \'UNIT\' => \'P\', \'LIMIT_VALUE\' => 0, ), "");};', )*/
        $dbProductDiscounts = CSaleDiscount::GetList(
            array("SORT" => "ASC"),
            array('%NAME' => '%\\|k%'),
            false,
            false,
            ['NAME', 'ID', 'CONDITIONS', 'UNPACK', 'ACTION', 'APPLICATION']
        );
        $arResult = [];
        while ($arProductDiscounts = $dbProductDiscounts->Fetch()) {
            $name = preg_replace('/[^\|]+(\|k\d+)/', '$1', $arProductDiscounts['NAME']);
            $arResult[$arProductDiscounts['ID']] = [$name, $arProductDiscounts['ID']];
        }
        return $arResult;
    }
} 