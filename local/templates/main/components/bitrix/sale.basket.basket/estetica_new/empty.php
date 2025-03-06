<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("В корзине нет товаров");
use Bitrix\Main\Localization\Loc;
?>	
	
    <div class="personal-favorite-empty">
        <div class="personal-favorite-empty-message">
		<div><?= classBuildSCG::buildSVG('empty-cart-p1')?></div>
			<div><?= classBuildSCG::buildSVG('empty-cart-p2')?></div>
			<p><?= GetMessage('SBB_EMPTY_BASKET_TITLE');?></p>
		</div>
    </div>