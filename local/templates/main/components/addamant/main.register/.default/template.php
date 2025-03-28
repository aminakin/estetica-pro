<?

/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($arResult["SHOW_SMS_FIELD"] == true) {
    CJSCore::Init('phone_auth');
}
?>
<div class="bx-auth-reg">

    <? if ($USER->IsAuthorized()): ?>
        <script>
            $('#register').addClass(' success');
            $('.to-catalog').addClass(' success');
        </script>

        <div class="h2 registration-success"><?= GetMessage('registration_success'); ?></div>
        <div class="registration-description"><?= GetMessage('registration_description'); ?></div>

    <? else: ?>
        <div class="registration-title major-title-pages">
            <?= GetMessage('registration'); ?>
        </div>
        <?
        if (count($arResult["ERRORS"]) > 0):
            foreach ($arResult["ERRORS"] as $key => $error)
                if (intval($key) == 0 && $key !== 0)
                    $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);

            $matchPhone = preg_match('/телефона/', $arResult["ERRORS"][0], $matchPhone);
            $matchEmail = preg_match('/email/', $arResult["ERRORS"][0], $matchEmail);

        elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
        ?>
            <p><? echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
        <? endif ?>

        <? if ($arResult["SHOW_SMS_FIELD"] == true): ?>


            <script>
                new BX.PhoneAuth({
                    containerId: 'bx_register_resend',
                    errorContainerId: 'bx_register_error',
                    interval: <?= $arResult["PHONE_CODE_RESEND_INTERVAL"] ?>,
                    data: <?= CUtil::PhpToJSObject([
                                'signedData' => $arResult["SIGNED_DATA"],
                            ]) ?>,
                    onError: function(response) {
                        var errorDiv = BX('bx_register_error');
                        var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
                        errorNode.innerHTML = '';
                        for (var i = 0; i < response.errors.length; i++) {
                            errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
                        }
                        errorDiv.style.display = '';
                    }
                });
            </script>

            <div id="bx_register_error" style="display:none"><? ShowError("error") ?></div>

            <div id="bx_register_resend"></div>
        <? else: ?>
            <form method="post" id="regform" action="<?= POST_FORM_ACTION_URI ?>" name="regform" enctype="multipart/form-data" class="form-regform">
                <? if ($arResult["BACKURL"] <> ''):
                ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>" />
                <? endif; ?>
                <? if ($arResult["USER_PROPERTIES"]["DATA"]['UF_DISCOUNT_CARD_NUM']) {
                    $arResult["VALUES"][] = $arResult["USER_PROPERTIES"]["DATA"]['UF_DISCOUNT_CARD_NUM'];
                } ?>
                <? foreach ($arResult["SHOW_FIELDS"] as $FIELD): ?>
                    <? if ($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true): ?>
                        <div class="row <?= $FIELD ?>">
                            <? echo GetMessage("main_profile_time_zones_auto") ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?>
                            <span class="starrequired">*</span><? endif ?>
                        <select name="REGISTER[AUTO_TIME_ZONE]"
                            onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
                            <option value=""><? echo GetMessage("main_profile_time_zones_auto_def") ?></option>
                            <option value="Y" <?= $arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : "" ?>><? echo GetMessage("main_profile_time_zones_auto_yes") ?></option>
                            <option value="N" <?= $arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : "" ?>><? echo GetMessage("main_profile_time_zones_auto_no") ?></option>
                        </select>
                        </div>
                        <div class="row <?= $FIELD ?>">
                            <? echo GetMessage("main_profile_time_zones_zones") ?>
                            <select name="REGISTER[TIME_ZONE]" <? if (!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"' ?>>
                                <? foreach ($arResult["TIME_ZONE_LIST"] as $tz => $tz_name): ?>
                                    <option value="<?= htmlspecialcharsbx($tz) ?>" <?= $arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : "" ?>><?= htmlspecialcharsbx($tz_name) ?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                    <? else: ?>
                        <div class="row <?= $FIELD ?>">
                            <? switch ($FIELD) {
                                case "LAST_NAME": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span class="required">*</span><? endif ?></label>
                                    <input class="<?= $FIELD ?><?= $arResult["ERRORS"]['LAST_NAME']? ' error':''?>" placeholder="<?= GetMessage("REGISTER_FIELD_" . $FIELD . "_PLACEHOLDER") ?>" size="30" type="text" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                    <? if ($arResult["ERRORS"]['LAST_NAME'] != ''): ?>
                                        <div class="message-input-spec">
                                            <? echo GetMessage("MESSAGE_FIELD_LAST_NAME"); ?>
                                        </div>
                                    <? endif; ?>
                                    <div class="message-input div<?= $FIELD ?>">
                                        <?= GetMessage('last_name'); ?>
                                    </div>
                                <? break;
                                case "NAME": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span class="required">*</span><? endif ?></label>
                                    <input class="<?= $FIELD ?><?= $arResult["ERRORS"]['NAME']? ' error':''?>" placeholder="<?= GetMessage("REGISTER_FIELD_" . $FIELD . "_PLACEHOLDER") ?>" size="30" type="text" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                    <? if ($arResult["ERRORS"]['NAME'] != ''): ?>
                                        <div class="message-input-spec">
                                            <? echo GetMessage("MESSAGE_FIELD_NAME"); ?>
                                        </div>
                                    <? endif; ?>
                                    <div class="message-input div<?= $FIELD ?>">
                                        <?= GetMessage('first_name'); ?>
                                    </div>
                                <? break;
                                case "SECOND_NAME": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span class="required">*</span><? endif ?></label>
                                    <input class="<?= $FIELD ?><?= $arResult["ERRORS"]['SECOND_NAME']? ' error':''?>" placeholder="<?= GetMessage("REGISTER_FIELD_" . $FIELD . "_PLACEHOLDER") ?>" size="30" type="text" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                    <? if ($arResult["ERRORS"]['SECOND_NAME'] != ''): ?>
                                        <div class="message-input-spec">
                                            <? echo GetMessage("MESSAGE_FIELD_SECOND_NAME"); ?>
                                        </div>
                                    <? endif; ?>
                                    <div class="message-input div<?= $FIELD ?>">
                                        <?= GetMessage('second_name'); ?>
                                    </div>
                                <? break;
                                case "EMAIL": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span class="required">*</span><? endif ?></label>
                                    <input class="<?= $FIELD ?><?= $arResult["ERRORS"]['EMAIL']? ' error':''?>" placeholder="<?= GetMessage("REGISTER_FIELD_" . $FIELD . "_PLACEHOLDER") ?>" size="30" type="email" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                    <? if ($arResult["ERRORS"]['EMAIL'] != ''): ?>
                                        <div class="message-input-spec">
                                            <? echo GetMessage("MESSAGE_FIELD_EMAIL"); ?>
                                        </div>
                                    <? endif; ?>
                                    <div class="message-input div<?= $FIELD ?>">
                                        <?= GetMessage('no_email'); ?>
                                    </div>
                                <? break;
                                case "PERSONAL_PHONE": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span class="required">*</span><? endif ?></label>
                                    <input class="<?= $FIELD ?><?= $arResult["ERRORS"]['PERSONAL_PHONE']? ' error':''?>" placeholder="<?= GetMessage("REGISTER_FIELD_" . $FIELD . "_PLACEHOLDER") ?>" size="30" type="text" <?= ($FIELD == 'PERSONAL_PHONE') ? 'autocomplete="tel"' : '' ?> name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                    <? if ($arResult["ERRORS"]['PERSONAL_PHONE'] != ''): ?>
                                        <div class="message-input-spec">
                                            <? echo GetMessage("MESSAGE_FIELD_PERSONAL_PHONE"); ?>
                                        </div>
                                    <? endif; ?>
                                    <div class="message-input div<?= $FIELD ?>">
                                        <?= GetMessage('bonus_cart'); ?>
                                    </div>
                                <? break;
                                case "PASSWORD": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><span class="required">*</span></label>
                                    <input size="30" type="password" name="REGISTER[<?= $FIELD ?>]" placeholder="<?= GetMessage("REGISTER_FIELD_" . $FIELD . "_PLACEHOLDER") ?>" value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off" class="bx-auth-input<?= $arResult["ERRORS"]['PASSWORD']? ' error':''?>" minlength="6" />
                                    <? if ($arResult["ERRORS"]['PASSWORD'] != ''): ?>
                                        <div class="message-input-spec">
                                            <? echo GetMessage("MESSAGE_FIELD_PASSWORD"); ?>
                                        </div>
                                    <? endif; ?>
                                    <div class="message-input" style="display: none">
                                        <?= GetMessage('six_symbol'); ?>
                                    </div>
                                    
                                    <div class="registration__password__eye-open" onclick="handleChangeVisiblePass(this)">
                                    <?= classBuildSCG::buildSVG('eye'); ?>
                                    </div>
                                    <div class="registration__password__eye-close" onclick="handleChangeVisiblePass(this)">
                                    <?= classBuildSCG::buildSVG('eye'); ?>
                                    </div>
                                    <?
                                    if ($arResult["SECURE_AUTH"]): ?>
                                        <span class="bx-auth-secure" id="bx_auth_secure" title="<? echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
                                            <div class="bx-auth-secure-icon"></div>
                                        </span>
                                        <noscript>
                                            <span class="bx-auth-secure" title="<? echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
                                                <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                                            </span>
                                        </noscript>
                                        <script type="text/javascript">
                                            document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                        </script>
                                    <? endif ?>
                                <? break;
                                case "CONFIRM_PASSWORD": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><span class="required">*</span></label>
                                    <input size="30" type="password" name="REGISTER[<?= $FIELD ?>]" placeholder="<?= GetMessage("REGISTER_FIELD_" . $FIELD . "_PLACEHOLDER") ?>" value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off" minlength="6" class="bx-auth-confirm-input<?= $arResult["ERRORS"]['CONFIRM_PASSWORD']? ' error':''?>"  />
                                    <? if ($arResult["ERRORS"]['CONFIRM_PASSWORD'] != ''): ?>
                                        <div class="message-input-spec">
                                            <? echo GetMessage("MESSAGE_FIELD_CONFIRM_PASSWORD"); ?>
                                        </div>
                                    <? endif; ?>
                                    <div class="message-input" style="display: none">
                                        <?= GetMessage('bad_password'); ?>
                                    </div>
                                    <div class="registration__password__eye-open" onclick="handleChangeVisiblePass(this)">
                                    <?= classBuildSCG::buildSVG('eye'); ?>
                                    </div>
                                    <div class="registration__password__eye-close" onclick="handleChangeVisiblePass(this)">
                                    <?= classBuildSCG::buildSVG('eye'); ?>
                                    </div>
                                <?
                                    break;
                                case "PERSONAL_BIRTHDAY": ?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span class="required">*</span><? endif ?></label>
                                    <div class="profile-field__input-wrap">
                                        <div class="profile-field__select-wrap">
                                            <div class="profile-field__select">
                                                <select name="USER_BIRTHDAY_DAY" id="USER_BIRTHDAY_DAY" onchange="changeBithday(this)" data-type="day">
                                                    <? foreach ($arResult['CALENDAR']['DAY'] as $k => $item) { ?>
                                                        <option value="<?= $k ?>" <?= $k == 0 ? 'disabled' : '' ?> <?= $arResult['BIRTHDAY']['DAY'] == $k ? 'selected' : '' ?>>
                                                            <?= $item ?>
                                                        </option>
                                                    <? } ?>
                                                </select>
                                            </div>
                                            <div class="profile-field__select">
                                                <select name="USER_BIRTHDAY_MONTH" id="USER_BIRTHDAY_MONTH" onchange="changeBithday(this)" data-type="month">
                                                    <? foreach ($arResult['CALENDAR']['MONTH'] as $k => $item) { ?>
                                                        <option value="<?= $k ?>" <?= $k == 0 ? 'disabled' : '' ?> <?= $arResult['BIRTHDAY']['MONTH'] == $k ? 'selected' : '' ?>>
                                                            <?= $item ?>
                                                        </option>
                                                    <? } ?>
                                                </select>
                                            </div>
                                            <div class="profile-field__select">
                                                <select name="USER_BIRTHDAY_YEAR" id="USER_BIRTHDAY_YEAR" onchange="changeBithday(this)" data-type="year">
                                                    <? foreach ($arResult['CALENDAR']['YEAR'] as $k => $item) { ?>
                                                        <option value="<?= $k ?>" <?= $k == 0 ? 'disabled' : '' ?> <?= $arResult['BIRTHDAY']['YEAR'] == $k ? 'selected' : '' ?>>
                                                            <?= $item ?>
                                                        </option>
                                                    <? } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" class="<?= $FIELD ?>" maxlength="50" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                    </div>
                                <? break;
                                case "UF_SUBSCRIBES": ?>
                                    <div class="profile-field__subscribe">
                                        <input class="<?= $FIELD ?>" type="checkbox" id="REGISTER[<?= $FIELD ?>]" checked="checked" onclick="changeSubcribe(this)" name="REGISTER[<?= $FIELD ?>]" value="N" />
                                        <label for="REGISTER[<?= $FIELD ?>]" class="text-line"><?= GetMessage('uf_subscribe'); ?></label>
                                    </div>
                                <? break;?> 
                            <? } ?>
                        </div>
                    <? endif ?>
                <? endforeach ?>
                <? // ********************* User properties ***************************************************
                ?>
                <? if ($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
                    <? if (!$arResult["USER_PROPERTIES"]["DATA"]['UF_PERSONAL_AGREE']) { ?>
                        <tr>
                            <td colspan="2"><?= strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB") ?></td>
                        </tr>
                        <? foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): ?>
                            <tr>
                                <td><?= $arUserField["EDIT_FORM_LABEL"] ?>:<? if ($arUserField["MANDATORY"] == "Y"): ?><span class="starrequired">*</span><? endif; ?></td>
                                <td><? $APPLICATION->IncludeComponent(
                                        "bitrix:system.field.edit",
                                        $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                                        array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"),
                                        null,
                                        array("HIDE_ICONS" => "Y")
                                    ); ?></td>
                            </tr>
                        <? endforeach; ?>
                    <? } ?>
                <? endif; ?>
                <? // ******************** /User properties ***************************************************
                ?>
                <?
                if ($arResult["USE_CAPTCHA"] == "Y") {
                ?>
                    <tr>
                        <td colspan="2"><b></b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>" />
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180"
                                height="40" alt="CAPTCHA" />
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
                    </tr>
                <?
                }
                ?>

                <div class="row UF_PERSONAL_AGREE">
                <div class="profile-field__personal-agree">
                    <input id="personal-agree" name="UF_PERSONAL_AGREE" checked="checked" type="checkbox" />
                    <label for="personal-agree" class="terms-line"><?= GetMessage("REGISTER_PERSONAL_AGREE") ?></label>
                    </div>
                </div>
                <div class="row SUBMIT" style="display: -webkit-flex;display: -moz-flex;display: -ms-flex;display: -o-flex;display: flex;justify-content: center;">
                    <? if ($matchEmail == 1): ?>
                        <div class="message-input-spec">
                            <? echo GetMessage("MESSAGE_FIELD_EMAILHASBE"); ?>
                        </div>
                    <? endif; ?>
                    <? if ($matchPhone == 1): ?>
                        <div class="message-input-spec">
                            <? echo GetMessage("MESSAGE_FIELD_PHOBEHASBE"); ?>
                        </div>
                    <? endif; ?>
                    <input type="submit" name="register_submit_button" value="<?= GetMessage("AUTH_REGISTER") ?>" class="button-submit-registration" />
                    <?/*input type="submit" name="register_submit_button_disabled" disabled value="Заполните поля" class="button-submit-registration_disabled" /*/ ?>
                </div>
            </form>
            <div class="just-do-it" dis><a href="#auth" class="linkOpen auth-logo"><?= GetMessage('NEED_login'); ?></a></div>
        <? endif //$arResult["SHOW_SMS_FIELD"] == true 
        ?>

    <? endif ?>
</div>
<script>
    inputsRequiredFields();
</script>