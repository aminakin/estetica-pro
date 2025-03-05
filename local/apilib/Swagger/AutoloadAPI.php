<?php

namespace ApiLib\Swagger;

use Bitrix\Main\Engine\Controller;

class AutoloadAPI extends Controller
{
    // coomand to generate -  estetica-pro.ru/local/vendor/zircote/swagger-php/bin/openapi src -o openapi.json

    /**
        * @OA\Info(title="Bitrix API", version="0.1")
    */
    public static function generateDoc(){
        $libPath = realpath(__DIR__ . '/../');
        $openapi = \OpenApi\Generator::scan([$libPath]);
//        header('Content-Type: application/x-yaml');
        return file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/openapi.json', $openapi->toJson());
    }
}