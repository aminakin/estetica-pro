<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}


/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

//one css for all system.auth.* forms
//$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>


<div class="bx-authform forpass">
<?
if(!empty($arParams["~AUTH_RESULT"])):
	$text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
?>
	<div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?endif?>

<div class="forpassErrorMessage"></div>

	<p class="war"><?=$APPLICATION->arAuthResult['MESSAGE'];?></p>
	

	<form name="bform" method="post" id="btnForpasswd" target="_top" action="<?=$arResult["AUTH_URL"]?>" >

<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
		<input type="hidden" name="AUTH_FORM" value="Y">
		<input type="hidden" name="TYPE" value="SEND_PWD">

		<div class="bx-authform-formgroup-container">
			
			<div class="bx-authform-input-container">
				<div class="bx-authform-text-forgot"><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></div>
				<label for="USER_LOGIN"><?=GetMessage("AUTH_EMAIL_FORGOT")?></label>
				<input type="text" name="USER_LOGIN" maxlength="255" class="forpass-input" value="<?=$arResult["LAST_LOGIN"]?>" />
				<input type="hidden" name="USER_EMAIL" />
			</div>
		</div>

		<div class="bx-authform-formgroup-container">
			<input type="submit" class="btn btn-primary btnForpasswd"  name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
		</div>

	</form>

</div>

<script type="text/javascript">
document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
document.bform.USER_LOGIN.focus();
</script>
