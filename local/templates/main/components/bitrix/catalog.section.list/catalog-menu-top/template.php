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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap_mobile',
		'TITLE' => 'bx_sitemap_mobile_title',
		'LIST' => 'top-mobile-menu__catalog-list',
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));


?>
<? if (0 < $arResult["SECTIONS_COUNT"]) { ?>
	<ul class="<? echo $arCurView['LIST']; ?>">
		<li class="back-menu"><?= classBuildSCG::buildSVG('arrow'); ?><span>Каталог</span></li>
		<?
		$intCurrentDepth = 1;
		$boolFirst = true;
		foreach ($arResult['SECTIONS'] as &$arSection) {
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

			if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL']) {
				if (0 < $intCurrentDepth)
					echo "\n", str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']), '<ul class="top-mobile-menu__catalog-list"> <li class="back-menu">' . classBuildSCG::buildSVG("arrow") . '<span>Каталог</span></li>';
			} elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL']) {
				if (!$boolFirst)
					echo '</li>';
			} else {
				while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL']) {
					echo '</li>', "\n", str_repeat("\t", $intCurrentDepth), '</ul>', "\n", str_repeat("\t", $intCurrentDepth - 1);
					$intCurrentDepth--;
				}
				echo str_repeat("\t", $intCurrentDepth - 1), '</li>';
			}

			echo (!$boolFirst ? "\n" : ''), str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
		?>

			<li class="<?= ($arSection['RELATIVE_DEPTH_LEVEL'] == 2) ? 'second' : 'first' ?>" id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
				<a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>" class="<?php if ($APPLICATION->GetCurPage(false) == $arSection['SECTION_PAGE_URL']) echo "active"; ?>">
					<? if ($arSection['IBLOCK_ID'] == 25): ?>
						
						<span class="section-img"><img src="<?= ($arSection["PICTURE"] == false)? $templateFolder.'/images/noimage.png': $arSection["PICTURE"]['SRC'] ?>"></span>
						<span><?= $arSection["NAME"]; ?></span>
					<? else: ?>
						<? echo $arSection["NAME"]; ?>
						<?= classBuildSCG::buildSVG('arrow'); ?>
					<? endif; ?>
				</a>

			<?
			$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
			$boolFirst = false;
		}
		unset($arSection);
		while ($intCurrentDepth > 1) {
			echo '</li>', "\n", str_repeat("\t", $intCurrentDepth), '</ul>', "\n", str_repeat("\t", $intCurrentDepth - 1);
			$intCurrentDepth--;
		}
		if ($intCurrentDepth > 0) {
			echo '</li>', "\n";
		} ?>
	</ul>
<? } ?>