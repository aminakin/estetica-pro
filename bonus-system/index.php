<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Профессиональные материалы для маникюра и наращивания ресниц. Известные и востребованные бренды по официальным ценам производителей. Отправляем в любой город РФ. Быстрая онлайн-оплата заказа на сайте.");
$APPLICATION->SetPageProperty("title", "Бонусная система");
$APPLICATION->SetTitle("Бонусная система");
?><div class="bonus-page" style="padding: 0px;">
	<div class="section-top">
		<div class="text top" style="display: none;">
			<p>
				Совершайте покупки в esteticа-pro<br class="br-mobile"> с бонусной картой,<br>
 				<em>накапливайте баллы <br class="br-mobile">для оплаты товаров.</em>
			</p>
		</div>
		<div class="top-images">
			<div class="text">
				<p>
					Для получения <em>бонусной карты</em> необходимо  <a href="#auth" class="enterLink linkOpen menu-links"><em>создать аккаунт</em></a> на сайте estetica-pro.ru. Бонусная карта <em>сформируется автоматически</em>. 
					Пластиковую карту мы вложим в ваш заказ. <em>Или сделайте покупки в розничном магазине estetica-pro</em> и получите вашу карту на кассе.</p>
				<p> <em>Бонус будет начислен уже за первый заказ!</em></p>
			</div>
		</div>
		<?/*div class="steps-items">
			<div class="step-item">
				<div class="text">
					<p>
						<em>Дисконтные карты</em> <br>
						старого образца будут <em>аннулированы</em>
					</p>
				</div>
				<div class="number">
					<img src="/bonus-system/images/one.png">
				</div>
			</div>
			<div class="step-item">
				<div class="text">
					<p>
						Клиенту будет присвоен <em>новый номер</em>, <br>
						при этом все <em>накопления сохранятся</em>
					</p>
				</div>
				<div class="number">
					<img src="/bonus-system/images/two.png">
				</div>
			</div>
			<div class="step-item">
				<div class="text">
					<p>
						Бонусная карта является <em>именной</em> <br>
						и <em>не подлежит передаче</em> третьим лицам
					</p>
				</div>
				<div class="number">
					<img src="/bonus-system/images/tree.png">
				</div>
			</div>
		</div*/?>
	</div>
	<div class="section-center">
		<div class="title-header">
			<h1>Как это работает?</h1>
		</div>
		<div class="text center">
			<p>
				В бонусной системе esteticа-pro <em>3 уровня, чем выше уровень, тем выше бонус</em>
			</p> 
		</div>
		<div class="level-items">
			<div class="level-item">
				<div class="number">
					<img src="/bonus-system/images/level-one.png">
				</div>
				<div class="text">
					<p>
						5% от суммы покупки<br>
						при накоплениях от 1 ₽ до 39 999 ₽
					</p>
				</div>
			</div>
			<div class="level-item">
				<div class="number">
					<img src="/bonus-system/images/level-two.png">
				</div>
				<div class="text">
					<p>
						7% от суммы покупки<br>
						при накоплениях от 40 000 ₽ до 99 999 ₽
					</p>
				</div>
			</div>
			<div class="level-item">
				<div class="number">
					<img src="/bonus-system/images/level-tree.png">
				</div>
				<div class="text">
					<p>
						10% от суммы покупки<br>
						при накоплениях от 100 000 ₽
					</p>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	
	<div class="bonus-page">
	<div class="section-bottom">
		<div class="top-images">
		<div class="container">
			<div class="text">
				<p>
					Оплатить бонусами можно <em>до 50%</em> от общей суммы покупки. Cрок действия бонусов - <em>60 дней</em> с момента начисления.
 					<span><em>После оплаты бонусными баллами на остаток суммы будут начислены новые бонусы. Товары-исключения помечены в карточке товара «Бонусы не начисляются»</em></span>
				</p>
			</div>
		</div>
	</div>
	</div>
	</div>
	<div class="container">
	<div class="bonus-page">
		<div class="text bottom">
			<p>
				<em>Отслеживайте</em> информацию об уровне и количестве накопленных баллов <em>в аккаунте.</em> <br>
 				<em>Списание накопленных баллов доступно только после авторизации на сайте.</em>
			</p>
			<? if ($USER->IsAuthorized()):?>
				<a href="/catalog/" class="get-bonus-cart" >Перейти к покупкам</a>
			<?else:?>
				<div class="get-cart" ><a href="#auth" class="enterLink linkOpen menu-links">Получить карту</a></div>
			<?endif;?> 
		</div>
	</div>
</div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>