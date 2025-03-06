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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap_oneline brand',
		'TITLE' => 'bx_sitemap_title_oneline',
		'LIST' => 'bx_sitemap_ul_oneline',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?><div class="<? echo $arCurView['CONT']; ?>"><?
if (0 < $arResult["SECTIONS_COUNT"])
{
?>
<?/*<ul class="<? echo $arCurView['LIST']; ?>">
<?
	$intCurrentDepth = 1;
	$boolFirst = true;
	foreach ($arResult['SECTIONS'] as &$arSection)
	{
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

		if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
		{
			if (0 < $intCurrentDepth)
				echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul class="second-ul">';
		}
		elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
		{
			if (!$boolFirst)
				echo '</li>';
		}
		else
		{
			while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
			{
				echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
				$intCurrentDepth--;
			}
			echo str_repeat("\t", $intCurrentDepth-1),'</li>';
		}

		echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);?>
        <li class="<? if($arSection['RELATIVE_DEPTH_LEVEL'] == 1){echo 'first';} ?><? if($arSection['RELATIVE_DEPTH_LEVEL'] == 2){echo 'second';} ?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
			<?$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];?>
			<span>
				<a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>" class="<?php if($APPLICATION->GetCurPage(false) == $arSection['SECTION_PAGE_URL']) echo "active";?>">
					<? echo $arSection["NAME"];?>
				</a>
				<? if($arSection['RELATIVE_DEPTH_LEVEL'] == 1 || $arSection['RELATIVE_DEPTH_LEVEL'] == 2){?> <?= classBuildSCG::buildSVG('arrow');?><?}?>
			</span>
		
		<?$boolFirst = false;
	}
	unset($arSection);
	while ($intCurrentDepth > 1)
	{
		echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
		$intCurrentDepth--;
	}
	if ($intCurrentDepth > 0)
	{
		echo '</li>',"\n";
	}
?>
</ul>
<?
	echo ('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');*/
}
?>
    <ul class="first-block">
        <? foreach ($arResult['SECTIONS'] as &$arSection): ?>
            <? if($arSection['DEPTH_LEVEL'] == 1): ?>
                <?
                    $no_children = true;
                    if(($arSection['RIGHT_MARGIN'] - $arSection['LEFT_MARGIN']) > 1) {
                        $no_children = false;
                    }
                ?>
                <li class="first <?if($no_children):?>no_children<?endif;?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>" data-parents="<?=$arSection['ID']?>">
                    <span>
                        <a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>" class="<?php if($APPLICATION->GetCurPage(false) == $arSection['SECTION_PAGE_URL']) echo "active";?>">
                            <? echo $arSection["NAME"];?>
                        </a>
                        <?= classBuildSCG::buildSVG('arrow');?>
                    </span>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>

    <ul class="second-block">
        <? foreach ($arResult['SECTIONS'] as &$arSection): ?>
            <? if($arSection['DEPTH_LEVEL'] == 2): ?>
                <?
                    $no_children = true;
                    if(($arSection['RIGHT_MARGIN'] - $arSection['LEFT_MARGIN']) > 1) {
                        $no_children = false;
                    }
                ?>
                <li class="second <?if($no_children):?>no_children<?endif;?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>" data-parents="<?=$arSection['ID']?>" data-children="<?=$arSection['IBLOCK_SECTION_ID']?>">
                    <span>
                        <a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>" class="<?php if($APPLICATION->GetCurPage(false) == $arSection['SECTION_PAGE_URL']) echo "active";?>">
                            <? echo $arSection["NAME"];?>
                        </a>
                        <?= classBuildSCG::buildSVG('arrow');?>
                    </span>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>

    <ul class="third-block">
        <? foreach ($arResult['SECTIONS'] as &$arSection): ?>
            <? if($arSection['DEPTH_LEVEL'] == 3): ?>
                <li class="third" id="<?=$this->GetEditAreaId($arSection['ID']);?>" data-children="<?=$arSection['IBLOCK_SECTION_ID']?>">
                    <span>
                        <a href="<? echo $arSection["SECTION_PAGE_URL"]; ?>" class="<?php if($APPLICATION->GetCurPage(false) == $arSection['SECTION_PAGE_URL']) echo "active";?>">
                            <? echo $arSection["NAME"];?>
                        </a>
                    </span>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>
</div>