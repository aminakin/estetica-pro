<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (strlen($arResult["PAGE_URL"]) > 0)
{
	?>
    <div class="social">
            <?if (is_array($arResult["BOOKMARKS"]) && count($arResult["BOOKMARKS"]) > 0)
            {
                foreach($arResult["BOOKMARKS"] as $name => $arBookmark)
                {
                    ?><?=str_ireplace('</a>', $arResult['SOC'][$name].'</a>', $arBookmark["ICON"])?><?
                }
            }
            ?>
            <div class="clear"></div>
    </div>
<?}
else
{
	?><?=GetMessage("SHARE_ERROR_EMPTY_SERVER")?><?
}
?>