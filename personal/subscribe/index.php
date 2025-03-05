<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подписка на рассылки");
?>
<?
global $USER;
if ($USER->IsAuthorized()){ 
	
	$APPLICATION->IncludeComponent(
		"bitrix:menu", 
		"personal_menu", 
		Array(
			"ALLOW_MULTI_SELECT" => "N",	
			"CHILD_MENU_TYPE" => "left",	
			"DELAY" => "N",	
			"MAX_LEVEL" => "1",	
			"MENU_CACHE_GET_VARS" => "",	
			"MENU_CACHE_TIME" => "3600",	
			"MENU_CACHE_TYPE" => "N",	
			"MENU_CACHE_USE_GROUPS" => "Y",	
			"ROOT_MENU_TYPE" => "profil_menu",	
			"USE_EXT" => "N",	
			"COMPONENT_TEMPLATE" => ".default"
		),
		false
	);
	?>
	
	<div class="bl-left">
		<?
		$APPLICATION->IncludeComponent(
	"bitrix:subscribe.form", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"PAGE" => "#SITE_DIR#personal/subscribe/subscr_edit.php",
		"SHOW_HIDDEN" => "N",
		"USE_PERSONALIZATION" => "Y",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);
		?>
	</div>
	<div style="clear: both;"></div>
<?}
else{
	$APPLICATION->IncludeComponent(
		"bitrix:main.profile",
		"",
		Array(
			"CHECK_RIGHTS" => "N",
			"SEND_INFO" => "N",
			"SET_TITLE" => "Y",
			"USER_PROPERTY" => array()
		)
	);
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>