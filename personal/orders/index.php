<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Профессиональные материалы для маникюра и наращивания ресниц. Известные и востребованные бренды по официальным ценам производителей. Отправляем в любой город РФ. Быстрая онлайн-оплата заказа на сайте.");
$APPLICATION->SetTitle("Мои заказы");
?>
<?global $USER;
	if ($USER->IsAuthorized()) {
	$APPLICATION->IncludeComponent(
	    "bitrix:menu",
	    "personal_menu",
	    array(
	        "ALLOW_MULTI_SELECT" => "N",
	        "CHILD_MENU_TYPE" => "left",
	        "COMPONENT_TEMPLATE" => ".default",
	        "DELAY" => "N",
	        "MAX_LEVEL" => "1",
	        "MENU_CACHE_GET_VARS" => "",
	        "MENU_CACHE_TIME" => "3600",
	        "MENU_CACHE_TYPE" => "N",
	        "MENU_CACHE_USE_GROUPS" => "Y",
	        "ROOT_MENU_TYPE" => "profil_menu",
	        "USE_EXT" => "N"
	    )
	); ?>

    <div class="main_profile_wrapper">
    	
        <?$APPLICATION->IncludeComponent(
			"bitrix:sale.personal.order", 
			"estetica", 
			array(
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"CACHE_GROUPS" => "Y",
				"CACHE_TIME" => "3600",
				"CACHE_TYPE" => "A",
				"CUSTOM_SELECT_PROPS" => array(
				),
				"DETAIL_HIDE_USER_INFO" => array(
					0 => "0",
				),
				"HISTORIC_STATUSES" => array(
				),
				"NAV_TEMPLATE" => "",
				"ORDERS_PER_PAGE" => "10",
				"ORDER_DEFAULT_SORT" => "STATUS",
				"PATH_TO_BASKET" => "/personal/cart",
				"PATH_TO_CATALOG" => "/catalog/",
				"PATH_TO_PAYMENT" => "/personal/payment.php",
				"PROP_1" => array(
				),
				"PROP_2" => array(
				),
				"RESTRICT_CHANGE_PAYSYSTEM" => array(
					0 => "F",
				),
				"SAVE_IN_SESSION" => "Y",
				"SEF_FOLDER" => "/personal/orders/",
				"SEF_MODE" => "Y",
				"SET_TITLE" => "Y",
				"STATUS_COLOR_F" => "gray",
				"STATUS_COLOR_N" => "green",
				"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
				"COMPONENT_TEMPLATE" => "estetica",
				"STATUS_COLOR_D" => "gray",
				"DISALLOW_CANCEL" => "N",
				"REFRESH_PRICES" => "N",
				"SEF_URL_TEMPLATES" => array(
					"list" => "",
					"detail" => "detail/#ID#/",
					"cancel" => "cancel/#ID#",
				)
			),
			false
		); ?>
		<? }else{?>
        <?$APPLICATION->SetTitle("Авторизация");?>
        <div class="auth_page" id="auth">
            <? $APPLICATION->IncludeComponent(
                "bitrix:system.auth.form",
                "auth_header",
                array(
                    "FORGOT_PASSWORD_URL" => "/login/forgot.php",
                    "PROFILE_URL" => "/personal",
                    "REGISTER_URL" => "/login/registration.php",
                    "SHOW_ERRORS" => "Y",
                    "COMPONENT_TEMPLATE" => "auth_header"
                ),
                   false
            );
            ?>
            <div class="info_bonus"><?= GetMessage('AUTH_BONUS'); ?></div>
        </div> 
    <?} ?>
    </div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>