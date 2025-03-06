<?php
include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;

if (!CModule::IncludeModule('main') || !CModule::IncludeModule("sale"))
    return;

$userID = $USER->getid();
$result = [];

if (isset($_POST['event']) && $_POST['event'] !== '') {
    $error = [];
    switch ($_POST['event']) {
        case 'UPDATE_PROFILE':
            $birthday = $_POST['PERSONAL_BIRTHDAY'];

            if ($birthday !== '') {
                $birthdayArray = explode('.', $birthday);
                if (count($birthdayArray) < 3) {
                    $error['PERSONAL_BIRTHDAY'] = 'ERROR_BIRTHDAY_FORMAT';
                } else {
                    $errorFlag = false;
                    foreach ($birthdayArray as $item) {
                        if ($item == '')
                            $errorFlag = true;
                    }

                    if ($errorFlag)
                        $error['PERSONAL_BIRTHDAY'] = 'ERROR_BIRTHDAY_FORMAT';

                    unset($errorFlag);
                }
            }

            if ($_POST['NAME'] == '') {
                $error['NAME'] = 'ERROR_EMPTY_FIELD';
            }

            if ($_POST['LAST_NAME'] == '') {
                $error['LAST_NAME'] = 'ERROR_EMPTY_FIELD';
            }

            if ($_POST['SECOND_NAME'] == '') {
                $error['SECOND_NAME'] = 'ERROR_EMPTY_FIELD';
            }

            if ($_POST['EMAIL'] == '') {
                $error['EMAIL'] = 'ERROR_EMPTY_FIELD';
            } else {
                if (!filter_var($_POST['EMAIL'], FILTER_VALIDATE_EMAIL)) {
                    $error['EMAIL'] = 'ERROR_EMAIL_FORMAT';
                }
            }


            if ($_POST['PERSONAL_PHONE'] == '') {
                $error['PERSONAL_PHONE'] = 'ERROR_EMPTY_FIELD';
            } else {
                $_POST['PERSONAL_PHONE'] = preg_replace('/[\D]+/', '', $_POST['PERSONAL_PHONE']);
                if (strlen($_POST['PERSONAL_PHONE']) != 11) {
                    $error['PERSONAL_PHONE'] = 'ERROR_PERSONAL_PHONE_FORMAT';
                }
            }

            if (count($error)) {
                $result['success'] = false;
                $result['error'] = $error;
            } else {
                $fields = [
                    'NAME' => $_POST['NAME'],
                    'LAST_NAME' => $_POST['LAST_NAME'],
                    'SECOND_NAME' => $_POST['SECOND_NAME'],
                    'PERSONAL_PHONE' => $_POST['PERSONAL_PHONE'],
                    'PERSONAL_BIRTHDAY' => $_POST['PERSONAL_BIRTHDAY'],
                ];
                (new CUser)->Update($userID, $fields);
                $result['success'] = true;
            }
            break;
        case 'UPDATE_LOCATION':

            $fields = [
                'CITY' => [
                    'VALUE' => $_POST['CITY'],
                    'ID' => $_POST['CITY_id'],
                    'TYPE' => $_POST['CITY_type'],
                ],
                'STREET' => [
                    'VALUE' => $_POST['STREET'],
                    'ID' => $_POST['STREET_id'],
                    'TYPE' => $_POST['STREET_type'],
                ],
                'HOUSE' => [
                    'VALUE' => $_POST['HOUSE'],
                    'ID' => $_POST['HOUSE_id'],
                    'TYPE' => $_POST['HOUSE_type'],
                ],
                'INDEX' => [
                    'VALUE' => $_POST['INDEX'],
                    'ID' => $_POST['INDEX_id'],
                    'TYPE' => $_POST['INDEX_type'],
                ]
            ];


            foreach ($fields as $k => $v) {
                if ($v['VALUE'] == '')
                    $error[$k] = 'ERROR_EMPTY_FIELD';
                elseif ($v['ID'] == '')
                    $error[$k] = 'ERROR_EMPTY_ID';
                elseif ($v['TYPE'] == '')
                    $error[$k] = 'ERROR_EMPTY_TYPE';
            }

            if (count($error)) {
                $result['success'] = false;
                $result['error'] = $error;
            } else {
                foreach ($fields as $v) {
                    $arFields = array('VALUE' => $v['VALUE']);

                    if ($v['TYPE'] == 'LOCATION') {
                        $arFields['TYPE'] = 'LOCATION';
                        $arFields['VALUE'] = CSaleLocation::GetList(array(), array('CITY_NAME' => $v['VALUE']))->GetNext()['ID'];
                    }

                    CSaleOrderUserPropsValue::Update($v['ID'], $arFields);
                }

                $result['success'] = true;
            }
            break;
        case 'UPDATE_PASSWORD':
            $currentPassword = $_POST['CURRENT_PASSWORD'];
            $newPassword = $_POST['NEW_PASSWORD'];
            $confirmPassword = $_POST['NEW_PASSWORD_CONFIRM'];

            /*
            $securityPolicy = \CUser::GetGroupPolicy([2]);
            $errors = (new \CUser)->CheckPasswordAgainstPolicy($currentPassword, $securityPolicy);
             * */
            if ($currentPassword == '') {
                $error['CURRENT_PASSWORD'] = 'ERROR_EMPTY_FIELD';
            } elseif (strlen($currentPassword) < 6) {
                $error['CURRENT_PASSWORD'] = 'ERROR_PASS_LENGTH';
            } else {
                $rsUser = CUser::GetByID($userID);
                $checkUserPassword = false;

                if ($arUser = $rsUser->Fetch()) {
                    $hashLength = strlen($arUser["PASSWORD"]);

                    if ($hashLength > 100) {
                        $salt = substr($arUser["PASSWORD"], 3, 16);
                        $hashPassword = crypt($currentPassword, "$6$" . $salt . "$");
                    } else if ($hashLength > 32) {
                        $salt = substr($arUser["PASSWORD"], 0, $hashLength - 32);
                        $hashPassword = $salt . md5($salt . $currentPassword);
                    } else {
                        $salt = "";
                        $hashPassword = $arUser["PASSWORD"];
                    }

                    if ($hashPassword != $arUser["PASSWORD"]) {
                        $error['CURRENT_PASSWORD'] = 'ERROR_PASS_INVALID';
                    }
                } else {
                    $error['CURRENT_PASSWORD'] = 'ERROR_USER_NOT_FOUND';
                }
            }

            $passInvalid = false;

            if ($newPassword == '') {
                $error['NEW_PASSWORD'] = 'ERROR_EMPTY_FIELD';
                $passInvalid = true;
            } elseif (strlen($newPassword) < 6) {
                $error['NEW_PASSWORD'] = 'ERROR_PASS_LENGTH';
                $passInvalid = true;
            }

            if ($confirmPassword == '') {
                $error['NEW_PASSWORD_CONFIRM'] = 'ERROR_EMPTY_FIELD';
                $passInvalid = true;
            } elseif (strlen($confirmPassword) < 6) {
                $error['NEW_PASSWORD_CONFIRM'] = 'ERROR_PASS_LENGTH';
                $passInvalid = true;
            }

            if ($newPassword != $confirmPassword && !$passInvalid) {
                $error['NEW_PASSWORD'] = 'ERROR_PASS_NOT_EQ';
                $error['NEW_PASSWORD_CONFIRM'] = 'ERROR_PASS_NOT_EQ';
            }

            if (count($error)) {
                $result['success'] = false;
                $result['error'] = $error;
            } else {
                $fields = array(
                    "PASSWORD" => $newPassword,
                    "CONFIRM_PASSWORD" => $confirmPassword
                );

                $changePassResult = $USER->Update($userID, $fields);
                if (!$changePassResult) {
                    $result['success'] = false;
                    $result['error'] = [
                        'NEW_PASSWORD' => 'ERROR_PASS_NOT_SAVE',
                        'CONFIRM_PASSWORD' => 'ERROR_PASS_NOT_SAVE',
                    ];
                } else {
                    $result['success'] = true;
                }
            }
            break;
        case 'REMOVE_USER':
            $result['success'] = true;
            /*Удаление аккаунта не целесообразно, так как возврата не будет и еще нужно удалять заказы,
            так что лучше просто отключить его, чтобы можно было восстановить*/
            (new CUser)->Update($userID, ['ACTIVE' => 0]);
            break;
        case 'UPDATE_SUBSCRIBE':
            $subscribe = $_POST['SUBSCRIBE'] == 'Y';
            $personal = $_POST['PERSONAL_DATA'] == 'Y';
           
            if (!$personal) {
                $result['success'] = false;
                $result['error'] = ['PERSONAL_DATA' => 'ERROR_EMPTY_PERSONAL_DATA'];
            } else {
                $res = \Bitrix\Sender\Subscription::getMailingList([]);
                $subscribeList = [];
                $uEmail = $USER->getEmail();

                

                foreach ($res as $mailList) {
                    $subscribeList[] = $mailList['ID'];
                }

                $contactId = \Bitrix\Sender\ContactTable::addIfNotExist(['EMAIL' => $uEmail]);
                $contact = new \Bitrix\Sender\Entity\Contact($contactId);
                if ($subscribe) {
                    foreach ($subscribeList as $item) {
                        $contact->subscribe($item);
                        $fields = Array( "UF_SUBSCRIBES" => '1',); 
                        $USER->Update($USER->GetID(), $fields);
                    }
                } else {
                    foreach ($subscribeList as $item) {
                        $fields = Array( "UF_SUBSCRIBES" => '0',); 
                        $USER->Update($USER->GetID(), $fields);
                        $contact->unsubscribe($item);
                        $contact->removeById($contactId);
                    }
                }
                $result['success'] = true;
            }
            break;
        default:
            $result['success'] = false;
            $result['message'] = 'undefined event';
            break;
    }

} else {
    $result['success'] = false;
    $result['message'] = 'undefined event';
}

echo json_encode($result);