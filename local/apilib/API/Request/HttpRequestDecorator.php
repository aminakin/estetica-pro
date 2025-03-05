<?php

namespace ApiLib\API\Request;

use Bitrix\Main\HttpRequest;
use Bitrix\Main\Server;
use Bitrix\Main\Type;

class HttpRequestDecorator
{
    public static function decorator(HttpRequest &$request)
    {
        if (method_exists($request, 'isJson')) {
            if ($request->isJson()) {
                $request->decodeJson();
                $values = $request->getJsonList()->getValues();
                foreach ($values as $key => $value) {
                    $request->set($key, $value);
                }
            }
        } else {
            $accept = $request->getHeader('Accept');
            if ($accept == 'application/json') {
                $data = $request::getInput();
                $objData = json_decode($data);
                $values = $request->getValues();
                $values = array_merge($values, (array)$objData);
                $request->setValues($values);
            }
        }
    }
}
