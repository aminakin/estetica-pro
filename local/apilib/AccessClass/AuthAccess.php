<?php

namespace ApiLib\AccessClass;

use Bitrix\Main\HttpRequest;

class AuthAccess extends Access
{
    public function handle(HttpRequest $request)
    {
        global $USER;
        if (!$USER->IsAuthorized() || !check_bitrix_sessid())
            $this->errorAccess(401, ['TYPE' => 'ERROR', 'MESSAGE' => 'Not auth'], [['name' => 'Content-Type', 'value' => 'application/json']]);
    }
}