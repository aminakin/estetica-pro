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
<div class="timetable-wrap-button">
	<div class="button-nail active"><?= GetMessage('CT_BNL_ELEMENT_NAIL');?></div>
	<div class="button-lash"><?= GetMessage('CT_BNL_ELEMENT_LASH');?></div>
</div>

<div class="timetable-list course-nail">
<?foreach($arResult["SECTIONS1602"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
		
	<?if(!empty($arItem["ELEMENTITEMS"])){?>
		<div class="title-month">
			<?echo $arItem['NAME'];?>
		</div> 
		<div class="timetable-list-wrap">
			<?foreach($arItem["ELEMENTITEMS"] as $arItemlist):?>
				<div class="timetable-item" id="<?=$this->GetEditAreaId($arItemlist['ID']);?>">
					<div class="date-wrap">
						<? $date = $arItemlist['PROPERTIES']['DAY_MOVE']['VALUE'].' '. 
								   $arItemlist['PROPERTIES']['MONTH_MOVE']['VALUE'].' '.
								   $arItemlist['PROPERTIES']['TIME_MOVE']['VALUE'];
						echo $date;?>
					</div>
					<div class="name-wrap">
						<?	$var = $arResult['PROPERTIES']['NAME_COURSE']['VALUE'];  //записать значение нужного поля в переменную var
							$res = CIBlockElement::GetByID($var);
							if($ar_res = $res->GetNext())?>
							<p><a href="<?=$ar_res['DETAIL_PAGE_URL'];?>"><?= $arItemlist['NAME_COURSE'];?></a></p>
							<?= $arItemlist['TYPE_COURSE']['VALUE'];?>
					</div>
					<div class="instr-wrap">
						<div class="img-instr">
							<? if(!empty($arItemlist['FOTO_INSTR']['VALUE'])){?>
							<img src="<?= CFile::GetPath($arItemlist['FOTO_INSTR']['VALUE']); ?>" alt="">
							<?}?>
						</div>
						<div class="text-instr">
							<p><?= $arItemlist['INSTR']['VALUE'];?></a></p>
							<?= $arItemlist['PROF_INSTR']['VALUE'];?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	<?}?>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
<div class="timetable-list course-lash" style="display: none;">
<?foreach($arResult["SECTIONS1601"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
		
	<?if(!empty($arItem["ELEMENTITEMS"])){?>
		<div class="title-month">
			<?echo $arItem['NAME'];?>
		</div> 
		<div class="timetable-list-wrap">
			<?foreach($arItem["ELEMENTITEMS"] as $arItemlist):?>
				<div class="timetable-item" id="<?=$this->GetEditAreaId($arItemlist['ID']);?>">
					<div class="date-wrap">
						<? $date = $arItemlist['PROPERTIES']['DAY_MOVE']['VALUE'].' '. 
								   $arItemlist['PROPERTIES']['MONTH_MOVE']['VALUE'].' '.
								   $arItemlist['PROPERTIES']['TIME_MOVE']['VALUE'];
						echo $date;?>
					</div>
					<div class="name-wrap">

						<p><?= $arItemlist['NAME_COURSE'];?></p>
						<?= $arItemlist['TYPE_COURSE']['VALUE'];?>
					</div>
					<div class="instr-wrap">
						<div class="img-instr">
							<? if(!empty($arItemlist['FOTO_INSTR']['VALUE'])){?>
							<img src="<?= CFile::GetPath($arItemlist['FOTO_INSTR']['VALUE']); ?>" alt="">
							<?}?>
						</div>
						<div class="text-instr">
							<p><?= $arItemlist['INSTR']['VALUE'];?></p>
							<?= $arItemlist['PROF_INSTR']['VALUE'];?>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	<?}?>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
<script type="text/javascript">
</script>