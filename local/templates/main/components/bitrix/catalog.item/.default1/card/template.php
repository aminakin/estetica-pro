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
<div class="add-block" id="<?=$areaId?>" data-entity="item">
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
        array('width' => 220, 'height' => 205),
        BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
        false
    );
    if (!$file['src']) $file['src']= SITE_TEMPLATE_PATH.'/images/icons/nophoto220x205.png';?>
    <span class="img"><a href="<?=$item['DETAIL_PAGE_URL']?>"><img src="<?=$file['src']?>" alt=""></a></span>
    <a href="<?=$item['DETAIL_PAGE_URL']?>" class="name"><?=$item['NAME']?></a>
    <div class="price"><?=format_price($price['RATIO_PRICE'])?>&nbsp;<span class="rub">Р</span></div>
    <?if ($item['CAN_BUY']=='Y')
    {?>
        <input type="number" autocomplete="off" min="1" value="1" class="qty" />
        <a href="#" class="whiteBtn invert buyBtn" data-id="<?=$item['ID']?>"><?=GetMessage('IB')?></a>
        <a href="#" class="inFavorite favoriteBtn" data-favorite-id="<?=$item['ID']?>"></a>
    <?}
    else
    {?>
        <div class="nogoods">Товар отсутсвует на складе</div>
    <?}?>
    <?/*<div class="hover">
        <div class="price"><?=format_price($price['RATIO_PRICE'])?>&nbsp;<span class="rub">Р</span></div>
        <?if ($item['CAN_BUY']=='Y')
        {?>
            <input type="number" autocomplete="off" min="1" value="1" class="qty" />
        <?}?>
        <a href="#" class="whiteBtn quick" data-id="<?=$item['ID']?>"><?=GetMessage('Q')?></a>
        <?if ($item['CAN_BUY']=='Y')
        {?>
            <a href="#" class="addToBasket whiteBtn buyBtn" data-id="<?=$item['ID']?>"><?=GetMessage('IB')?></a>
			<a href="#" class="inFavorite invert favoriteBtn" data-favorite-id="<?=$item['ID']?>"></a>
        <?}
        else
        {?>
            <div class="nogoods">Товар отсутсвует на складе</div>
        <?}?>
    </div>*/?>
</div>
<?/*<div class="quick_view" data-qid="<?=$item['ID']?>">
    <a href="#" class="qclose"></a>
    <div class="img">
        <?if ($item['PROPERTIES']['ICON']['VALUE'])
        {?>
            <div class="act" style="background: #<?=$p['COLOR']['VALUE']?>;"><?=$f['NAME']?></div>
        <?}?>
        <a href="<?=$item['DETAIL_PAGE_URL']?>"><img src="<?=$file['src']?>" alt=""></a>
    </div>
    <div class="other">
        <a href="<?=$item['DETAIL_PAGE_URL']?>" class="name"><?=$item['NAME']?></a>
        <div class="price"><?=format_price($price['RATIO_PRICE'])?>&nbsp;<span class="rub">Р</span></div>
        <?if ($item['CAN_BUY']=='Y')
        {?>
            <input type="number" autocomplete="off" min="1" value="1"/>
        <?}
        else
        {?>
            <div class="nogoods">Товар отсутсвует на складе</div>
        <?}?>
        <div class="clear"></div>
        <div class="buttons">
            <?if ($item['CAN_BUY']=='Y')
            {?>
                <a href="#" class="addToBasket blackBtn buyBtn" data-id="<?=$item['ID']?>"><?=GetMessage('IB')?></a>
				<a href="#" class="inFavorite favoriteBtn" data-favorite-id="<?=$item['ID']?>"></a>
            <?}?>
        </div>
        <div>
            <table class="quicktable">
                <?foreach ($item['PROPERTIES'] as $prop)
                {
                    if ($prop['SORT']<=500) continue;
                    if (!$prop['VALUE']) continue;
                    if (is_array($prop['VALUE']))
                    {
                        foreach ($prop['VALUE'] as $key=>$p)
                        {?>
                            <tr>
                                <td><?=$prop['DESCRIPTION'][$key]?></td>
                                <td>
                                    <?
                                        echo $p;
                                    ?>
                                </td>
                            </tr>
                        <?}
                    }
                    else
                    {?>
                    <tr>
                        <td><?=$prop['NAME']?></td>
                        <td>
                            <?
                            if ($prop['PROPERTY_TYPE']!='E')
                            {
                                echo $prop['VALUE'];
                            }
                            else{
                                if (!$prop_val[$prop['VALUE']])
                                {
                                    $res_prop = CIBlockElement::GetByID($prop['VALUE']);
                                    if ($rp = $res_prop->GetNext())
                                    {
                                        $prop_val[$prop['VALUE']] = $rp['NAME'];
                                    }
                                }
                                echo $prop_val[$prop['VALUE']];
                            }
                            ?>
                        </td>
                    </tr>
                    <?}?>
                    <?
                }?>
            </table>
        </div>
    </div>
</div>*/?>