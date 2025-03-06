<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
<div class="representatives">
    <h2 class="representatives__title">
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "AREA_FILE_SUFFIX" => "inc",
                "EDIT_TEMPLATE" => "",
                "PATH" => "/include/representative_title.php"
            )
        ); ?>
    </h2>
    <? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?><br/>
    <? endif; ?>
    <? foreach ($arResult["REPRESENTATIVES"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="representative clearfix" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
            <div class="representative__city representative__col">
                <?= $arItem['CITY'] ?>
            </div>
            <div class="representative__address representative__col">
                <?= $arItem['ADDRESS'] ?>
                <div class="representative__time-work">
                    <?= GetMessage('FROM') ?> <?= $arItem['TIME_WORK']['FROM'] ?> <?= GetMessage('TO') ?> <?= $arItem['TIME_WORK']['TO'] ?>
                </div>
            </div>
            <? if (count($arItem['PHONE']) && $arItem['PHONE'] !== null) { ?>
                <div class="representative__phone representative__col">
                    <? foreach ($arItem['PHONE'] as $phone) { ?>
                        <a href="tel:<?= $phone ?>" class="representative__phone-link"><?= $phone ?></a>
                    <? } ?>
                </div>
            <? } ?>
            <div class="representative__social representative-social representative__col">
                <? if (count($arItem['INST']) && $arItem['INST'] !== null) { ?>
                    <div class="representative-social__item representative-social__inst">
                        <? foreach ($arItem['INST'] as $item) { ?>
                            <a href="<?= $item['LINK'] ?>" target="_blank"
                               class="representative-social__item-link"><?= $item['SHORT_LINK'] ?></a>
                        <? } ?>
                    </div>
                <? } ?>

                <? if (count($arItem['SITE']) && $arItem['SITE'] !== null) { ?>
                    <div class="representative-social__item representative-social__site">
                        <? foreach ($arItem['SITE'] as $item) { ?>
                            <a href="<?= $item['LINK'] ?>" target="_blank"
                               class="representative-social__item-link"><?= $item['SHORT_LINK'] ?></a>
                        <? } ?>
                    </div>
                <? } ?>

                <? if (count($arItem['VK']) && $arItem['VK'] !== null) { ?>
                    <div class="representative-social__item representative-social__vk">
                        <? foreach ($arItem['VK'] as $item) { ?>
                            <a href="<?= $item['LINK'] ?>" target="_blank"
                               class="representative-social__item-link"><?= $item['SHORT_LINK'] ?></a>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
        </div>
    <? endforeach; ?>
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <br/><?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
</div>
