<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
    
    /**
     * @var CBitrixComponentTemplate $this
     */
    
    $CPN = 'bxmaker.authuserphone.call';
    
    $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();
    
    $this->setFrameMode(true);
    
    $rand = $arParams['RAND_STRING'];

?>
    <div class="bxmaker-authuserphone-call__block bxmaker-authuserphone-call__block--default"
         id="bxmaker-authuserphone-call__block<?= $rand; ?>" data-rand="<?= $rand; ?>">
        
        <? if (\Bxmaker\AuthUserPhone\Manager::getInstance()->isExpired()): ?>
            <div class="bxmaker-authuserphone-notice bxmaker-authuserphone-notice--error">
                <?= GetMessage($CPN . 'DEMO_EXPIRED'); ?>
            </div>
        <? endif; ?>
        
        <?
            $frame = $this->createFrame('bxmaker-authuserphone-call__block' . $rand)->begin();
            $frame->setAnimation(true);
        ?>
        <div class="wrap_title_authuserphone">
            <div class="bxmaker-authuserphone-title"><?= GetMessage($CPN . 'AUTH_TITLE'); ?></div>
        
        <? if ($arResult['USER_IS_AUTHORIZED'] == 'Y'): ?>
            <div class="bxmaker-authuserphone-msg bxmaker-authuserphone-msg--success">
                <?= GetMessage($CPN . 'USER_IS_AUTHORIZED'); ?>
            </div>
        <? else: ?>
            <div class="bxmaker-authuserphone-call__block--auth bxmaker-authuserphone-call__block active">
                <span>/</span>
                <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center link_block">
                    <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link js-baup-register"><?= GetMessage($CPN . 'BTN_REGISTER'); ?></div>
                </div>
            </div>
             <div class="bxmaker-authuserphone-call__block--register bxmaker-authuserphone-call__block ">
                <span>/</span>
                <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center link_block">
                    <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link js-baup-auth"><?= GetMessage($CPN . 'BTN_AUTH'); ?></div>
                </div>
            </div>
            <div class="bxmaker-authuserphone-call__block--forgot bxmaker-authuserphone-call__block">
                <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center link_block">
                    <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link js-baup-auth"><?= GetMessage($CPN . 'BTN_AUTH'); ?></div>
                </div>
            </div>
            
        </div>
            <div class="bxmaker-authuserphone-msg"></div>

            <div class="bxmaker-authuserphone-call__container">

                <!--Авторизация-->
                <div class="bxmaker-authuserphone-call__block--auth bxmaker-authuserphone-call__block active">

                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                            <label class="bxmaker-authuserphone-input__label"
                                   for="bxmaker-authuserphone-call__input-phone<?= $rand; ?>">
                                <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_PHONE'); ?></span>
                            </label>
                            <input class="bxmaker-authuserphone-input__field" type="text" name="phone"
                                   id="bxmaker-authuserphone-call__input-phone<?= $rand; ?>">
                            
                        </div>
                    </div>

                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                            <label class="bxmaker-authuserphone-input__label"
                                   for="bxmaker-authuserphone-call__input-password<?= $rand; ?>">
                                <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_PASSWORD'); ?></span>
                            </label>
                            <input class="bxmaker-authuserphone-input__field js-on-enter-continue" type="password"
                                   name="password" id="bxmaker-authuserphone-call__input-password<?= $rand; ?>">
                            

                            <span class="bxmaker-authuserphone-input__show-pass"
                                  title="<?= GetMessage($CPN . 'INPUT_PASSWORD_SHOW'); ?>"
                                  data-title-show="<?= GetMessage($CPN . 'INPUT_PASSWORD_SHOW'); ?>"
                                  data-title-hide="<?= GetMessage($CPN . 'INPUT_PASSWORD_HIDE'); ?>"></span>
                        </div>

                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--small js-baup-forgot"><?= GetMessage($CPN . 'BTN_FORGOT_PASSWORD'); ?></div>
                    </div>

                    <div class="bxmaker-authuserphone-captcha"></div>


                    <div class="bxmaker-authuserphone-row button_submit">
                        <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                            <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--button js-baup-auth-enter  js-baup-continue "><?= GetMessage($CPN . 'BTN_INPUT'); ?></div>
                        </div>
                    </div>

                    <?/*div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link js-baup-register"><?= GetMessage($CPN . 'BTN_REGISTER'); ?></div>
                    </div*/?>

                </div>

                <!--Регистрация-->
                <div class="bxmaker-authuserphone-call__block--register bxmaker-authuserphone-call__block ">

                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                            <label class="bxmaker-authuserphone-input__label"
                                   for="bxmaker-authuserphone-call__input-register_phone<?= $rand; ?>">
                                <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_PHONE'); ?></span>
                            </label>
                            <input class="bxmaker-authuserphone-input__field" type="text" name="register_phone"
                                   id="bxmaker-authuserphone-call__input-register_phone<?= $rand; ?>">
                            
                        </div>
                    </div>
                    
                    
                    <? if ($oManager->isNeedRegisterLogin()): ?>
                        <div class="bxmaker-authuserphone-row">
                            <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                                <label class="bxmaker-authuserphone-input__label"
                                       for="bxmaker-authuserphone-call__input-register_login<?= $rand; ?>">
                                    <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_LOGIN'); ?></span>
                                </label>
                                <input class="bxmaker-authuserphone-input__field" type="text" name="register_login"
                                       id="bxmaker-authuserphone-call__input-register_login<?= $rand; ?>">
                                
                            </div>
                        </div>
                    <? endif; ?>
                    
                    <? if ($oManager->isNeedRegisterEmail()): ?>
                        <div class="bxmaker-authuserphone-row">
                            <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                                <label class="bxmaker-authuserphone-input__label"
                                       for="bxmaker-authuserphone-call__input-register_email<?= $rand; ?>">
                                    <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_EMAIL'); ?></span>
                                </label>
                                <input class="bxmaker-authuserphone-input__field" type="email" name="register_email"
                                       id="bxmaker-authuserphone-call__input-register_email<?= $rand; ?>">
                                
                            </div>
                        </div>
                    <? endif; ?>

                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                            <label class="bxmaker-authuserphone-input__label"
                                   for="bxmaker-authuserphone-call__input-register_password<?= $rand; ?>">
                                <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_PASSWORD'); ?></span>
                            </label>
                            <input class="bxmaker-authuserphone-input__field" type="password" name="register_password"
                                   id="bxmaker-authuserphone-call__input-register_password<?= $rand; ?>">
                            

                            <span class="bxmaker-authuserphone-input__show-pass"
                                  title="<?= GetMessage($CPN . 'INPUT_PASSWORD_SHOW'); ?>"
                                  data-title-show="<?= GetMessage($CPN . 'INPUT_PASSWORD_SHOW'); ?>"
                                  data-title-hide="<?= GetMessage($CPN . 'INPUT_PASSWORD_HIDE'); ?>"></span>
                        </div>
                    </div>
                    
                    <? if ($arParams['CONSENT_SHOW'] == 'Y'):
                        
                        $arFields = array();
                        $arFields[] = GetMessage($CPN . 'INPUT_PHONE');
                        
                        
                        if ($oManager->isNeedRegisterEmail()) {
                            $arFields[] = GetMessage($CPN . 'INPUT_EMAIL');
                        }
                        if ($oManager->isNeedRegisterLogin()) {
                            $arFields[] = GetMessage($CPN . 'INPUT_LOGIN');
                        }
                        
                        ?>
                        <div class="bxmaker-authuserphone-row">
                            <div class="bxmaker-authuserphone-call__consent ">
                                <? $APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "", array(
                                    'ID' => $arParams['CONSENT_ID'],
                                    "IS_CHECKED" => 'N',
                                    "IS_LOADED" => "Y",
                                    "AUTO_SAVE" => "N",
                                    'SUBMIT_EVENT_NAME' => 'bxmaker_authuserphone_call' . $rand,
                                    'REPLACE' => array(
                                        'button_caption' => GetMessage($CPN . 'BTN_REGISTER'),
                                        'fields' => $arFields
                                    ),
                                ), $component); ?>

                            </div>
                        </div>
                    <? endif; ?>

                    <div class="bxmaker-authuserphone-captcha"></div>

 
                    <div class="bxmaker-authuserphone-row button_submit">
                        <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                            <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--button js-baup-register-enter"><?= GetMessage($CPN . 'BTN_REGISTER'); ?></div>
                        </div>
                    </div>

                    <?/*div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link js-baup-auth"><?= GetMessage($CPN . 'BTN_AUTH'); ?></div>
                    </div*/?>

                </div>

                <!--Код из смс-->
                <div class="bxmaker-authuserphone-call__block--smscode bxmaker-authuserphone-call__block">

                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                            <label class="bxmaker-authuserphone-input__label"
                                   for="bxmaker-authuserphone-call__input-smscode<?= $rand; ?>">
                                <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_SMSCODE'); ?></span>
                            </label>
                            <input class="bxmaker-authuserphone-input__field js-on-enter-continue" type="text"
                                   name="smscode" id="bxmaker-authuserphone-call__input-smscode<?= $rand; ?>">
                            
                        </div>

                        <div class="bxmaker-authuserphone-captcha"></div>
                        
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--small js-baup-sendcode "><?= GetMessage($CPN . 'BTN_SEND_CODE'); ?></div>

                        <div class="js-timeout-info"></div>

                    </div>


                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                            <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--button js-baup-smscode-next js-baup-continue"><?= GetMessage($CPN . 'BTN_NEXT'); ?></div>
                        </div>
                    </div>

                    <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--second js-baup-back"><?= GetMessage($CPN . 'BTN_BACK'); ?></div>
                        &nbsp;
                        &nbsp;
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--second js-baup-change-confirm"><?= GetMessage($CPN . 'CHANGE_CONFIRM'); ?></div>
                    </div>

                </div>

                <!--Звонок от клиента-->
                <div class="bxmaker-authuserphone-call__block--usercall bxmaker-authuserphone-call__block">


                    <div class="bxmaker-authuserphone-row">

                        <div class="bxmaker-authuserphone-confirm__description">
                            <?= GetMessage($CPN . 'CALLPHONE_INFO'); ?>
                        </div>

                        <div class="bxmaker-authuserphone-confirm__callphone">
                            <input class="bxmaker-authuserphone-input__field js-on-enter-continue"
                                   placeholder="- - - - - - - - - - -" type="text" name="callphone"
                                   id="bxmaker-authuserphone-call__input-callphone<?= $rand; ?>">
                        </div>
                        
                        <div class="bxmaker-authuserphone-captcha"></div>

                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--small js-baup-get-callphone "><?= GetMessage($CPN . 'BTN_GET_CALLPHONE'); ?></div>
                    </div>

                    <div class="bxmaker-authuserphone-row button_submit">
                        <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                            <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--button js-baup-usercall-next js-baup-continue"><?= GetMessage($CPN . 'BTN_NEXT'); ?></div>
                        </div>
                    </div>

                    <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--second js-baup-back"><?= GetMessage($CPN . 'BTN_BACK'); ?></div>
                        &nbsp;
                        &nbsp;
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--second js-baup-change-confirm"><?= GetMessage($CPN . 'CHANGE_CONFIRM'); ?></div>
                    </div>

                </div>


                <!--Звонок от бота-->
                <div class="bxmaker-authuserphone-call__block--botcall bxmaker-authuserphone-call__block">

                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-confirm__description">
                            <?= GetMessage($CPN . 'BOTCALL_INFO'); ?>
                        </div>

                        <div class="bxmaker-authuserphone-confirm__input">
                            <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                                <input class="bxmaker-authuserphone-input__field" type="text" name="botcode"
                                       placeholder="<?= GetMessage($CPN . 'INPUT_BOTCODE'); ?>"
                                       id="bxmaker-authuserphone-call__input-botcall_code<?= $rand; ?>">
                            </div>
                        </div>
                        
                        <div class="bxmaker-authuserphone-captcha"></div>

                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--small js-baup-get-botcall "><?= GetMessage($CPN . 'BTN_GET_BOTCALL'); ?></div>

                    </div>

                    <div class="bxmaker-authuserphone-row button_submit">
                        <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                            <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--button js-baup-botcall-next js-baup-continue"><?= GetMessage($CPN . 'BTN_NEXT'); ?></div>
                        </div>
                    </div>

                    <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--second js-baup-back"><?= GetMessage($CPN . 'BTN_BACK'); ?></div>
                        &nbsp;
                        &nbsp;
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link bxmaker-authuserphone-btn--second js-baup-change-confirm"><?= GetMessage($CPN . 'CHANGE_CONFIRM'); ?></div>
                    </div>

                </div>


                <!--Восстановление пароля-->
                <div class="bxmaker-authuserphone-call__block--forgot bxmaker-authuserphone-call__block">

                    <div class="bxmaker-authuserphone-row">
                        <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                            <label class="bxmaker-authuserphone-input__label"
                                   for="bxmaker-authuserphone-call__input-forgot_phone<?= $rand; ?>">
                                <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_PHONE'); ?></span>
                            </label>
                            <input class="bxmaker-authuserphone-input__field js-on-enter-continue" type="text"
                                   name="forgot_phone" id="bxmaker-authuserphone-call__input-forgot_phone<?= $rand; ?>">
                            
                        </div>
                    </div>
                    
                    <? if ($oManager->isEnableResotreByEmail()): ?>
                        <div class="bxmaker-authuserphone-row">
                            <div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">
                                <label class="bxmaker-authuserphone-input__label"
                                       for="bxmaker-authuserphone-call__input-forgot_email<?= $rand; ?>">
                                    <span class="bxmaker-authuserphone-input__label-text"><?= GetMessage($CPN . 'INPUT_EMAIL'); ?></span>
                                </label>
                                <input class="bxmaker-authuserphone-input__field" type="email" name="forgot_email"
                                       id="bxmaker-authuserphone-call__input-forgot_email<?= $rand; ?>">
                                

                                <div class="bxmaker-authuserphone-call__forgot-or"><?= GetMessage($CPN . 'OR'); ?></div>
                            </div>
                        </div>
                    <? endif; ?>

                    <div class="bxmaker-authuserphone-captcha"></div>


                    <div class="bxmaker-authuserphone-row button_submit">
                        <div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                            <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--button js-baup-forgot-enter js-baup-continue"><?= GetMessage($CPN . 'BTN_NEXT'); ?></div>
                        </div>
                    </div>

                    <?/*div class="bxmaker-authuserphone-btn__area bxmaker-authuserphone-btn__area--center">
                        <div class="bxmaker-authuserphone-btn bxmaker-authuserphone-btn--link js-baup-auth"><?= GetMessage($CPN . 'BTN_AUTH'); ?></div>
                    </div*/?>

                </div>
                <div class="link_bonus"><a href="/bonus_system/"><?= GetMessage($CPN . 'INFO_BONUS'); ?></a></div>
            </div>
        
        <? endif; ?>

        <script type="text/javascript" class="bxmaker-authuserphone-jsdata">
            <?
            
            // component parameters
            $signer = new \Bitrix\Main\Security\Sign\Signer;
            $signedParameters = $signer->sign(base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
                'bxmaker.authuserphone.call');
            $signedTemplate = $signer->sign($arResult['TEMPLATE'], 'bxmaker.authuserphone.call');
            ?>

            window.BxmakerAuthUserPhoneCallData = window.BxmakerAuthUserPhoneCallData || {};
            window.BxmakerAuthUserPhoneCallData["<?=$rand;?>"] = <?= Bitrix\Main\Web\Json::encode(array(
                'parameters' => $signedParameters,
                'template' => $signedTemplate,
                'siteId' => SITE_ID,
                'consentShow' => $arParams['CONSENT_SHOW'],
                'ajaxUrl' => $this->getComponent()->getPath() . '/ajax.php',
                'rand' => $rand,
                'confirmQueue' => $arParams['CONFIRM_QUEUE'],
                'isEnableConfirmSmsCode' => $arParams['CONFIRM_SMSCODE'],
                'isEnableConfirmUserCall' => $arParams['CONFIRM_USERCALL'],
                'isEnableConfirmBotCall' => $arParams['CONFIRM_BOTCALL'],
                'isEnableAutoSendSmsCode' => ($oManager->isEnableAutoRegister() ? 'Y' : 'N'),
                
                'messages' => array(
                    'btn_send_code' => GetMessage($CPN . 'BTN_SEND_CODE'),
                    'send_code_timeout' => GetMessage($CPN . 'BTN_SEND_CODE_TIMEOUT'),
                    'authTitle' => GetMessage($CPN . 'AUTH_TITLE'),
                    'registerTitle' => GetMessage($CPN . 'REG_TITLE'),
                    'forgotTitle' => GetMessage($CPN . 'FORGOT_TITLE'),
                    'smscodeTitle' => GetMessage($CPN . 'SMSCODE_TITLE'),
                    'updateCaptcha' => GetMessage($CPN . 'UPDATE_CAPTCHA_IMAGE'),
                    'inputCaptchaWord' => GetMessage($CPN . 'INPUT_CAPTHCA_WORD'),
                ),
            
            ));?>;
        </script>
        
        <?
            $frame->beginStub();
        ?>
        <div class="bxmaker-authuserphone-loading"></div>
        <?
            $frame->end(); ?>

    </div>


<?

