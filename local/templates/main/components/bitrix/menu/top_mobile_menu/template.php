<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<nav class="top-mobile-menu">
    <ul>
<?
$previousLevel = 0;
foreach($arResult as $arItem):?>
	<?if($arItem['DEPTH_LEVEL'] == 1):?>
		<li>
            <a href="<?=$arItem["LINK"]?>" class="<?=$arItem['PARAMS']['CATALOG']?'top-mobile-menu__catalog_opener ':''?><?if ($arItem["SELECTED"]):?>root-item selected<?else:?>root-item<?endif?>">
                <?=$arItem["TEXT"]?>
            </a>
            <?
            if($arItem['PARAMS']['CATALOG']) {
                $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "top_menu_mobile_multilevel",
                    Array(
                        "COMPONENT_TEMPLATE" => "vertical_multilevel",
                        "ROOT_MENU_TYPE" => "left",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS" => "",
                        "MAX_LEVEL" => "1",
                        "CHILD_MENU_TYPE" => "left",
                        "USE_EXT" => "Y",
                        "DELAY" => "N",
                        "ALLOW_MULTI_SELECT" => "N",
                    ),
                    false
                );
            }
            ?>
        </li>
	<?else:?>
		<?continue;?>
	<?endif;?>
	
	
<?endforeach?>



	</ul>
</nav>
<?endif?>