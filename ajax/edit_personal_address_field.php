<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule('main') || !CModule::IncludeModule("sale"))
    return;

print_r($_REQUEST);

$arFields = array('VALUE' => $_REQUEST['field_value']);

if($_REQUEST['field_type'] == 'LOCATION') {
    $arFields['TYPE'] = 'LOCATION';
    $arFields['VALUE'] = CSaleLocation::GetList(array(), array('CITY_NAME' => $_REQUEST['field_value']))->GetNext()['ID'];
}
print_r($arFields);
CSaleOrderUserPropsValue::Update(
    $_REQUEST['field_id'],
    $arFields
);



?>