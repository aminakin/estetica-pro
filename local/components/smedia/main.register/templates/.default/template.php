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
?>
<div class="bx-auth-reg">

    <? if ($USER->IsAuthorized()): ?>
        <div class="h2 registration-success"><?= GetMessage('registration_success');?></div>
        <div class="registration-description"><?= GetMessage('registration_description');?></div>
    <? else: ?>
        <div class="auth-container">
            <a href="#auth" class="linkOpen auth-logo h2"><?= GetMessage('enter');?></a>
            <div class="separate">/</div>
            <div class="h2 auth-logo"><?= GetMessage('registration');?></div>
        </div>
        <?

        if (count($arResult["ERRORS"]) > 0):
            echo '<div class="error" style="display: none;">';
            foreach ($arResult["ERRORS"] as $key => $error){
                if (intval($key) == 0 && $key !== 0){
                    
                    //$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);
                    //var_export($arResult["ERRORS"]);
                   $arResult["ERRORS"][$key] = GetMessage("MESSAGE_FIELD_" . $key);
                   /*var_export($arResult["ERRORS"][$key]);*/
                }
            }
            ShowError(implode("<br />", $arResult["ERRORS"]));
            echo '</div>';

        elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
            <p class="war"><? echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
        <? endif ?>
        

        <form method="post" id="regform" action="<?= POST_FORM_ACTION_URI ?>" name="regform"  enctype="multipart/form-data" class="form-regform">
            <?if ($arResult["BACKURL"] <> ''):
                ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <?endif;?>
            <?if ($arResult["USER_PROPERTIES"]["DATA"]['UF_DISCOUNT_CARD_NUM']) {$arResult["VALUES"][] = $arResult["USER_PROPERTIES"]["DATA"]['UF_DISCOUNT_CARD_NUM'];}?>
            <? foreach ($arResult["SHOW_FIELDS"] as $FIELD): ?>
                <? //var_export($FIELD);?>
                <? if ($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true): ?>
                    <div class="row <?= $FIELD ?>">
                        <? echo GetMessage("main_profile_time_zones_auto") ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?>
                            <span class="starrequired">*</span><? endif ?>
                        <select name="REGISTER[AUTO_TIME_ZONE]"
                                onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
                            <option value=""><? echo GetMessage("main_profile_time_zones_auto_def") ?></option>
                            <option value="Y"<?= $arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : "" ?>><? echo GetMessage("main_profile_time_zones_auto_yes") ?></option>
                            <option value="N"<?= $arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : "" ?>><? echo GetMessage("main_profile_time_zones_auto_no") ?></option>
                        </select>
                    </div>
                    <div class="row <?= $FIELD ?>">
                        <? echo GetMessage("main_profile_time_zones_zones") ?>
                        <select name="REGISTER[TIME_ZONE]"<? if (!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"' ?>>
                            <? foreach ($arResult["TIME_ZONE_LIST"] as $tz => $tz_name): ?>
                                <option value="<?= htmlspecialcharsbx($tz) ?>"<?= $arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : "" ?>><?= htmlspecialcharsbx($tz_name) ?></option>
                            <? endforeach ?>
                        </select>
                    </div>
                <? else: ?>
                    <? //var_export($FIELD);?>
                    <div class="row <?= $FIELD ?>">
                        <? switch ($FIELD) {
                            case "LAST_NAME":?>
                            <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<? endif ?></label>
                                <input class="<?=$FIELD?>" size="30" type="text" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                <? if($arResult["ERRORS"]['LAST_NAME'] !=''):?>
                                    <div class="message-input-spec">
                                        <? echo GetMessage("MESSAGE_FIELD_LAST_NAME"); ?>
                                    </div>
                                <? endif;?>
                                <div class="message-input div<?=$FIELD?>" >
                                    <?= GetMessage('last_name');?>
                                </div>
                            <?break;
                            case "NAME":?>
                            <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<? endif ?></label>
                                <input class="<?=$FIELD?>" size="30" type="text" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                <? if($arResult["ERRORS"]['NAME'] !=''):?>
                                    <div class="message-input-spec">
                                        <? echo GetMessage("MESSAGE_FIELD_NAME"); ?>
                                    </div>
                                <? endif;?>
                                <div class="message-input div<?=$FIELD?>" >
                                    <?= GetMessage('first_name');?>
                                </div>
                            <?break;
                            case "SECOND_NAME":?>
                            <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<? endif ?></label>
                                <input class="<?=$FIELD?>" size="30" type="text" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                <? if($arResult["ERRORS"]['SECOND_NAME'] !=''):?>
                                    <div class="message-input-spec">
                                        <? echo GetMessage("MESSAGE_FIELD_SECOND_NAME"); ?>
                                    </div>
                                <? endif;?>
                                <div class="message-input div<?=$FIELD?>" >
                                    <?= GetMessage('second_name');?>
                                </div>
                            <?break;
                            case "EMAIL":?>
                            <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<? endif ?></label>
                                <input class="<?=$FIELD?>" size="30" type="email" name="REGISTER[<?= $FIELD ?>]"  value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                <? if($arResult["ERRORS"]['EMAIL'] !=''):?>
                                    <div class="message-input-spec">
                                        <? echo GetMessage("MESSAGE_FIELD_EMAIL"); ?>
                                    </div>
                                <? endif;?>
                                <div class="message-input div<?=$FIELD?>" >
                                    <?= GetMessage('no_email');?>
                                </div>
                            <?break;
                            case "PERSONAL_PHONE":?>
                            <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<? endif ?></label>
                                <input class="<?=$FIELD?>" size="30" type="text" <?= ($FIELD == 'PERSONAL_PHONE') ? 'autocomplete="tel"' : '' ?> name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" />
                                <? if($arResult["ERRORS"]['PERSONAL_PHONE'] !=''):?>
                                    <div class="message-input-spec">
                                        <? echo GetMessage("MESSAGE_FIELD_PERSONAL_PHONE"); ?>
                                    </div>
                                <? endif;?>
                                <div class="message-input div<?=$FIELD?>" >
                                    <?= GetMessage('bonus_cart');?>
                                </div>
                            <?break;
                            case "PASSWORD":?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?></label>
                                <input size="30" type="password" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off" class="bx-auth-input" minlength="6" />
                                <div class="message-input" style="display: none">
                                    <?= GetMessage('six_symbol');?>
                                </div>
                                <? if($arResult["ERRORS"]['PASSWORD'] !=''):?>
                                    <div class="message-input-spec">
                                        <? echo GetMessage("MESSAGE_FIELD_PASSWORD"); ?>
                                    </div>
                                <? endif;?>
                                <div class="registration__password__daw__access">
                                    <svg width="8" height="7" viewBox="0 0 8 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 3.93103L3.54545 6L7 1" stroke="#33CCCC" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="registration__password__daw__error">
                                    <svg width="7" height="7" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1.07648L5.85593 5.99998" stroke="#E9544F" stroke-width="0.6" stroke-linecap="round"/>
                                        <path d="M1.14404 5.92438L6 1.00001" stroke="#E9544F" stroke-width="0.6" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="registration__password__eye-open" onclick="handleChangeVisiblePass(this)">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.00708 7.5C6.00708 7.10218 6.16512 6.72064 6.44642 6.43934C6.72772 6.15804 7.10926 6 7.50708 6C7.9049 6 8.28644 6.15804 8.56774 6.43934C8.84905 6.72064 9.00708 7.10218 9.00708 7.5C9.00708 7.89782 8.84905 8.27936 8.56774 8.56066C8.28644 8.84196 7.9049 9 7.50708 9C7.10926 9 6.72772 8.84196 6.44642 8.56066C6.16512 8.27936 6.00708 7.89782 6.00708 7.5Z" fill="#808189"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4638 7.5L14.9254 7.314V7.312L14.9234 7.308L14.9174 7.294C14.887 7.21881 14.8538 7.14476 14.8179 7.072C14.3986 6.19863 13.8594 5.38873 13.2162 4.666C12.0382 3.348 10.1728 2 7.4995 2C4.82722 2 2.9618 3.348 1.78185 4.666C1.13861 5.38876 0.599429 6.19865 0.180076 7.072C0.145772 7.14533 0.112934 7.21935 0.0815812 7.294L0.0766068 7.308L0.0746169 7.312V7.313C0.0746169 7.313 0.0736221 7.314 0.535252 7.5L0.0736221 7.314L0 7.5L0.0736221 7.686L0.535252 7.5L0.0736221 7.686V7.688L0.0766068 7.692L0.0815812 7.706C0.112173 7.78114 0.145356 7.85518 0.181071 7.928C0.600436 8.80134 1.13962 9.61123 1.78285 10.334C2.9608 11.652 4.82623 13 7.4995 13C10.1718 13 12.0372 11.652 13.2172 10.335C13.8605 9.61197 14.3997 8.80172 14.8189 7.928C14.8532 7.85465 14.886 7.78064 14.9174 7.706L14.9234 7.692L14.9244 7.688V7.687C14.9254 7.687 14.9254 7.686 14.4638 7.5L14.9254 7.686L15 7.5L14.9254 7.314L14.4638 7.5ZM7.4995 5C6.83985 5 6.20721 5.26339 5.74076 5.73223C5.27432 6.20107 5.01227 6.83696 5.01227 7.5C5.01227 8.16304 5.27432 8.79893 5.74076 9.26777C6.20721 9.73661 6.83985 10 7.4995 10C8.15916 10 8.79179 9.73661 9.25824 9.26777C9.72469 8.79893 9.98674 8.16304 9.98674 7.5C9.98674 6.83696 9.72469 6.20107 9.25824 5.73223C8.79179 5.26339 8.15916 5 7.4995 5Z" fill="#808189"/>
                                    </svg>
                                
                                </div>
                                <div class="registration__password__eye-close" onclick="handleChangeVisiblePass(this)">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 4C13.6275 4.86444 13.1487 5.66609 12.5774 6.3815C12.4097 6.59081 12.2334 6.7913 12.0491 6.98232L13.5564 8.66997M11.3634 7.61383C10.3657 8.43548 9.17811 8.91824 7.94223 9.00453V11M7.05866 9.00453C5.82287 8.91853 4.63525 8.43612 3.63746 7.61482L2.07001 9.37175M2.95181 6.98331C2.76723 6.79228 2.59062 6.59179 2.42255 6.38249C1.85127 5.6671 1.37242 4.86545 1 4.00099" stroke="#808189" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <?
                                if ($arResult["SECURE_AUTH"]): ?>
                                    <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none" >
    					                <div class="bx-auth-secure-icon"></div>
    				                </span>
                                    <noscript>
    				                    <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
                                            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
    				                    </span>
                                    </noscript>
                                    <script type="text/javascript">
                                      document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                    </script>
                                <? endif ?>
                            <? break;
                            case "CONFIRM_PASSWORD":?>
                                    <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?></label>
                                <input size="30" type="password" name="REGISTER[<?= $FIELD ?>]" value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off" minlength="6" class="bx-auth-confirm-input" />
                                <? if($arResult["ERRORS"]['CONFIRM_PASSWORD'] !=''):?>
                                    <div class="message-input-spec">
                                        <? echo GetMessage("MESSAGE_FIELD_CONFIRM_PASSWORD"); ?>
                                    </div>
                                <? endif;?>
                                <div class="message-input" style="display: none">
                                    <?= GetMessage('bad_password');?>
                                </div>
                                <div class="registration__password__daw__access">
                                    <svg width="8" height="7" viewBox="0 0 8 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 3.93103L3.54545 6L7 1" stroke="#33CCCC" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="registration__password__daw__error">
                                    <svg width="7" height="7" viewBox="0 0 7 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 1.07648L5.85593 5.99998" stroke="#E9544F" stroke-width="0.6" stroke-linecap="round"/>
                                        <path d="M1.14404 5.92438L6 1.00001" stroke="#E9544F" stroke-width="0.6" stroke-linecap="round"/>
                                    </svg>
                                </div>
                                <div class="registration__password__eye-open" onclick="handleChangeVisiblePass(this)">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.00708 7.5C6.00708 7.10218 6.16512 6.72064 6.44642 6.43934C6.72772 6.15804 7.10926 6 7.50708 6C7.9049 6 8.28644 6.15804 8.56774 6.43934C8.84905 6.72064 9.00708 7.10218 9.00708 7.5C9.00708 7.89782 8.84905 8.27936 8.56774 8.56066C8.28644 8.84196 7.9049 9 7.50708 9C7.10926 9 6.72772 8.84196 6.44642 8.56066C6.16512 8.27936 6.00708 7.89782 6.00708 7.5Z" fill="#808189"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4638 7.5L14.9254 7.314V7.312L14.9234 7.308L14.9174 7.294C14.887 7.21881 14.8538 7.14476 14.8179 7.072C14.3986 6.19863 13.8594 5.38873 13.2162 4.666C12.0382 3.348 10.1728 2 7.4995 2C4.82722 2 2.9618 3.348 1.78185 4.666C1.13861 5.38876 0.599429 6.19865 0.180076 7.072C0.145772 7.14533 0.112934 7.21935 0.0815812 7.294L0.0766068 7.308L0.0746169 7.312V7.313C0.0746169 7.313 0.0736221 7.314 0.535252 7.5L0.0736221 7.314L0 7.5L0.0736221 7.686L0.535252 7.5L0.0736221 7.686V7.688L0.0766068 7.692L0.0815812 7.706C0.112173 7.78114 0.145356 7.85518 0.181071 7.928C0.600436 8.80134 1.13962 9.61123 1.78285 10.334C2.9608 11.652 4.82623 13 7.4995 13C10.1718 13 12.0372 11.652 13.2172 10.335C13.8605 9.61197 14.3997 8.80172 14.8189 7.928C14.8532 7.85465 14.886 7.78064 14.9174 7.706L14.9234 7.692L14.9244 7.688V7.687C14.9254 7.687 14.9254 7.686 14.4638 7.5L14.9254 7.686L15 7.5L14.9254 7.314L14.4638 7.5ZM7.4995 5C6.83985 5 6.20721 5.26339 5.74076 5.73223C5.27432 6.20107 5.01227 6.83696 5.01227 7.5C5.01227 8.16304 5.27432 8.79893 5.74076 9.26777C6.20721 9.73661 6.83985 10 7.4995 10C8.15916 10 8.79179 9.73661 9.25824 9.26777C9.72469 8.79893 9.98674 8.16304 9.98674 7.5C9.98674 6.83696 9.72469 6.20107 9.25824 5.73223C8.79179 5.26339 8.15916 5 7.4995 5Z" fill="#808189"/>
                                    </svg>
                                </div>
                                <div class="registration__password__eye-close" onclick="handleChangeVisiblePass(this)">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M14 4C13.6275 4.86444 13.1487 5.66609 12.5774 6.3815C12.4097 6.59081 12.2334 6.7913 12.0491 6.98232L13.5564 8.66997M11.3634 7.61383C10.3657 8.43548 9.17811 8.91824 7.94223 9.00453V11M7.05866 9.00453C5.82287 8.91853 4.63525 8.43612 3.63746 7.61482L2.07001 9.37175M2.95181 6.98331C2.76723 6.79228 2.59062 6.59179 2.42255 6.38249C1.85127 5.6671 1.37242 4.86545 1 4.00099" stroke="#808189" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <?
                            break;
                            case "PERSONAL_BIRTHDAY":?>
                            <label for="REGISTER[<?= $FIELD ?>]"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?><? if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<? endif ?></label>
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
                                    <input type="hidden" class="<?=$FIELD?>" maxlength="50" value="<?= $arResult["VALUES"][$FIELD] ?>"/>
                                </div>
                            <?break;
                            case "UF_SUBSCRIBES":?>
                                <div class="profile-field__subscribe">
                                    <input class="<?=$FIELD?>" type="checkbox" checked="checked" onclick="changeSubcribe(this)" name="REGISTER[<?= $FIELD ?>]" value="N" />
                                    <label for="REGISTER[<?= $FIELD ?>]" class="text-line"><?= GetMessage('uf_subscribe');?></label>
                                </div>
                            <?break;
                            default:?>
                                <div class="registration-svg-block <? if ($FIELD === 'EMAIL') echo 'email' ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="14" viewBox="0 0 12 14" fill="none">
                                            <path d="M11 14C11 11.2386 8.76142 9 6 9C3.23858 9 1 11.2386 1 14" stroke="black" stroke-width="0.6"/>
                                            <circle opacity="0.8" cx="6" cy="5" r="4" stroke="black" stroke-width="0.6"/>
                                        </svg>
                                </div>
                                <?
                        } ?></div>
                <? endif ?>
            <? endforeach ?>
            <? // ********************* User properties ***************************************************?>
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
                                    array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"), null, array("HIDE_ICONS" => "Y")); ?></td>
                        </tr>
                    <? endforeach; ?>
                <? } ?>
            <? endif; ?>
            <? // ******************** /User properties ***************************************************?>
            <?
            if ($arResult["USE_CAPTCHA"] == "Y") {
                ?>
                <tr>
                    <td colspan="2"><b></b></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180"
                             height="40" alt="CAPTCHA"/>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="text" name="captcha_word" maxlength="50" value=""/></td>
                </tr>
                <?
            }
            ?>
           
            <div class="row UF_PERSONAL_AGREE">
                <input id="personal-agree" name="UF_PERSONAL_AGREE" checked="checked" type="checkbox"/>
                <div class="message-input message-input-checkbox" ><?= GetMessage("no_agree") ?></div>
                <label for="personal-agree" class="terms-line"><?= GetMessage("REGISTER_PERSONAL_AGREE") ?></label>
            </div>
            <div class="row SUBMIT" style="display: -webkit-flex;display: -moz-flex;display: -ms-flex;display: -o-flex;display: flex;justify-content: center;">
            <? if($arResult["ERRORS"]['0'] != ''):?>
                <div class="message-input-spec">
                    <? echo GetMessage("MESSAGE_FIELD_PERSONAL"); ?>
                </div>
            <? endif;?>
                <input type="submit" name="register_submit_button" value="<?= GetMessage("AUTH_REGISTER") ?>" class="button-submit-registration" />
                <?/*input type="submit" name="register_submit_button_disabled" disabled value="Заполните поля" class="button-submit-registration_disabled" /*/?>
            </div>
        </form>
    <? endif ?>
</div>

<script>
  inputsRequiredFields();
</script>