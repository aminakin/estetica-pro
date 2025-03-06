<?php
$activeElementsNail = CIBlockSection::GetSectionElementsCount(1, Array("CNT_ACTIVE"=>"Y"));
$activeElementsLash = CIBlockSection::GetSectionElementsCount(2, Array("CNT_ACTIVE"=>"Y"));
$allElements = $activeElementsNail + $activeElementsLash;

$titles = array(' курс', ' курса', ' курсов');
function course_count($number, $titles){
    $cases = array (2, 0, 1, 1, 1, 2);
    $format = $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
    return sprintf($format, $number);
}

$this->SetViewTarget('allElements');
    echo $allElements;
    echo course_count($allElements, $titles);
$this->EndViewTarget();

$this->SetViewTarget('activeElementsNail');
    echo $activeElementsNail;
    echo course_count($activeElementsNail, $titles);
$this->EndViewTarget();

$this->SetViewTarget('activeElementsLash');
    echo $activeElementsLash;
    echo course_count($activeElementsLash, $titles);
$this->EndViewTarget();





foreach ($arResult['ITEMS'] as $id=>$item) {
    if ($item["PREVIEW_PICTURE"]["SRC"]) {
        $arResult['ITEMS'][$id]["PICTURE_SMALL"] = CFile::ResizeImageGet(
            $item["PREVIEW_PICTURE"]['ID'],
            array('width' => 470, 'height' => 306),
            BX_RESIZE_IMAGE_EXACT,
            false
        );
    }
}
global $APPLICATION;

$cp = $this->__component;

if (is_object($cp))
{
    if ($arResult['SECTION']['PATH'][0]['NAME'])
    {
        $cp->arResult['MY_TITLE'] = $arResult['SECTION']['PATH'][0]['NAME'];
    }
    else
    {
        $cp->arResult['MY_TITLE'] = $arResult['NAME'];
    }
}
