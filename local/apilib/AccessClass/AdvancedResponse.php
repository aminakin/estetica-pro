<?php

namespace ApiLib\AccessClass;

class AdvancedResponse extends Access
{
    public function getErrorAccess($code, $message, $headers = [])
    {
        $this->errorAccess($code, $message, $headers);
    }
    public static function response($code, $message, $headers = [])
    {
        (new self)->getErrorAccess($code, $message, $headers);
    }
}