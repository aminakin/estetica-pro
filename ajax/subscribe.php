<?php 

	/**
	 * request_method: POST
	 * @param string email
	 * @return JSON:
	 * {status: 'ok|error', message: 'any text for user'}
	 */

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $APPLICATION;

	$APPLICATION->IncludeComponent(
		'machaon:subscribe.easy', 
		'', 
		array(
			'RUBRICS' => array(5)
		)
	);