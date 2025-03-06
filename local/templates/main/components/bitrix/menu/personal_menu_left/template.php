<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
    <div class="filter">
        <div class="filterbody active">
            <div class="sections">
                <?
                foreach($arResult as $arItem):
                    if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                        continue;
                    ?>
                    <?if($arItem["SELECTED"]):?>
                        <a href="<?=$arItem["LINK"]?>" class="active"><?=$arItem["TEXT"]?></a>
                    <?else:?>
                        <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
                    <?endif?>

                <?endforeach?>
                <a href="tel:79229776867" class="active">+7 (8332) 77 68 67</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
<?endif?>