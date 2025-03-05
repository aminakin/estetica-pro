<?php
/*деактивация пустых товаров*/

define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS',true);
define('BX_CRONTAB', true);
define('BX_NO_ACCELERATOR_RESET', true);

function cronLaunch() {
    return (php_sapi_name() == 'cli');
}

if (!$_SERVER['DOCUMENT_ROOT']) {
    $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__);
}

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

set_time_limit(0);

if (CModule::IncludeModule('iblock') && CModule::IncludeModule('catalog'))
{
    $res = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID'=>3,
            'ACTIVE'=>'Y',
            [
                "LOGIC" => "OR",
                ['CATALOG_AVAILABLE'=>'N'],
                ["CATALOG_PRICE_1" => false],
                ['DETAIL_PICTURE' => false],
                ['PREVIW_TEXT'=>false, 'DETAIL_TEXT' => false]
            ],
        ],
        false,
        false,
        ['ID']
    );
    $upd = new CIBlockElement;
    while($el = $res->GetNext())
    {
        $upd->Update($el['ID'], ['ACTIVE'=>'N']);
    }
}