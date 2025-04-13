<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

?>

<?LocalRedirect("/personal/profile/");?>
<p><a href="/personal/profile/">Мои данные</a></p>
<p><a href="/personal/orders/">Мои заказы</a></p>
<p><a href="/personal/bonuses/">Мои бонусы</a></p>
<p><a href="/cart/">Корзина</a></p>
<p><a href="/personal/subscribe/">Мои подписки</a></p>


<p><a href="#">Выйти</a></p>




<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>