<?php
foreach ($arResult['ITEMS'] as $id=>$item) {
    if ($item["PREVIEW_PICTURE"]["SRC"]) {
        $arResult['ITEMS'][$id]["PICTURE_SMALL"] = CFile::ResizeImageGet(
            $item["PREVIEW_PICTURE"]['ID'],
            array('width' => 488, 'height' => 318),
            BX_RESIZE_IMAGE_EXACT,
            false
        );
    }
}