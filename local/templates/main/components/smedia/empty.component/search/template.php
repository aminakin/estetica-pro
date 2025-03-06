<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $CONTAINER_I_ID;
if (empty($CONTAINER_I_ID))
	$CONTAINER_I_ID = 1;
else 
	$CONTAINER_I_ID++;
?>
<div id="title-search<?=$CONTAINER_I_ID?>" class="title-search">
    <form action="/search/index.php">
    	<? //var_export($arResult["REQUEST"]);?>
        <input type="text" class="search-line title-search-input" value="" id="title-search-input<?=$CONTAINER_I_ID?>" name="q" autocomplete="off" placeholder="Поиск товаров"/>
        <input type="submit" value="Найти" class="title-search-submit">
    </form>
</div>
