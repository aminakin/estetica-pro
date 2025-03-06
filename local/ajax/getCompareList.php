<?php 
	/**
	 * Ajax-контроллер для работы со списокм сравнения
	 * 
	 * Добавить
	 * ?action=ADD_TO_COMPARE_LIST&id=96
	 *
	 * Удалить
	 * ?action=DELETE_FROM_COMPARE_LIST&id=96
	 */
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	/** Чтобы по умолчанию режим ajax был включен **/
	$_REQUEST["ajax_action"] = "Y";

	$APPLICATION->IncludeComponent(
		"bitrix:catalog.compare.list", 
		"get_list", 
		array(
			"ACTION_VARIABLE" => "action",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"COMPARE_URL" => SITE_DIR."catalog/compare/",
			"COMPONENT_TEMPLATE" => "get_list",
			"DETAIL_URL" => "",
			"IBLOCK_ID" => "3",
			"IBLOCK_TYPE" => "catalog",
			"NAME" => "CATALOG_COMPARE_LIST",
			"POSITION" => "top left",
			"POSITION_FIXED" => "Y",
			"PRODUCT_ID_VARIABLE" => "id"
		),
		false
	);

