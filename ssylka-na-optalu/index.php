<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ссылка на оплату");?>
<?php
use Bitrix\Main\Application,
    Bitrix\Main\Context,
    Bitrix\Main\Request,
    Bitrix\Main\Server;

use Bitrix\Main\Loader;
use Bitrix\Sale;

Loader::includeModule("sale");

if ($USER->IsAdmin()) :
?>
<div>
    <form action="" method="POST">
        <label for="order_id">Введите номер нужного заказа:</label>
        <input id="order_id" name="order_id" placeholder="Номер заказа">
        <button type="submit" id="submit">Получить ссылку на оплату</button>
    </form>
</div>

<?php

$application = Application::getInstance();
$context = Application::getInstance()->getContext();
$request = $context->getRequest();
$orderId = $request->getPost("order_id");

if (intval($orderId) > 0) {
    $orderObj = \Bitrix\Sale\Order::loadByAccountNumber($orderId);
    $paymentCollection = $orderObj->getPaymentCollection();
    $payment = $paymentCollection[0];
    $service = Sale\PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
    $context = \Bitrix\Main\Application::getInstance()->getContext();
    $initResult = $service->initiatePay($payment, $context->getRequest());
    $buffered_output = $initResult->getPaymentUrl();
//var_dump($initResult);
    echo "<p class='result_url'>Ссылка на оплату:<br>" . "<span>" . $buffered_output . "</span>" . "</p>";
}

?>

<style>
    .result_url {
        margin-top: 20px;
        font-size: 16px;
        line-height: 18px;
    }

    .result_url span {
        display: block;
        width: 100%;
    }

    .sberbank__wrapper {
        display: none !important;
    }

    #link_result p {
        margin: 10px 0 !important;
        font-weight: 600;
    }

    #link_result a {
        font-size: 16px !important;
    }

    #submit {
        width: fit-content;
        padding: 6px 8px;
        cursor: pointer;
        border: 1px solid;
        margin-top: 10px;
    }
</style>
<?php endif; ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>