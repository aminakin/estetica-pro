<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>
<?if ($arResult["FORM_NOTE"])
{?>
    <center>Спасибо. Ваша заявка принята. Мы свяжемся с вами в ближайшее время</center>
<?}?>


<?if ($arResult["isFormNote"] != "Y")
{
?>
    <?=$arResult["FORM_HEADER"]?>

<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
<div>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
		if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
		{
            if ($FIELD_SID=='COURSE_ID'){
                $arQuestion['HTML_CODE'] = str_ireplace('value=""', 'value="'.$APPLICATION->GetTitle().'"', $arQuestion['HTML_CODE']);
            }
			echo $arQuestion["HTML_CODE"];
		}
		else
		{
	    if ($FIELD_SID=='PHONE'){
	        $arQuestion['HTML_CODE'] = str_ireplace('name', 'autocomplete="tel" name', $arQuestion['HTML_CODE']);
        }
        if ($FIELD_SID=='EMAIL'){
            $arQuestion['HTML_CODE'] = str_ireplace('type="text', 'type="email', $arQuestion['HTML_CODE']);
        }?>
		<div>
            <?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
            <span class="error-fld" title="<?=htmlspecialcharsbx($arResult["FORM_ERRORS"][$FIELD_SID])?>"></span>
            <?endif;?>
            <?
            if ($FIELD_SID=='SOGL')
            {
                $inp = '';
                if ($arQuestion["REQUIRED"] == "Y")
                {
                    $inp .= ' required ';
                }
                $arQuestion["HTML_CODE"] = str_ireplace('<input', '<input '.$inp, $arQuestion["HTML_CODE"]);
                echo str_ireplace('<label', '<label style="display: none;"', $arQuestion["HTML_CODE"]);
                echo $arQuestion["CAPTION"];
                if ($arQuestion["REQUIRED"] == "Y") echo $arResult["REQUIRED_SIGN"];
            }
            else
            {?>
                <?$inp = 'placeholder="'.$arQuestion['CAPTION'];
                if ($arQuestion["REQUIRED"] == "Y")
                {
                $inp .= '*" required ';
                }
                else
                {
                $inp .= '"';
                }
                $arQuestion["HTML_CODE"] = str_ireplace('<input', '<input '.$inp, $arQuestion["HTML_CODE"]);?>
			    <?=$arQuestion["HTML_CODE"]?>
            <?}?>
		</div>
	<?
		}
	} //endwhile
	?>
<?
if($arResult["isUseCaptcha"] == "Y")
{
?>
		<div class="captcha_t">
			<?/*<b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b>*/?>
		</div>
		<div>
			<input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
		</div>
		<div>
			<?//=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?//=$arResult["REQUIRED_SIGN"];?>
			<input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" />
		</div>
<?
} // isUseCaptcha
?>
    <div class="center">
		<input class="BlackBtn" <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
	</div>
</div>
<p class="fr">
<?=$arResult["REQUIRED_SIGN"];?> - <?=GetMessage("FORM_REQUIRED_FIELDS")?>
</p>
<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>