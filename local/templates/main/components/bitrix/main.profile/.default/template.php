<?

/**
 * @param array $arParams
 * @param array $arResult
 * @var string $templateFolder
 * @global CMain $APPLICATION
 */

use Bitrix\Sale;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loader::includeModule('sale');
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$APPLICATION->SetTitle("Личный кабинет");

$birthdayArray = explode(".", $arResult["arUser"]["PERSONAL_BIRTHDAY"]);
//$this->addExternalCss(SITE_TEMPLATE_PATH . "/js/jqueryui/jquery-ui.min.css");
//$this->addExternalJS(SITE_TEMPLATE_PATH . "/js/jqueryui/jquery-ui.min.js");

Loc::loadMessages(__FILE__);
?>

<div class="main-profile">

    <? if ($arResult["strProfileError"]): ?>
        <div class="alert alert-danger">
            <?= $arResult["strProfileError"] ?>
        </div>
    <? endif; ?>

    <? if ($arResult['DATA_SAVED'] == 'Y'): ?>
        <div class="alert alert-success">
            <?= Loc::getMessage('PROFILE_DATA_SAVED') ?>
        </div>
    <? endif; ?>

    <form method="post" class="main-profile__form" name="profile_form" action="<?= $arResult["FORM_TARGET"] ?>"
        enctype="multipart/form-data">
        <?= $arResult["BX_SESSION_CHECK"] ?>

        <input disabled type="hidden" name="lang" value="<?= LANG ?>" />
        <input disabled type="hidden" name="ID" value=<?= $arResult["ID"] ?> />

        <div class="main-profile__block main-profile-block">
            <div class="main-profile-block__head">
                <div class="main-profile__title"><?= Loc::getMessage('PROFILE_BLOCK') ?></div>
            </div>
            <div class="main-profile-block__body">
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('LAST_NAME') ?>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" type="text" name="LAST_NAME" maxlength="50" placeholder="<?= GetMessage("PLACEHOLDER_LAST_NAME")?>" value="<?= $arResult["arUser"]["LAST_NAME"] ?>" />
                        </div>
                    </div>
                </div>
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('NAME') ?>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" type="text" name="NAME" placeholder="<?= GetMessage("PLACEHOLDER_NAME")?>" maxlength="50" value="<?= $arResult["arUser"]["NAME"] ?>" />
                        </div>
                    </div>
                </div>
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('SECOND_NAME') ?>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" type="text" name="SECOND_NAME" maxlength="50" placeholder="<?= GetMessage("PLACEHOLDER_SECOND_NAME")?>" value="<?= $arResult["arUser"]["SECOND_NAME"] ?>" />
                        </div>
                    </div>
                </div>
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('USER_PHONE') ?>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" type="text" name="PERSONAL_PHONE"
                                placeholder="<?= GetMessage("PLACEHOLDER_PHONE")?>"
                                maxlength="50"
                                data-mask-type="phone" autocomplete="tel"
                                value="<?= $arResult["arUser"]["PERSONAL_PHONE"] ?>" />
                            <? if ($arResult["arUser"]["UF_DISCOUNT_CARD_NUM"] != null) { ?>
                                <span class="profile-field__desc">
                                    <?= Loc::getMessage('BONUS_DESC', ['#CARD#' => $arResult["arUser"]["UF_BONUS_CART"]]) ?>
                                </span>
                            <? } ?>
                        </div>
                    </div>
                </div>
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('EMAIL') ?>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" type="email" placeholder="<?= GetMessage("PLACEHOLDER_EMAIL")?>" name="EMAIL" maxlength="50" value="<?= $arResult["arUser"]["EMAIL"] ?>" />
                        </div>
                    </div>
                </div>
                <div class="main-profile-block__field profile-field date">
                    <div class="profile-field__inner">
                        <div class="profile-field__title profile-field--static">
                            <?= Loc::getMessage('USER_BIRTHDAY') ?>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input date" name="USER_BIRTHDAY_DAY" placeholder="<?= GetMessage("PLACEHOLDER_DAY")?>" id="USER_BIRTHDAY_DAY" maxlength="2" data-type="day" value="<?= $birthdayArray[0]?>" />     
                        </div>
                    </div>
                    <div class="profile-field__inner">
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input date" name="USER_BIRTHDAY_MONTH" placeholder="<?= GetMessage("PLACEHOLDER_MONTH")?>" id="USER_BIRTHDAY_MONTH" maxlength="2" data-type="month" value="<?= $birthdayArray[1]?>" />
                        </div>
                    </div>
                    <div class="profile-field__inner">
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input date" name="USER_BIRTHDAY_YEAR" placeholder="<?= GetMessage("PLACEHOLDER_YEAR")?>" id="USER_BIRTHDAY_YEAR" maxlength="4" data-type="year" value="<?= $birthdayArray[2]?>" />
                        </div>
                    </div>
                    <input type="hidden" name="PERSONAL_BIRTHDAY" maxlength="50" class="profile-field__input"value="<?= $arResult["arUser"]["PERSONAL_BIRTHDAY"] ?>" />
                </div>
            </div>
        </div>
        <div class="main-profile__block main-profile-block delivery">
            <div class="main-profile-block__head">
                <div class="main-profile__title"><?= Loc::getMessage('DELIVERY_BLOCK') ?></div>
            </div>
            <div class="main-profile-block__body">
                <? foreach ($arResult['DELIVERY_BLOCK'] as $v): ?>
                    <div class="main-profile-block__field profile-field">
                        <div class="profile-field__inner">
                            <div class="profile-field__title">
                                <?= $v['NAME'] ?>
                            </div>
                            <div class="profile-field__input-wrap">
                                <input class="profile-field__input" type="text" name="<?= $v['CODE'] ?>"
                                    placeholder="<?= Loc::getMessage('PLACEHOLDER_'.$v['CODE']) ?>"
                                    maxlength="50"
                                    value="<?= $v["VALUE"] ?>" />
                                <input class="profile-field__input" type="hidden" name="<?= $v['CODE'] ?>_id"
                                    value="<?= $v["ID"] ?>">
                                <input class="profile-field__input" type="hidden" name="<?= $v['CODE'] ?>_type"
                                    value="<?= $v["TYPE"] ?>">
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
        <div class="main-profile__block main-profile-block password">
            <div class="main-profile-block__head password">
                <div class="main-profile__title"><?= Loc::getMessage('PASSWORD_BLOCK') ?></div>
            </div>
            <div class="main-profile-block__body">
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('CURRENT_PASSWORD') ?><span>*</span>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" data-type="password" type="password"
                                name="CURRENT_PASSWORD"
                                maxlength="50" value="" />
                        </div>
                    </div>
                </div>
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('NEW_PASSWORD') ?><span>*</span>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" data-type="password" type="password" name="NEW_PASSWORD"
                                maxlength="50" value="" />
                        </div>
                    </div>
                </div>
                <div class="main-profile-block__field profile-field">
                    <div class="profile-field__inner">
                        <div class="profile-field__title">
                            <?= Loc::getMessage('NEW_PASSWORD_CONFIRM') ?><span>*</span>
                        </div>
                        <div class="profile-field__input-wrap">
                            <input class="profile-field__input" data-type="password" type="password"
                                name="NEW_PASSWORD_CONFIRM"
                                maxlength="50" value="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <? /** Сохранение */ ?>
        <div class="main-profile__save all-data" data-event="UPDATE_PROFILE" onclick="saveProfileBlock(this)"><?= Loc::getMessage('SAVE_BLOCK') ?></div>

        <div class="main-profile__block main-profile-block subscribe">
            <div class="main-profile-block__head">
                <div class="main-profile__title"><?= Loc::getMessage('SUBSCRIBE_BLOCK') ?></div>
            </div>
            <div class="main-profile-block__body">
                <div class="main-profile-block__subscribe subscribe-block">
                    <div class="subscribe-block__subscribe<?= $arResult['HAS_SUBSCRIBE'] ? ' subscribe-block--active' : '' ?>"
                        data-subscribe="Y">
                        <?= Loc::getMessage('SUBSCRIBE_DESC') ?>
                    </div>
                    <div class="subscribe-block__subscribe<?= !$arResult['HAS_SUBSCRIBE'] ? ' subscribe-block--active' : '' ?>"
                        data-subscribe="N">
                        <?= Loc::getMessage('UNSUBSCRIBE_DESC') ?>
                    </div>

                    <input type="hidden" class="profile-field__input" name="SUBSCRIBE" value="<?= $arResult['HAS_SUBSCRIBE'] ? 'Y' : 'N' ?>">
                    <input type="hidden" class="profile-field__input" name="PERSONAL_DATA" value="Y">
                </div>
            </div>
            <div class="main-profile-block__footer">
                <div class="main-profile__save" data-event="UPDATE_SUBSCRIBE" onclick="saveProfileBlock(this)">
                    <?= Loc::getMessage('SAVE_BLOCK') ?>
                </div>
                <div class="policy-privacy policy-privacy--active">
                    <?= Loc::getMessage('PRIVACY') ?>
                </div>
            </div>
        </div>
        <div class="main-profile__block main-profile-block remove">
            <div class="main-profile-block__head remove">
                <div class="main-profile__title"><?= Loc::getMessage('REMOVE_BLOCK') ?></div>
                <div class="main-profile-block__desc">
                    <?= Loc::getMessage('WARNING_REMOVE_BLOCK') ?>
                </div>
            </div>
            <div class="main-profile-block__footer">
                <div class="main-profile__save" data-event="REMOVE_USER" onclick="removeProfileBlock(this)">
                    <?= Loc::getMessage('REMOVE_BUTTON') ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    var message = {
        EDIT_BLOCK: "<?= Loc::getMessage("EDIT_BLOCK") ?>",
        SAVE_BLOCK: "<?= Loc::getMessage("SAVE_BLOCK") ?>",
        ERROR_UNDEFINED_EVENT: "<?= Loc::getMessage("ERROR_UNDEFINED_EVENT") ?>",
        ERROR_BIRTHDAY_FORMAT: "<?= Loc::getMessage("ERROR_BIRTHDAY_FORMAT") ?>",
        ERROR_EMPTY_FIELD: "<?= Loc::getMessage("ERROR_EMPTY_FIELD") ?>",
        ERROR_EMAIL_FORMAT: "<?= Loc::getMessage("ERROR_EMAIL_FORMAT") ?>",
        ERROR_PERSONAL_PHONE_FORMAT: "<?= Loc::getMessage("ERROR_PERSONAL_PHONE_FORMAT") ?>",
        AJAX_SAVING: "<?= Loc::getMessage("AJAX_SAVING") ?>",
        AJAX_SAVED: "<?= Loc::getMessage("AJAX_SAVED") ?>",
        ERROR_PASS_LENGTH: "<?= Loc::getMessage("ERROR_PASS_LENGTH") ?>",
        ERROR_PASS_INVALID: "<?= Loc::getMessage("ERROR_PASS_INVALID") ?>",
        ERROR_USER_NOT_FOUND: "<?= Loc::getMessage("ERROR_USER_NOT_FOUND") ?>",
        ERROR_PASS_NOT_EQ: "<?= Loc::getMessage("ERROR_PASS_NOT_EQ") ?>",
        ERROR_EMPTY_ID: "<?= Loc::getMessage("ERROR_EMPTY_ID") ?>",
        ERROR_EMPTY_TYPE: "<?= Loc::getMessage("ERROR_EMPTY_TYPE") ?>",
        REMOVE_BUTTON: "<?= Loc::getMessage("REMOVE_BUTTON") ?>",
        REMOVE_CONFIRM_TITLE: "<?= Loc::getMessage("REMOVE_CONFIRM_TITLE") ?>",
        REMOVE_CONFIRM_RETURN: "<?= Loc::getMessage("REMOVE_CONFIRM_RETURN") ?>",
        REMOVE_CONFIRM_CONTINUE: "<?= Loc::getMessage("REMOVE_CONFIRM_CONTINUE") ?>",
        ERROR_EMPTY_PERSONAL_DATA: "<?= Loc::getMessage("ERROR_EMPTY_PERSONAL_DATA") ?>",
    }
    var params = {
        TEMPLATE_PATH: '<?= $templateFolder ?>'
    };
    initMainProfileForm();
</script>