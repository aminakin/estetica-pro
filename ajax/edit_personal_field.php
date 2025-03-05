<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule('main'))
    return;


$user = new CUser;



if($_REQUEST['field_name'] == "NEW_PASSWORD") {
    $fields = Array(
        'PASSWORD' => $_REQUEST['field_value'],
        'CONFIRM_PASSWORD' => $_REQUEST['field_value']
    );
}  else {
    $fields = Array(
        $_REQUEST['field_name'] => $_REQUEST['field_value']
    );
}
$result = $user->Update($user->GetID(), $fields);

print_r($result);

?>