<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();


CJSCore::Init();
?>

<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>
<?if($arResult["FORM_TYPE"] == "login"):?>
    <form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" >
    	<?if($arResult["BACKURL"] <> ''):?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<?endif?>
		
		<?foreach ($arResult["POST"] as $key => $value):?>
			<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?endforeach?>
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
		<label for="USER_LOGIN">Логин:</label>
        <input type="text" name="USER_LOGIN" placeholder=""/>
        <script>
			BX.ready(function() {
				var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
				if (loginCookie)
				{
					var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
					var loginInput = form.elements["USER_LOGIN"];
					loginInput.value = loginCookie;
				}
			});
		</script>
		<label for="USER_PASSWORD">Пароль:</label>
		<input type="password" placeholder="" name="USER_PASSWORD" maxlength="50" size="17" autocomplete="off" />
		<?if($arResult["SECURE_AUTH"]):?>
			<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
				<div class="bx-auth-secure-icon"></div>
			</span>
			<noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
			</noscript>
			<script type="text/javascript">
				document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
			</script>
		<?endif?>
        <div class="auth-item-wrapper">
            <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                <label for="USER_REMEMBER" class="remember-me"><input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" /><?=GetMessage("AUTH_REMEMBER_ME")?></label>
            <?endif?>
            <a href="#forgot" class="linkOpen forgot-pswd">Забыли пароль?</a>
        </div>

        <div class="align-center">
        	<input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />
        </div>
    </form>
    <div class="bottom">
    	<a href="/login/registration.php">Регистрация</a> / <a href="#forgot" class="linkOpen">Забыли пароль?</a>
    </div>

<?else:?>
	<?//если авторизован ?>

	<form action="<?=$arResult["AUTH_URL"]?>">
		<table width="95%">
			<tr>
				<td align="center">
					<?=$arResult["USER_NAME"]?><br />
					[<?=$arResult["USER_LOGIN"]?>]<br />
					<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
				</td>
			</tr>
			<tr>
				<td align="center">
				<?foreach ($arResult["GET"] as $key => $value):?>
					<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
				<?endforeach?>
				<input type="hidden" name="logout" value="yes" />
				<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
				</td>
			</tr>
		</table>
	</form>
<?endif;?>
