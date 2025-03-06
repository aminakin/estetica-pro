<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?foreach($arResult["NEW_ITEMS"] as $key => $arItem):?>
    <div class="section_title"><?= $key;?>:</div>
    <div class="item-wrapper">
        <? foreach($arItem as $item):?>
            <?
                $this->AddEditAction($item['ID'], $item['EDIT_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($item['ID'], $item['DELETE_LINK'], CIBlock::GetArrayByID($item["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
            <a class="item-social" href="<?=$item['PROPERTIES']['LINK']['VALUE'];?>" id="<?=$this->GetEditAreaId($item['ID']);?>" target="_black">
                <img src="<?= CFile::GetPath($item['PROPERTIES']['FILE']['VALUE']);?>"> <?= $item["NAME"];?>
            </a>
        <?endforeach;?>
    </div>
<?endforeach;?>
