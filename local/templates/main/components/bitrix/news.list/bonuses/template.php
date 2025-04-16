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

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
Loc::loadMessages(__FILE__);

$arPrefix = ['FIRST', 'SECOND', 'THIRD'];
$icons = [
    'signal' => classBuildSCG::buildSVG('money', $templateFolder . "/images"),
    'percent' => classBuildSCG::buildSVG('percent', $templateFolder . "/images"),
    'b' => classBuildSCG::buildSVG('bonus', $templateFolder . "/images")
];

$currentBonus = declOfNum(intval($arParams['USER_BONUS']), [
    Loc::getMessage('BONUS_F_FORM'),
    Loc::getMessage('BONUS_S_FORM'),
    Loc::getMessage('BONUS_T_FORM'),
]);
$currentSum = declOfNum(intval($arParams['USER_SUM']), [
    Loc::getMessage('RUBLE_F_FORM'),
    Loc::getMessage('RUBLE_S_FORM'),
    Loc::getMessage('RUBLE_T_FORM'),
]);
$currentPercent = intval($arParams['USER_PERCENT']) . '%';
$currentLeftSum = declOfNum(intval($arParams['USER_LEFT_SUM']), [
    Loc::getMessage('RUBLE_F_FORM'),
    Loc::getMessage('RUBLE_S_FORM'),
    Loc::getMessage('RUBLE_T_FORM'),
]);

$search = ["#BONUS#", "#SUM#", "#PERCENT#", "#LEFT_SUM#"];
$replace = [$currentBonus, $currentSum, $currentPercent, $currentLeftSum];
?>
<?
foreach ($arResult["ITEMS"] as $arItem): ?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>

    <div class="bonus__cards">
        <? foreach ($arPrefix as $prefix) { ?>
            <div class="bonus-card__item" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                <div class="bonus-card__item-icon icon_<?= $arItem['PROPERTIES'][$prefix . '_ICON']['VALUE_XML_ID'] ?>">
                    <?= $icons[$arItem['PROPERTIES'][$prefix . '_ICON']['VALUE_XML_ID']] ?>
                </div>
                <div class="bonus-card__item-title">
                    <?= str_ireplace($search, $replace, $arItem['PROPERTIES'][$prefix . '_TITLE']['VALUE']); ?>
                </div>
                <div class="bonus-card__item-desc">
                    <?= str_ireplace($search, $replace, $arItem['PROPERTIES'][$prefix . '_DESC']['~VALUE']['TEXT']); ?>
                </div>
            </div>
        <? } ?>
    </div>
    <a class="bonus-card__item-link" href="/catalog/">
        к покупкам
    </a>
<? endforeach; ?>