<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$APPLICATION->SetTitle("В корзине нет товаров");

use Bitrix\Main\Localization\Loc;
?>

<div class="personal-cart-empty">
	<div class="personal-cart-empty-message">
		<?= classBuildSCG::buildSVG('empty-cart', $templateFolder."/images" );?>
		<p><?= GetMessage('SBB_EMPTY_BASKET_TITLE'); ?></p>
		<a href="/catalog/"><?= GetMessage('SBB_EMPTY_BASKET_CONTINE'); ?></a>
	</div>
</div>