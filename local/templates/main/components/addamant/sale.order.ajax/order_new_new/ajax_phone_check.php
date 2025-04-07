<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$phone = $_REQUEST['phone'];
$filter = Array("PERSONAL_PHONE" => $phone, "ACTIVE" => "Y");
$rsUser = CUser::GetList(($by="id"), ($order="desc"), $filter);

if($rsUser->is_filtered && $arResult = $rsUser->Fetch()){	
	if($arResult['PERSONAL_PHONE']){
		echo "success";
	}
	die();
}

echo "fail"?>