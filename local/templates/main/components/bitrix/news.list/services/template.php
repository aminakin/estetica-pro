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
<div class="services-block">
    <ul class="services-block__list">
        <?foreach($arResult['ITEMS'] as $key => $arItem):?>
            <li class="services-block__item">
                <div class="services-block__img">
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="">
                </div>
                <div class="services-block__title"> 
                    <?=$arItem['NAME']?>
                </div>
            </li>
        <?endforeach;?>
    </ul>
</div>


