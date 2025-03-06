<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="bx-auth">
<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
	<?if (strlen($arResult["BACKURL"]) > 0): ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? endif ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">
	<div class="data-table bx-changepass-table">
			<div class="input-container">
                <input type="text" placeholder="<?=GetMessage("AUTH_LOGIN")?>" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" class="bx-auth-input" />
			</div>
			<div style="display: none">
				<div><span class="starrequired">*</span><?=GetMessage("AUTH_CHECKWORD")?></div>
				<div><input type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" class="bx-auth-input" /></div>
			</div>
			<div>
				<div class="input-container">
                    <input type="password" placeholder="<?=GetMessage("AUTH_NEW_PASSWORD_REQ")?>" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" class="bx-auth-input" autocomplete="off" />
				    <div class="message-input">
                        от 6 символов
                    </div>
                    <span class="show-password" onclick="showPassword(this)"></span>
                </div>
                <?if($arResult["SECURE_AUTH"]):?>
                    <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
                    <noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
                    </noscript>
                    <script type="text/javascript">
                        document.getElementById('bx_auth_secure').style.display = 'inline-block';
                    </script>
                <?endif?>
			</div>
			<div style="display: none !important;">
				<div><span class="starrequired">*</span><?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?></div>
				<div><input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="bx-auth-input" autocomplete="off" /></div>
			</div>
		<?if($arResult["USE_CAPTCHA"]):?>
			<div>
				<div></div>
				<div>
					<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
				</div>
			</div>
			<div>
				<div><span class="starrequired">*</span><?echo GetMessage("system_auth_captcha")?></div>
				<div><input type="text" name="captcha_word" maxlength="50" value="" /></div>
			</div>
		<?endif?>
        <div><input type="submit" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" /></div>
	</div>


</form>
<? if($arResult['SHOW_FORM'] == false){?>
<script type="text/javascript">
//document.bform.USER_LOGIN.focus();
setTimeout(function(){
    location.href = '/';
}, 2000);

</script>
<?}?>


<?ShowMessage($arParams["~AUTH_RESULT"]);?>
<script type="text/javascript">
//document.bform.USER_LOGIN.focus();
function showPassword(node) {
    inputWrap = $(node).closest('.input-container');
    if (inputWrap.hasClass('show-password--active')) {
        inputWrap.removeClass('show-password--active').find('input').prop('type', 'password');
    } else {
        inputWrap.addClass('show-password--active').find('input').prop('type', 'text');
    }
}
</script>
</div>