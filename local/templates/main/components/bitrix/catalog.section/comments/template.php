<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */
?>
<?if ($arResult["ITEMS"])
{?>
    <section class="new-blocks onmain">
        <a href="/catalog/new/" class="first new-block hideMobile">
            <div class="title">Новинки</div>
            <img src="<?=SITE_TEMPLATE_PATH;?>/images/bg/plant.png" class="plant" alt="">
        </a>
        <a href="/catalog/new/" class="first-litle new-block hideFull">
            <div class="title">Новинки</div>
        </a>
        <?foreach($arResult["ITEMS"] as $arItem):?>

            <?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>

            <div class="new-block" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <span class="img"><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt=""></a></span>
                <?/*<div class="hover toBuy">*/?>
                    <div class="overflow">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="name"><?=$arItem['NAME']?></a>
                    </div>
                    <?/*<div class="price"><?=format_price($arItem['MIN_PRICE']['VALUE'])?>&nbsp;<span class="rub">Р</span></div>
					<?if ($arItem['CAN_BUY']=='Y')
					{?>
						<input type="number" autocomplete="off" min="1" value="1" class="qty" />
					<?}?>
                    <div class="clear"></div>
                    <?if ($arItem['CAN_BUY']=='Y')
					{?>
						<a href="#" class="whiteBtn buyBtn" data-id="<?=$arItem['ID']?>">В корзину</a>
						<a href="#" class="inFavorite favoriteBtn" data-favorite-id="<?=$arItem['ID']?>"></a>
					<?}
					else
					{?>
						<div class="nogoods">Товар отсутсвует на складе</div>
					<?}?>*/?>
                <?/*</div>*/?>
            </div>
         <?endforeach;?>

        <?/*<a href="/catalog/new/" class="last new-block">
            <div class="title">все Новинки</div>
        </a>*/?>
        <div class="clear"></div>
    </section>
<?}?>