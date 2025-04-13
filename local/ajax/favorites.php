<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
global $USER;

$APPLICATION->SetTitle(" ");
$GLOBALS['APPLICATION']->RestartBuffer();

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;

$application = Application::getInstance();
$context = $application->getContext();

/* Избранное */

if ($_GET['id']) {
    if (!$USER->IsAuthorized()) // Для неавторизованного
    {
        $arElements = unserialize($APPLICATION->get_cookie('favorites'));

        if (is_array($arElements) && !in_array($_GET['id'], $arElements)) {
            $arElements[] = $_GET['id'];
            $result = 1; // Датчик. Добавляем
        } else {
            if(is_array($arElements)){
                $key = array_search($_GET['id'], $arElements); // Находим элемент, который нужно удалить из избранного
                unset($arElements[$key]);
                $result = 2; // Датчик. Удаляем
            }  
        }

        $cookie = new Cookie("favorites", serialize($arElements), time() + 60 * 60 * 24 * 60);
        $cookie->setDomain($context->getServer()->getHttpHost());
        $cookie->setHttpOnly(false);
        $context->getResponse()->addCookie($cookie);
        $context->getResponse()->flush("");
    } else { // Для авторизованного
        $idUser = $USER->GetID();
        $rsUser = CUser::GetByID($idUser);
        $arUser = $rsUser->Fetch();
        $arElements = $arUser['UF_FAVORITES'];  // Достаём избранное пользователя

        if (is_array($arElements) && !in_array($_GET['id'], $arElements)) // Если еще нету этой позиции в избранном
        {
            $arElements[] = $_GET['id'];
            $result = 1;
        } else {
            $key = array_search($_GET['id'], $arElements); // Находим элемент, который нужно удалить из избранного
            unset($arElements[$key]);
            $result = 2;
        }
        $USER->Update($idUser, array("UF_FAVORITES" => $arElements)); // Добавляем элемент в избранное
    }
}

if ($_GET['del']) {
    if (!$USER->IsAuthorized()) {
        $arElements = unserialize($APPLICATION->get_cookie('favorites'));

        $arElements = array();

        $cookie = new Cookie("favorites", serialize($arElements), time() + 60 * 60 * 24 * 60);
        $cookie->setDomain($context->getServer()->getHttpHost());
        $cookie->setHttpOnly(false);
        $context->getResponse()->addCookie($cookie);
        $context->getResponse()->flush("");
    } else {
        $idUser = $USER->GetID();
        $rsUser = CUser::GetByID($idUser);
        $arUser = $rsUser->Fetch();
        $arElements = $arUser['UF_FAVORITES'];

        $arElements = array();

        $USER->Update($idUser, array("UF_FAVORITES" => $arElements));
    };

    $result = 1;
}

/* Избранное */

echo json_encode($result);
die();
