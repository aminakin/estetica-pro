<?php
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
    // $cp->SetResultCacheKeys(array('MY_TITLE'));
    // $arResult['MY_TITLE'] = $cp->arResult['MY_TITLE'];
    // $APPLICATION->SetTitle($cp->arResult['MY_TITLE']);
}