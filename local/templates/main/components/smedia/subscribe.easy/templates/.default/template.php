<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<section class="subscribeLine">
	<p class="js-successbox" style="display:none">Спасибо за подписку!</p>
    <form id="subscribe-form" name="subscribe-form" role="form" action="/ajax/subscribe.php" method="POST">
    	<?=bitrix_sessid_post();?>
        <p>Подпишитесь на нашу рассылку, чтобы получать больше хороших новостей:</p>    
        <p class="js-errorbox-all" style="display:none"></p>
        <input type="email" value="" name="email" placeholder="Введите e-mail" required="required"/>
        <input type="submit" class="white" value="Подписаться"/>
    </form>
</section>