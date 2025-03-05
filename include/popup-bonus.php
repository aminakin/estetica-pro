<div class="detail-popup bonus">
    <div class="detail-popup-wrap">
    <div class="bonus-page">
        <div class="detail-popup-close">
            <img src="/images/detail-popup.png">
	    </div>
    <div class="title-header">
        Бонусная система
	</div>
	<div class="section-top">
		<div class="text top" style="display: none;">
			<p>
				Совершайте покупки в esteticа-pro<br><br class="br-mobile"> с бонусной картой,
 				накапливайте баллы<br class="br-mobile"> для оплаты товаров.
			</p>
		</div>
		<div class="top-images">
			<div class="text">
				<p>
					 Для получения <em>бонусной карты</em><br class="br-mobile"> необходимо <br>
 					<a href="#register" class="enterLink linkOpen menu-links"><em>создать аккаунт</em></a> на сайте estetica-pro.ru.<br>
					 Бонусная карта <em>сформируется<br class="br-mobile"> автоматически</em>.<br>
					 Пластиковую картумы вложим<br> в ваш заказ.<br>
 					<em>Или сделайте покупки в розничном<br class="br-mobile"> магазине estetica-pro</em> 
					 и получите<br class="br-mobile"> вашу карту на кассе.</p>
				<p> <em>Бонус будет начислен уже за первый заказ!</em></p>
			</div>
		</div>
	</div>
	<div class="section-center">
		<div class="title-header">
            Как это работает?
		</div>
		<div class="text center">
			<p>
				В бонусной системе esteticа-pro<br>
 				<em>3 уровня, чем выше уровень, тем выше бонус</em>
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
	<div class="section-bottom">
		<div class="top-images">
			<div class="text">
				<p>
					 Оплатить бонусами можно <em>до 50%</em> <br>от общей суммы покупки.<br>
					 Cрок действия бонусов - <em>60 дней</em> <br>с момента начисления.<br>
 					<span><em>После оплаты бонусными баллами на остаток суммы будут начислены новые бонусы.<br>
					 Товары-исключения помечены в карточке товара «Бонусы не начисляются»</em></span>
				</p>
			</div>
		</div>
		<div class="text bottom">
			<p>
				<em>Отслеживайте</em> информацию об уровне<br>и количестве накопленных баллов<em>в аккаунте.</em> <br>
 				<em>Списание накопленных баллов доступно только<br>после авторизации на сайте.</em>
			</p>
			<? if ($USER->IsAuthorized()):?>
				<a href="/catalog/" class="get-bonus-cart" >Перейти к покупкам</a>
			<?else:?>
				<div class="get-cart" ><a href="#register" class="enterLink linkOpen menu-links">Получить карту</a></div>
			<?endif;?> 
		</div>
	</div>
    </div>
    </div>
</div>
