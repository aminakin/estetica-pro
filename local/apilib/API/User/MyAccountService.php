<?php

namespace ApiLib\API\User;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\Response\MyAccountRespnse;

class MyAccountService
{
    private $require = [
        'name' => 'Имя обязательно для заполнения',
        'last_name' => 'Фамилия обязательна для заполнения',
        'second_name' => 'Отчество обязательно для заполнения',
        'email' => 'E-mail обязателен для заполнения',
    ];

    public function show()
    {
        global $USER;
        $rsUser = \CUser::GetByID($USER->GetID()); $arUser = $rsUser->Fetch();

        return MyAccountRespnse::data($arUser);
    }

    public function update(array $values)
    {
        global $USER;
        $errors = [];
        foreach ($this->require as $requireKey => $requireItem) {
            if (!isset($values[$requireKey]) || empty($values[$requireKey])) {
                $errors[$requireKey] = $requireItem;
            }
        }

        if (count($errors)) {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $errors],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        $data = [
            'NAME' => $values['name'],
            'LAST_NAME' => $values['last_name'],
            'SECOND_NAME' => $values['second_name'],
            'EMAIL' => $values['email'],
            'PERSONAL_PHONE' => $values['phone'],
            'PERSONAL_BIRTHDAY' => $values['birthday'],
        ];

        $oUser = new \CUser();

        return [$oUser->Update($USER->GetID(), $data), $oUser->LAST_ERROR];
    }

    public function destroy($userId)
    {
        return (new \CUser())->Update($userId, ['ACTIVE' => 0]);
    }
}