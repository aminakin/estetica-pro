<?php
foreach ($arResult['ITEMS'] as $id=>$item) {
    if ($item["PREVIEW_PICTURE"]["SRC"]) {
        $arResult['ITEMS'][$id]["PICTURE_SMALL"] = CFile::ResizeImageGet(
            $item["PREVIEW_PICTURE"]['ID'],
            array('width' => 325, 'height' => 220),
            BX_RESIZE_IMAGE_EXACT,
            false
        );
    }
}