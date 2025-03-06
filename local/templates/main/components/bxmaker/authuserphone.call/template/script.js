if (!window.BxmakerAuthUserphoneCallConstructor) {

    /**
     * Обработка действий для комопнента bxmaker:authuserphone.call - аторизация, регситрация с подтверждением через смс, звонок
     * @param block
     *
     * @emits bxmaker.authuserphone.ajax {request, result, params} - событие вызывается после получения ответа на ajax запрос (смена номера отправка кода)
     *
     * @constructor
     */
    var BxmakerAuthUserphoneCallConstructor = function (block) {
        var that = this;
        that.block = block;
        that.activeBlock = 'auth';  // auth, register, codesms, usercall, usercall-check, botcall, botcoll-check, forgot
        that.lastAction = 'auth';  // auth, register, forgot
        that.curConfirmType = false; //текущий вараинт подтверждения
        that.data = (!!window.BxmakerAuthUserPhoneCallData && !!window.BxmakerAuthUserPhoneCallData[block.attr('data-rand')] ? window.BxmakerAuthUserPhoneCallData[block.attr('data-rand')] : false);
        that.timers = {
            'smscode': false
        };
        that.block.addClass('inited');

        //console.log('init',block.attr('data-rand'),  window.BxmakerAuthUserPhoneCallData);
    };

    BxmakerAuthUserphoneCallConstructor.prototype.init = function () {
        var that = this;

        if (that.data === false) {
            console.error('bxmaker:authuserphone.call template data is empty');
        }

        //поля заполнены или нет ---
        that.block.on("focus, blur", 'input', function () {
            var input = $(this);
            if (input.val().trim().length > 0) {
                input.closest('.bxmaker-authuserphone-input').addClass('bxmaker-authuserphone-input--filled');
            } else {
                input.closest('.bxmaker-authuserphone-input').removeClass('bxmaker-authuserphone-input--filled');
            }
        });

        //показать скрыть пароль
        that.block.on("click", '.bxmaker-authuserphone-input__show-pass', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);

            if (btn.hasClass("bxmaker-authuserphone-input__show-pass--active")) {
                btn.removeClass('bxmaker-authuserphone-input__show-pass--active').attr('title', btn.attr('data-title-show'));
                btn.parent().find('input[name$="password"]').prop('type', 'password');
            } else {
                btn.addClass('bxmaker-authuserphone-input__show-pass--active').attr('title', btn.attr('data-title-hide'));
                btn.parent().find('input[name$="password"]').prop('type', 'text');
            }
        });

        // забыл пароль -=
        that.block.on("click", '.js-baup-forgot', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.showForgot();
            that.showMessage();
        });

        that.block.on("click", '.js-baup-forgot-enter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.startForgotEnter();
        });

        that.block.on("click", '.js-baup-register', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.showRegister();
            that.showMessage();
        });

        that.block.on("click", '.js-baup-register-enter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);

            if (that.data.consentShow == 'Y') {
                BX.onCustomEvent('bxmaker_authuserphone_call' + that.getRand(), []);
            } else {
                that.startRegisterEnter();
            }
        });


        that.block.on("click", '.js-baup-auth', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.showAuth();
            that.showMessage();
        });

        that.block.on("click", '.js-baup-auth-enter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.startAuthEnter();
        });

        that.block.on("click", '.js-baup-captcha-reload', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.reloadCaptcha();
        });

        // получить код в смс
        that.block.on("click", '.js-baup-sendcode', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.startSendSmsCode();
        });
        // подтверждение смскода
        that.block.on("click", '.js-baup-smscode-next', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.checkSmsCode();
        });

        // завпрос номера бота на который должен позвонить пользователь
        that.block.on("click", '.js-baup-get-callphone', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.startUserCall();
        });

        // проверка совершил ли пользователь звонок
        that.block.on("click", '.js-baup-usercall-next', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.checkUserCall();
        });

        // запрос звонк аот робота
        that.block.on("click", '.js-baup-get-botcall', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.startBotCall();
        });

        // проверка код из номера от бота
        that.block.on("click", '.js-baup-botcall-next', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.checkBotCall();
        });


        //смена варианта подтверждения действия
        that.block.on("click", '.js-baup-change-confirm', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.showMessage(null);
            that.changeCurConfirmType();
            that.showConfirmBlock();
        });

        //кнопка назад
        that.block.on("click", '.js-baup-back', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            that.showBlock(that.getLastAction());
        });


        // по нажатию на интер - автоклик по кнопке войти
        that.block.on("keyup", 'input.js-on-enter-continue', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (e.keyCode == 13) {
                $(this).closest('.bxmaker-authuserphone-call__block').find('.js-baup-continue').click();
            }
        });

        // выход
        that.block.on("click", '.js-btn-logout', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var btn = $(this);
            location.href = location.pathname + (location.search.length > 0 ? location.search + '&' : '?') + 'logout=Y';
        });


        //показ регистрации если в хэше есть отметка
        if (location.hash == "#registration") {
            that.showRegister();
        }


        if (that.data.consentShow == 'Y' && !!BX.UserConsent) {
            BX.addCustomEvent(
                BX.UserConsent.load(BX('bxmaker-authuserphone-call__block' + that.getRand())),
                BX.UserConsent.events.save,
                function (data) {
                    that.startRegisterEnter({
                        'consent': 1,
                        'consent_id': data.id,
                        'consent_sec': data.sec,
                        'consent_url': data.url
                    });
                }
            );
        }

        //если всего 1 вариант подтверждения, то не показываем кнпоку смены варианта подтверждения
        if (that.getConfirmQueue().length <= 1) {
            that.block.addClass('bxmaker-authuserphone-call__block--easyconfirm');
        }

        // проверяем заполненость полей
        that.block.find('.bxmaker-authuserphone-input input').each(function () {
            var input = $(this);
            if (input.val().trim().length > 0) {
                input.closest('.bxmaker-authuserphone-input').addClass('bxmaker-authuserphone-input--filled');
            } else {
                input.closest('.bxmaker-authuserphone-input').removeClass('bxmaker-authuserphone-input--filled');
            }
        });


    };


    /**
     * Возвращает сообщение
     * @param name
     * @returns {string}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.getMessage = function (name) {
        return (('messages' in this.data && name in this.data.messages) ? this.data.messages[name] : '');
    };

    BxmakerAuthUserphoneCallConstructor.prototype.getConfirmTypeSmsCode = function (name) {
        return 'S';
    };
    BxmakerAuthUserphoneCallConstructor.prototype.getConfirmTypeUserCall = function (name) {
        return 'U';
    };
    BxmakerAuthUserphoneCallConstructor.prototype.getConfirmTypeBotCall = function (name) {
        return 'B';
    };

    /**
     * Объединение объектов
     * @param a
     * @param b
     */
    BxmakerAuthUserphoneCallConstructor.prototype.assign = function (a, b) {
        var that = this;
        var c = a, key;
        for (key in b) {
            if (typeof (b[key]) == 'object') {
                if (a.hasOwnProperty(key) && typeof (a[key]) == 'object') {
                    c[key] = that.assign(a[key], b[key]);
                } else {
                    c[key] = b[key];
                }
            } else {
                c[key] = b[key];
            }
        }
        return c;
    };

    /**
     * Возвращает порядок  достпных способов подтверждения
     * @returns {string[]}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.getConfirmQueue = function () {
        var that = this;
        var confirmQueue = (!!this.data && 'confirmQueue' in this.data ? this.data['confirmQueue'] : that.getConfirmTypeSmsCode() + that.getConfirmTypeUserCall() + that.getConfirmTypeBotCall());

        if (!that.isEnableConfirmSmsCode()) {
            confirmQueue = confirmQueue.replace(new RegExp(that.getConfirmTypeSmsCode()), '');
        }
        if (!that.isEnableConfirmUserCall()) {
            confirmQueue = confirmQueue.replace(new RegExp(that.getConfirmTypeUserCall()), '');
        }
        if (!that.isEnableConfirmBotCall()) {
            confirmQueue = confirmQueue.replace(new RegExp(that.getConfirmTypeBotCall()), '');
        }
        return confirmQueue.split('');
    };

    /**
     * Проверка включено ли подтверждение по коду из смс
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.isEnableConfirmSmsCode = function () {
        return ('isEnableConfirmSmsCode' in this.data ? (this.data['isEnableConfirmSmsCode'] == 'Y') : true);
    };

    /**
     * Проверка включено ли подтверждение по звонку пользователя
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.isEnableConfirmUserCall = function () {
        return ('isEnableConfirmUserCall' in this.data ? (this.data['isEnableConfirmUserCall'] == 'Y') : true);
    };

    /**
     * Проверка включено ли подтверждение по звонку робота
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.isEnableConfirmBotCall = function () {
        return ('isEnableConfirmBotCall' in this.data ? (this.data['isEnableConfirmBotCall'] == 'Y') : true);
    };

    /**
     * Проверка включена ли отправка смс с кодом сразу
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.isEnableAutoSendSmsCode = function () {
        return ('isEnableAutoSendSmsCode' in this.data ? (this.data['isEnableAutoSendSmsCode'] == 'Y') : true);
    };

    /**
     * Показ блока по названию, скрытие остальных блоков
     * @param name
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showBlock = function (name) {
        var that = this;
        that.block.find('.bxmaker-authuserphone-call__block:not(.bxmaker-authuserphone-call__block--' + name + ')').hide(300);
        that.block.find('.bxmaker-authuserphone-call__block--' + name + '').show(300);

        that.setActiveBlockName(name);

        if (that.getMessage(name + 'Title')) {
            that.block.find('.bxmaker-authuserphone-title').text(that.getMessage(name + 'Title'));
        }

        $(document).trigger('bxmaker.authuserphone.call.changeBlock', {'show': name});
    };

    /**
     * Возвращает название активного блока
     * @returns {string}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.getActiveBlockName = function () {
        var that = this;
        return that.activeBlock;
    };

    /**
     * Возвращает название активного блока
     * @returns {string}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.setActiveBlockName = function (name) {
        var that = this;
        that.activeBlock = name;
    };

    /**
     * Вернет ссылку на блок по имени
     * @param name
     * @returns {string}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.getBlockByName = function (name) {
        var that = this;
        return that.block.find('.bxmaker-authuserphone-call__block--' + name + '');
    };


    /**
     * Указывает последнее действие - регшистрация, авторизация, восстанволение доступа,
     * для понимания какой действие подтверждается смс, завонком и тп
     * @param action
     */
    BxmakerAuthUserphoneCallConstructor.prototype.setLastAction = function (action) {
        var that = this;
        that.lastAction = action;
    };

    /**
     * Возвращает название последнего действия, для которого происходит подтверждение
     * @returns {string|*}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.getLastAction = function () {
        var that = this;
        return that.lastAction;
    };

    /**
     * Рандомная строка данного блока
     * @returns {*}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.getRand = function () {
        var that = this;
        return that.block.attr('data-rand');
    };


    /**
     * показ блока регистрации
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showRegister = function () {
        var that = this;
        that.showBlock('register');
    };

    /**
     * показ блока авторизации
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showAuth = function () {
        var that = this;
        that.showBlock('auth');
    };

    BxmakerAuthUserphoneCallConstructor.prototype.showForgot = function () {
        var that = this;
        that.showBlock('forgot');
    };


    /**
     * Вывод сообщения
     * @param text
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showMessage = function (text) {
        var that = this;
        var msgBox = that.block.find('.bxmaker-authuserphone-msg');

        msgBox.removeClass('bxmaker-authuserphone-msg--success bxmaker-authuserphone-msg--error').empty();

        if (!text) {
            return true;
        }

        msgBox.addClass('bxmaker-authuserphone-msg--success').html(text);
    };

    /**
     * Показ ошибки
     * @param text
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showError = function (text) {
        var that = this;
        var msgBox = that.block.find('.bxmaker-authuserphone-msg');

        msgBox.removeClass('bxmaker-authuserphone-msg--success bxmaker-authuserphone-msg--error').empty();

        if (!text) {
            return true;
        }

        msgBox.addClass('bxmaker-authuserphone-msg--error').html(text);
    };

    /**
     * Действия на ответ после ajax  запроса
     * @param r
     */
    BxmakerAuthUserphoneCallConstructor.prototype.checkNeedRedirect = function (r) {
        var that = this;
        if (!!r && !!r.response && !!r.response.redirect) {
            location.href = r.response.redirect;
            return true;
        }
        return false;
    };

    /**
     * Проверка необходимости перезагрузки страницы после успешной авторизации
     * @param r
     */
    BxmakerAuthUserphoneCallConstructor.prototype.checkNeedReload = function (r) {
        var that = this;
        if (!!r && !!r.response && !!r.response.reload) {
            location.reload();
            return true;
        }
        return false;
    };

    /**
     * Проверка необходимости отображить капчу
     * @param r
     */
    BxmakerAuthUserphoneCallConstructor.prototype.checkNeedShowCaptcha = function (r) {
        var that = this;
        if (!!r && !!r.error && !!r.error.more && !!r.error.more.need_captcha && r.error.more.need_captcha) {
            that.showCaptcha(r.error.more.captcha_sid, r.error.more.captcha_src);
        }
    };

    /**
     * Показ капчи по  коду и пути до картинки
     * @param sid
     * @param src
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showCaptcha = function (sid, src) {
        var that = this;

        that.getBlockByName(that.getActiveBlockName()).find('.bxmaker-authuserphone-captcha').show()
            .html('<input type="hidden" name="captcha_sid" value="' + sid + '"/>' +
                '<img src="' + src + '" title="' + that.getMessage('updateCaptcha') + '" class="bxmaker-authuserphone-captcha__img js-baup-captcha-reload"/>' +
                '<span class="bxmaker-authuserphone-captcha__btn-reload js-baup-captcha-reload" title="' + that.getMessage('updateCaptcha') + '"></span>' +
                '<div class="bxmaker-authuserphone-input bxmaker-authuserphone-input--top">' +
                '<input class="bxmaker-authuserphone-input__field" type="text" name="captcha_word" id="bxmaker-authuserphone-call__input-captcha_word' + that.getRand() + '">' +
                '<label class="bxmaker-authuserphone-input__label" for="bxmaker-authuserphone-call__input-captcha_word' + that.getRand() + '">' +
                '<span class="bxmaker-authuserphone-input__label-text">' + that.getMessage('inputCaptchaWord') + '</span>' +
                '</label>' +
                '</div>').find('input[name="captcha_word"]').focus();
    };

    /**
     * Скрытие капчи
     * @param sid
     * @param src
     */
    BxmakerAuthUserphoneCallConstructor.prototype.hideCaptcha = function (sid, src) {
        var that = this;
        that.block.find('.bxmaker-authuserphone-captcha').empty().hide();
    };

    /**
     * Обновление капчи
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.reloadCaptcha = function () {
        var that = this;
        var box = that.getBlockByName(that.getActiveBlockName());
        var btn = box.find('.bxmaker-authuserphone-captcha__btn-reload');

        if (btn.hasClass('preloader')) return false;
        btn.addClass('preloader');

        var data = {
            sessid: BX.bitrix_sessid(),
            parameters: that.data.parameters,
            template: that.data.template,
            siteId: that.data.siteId,
            method: 'getCaptcha'
        };

        $.ajax({
            url: that.data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                btn.removeClass('preloader');
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': that.data,
                    'request': data,
                    'result': r,
                });

                btn.removeClass('preloader');

                if (!!r.error) {
                    that.showError(r.error.msg);
                } else if (!!r.response) {
                    that.showCaptcha(r.response.captcha_sid, r.response.captcha_src);
                }

            }
        });

    };


    /**
     * Проверка необходимости подтвердить действие по смс, звонком и тп
     * @param r
     */
    BxmakerAuthUserphoneCallConstructor.prototype.checkNeedConfirm = function (r) {
        var that = this;
        if (!!r && !!r.error && !!r.error.code && r.error.code == 'ERROR_NEED_CONFIRM') {
            that.showConfirmBlock(r.error.more);
        }
    };

    /**
     * Возвращает тип текущего варианта подтверждения действия
     * @param r
     */
    BxmakerAuthUserphoneCallConstructor.prototype.getCurConfirmType = function (r) {
        var that = this;
        if (!this.curConfirmType) {
            var types = that.getConfirmQueue();
            if (types.length <= 0) {
                this.curConfirmType = that.getConfirmTypeSmsCode();
            } else {

                this.curConfirmType = types[0];
            }
        }
        return this.curConfirmType;
    };

    /**
     * Меняет текущий вариант подтверждения на следующий из списка достпных
     */
    BxmakerAuthUserphoneCallConstructor.prototype.changeCurConfirmType = function () {
        var that = this;
        var types = that.getConfirmQueue();

        if (types.length <= 0) {
            this.curConfirmType = that.getConfirmTypeSmsCode();
        } else {
            // ищем
            var i = types.indexOf(this.curConfirmType);
            if (~i) {
                var b = (types.slice(i + 1, types.length).join('') + types.slice(0, i).join('') + this.curConfirmType).split('');
                this.curConfirmType = b[0];
            } else {
                this.curConfirmType = types[0];
            }
        }
    };


    /**
     * Показывает вариант подтверждения
     * @param data
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showConfirmBlock = function (data) {
        var that = this;
        switch (that.getCurConfirmType()) {
            case that.getConfirmTypeUserCall(): {
                that.showConfirmByUserCall(data);
                break;
            }
            case that.getConfirmTypeBotCall(): {
                that.showConfirmByBotCall(data);
                break;
            }
            default: {
                that.showConfirmBySmsCode(data);
            }
        }
    };


    /**
     * Вход по номеру телефона и паролю
     */
    BxmakerAuthUserphoneCallConstructor.prototype.startAuthEnter = function (params) {
        var that = this;
        var btn = that.block.find('.js-baup-auth-enter');
        var authBox = that.getBlockByName('auth');
        var params = params || {};

        if (btn.hasClass('preloader')) return false;
        btn.addClass('preloader');

        that.setLastAction('auth');

        var data = {
            sessid: BX.bitrix_sessid(),
            parameters: that.data.parameters,
            template: that.data.template,
            siteId: that.data.siteId,
            method: 'auth',
            phone: authBox.find('input[name="phone"]').val(),
            password: authBox.find('input[name="password"]').val()
        };

        if (authBox.find('input[name="captcha_word"]').length) {
            data.captcha_sid = authBox.find('input[name="captcha_sid"]').val();
            data.captcha_word = authBox.find('input[name="captcha_word"]').val();
        }

        //замена парамтеров
        data = that.assign(data, params);
        delete data.callback;

        that.hideCaptcha();

        $.ajax({
            url: that.data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                btn.removeClass('preloader');

                if ('callback' in params && typeof (params.callback) == 'function') {
                    params.callback(r);
                }
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': that.data,
                    'request': data,
                    'result': r,
                });

                btn.removeClass('preloader');

                //повтор при смене сессии --
                if (!!r.error && r.error.code == 'INVALID_SESSID' && r.error.more && r.error.more.sessid && !params.repeatRequest) {
                    BX.message({"bitrix_sessid": r.error.more.sessid});
                    params.repeatRequest = 1;
                    that.startAuthEnter(params);
                    return false;
                }

                that.checkNeedRedirect(r);
                that.checkNeedReload(r);
                that.checkNeedShowCaptcha(r);
                that.checkNeedConfirm(r);

                if (!!r.error) {
                    switch (r.error.code) {
                        case 'ERROR_NEED_CONFIRM': {
                            break;
                        }
                        default: {
                            that.showError(r.error.msg);
                            break;
                        }
                    }
                } else if (!!r.response) {
                    that.showMessage(r.response.msg);
                }

                if ('callback' in params && typeof (params.callback) == 'function') {
                    params.callback(r);
                }
            }
        });


    };

    /**
     * Регистрация
     * @param params
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.startRegisterEnter = function (params) {
        var that = this;
        var btn = that.block.find('.js-baup-register-enter');
        var box = that.getBlockByName('register');
        var params = params || {};

        if (btn.hasClass('preloader')) return false;
        btn.addClass('preloader');

        that.setLastAction('register');

        var data = {
            sessid: BX.bitrix_sessid(),
            parameters: that.data.parameters,
            template: that.data.template,
            siteId: that.data.siteId,
            method: 'register',
            phone: box.find('input[name="register_phone"]').val(),
            password: box.find('input[name="register_password"]').val(),
            login: box.find('input[name="register_login"]').val(),
            email: box.find('input[name="register_email"]').val(),
        };

        //капча
        if (box.find('input[name="captcha_word"]').length) {
            data.captcha_sid = box.find('input[name="captcha_sid"]').val();
            data.captcha_word = box.find('input[name="captcha_word"]').val();
        }

        //согласие
        if (!!params && !!params.consent) {
            data.consent = data.consent;
            data.consent_id = data.id;
            data.consent_sec = data.sec;
            data.consent_url = data.url;
        }

        //замена парамтеров
        data = that.assign(data, params);
        delete data.callback;

        that.hideCaptcha();

        $.ajax({
            url: that.data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                btn.removeClass('preloader');

                if ('callback' in params && typeof (params.callback) == 'function') {
                    params.callback(r);
                }
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': that.data,
                    'request': data,
                    'result': r,
                });


                btn.removeClass('preloader');

                //повтор при смене сессии --
                if (!!r.error && r.error.code == 'INVALID_SESSID' && r.error.more && r.error.more.sessid && !params.repeatRequest) {
                    BX.message({"bitrix_sessid": r.error.more.sessid});
                    params.repeatRequest = 1;
                    that.startRegisterEnter(params);
                    return false;
                }


                that.checkNeedRedirect(r);
                that.checkNeedReload(r);
                that.checkNeedShowCaptcha(r);
                that.checkNeedConfirm(r);

                if (!!r.error) {
                    switch (r.error.code) {
                        case 'ERROR_NEED_CONFIRM': {
                            break;
                        }
                        default: {
                            that.showError(r.error.msg);
                            break;
                        }
                    }
                } else if (!!r.response) {
                    that.showMessage(r.response.msg);
                }

                if ('callback' in params && typeof (params.callback) == 'function') {
                    params.callback(r);
                }

            }
        });

    };

    /**
     * Восстановление доступа по email или номеру телефона
     * @param params
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.startForgotEnter = function (params) {
        var that = this;
        var btn = that.block.find('.js-baup-forgot-enter');
        var box = that.getBlockByName('forgot');
        var params = params || {};

        if (btn.hasClass('preloader')) return false;
        btn.addClass('preloader');

        that.setLastAction('forgot');

        var data = {
            sessid: BX.bitrix_sessid(),
            parameters: that.data.parameters,
            template: that.data.template,
            siteId: that.data.siteId,
            method: 'forgot',
            phone: box.find('input[name="forgot_phone"]').val(),
            email: box.find('input[name="forgot_email"]').val()
        };

        //капча
        if (box.find('input[name="captcha_word"]').length) {
            data.captcha_sid = box.find('input[name="captcha_sid"]').val();
            data.captcha_word = box.find('input[name="captcha_word"]').val();
        }

        //замена парамтеров
        data = that.assign(data, params);
        delete data.callback;

        that.hideCaptcha();

        $.ajax({
            url: that.data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                btn.removeClass('preloader');

                if ('callback' in params && typeof (params.callback) == 'function') {
                    params.callback(r);
                }
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': that.data,
                    'request': data,
                    'result': r,
                });


                btn.removeClass('preloader');

                //повтор при смене сессии --
                if (!!r.error && r.error.code == 'INVALID_SESSID' && r.error.more && r.error.more.sessid && !params.repeatRequest) {
                    BX.message({"bitrix_sessid": r.error.more.sessid});
                    params.repeatRequest = 1;
                    that.startForgotEnter(params);
                    return false;
                }

                that.checkNeedRedirect(r);
                that.checkNeedReload(r);
                that.checkNeedShowCaptcha(r);
                that.checkNeedConfirm(r);

                if (!!r.error) {
                    switch (r.error.code) {
                        case 'ERROR_NEED_CONFIRM': {
                            break;
                        }
                        default: {
                            that.showError(r.error.msg);
                            break;
                        }
                    }
                } else if (!!r.response) {
                    that.showMessage(r.response.msg);
                }

                if ('callback' in params && typeof params.callback == 'function') {
                    params.callback(r);
                }
            }
        });

    };

    /**
     * Показывает блок подтверждение действия по коду из смс
     * @param data
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showConfirmBySmsCode = function (data) {
        var that = this;
        var data = data || {};
        var block = that.getBlockByName('smscode');

        that.showBlock('smscode');

        //кликаем по кнопке отправить смс за пользователя
        if (that.isEnableAutoSendSmsCode()) {
            that.startSendSmsCode();
        }

        //активируем поле ввода кода
        block.find('input[name="smscode"]').val('').focus();
    };

    /**
     * Показывает блок подтверждения действия по звонку пользвоателя
     * @param data
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showConfirmByUserCall = function (data) {
        var that = this;
        var data = data || {};
        var block = that.getBlockByName('usercall');

        that.showBlock('usercall');

    };

    /**
     * Показывает блок подтверждения действия по звонку робота пользователю
     * @param data
     */
    BxmakerAuthUserphoneCallConstructor.prototype.showConfirmByBotCall = function (data) {
        var that = this;
        var data = data || {};
        var block = that.getBlockByName('botcall');

        that.showBlock('botcall');
    };


    /**
     * Запрос на отправку кода в смс
     * @param params
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.startSendSmsCode = function (params) {
        var that = this;
        var btn = that.block.find('.js-baup-sendcode');
        var boxLast = that.getBlockByName(that.getLastAction());
        var boxCurrent = that.getBlockByName('smscode');

        if (btn.hasClass('preloader') || btn.hasClass('timeout')) return false;
        btn.addClass('preloader');

        var data = {
            sessid: BX.bitrix_sessid(),
            parameters: that.data.parameters,
            template: that.data.template,
            siteId: that.data.siteId,
            method: 'sendCode',
            phone: boxLast.find('input[name$="phone"]').val()
        };

        //капча
        if (boxCurrent.find('input[name="captcha_word"]').length) {
            data.captcha_sid = boxCurrent.find('input[name="captcha_sid"]').val();
            data.captcha_word = boxCurrent.find('input[name="captcha_word"]').val();
        }

        that.hideCaptcha();

        $.ajax({
            url: that.data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                btn.removeClass('preloader');
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': that.data,
                    'request': data,
                    'result': r,
                });

                var timeout = 0;
                that.checkNeedShowCaptcha(r);
                btn.removeClass("preloader");

                //фокус на поле
                boxCurrent.find('input[name="smscode"]').focus();

                if (!!r.response) {
                    that.showMessage(r.response.msg);


                    if (!!r.response.time) {
                        timeout = (!!r.response.time ? r.response.time : 59);

                        if (that.timers.smscode) {
                            clearInterval(that.timers.smscode);
                        }

                        // индикатор
                        that.timers.smscode = setInterval(function () {
                            if (--timeout > 0) {
                                btn.text(that.getMessage('send_code_timeout').replace(/#TIMEOUT#/, timeout));
                            } else {
                                clearInterval(that.timers.smscode);
                                btn.text(that.getMessage('btn_send_code'));
                                btn.removeClass("timeout");
                            }
                        }, 1000);

                        //сразу отображаем
                        btn.text(that.getMessage('send_code_timeout').replace(/#TIMEOUT#/, timeout)).addClass('timeout');
                    } else {
                        if (that.timers.smscode) {
                            clearInterval(that.timers.smscode);
                        }
                        btn.text(that.getMessage('btn_send_code')).removeClass("timeout");
                    }
                } else if (!!r.error) {

                    that.showError(r.error.msg);

                    if (!!r.error.more && !!r.error.more.time) {

                        timeout = (!!r.error.more.time ? r.error.more.time : 59);

                        if (that.timers.smscode) {
                            clearInterval(that.timers.smscode);
                        }

                        that.timers.smscode = setInterval(function () {
                            if (--timeout > 0) {
                                btn.text(that.getMessage('send_code_timeout').replace(/#TIMEOUT#/, timeout));
                            } else {
                                clearInterval(that.timers.smscode);
                                btn.text(that.getMessage('btn_send_code'));
                                btn.removeClass("timeout");
                            }
                        }, 1000);

                        btn.text(that.getMessage('send_code_timeout').replace(/#TIMEOUT#/, timeout)).addClass('timeout');
                    } else {
                        if (that.timers.smscode) {
                            clearInterval(that.timers.smscode);
                        }
                        btn.text(that.getMessage('btn_send_code')).removeClass("timeout");
                    }
                }

            }
        });

    };

    /**
     * Проверка смс кода
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.checkSmsCode = function () {
        var that = this;
        var btn = that.block.find('.js-baup-smscode-next');
        var boxLast = that.getBlockByName(that.getLastAction());
        var boxCurrent = that.getBlockByName('smscode');
        var data = {
            confirmType: that.getConfirmTypeSmsCode()
        };

        if (btn.hasClass('preloader')) return false;
        btn.addClass('preloader');

        //капча
        if (boxCurrent.find('input[name="captcha_word"]').length) {
            data.captcha_sid = boxCurrent.find('input[name="captcha_sid"]').val();
            data.captcha_word = boxCurrent.find('input[name="captcha_word"]').val();
        }

        data.confirmValue = boxCurrent.find('input[name="smscode"]').val();

        data.callback = function (r) {
            btn.removeClass('preloader');
        };
        that.confirmLastAction(data);
    };


    /**
     * Запрос номера телефона для показа, на который должен позвонить пользователя для подтверждения действия
     * @param params
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.startUserCall = function (params) {
        var that = this;
        var btn = that.block.find('.js-baup-get-callphone');
        var boxLast = that.getBlockByName(that.getLastAction());
        var boxCurrent = that.getBlockByName('usercall');

        if (btn.hasClass('preloader') || btn.hasClass('timeout')) return false;
        btn.addClass('preloader');

        var data = {
            sessid: BX.bitrix_sessid(),
            parameters: that.data.parameters,
            template: that.data.template,
            siteId: that.data.siteId,
            method: 'userCall',
            phone: boxLast.find('input[name$="phone"]').val()
        };

        //капча
        if (boxCurrent.find('input[name="captcha_word"]').length) {
            data.captcha_sid = boxCurrent.find('input[name="captcha_sid"]').val();
            data.captcha_word = boxCurrent.find('input[name="captcha_word"]').val();
        }

        that.hideCaptcha();

        $.ajax({
            url: that.data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                btn.removeClass('preloader');
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': that.data,
                    'request': data,
                    'result': r,
                });


                var timeout = 0;
                that.checkNeedShowCaptcha(r);
                btn.removeClass("preloader");

                if (!!r.response) {
                    boxCurrent.find('input[name="callphone"]').val(r.response.phone);
                } else if (!!r.error) {
                    that.showError(r.error.msg);
                }

            }
        });

    };


    /**
     * Проверка звонка пользователя
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.checkUserCall = function () {
        var that = this;
        var btn = that.block.find('.js-baup-usercall-next');
        var boxCurrent = that.getBlockByName('usercall');
        var data = {
            confirmType: that.getConfirmTypeUserCall()
        };

        if (btn.hasClass('preloader')) return false;
        btn.addClass('preloader');

        //капча
        if (boxCurrent.find('input[name="captcha_word"]').length) {
            data.captcha_sid = boxCurrent.find('input[name="captcha_sid"]').val();
            data.captcha_word = boxCurrent.find('input[name="captcha_word"]').val();
        }

        data.confirmValue = boxCurrent.find('input[name="callphone"]').val();

        data.callback = function (r) {
            btn.removeClass('preloader');
        };

        that.confirmLastAction(data);
    };

    /**
     * Запрос звонка от бота для получения кода
     * @param params
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.startBotCall = function (params) {
        var that = this;
        var btn = that.block.find('.js-baup-get-botcall');
        var boxLast = that.getBlockByName(that.getLastAction());
        var boxCurrent = that.getBlockByName('botcall');

        if (btn.hasClass('preloader') || btn.hasClass('timeout')) return false;
        btn.addClass('preloader');

        var data = {
            sessid: BX.bitrix_sessid(),
            parameters: that.data.parameters,
            template: that.data.template,
            siteId: that.data.siteId,
            method: 'botCall',
            phone: boxLast.find('input[name$="phone"]').val()
        };

        //капча
        if (boxCurrent.find('input[name="captcha_word"]').length) {
            data.captcha_sid = boxCurrent.find('input[name="captcha_sid"]').val();
            data.captcha_word = boxCurrent.find('input[name="captcha_word"]').val();
        }

        that.hideCaptcha();

        $.ajax({
            url: that.data.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: data,
            error: function (r) {
                btn.removeClass('preloader');
            },
            success: function (r) {

                // событие получения ответа на ajax запрос
                $(document).trigger('bxmaker.authuserphone.ajax', {
                    'params': that.data,
                    'request': data,
                    'result': r,
                });


                var timeout = 0;
                that.checkNeedShowCaptcha(r);
                btn.removeClass("preloader");

                if (!!r.response) {
                    boxCurrent.find('input[name="botcode"]').val('');
                } else if (!!r.error) {
                    that.showError(r.error.msg);
                }
            }
        });

    };


    /**
     * Проверка кода из номера бота
     * @returns {boolean}
     */
    BxmakerAuthUserphoneCallConstructor.prototype.checkBotCall = function () {
        var that = this;
        var btn = that.block.find('.js-baup-botcall-next');
        var boxCurrent = that.getBlockByName('botcall');
        var data = {
            confirmType: that.getConfirmTypeBotCall()
        };

        if (btn.hasClass('preloader')) return false;
        btn.addClass('preloader');

        //капча
        if (boxCurrent.find('input[name="captcha_word"]').length) {
            data.captcha_sid = boxCurrent.find('input[name="captcha_sid"]').val();
            data.captcha_word = boxCurrent.find('input[name="captcha_word"]').val();
        }

        data.confirmValue = boxCurrent.find('input[name="botcode"]').val();

        data.callback = function (r) {
            btn.removeClass('preloader');
        };

        that.confirmLastAction(data);
    };


    /**
     * Подтверждение последнего действия, которое требует подтверждения
     * @param params
     */
    BxmakerAuthUserphoneCallConstructor.prototype.confirmLastAction = function (params) {
        var that = this;
        var btn = that.block.find('.js-baup-usercall-next');
        var boxLast = that.getBlockByName(that.getLastAction());

        switch (that.getLastAction()) {
            case 'auth': {
                that.startAuthEnter(params);
                break;
            }
            case 'register': {
                that.startRegisterEnter(params);
                break;
            }
            case 'forgot': {
                that.startForgotEnter(params);
                break;
            }
            default: {

                break;
            }
        }
    };

}

if (!window.BxmakerAuthUserphoneCallWorker) {
    function BxmakerAuthUserphoneCallWorker() {
        window.BxmakerAuthUserphoneCall = window.BxmakerAuthUserphoneCall || {};
        $('.bxmaker-authuserphone-call__block.bxmaker-authuserphone-call__block--default:not(.inited)').each(function () {
            var block = $(this);
            var rand = block.attr('data-rand');

            if (!!block && !!window.BxmakerAuthUserphoneCall[rand]) return false;
            window.BxmakerAuthUserphoneCall[rand] = new BxmakerAuthUserphoneCallConstructor(block);
            window.BxmakerAuthUserphoneCall[rand].init();
        });
    }
}

if (window.frameCacheVars !== undefined && !!window.frameCacheVars.AUTO_UPDATE) {
    BX.addCustomEvent("onFrameDataReceived", BxmakerAuthUserphoneCallWorker);
} else {
    BX.ready(BxmakerAuthUserphoneCallWorker);
}

