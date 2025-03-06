<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

    $CPN = 'bxmaker.authuserphone.login';

    $oManager = \Bxmaker\AuthUserPhone\Manager::getInstance();

    $this->setFrameMode(true);

    $rand = $arParams['RAND_STRING'];

?>
    <div class="c-bxmaker-authuserphone_login-default-box bxmaker-authuserphone-login bxmaker-authuserphone-login--default" id="bxmaker-authuserphone-login--<?= $rand; ?>" data-rand="<?= $rand; ?>" data-consent="<?= $arParams['CONSENT_SHOW']; ?>">
        
        <span class="cbaup_btn_reg"><?= GetMessage($CPN . 'BTN_REGISTER'); ?></span>

        <? if (\Bxmaker\AuthUserPhone\Manager::getInstance()->isExpired()): ?>
            <p style="color:red; padding:0;margin:0 ;"><?= GetMessage($CPN . 'DEMO_EXPIRED'); ?></p>
        <? endif; ?>

        <div class="cbaup_title" data-register-title="<?= GetMessage($CPN . 'BTN_REGISTER'); ?>"
             data-auth-title="<?= GetMessage($CPN . 'AUTH_TITLE'); ?>"><?= GetMessage($CPN . 'AUTH_TITLE'); ?></div>

        <?

            $frame = $this->createFrame('bxmaker-authuserphone-login--' . $rand)->begin('<div class="bxmaker-authuserphone-loading"></div>');
            $frame->setAnimation(true);
        ?>


        <? if ($arResult['USER_IS_AUTHORIZED'] == 'Y'): ?>
            <div class="msg success" style="margin-bottom:0;">
                <?= GetMessage($CPN . 'USER_IS_AUTHORIZED'); ?>
            </div>
        <? else: ?>

            <div class="msg ">

            </div>


            <div class="cbaup_row">
                <input type="text" name="phone" class="phone" placeholder="<?= GetMessage($CPN . 'INPUT_PHONE'); ?>"
                       data-reg-placeholder="<?= GetMessage($CPN . 'INPUT_PHONE_REG'); ?>" data-auth-placeholder="<?= GetMessage($CPN . 'INPUT_PHONE'); ?>"/>
            </div>

            <? if ($oManager->getParam('REGISTRATION_EMAIL', 'N') == 'Y'): ?>
                <div class="cbaup_row email_box">
                    <input type="text" name="email" class="email"
                           placeholder="<?= GetMessage($CPN . 'INPUT_EMAIL'); ?>"/>
                </div>
            <? endif; ?>

            <? if ($oManager->getParam('REGISTRATION_LOGIN', 'N') == 'Y'): ?>
                <div class="cbaup_row login_box">
                    <input type="text" name="login" class="login"
                           placeholder="<?= GetMessage($CPN . 'INPUT_LOGIN'); ?>"/>
                </div>
            <? endif; ?>

            <div class="cbaup_row">
                <input type="password" name="password" class="password" data-auth="<?= GetMessage($CPN . 'INPUT_PASSWORD'); ?>"
                       data-reg="<?= GetMessage($CPN . 'INPUT_SMSCODE'); ?>"
                       placeholder="<?= GetMessage($CPN . 'INPUT_PASSWORD'); ?>"/>
                <span class="btn_show_password" title="<?= GetMessage($CPN . 'BTN_SHOW_PASSWORD'); ?>"
                      data-title-show="<?= GetMessage($CPN . 'BTN_SHOW_PASSWORD'); ?>"
                      data-title-hide="<?= GetMessage($CPN . 'BTN_HIDE_PASSWORD'); ?>"></span>
            </div>

            <div class="cbaup_row captcha">
                    <input type="hidden" name="captcha_sid" value="0b853532ea27dba6a71666bb89ab6760"/>
                    <img src="/bitrix/tools/captcha.php?captcha_sid=0b853532ea27dba6a71666bb89ab6760" title="<?= GetMessage($CPN . 'UPDATE_CAPTCHA_IMAGE');?>" alt=""/>
                    <span class="btn_captcha_reload" title="<?= GetMessage($CPN . 'UPDATE_CAPTCHA_IMAGE');?>"></span>
                    <input type="text" name="captcha_word" class="captcha_word" placeholder="<?= GetMessage($CPN . 'INPUT_CAPTHCA');?>"/>
 
            </div>

            <div class="cbaup_row mini ">
                <input type="checkbox" name="remember" id="cbaup_remember" value="Y"/>
                <label for="cbaup_remember"><?= GetMessage($CPN . 'REMEMBER_ME'); ?></label>
            </div>

            <div class="cbaup_row ">
                <span class="cbaup_btn_link"><?= GetMessage($CPN . 'BTN_SEND_CODE'); ?></span>
            </div>

           
        <div class="cbaup_row ">
            <span class="cbaup_btn_send_email" ><?= GetMessage($CPN . 'BTN_SEND_EMAIL');?></span>
        </div>
        
        <? endif; ?>

        <?

            if ($arParams['CONSENT_SHOW'] == 'Y'):


                $arFields = array();
                $arFields[] = GetMessage($CPN . 'INPUT_PHONE_REG');


                if ($oManager->getParam('REGISTRATION_EMAIL', 'N') == 'Y') {
                    $arFields[] = GetMessage($CPN . 'INPUT_EMAIL');
                }
                if ($oManager->getParam('REGISTRATION_LOGIN', 'N') == 'Y') {
                    $arFields[] = GetMessage($CPN . 'INPUT_LOGIN');
                }

                ?>
                <div class="cbaup_row cbaup_row--registration ">
                    <? $APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "", array(
                            'ID'                => $arParams['CONSENT_ID'],
                            "IS_CHECKED"        => 'N',
                            "IS_LOADED"         => "Y",
                            "AUTO_SAVE"         => "N",
                            'SUBMIT_EVENT_NAME' => 'bxmaker_authuserphone_registration',
                            'REPLACE'           => array(
                                'button_caption' => GetMessage($CPN . 'BTN_REG_INTER'),
                                'fields'         => $arFields
                            ),
                        ), $component); ?>

                </div>
            <? endif; ?>

        <? if ($arResult['USER_IS_AUTHORIZED'] != 'Y'): ?>

            <div class="cbaup_row btn_box">
                <div class="cbaup_btn " data-auth-title="<?= GetMessage($CPN . 'BTN_INTER'); ?>"
                     data-reg-title="<?= GetMessage($CPN . 'BTN_REG_INTER'); ?>"><?= GetMessage($CPN . 'BTN_INTER'); ?></div>
            </div>

        <? endif; ?>

        <script type="text/javascript" class="bxmaker-authuserphone-jsdata">
            <?

            // component parameters
            $signer = new \Bitrix\Main\Security\Sign\Signer;
            $signedParameters = $signer->sign(base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])), 'bxmaker.authuserphone.login');
            $signedTemplate = $signer->sign($arResult['TEMPLATE'], 'bxmaker.authuserphone.login');
            ?>

            window.BxmakerAuthUserPhoneLoginData = window.BxmakerAuthUserPhoneLoginData || {};
            window.BxmakerAuthUserPhoneLoginData["<?=$rand;?>"] = <?= Bitrix\Main\Web\Json::encode(array(
                'parameters' => $signedParameters,
                'template'   => $signedTemplate,
                'siteId'     => SITE_ID,
                'ajaxUrl'    => $this->getComponent()->getPath() . '/ajax.php',
                'rand' => $rand,

                'messages' => array(
                    'UPDATE_CAPTCHA_IMAGE'  => GetMessage($CPN . 'UPDATE_CAPTCHA_IMAGE'),
                    'INPUT_CAPTHCA'         => GetMessage($CPN . 'INPUT_CAPTHCA'),
                    'REGISTER_INFO'         => GetMessage($CPN . 'REGISTER_INFO'),
                    'BTN_SEND_CODE'         => GetMessage($CPN . 'BTN_SEND_CODE'),
                    'BTN_SEND_EMAIL'        => GetMessage($CPN . 'BTN_SEND_EMAIL'),
                    'BTN_SEND_CODE_TIMEOUT' => GetMessage($CPN . 'BTN_SEND_CODE_TIMEOUT')
                ),

            ));?>;
        </script>


        <?
            $frame->end();
        ?>

    </div>
<?
