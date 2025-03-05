<?php

namespace ApiLib\AccessClass;

use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Authentication;

abstract class Access
{
    protected function errorAccess($code, $message, $headers = [])
    {
        if (!is_string($message))
            $message = json_encode($message);
        $response = Context::getCurrent()->getResponse()->setStatus($code)->setContent($message);
        if (count($headers)) {
            foreach($headers as $addHeader)
            if (isset($addHeader['name']) && isset($addHeader['value'])) {
                $response->addHeader($addHeader['name'], $addHeader['value']);
            }
        }

        Application::getInstance()->end($code, $response);
    }
}