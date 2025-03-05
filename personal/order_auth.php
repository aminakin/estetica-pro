<?/* require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
header_remove();
global $USER;
if ($USER->IsAuthorized()){ 
	header("Location: /personal/order.php");
}
?>

<div class="order-block desktop">
	<div class="order-auth">
		<div class="h2">Войти</div>
		<?
		
		    $APPLICATION->IncludeComponent(
	"bitrix:system.auth.form", 
	"auth.form", 
	array(
		"FORGOT_PASSWORD_URL" => "/login/forgot.php",
		"PROFILE_URL" => "/personal",
		"REGISTER_URL" => "/login/registration.php",
		"SHOW_ERRORS" => "Y",
		"COMPONENT_TEMPLATE" => "auth.form"
	),
	false
);
		
		?>
	</div>
	<div class="order-register">
		<div class="h2">Зарегистрироваться</div>
		<p class="order-description">Зарегистрируйтесь, чтобы ваши данные и&nbsp;заказ сохранялись в личном кабинете</p>
		<a href="#register" class="btn btn-link whiteBtn buyBtn linkOpen">Регистрация</a>
	</div>
	<div class="order-unregister">
		<div class="h2">Без регистрации</div>
		<p class="order-description">оформление заказа без регистрации</p>
		<a href="/personal/order.php" class="btn btn-link whiteBtn">оформить заказ</a>
	</div>
</div>
<div class="order-block mobile">
	<div class="order-auth">
		<a href="#auth" class="enterLink linkOpen">Войти</a>
	</div>
	<div class="order-register">
		<a href="#register" class="enterLink linkOpen">Регистрация</a>
	</div>
	<div class="order-unregister">
		<a href="/personal/order.php" class="enterLink">Без регистрации</a>
	</div>
</div>

<h2 class="order-delivery-title">Доставка</h2>
<p class="order-delivery-text">
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include", 
			".default", 
			array( 
				"AREA_FILE_SHOW" => "file", 
				"PATH" => SITE_DIR . "include/order_auth_delivery.php", 
				"EDIT_TEMPLATE" => "" ), 
				false 
		);?>
	
</p>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");*/?>