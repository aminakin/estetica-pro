<?
foreach ($arResult['ITEMS'] as $item) {
    $arResult['REPRESENTATIVES'][$item['ID']] = [
        'ID' => $item['ID'],
        'NAME' => $item['NAME'],
        'IBLOCK_ID' => $item['IBLOCK_ID'],
        'ADDRESS' => $item['PROPERTIES']['ADDRESS']['VALUE'],
        'TIME_WORK' => [
            'FROM' => $item['PROPERTIES']['TIME_WORK']['VALUE'],
            'TO' => $item['PROPERTIES']['TIME_WORK']['DESCRIPTION'],
        ]
    ];

    if (count($item['PROPERTIES']['PHONE']['VALUE']) && $item['PROPERTIES']['PHONE']['VALUE'] != null) {
        foreach ($item['PROPERTIES']['PHONE']['VALUE'] as $key => $val) {
            $arResult['REPRESENTATIVES'][$item['ID']]['PHONE'][$key] = $val;
        }
    }

    if (count($item['PROPERTIES']['INST']['VALUE']) && $item['PROPERTIES']['INST']['VALUE'] != null) {
        foreach ($item['PROPERTIES']['INST']['VALUE'] as $key => $val) {
            $arResult['REPRESENTATIVES'][$item['ID']]['INST'][$key] = [
                'LINK' => $item['PROPERTIES']['INST']['DESCRIPTION'][$key],
                'SHORT_LINK' => $val,
            ];
        }
    }


    if (count($item['PROPERTIES']['SITE']['VALUE']) && $item['PROPERTIES']['SITE']['VALUE'] != null) {
        foreach ($item['PROPERTIES']['SITE']['VALUE'] as $key => $val) {
            $arResult['REPRESENTATIVES'][$item['ID']]['SITE'][$key] = [
                'LINK' => $item['PROPERTIES']['SITE']['DESCRIPTION'][$key],
                'SHORT_LINK' => $val,
            ];
        }
    }


    if (count($item['PROPERTIES']['VK']['VALUE']) && $item['PROPERTIES']['VK']['VALUE'] != null) {
        foreach ($item['PROPERTIES']['VK']['VALUE'] as $key => $val) {
            $arResult['REPRESENTATIVES'][$item['ID']]['VK'][$key] = [
                'LINK' => $item['PROPERTIES']['VK']['DESCRIPTION'][$key],
                'SHORT_LINK' => $val,
            ];
        }
    }

    $res = CIBlockElement::GetByID($item['PROPERTIES']['CITY']['VALUE']);
    if ($ar_res = $res->GetNext()) {
        $arResult['REPRESENTATIVES'][$item['ID']]['CITY'] = $ar_res['NAME'];
    }
}

$arResult['REPRESENTATIVES'] = customMultiSort($arResult['REPRESENTATIVES'], 'CITY');

function customMultiSort($array, $field)
{
    $sortArr = array();
    foreach ($array as $key => $val) {
        $sortArr[$key] = $val[$field];
    }

    array_multisort($sortArr, $array);

    return $array;
}