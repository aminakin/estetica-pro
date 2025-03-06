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
$this->setFrameMode(true);?>

<?$INPUT_ID = trim($arParams["~INPUT_ID"]);
if(strlen($INPUT_ID) <= 0)
	$INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if(strlen($CONTAINER_ID) <= 0)
	$CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);
global $CONTAINER_I_ID;
if (empty($CONTAINER_I_ID))
	$CONTAINER_I_ID = 1;
else 
	$CONTAINER_I_ID++;

$CONTAINER_ID .= $CONTAINER_I_ID;

if($arParams["SHOW_INPUT"] !== "N"):?>
	<div id="<?echo $CONTAINER_ID?>">
		<form action="<?echo $arResult["FORM_ACTION"]?>">
		    <a href="#" class="close"></a>
		    <input type="text" value="" id="<?echo $INPUT_ID?>" name="q" autocomplete="off" placeholder="Что вы ищете?"/>
		    <!--&nbsp;<input name="s" type="submit" value="<?//=GetMessage("CT_BST_SEARCH_BUTTON");?>" /-->
		</form>

	</div>
<?endif?>
<script>
	BX.ready(function(){
		new JCTitleSearch({
			'AJAX_PAGE' : '<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>',
			'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
			'INPUT_ID': '<?echo $INPUT_ID?>',
			'MIN_QUERY_LEN': 2
		});
	});
</script>
