<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>

<div class="subscribe-popup__form">
	<div class="close subscribe">
		<?= classBuildSCG::buildSVG('big-cross'); ?>
	</div><div class="subscribe-popup__form-wrap">
	<div class="subscribe-popup__image"></div>
	<? if ($arResult["MESSAGE"] == 'Элемент успешно добавлен'): ?>
		<div class="subscribe-popup__success">
			<p class="major-title-pages"><?= Loc::getMessage('IBLOCK_FORM_SUCCESS'); ?></p>
		</div>
	<? else: ?>
		<form name="iblock_add" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
			<? if ($arResult["MESSAGE"] <> '' && $arResult["MESSAGE"] !== 'Элемент успешно добавлен'): ?>
				<?php ShowNote($arResult["MESSAGE"]) ?>
			<?php endif;
			$existValues = $arParams['ID'] > 0 || !empty($arResult['ERRORS']);
			if (!empty($arResult["ERRORS"])): ?>
				<p class="subscribe-popup__error"><?php ShowError(implode("<br />", $arResult["ERRORS"])) ?></p>
			<?php endif; ?>
			<h3 class="major-title-pages"><?= Loc::getMessage('IBLOCK_FORM_TITLE'); ?></h3>
			<p><?= Loc::getMessage('IBLOCK_FORM_SUBTITLE'); ?></p>
			<div class="subscribe-popup__fields">
				<?= bitrix_sessid_post() ?>
				<?php if ($arParams["MAX_FILE_SIZE"] > 0): ?><input type="hidden" name="MAX_FILE_SIZE" value="<?= $arParams["MAX_FILE_SIZE"] ?>" /><?php endif ?>
				<?php if (is_array($arResult["PROPERTY_LIST"]) && !empty($arResult["PROPERTY_LIST"])): ?>
					<?php foreach ($arResult["PROPERTY_LIST"] as $propertyID):
						if ($propertyID == 'NAME'): ?>
							<input type="hidden" name="PROPERTY[<?= $propertyID ?>][<?= $i ?>]" size="<?= $arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]; ?>" value="<?= 'подписка ' . date("Y-m-d H:i:s") ?>" />
						<? else: ?>
							<input type="text" name="PROPERTY[<?= $propertyID ?>][<?= $i ?>]" size="<?= $arResult["PROPERTY_LIST_FULL"][$propertyID]["COL_COUNT"]; ?>" value="<?= $value ?>" placeholder="<?= $arResult["PROPERTY_LIST_FULL"][$propertyID]["NAME"] ?>" />
						<?php endif ?>
					<?php endforeach; ?>
					<?php if ($arParams["USE_CAPTCHA"] == "Y" && $arParams["ID"] <= 0): ?>
						<input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>" />
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA" />
						<input type="text" name="captcha_word" maxlength="50" value="">
					<?php endif ?>
			</div>
		<?php endif ?>
		<div class="subscribe-popup__btn">
			<input type="submit" name="iblock_submit" value="<?= GetMessage("IBLOCK_FORM_SUBMIT") ?>" />
			<p><?= Loc::getMessage('IBLOCK_FORM_AGREEMENT'); ?></p>
		</div>
		</form>
	<?php endif; ?>
	</div>
</div>