<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */
?>
<script>
    $(document).ready(function () {
        $('input, select').styler({selectSearch: true});
    })
</script>
<div class="new-block">
    <?if ($item['PROPERTIES']['ICON']['VALUE'])
    {
        $res = CIBlockElement::GetByID($item['PROPERTIES']['ICON']['VALUE']);
        if ($el = $res->GetNextElement())
        {
            $p = $el->GetProperties();
            $f = $el->GetFields();
        }?>
        <div class="act" style="background: #<?=$p['COLOR']['VALUE']?>;"><?=$f['NAME']?></div>
    <?}?>
    <?
    $file = CFile::ResizeImageGet(
        $item['PREVIEW_PICTURE']['ID'],
        array('width' => 244, 'height' => 200),
        BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
        false
    );?>
    <span class="img"><a href="<?=$item['DETAIL_PAGE_URL']?>" class=""><img src="<?=$file['src']?>" alt=""></a></span>
    <div class="tovar-name">
        <a href="<?=$item['DETAIL_PAGE_URL']?>" class=""><?=$item['NAME']?></a>
    </div>
    <div class="price"><?=format_price($price['RATIO_PRICE'])?>&nbsp;<span class="rub">Р</span></div>
	<?if ($item['CAN_BUY']=='Y')
    {?>
		<input type="number" class='qty' autocomplete="off" min="1" value="1"/>
	<?}?>
    <div class="clear"></div>
    <?if ($item['CAN_BUY']=='Y')
    {?>
		<a href="#" class="whiteBtn buyBtn" data-id="<?=$item['ID']?>"><?=GetMessage('IB')?></a>
		<a href="#" class="inFavorite favoriteBtn" data-favorite-id="<?=$item['ID']?>"></a>
	<?}
    else
    {?>
        <div class="nogoods">Товар отсутсвует на складе</div>
    <?}?>
</div>