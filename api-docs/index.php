<?php
/* inc composer */
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if (file_exists($autoloadPathDbconn = realpath(__DIR__.'/../local/vendor/autoload.php'))) {
    require_once($autoloadPathDbconn);
}

\CModule::IncludeModule('sberbank.ecom2');
\CModule::IncludeModule('sale');
\Bitrix\Sale\PaySystem\Manager::includeHandler('sberbank_ecom2');
use ApiLib\Swagger\AutoloadAPI;
AutoloadAPI::generateDoc();


?>

<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="stylesheet" type="text/css" href="./swagger-ui.css" />
    <link rel="stylesheet" type="text/css" href="index.css" />
    <link rel="icon" type="image/png" href="./favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="./favicon-16x16.png" sizes="16x16" />
  </head>

  <body>
    <div id="swagger-ui"></div>
    <script src="./swagger-ui-bundle.js" charset="UTF-8"> </script>
    <script src="./swagger-ui-standalone-preset.js" charset="UTF-8"> </script>
    <script src="./swagger-initializer.js" charset="UTF-8"> </script>
  </body>
</html>
