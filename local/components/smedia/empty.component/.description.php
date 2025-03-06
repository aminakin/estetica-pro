<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("CD_BSF_NAME"),
	"DESCRIPTION" => GetMessage("CD_BSF_DESCRIPTION"),
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "service",
		"CHILD" => array(
			"ID" => "empty",
			"NAME" => GetMessage("CD_BSF_SERVICE")
		)
	),
);

?>