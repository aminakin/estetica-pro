<?php

namespace ApiLib\API\User;

use Bitrix\Main\UserTable;
use Illuminate\Support\Collection;

class PushSettingsService
{
    public function index($userId = 0)
    {
        global $USER;

        if ($userId === 0)
            $userId = $USER->GetID();

        $rsUser = \CUser::GetByID($userId);
        $arUser = $rsUser->Fetch();
        $value = $arUser['UF_SEND_PUSH'];

        if ($value === false || $value == "0")
            return false;

        return true;
    }

    public function update(array $data)
    {
        global $USER;
        global $USER_FIELD_MANAGER;

        $userId = $USER->GetID();
        $value = $data['value'];

        $USER_FIELD_MANAGER->Update('USER', $userId, ['UF_SEND_PUSH' => $value]);

        return $value;
    }

    public function getAllDisable()
    {
        $query = UserTable::query();
        $query->where('UF_SEND_PUSH', '0');
        $query->setSelect(['ID']);
        $arIds = Collection::make($query->exec()->fetchAll());

        return $arIds->pluck('ID')->toArray();
//        \CUser::GetList('', '', [], []);
    }
}
