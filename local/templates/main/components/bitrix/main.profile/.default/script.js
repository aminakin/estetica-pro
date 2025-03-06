function initMainProfileForm() {
    let field = $('.profile-field__input');
    field.each(function (i, item) {
        placeholderInit(item);

        $(item).on('change, keyup, keydown, blur, focusin, input, click', function () {
            placeholderToTop(item);
        }).focus(function () {
            placeholderToTop(item);
        });

        $(item).blur(function () {
            placeholderReturnFromTop(item);
        });
    });

    $(document).on('click', '.main-profile__edit', function () {
        let fieldsBody = $(this).closest('.main-profile-block__head').siblings('.main-profile-block__body')
        fieldsBody.find('input[disabled], select[disabled]').removeAttr('disabled').trigger('refresh');
        addSaveButton($(this), $(this).data('event'));
    }).on('click', '.subscribe-block__subscribe:not(.subscribe-block--active)', function () {
        $(this).addClass('subscribe-block--active').siblings('.subscribe-block__subscribe').removeClass('subscribe-block--active');
        $(this).siblings('input[name="SUBSCRIBE"]').val(this.dataset.subscribe);
    }).on('click', '.policy-privacy', function () {
        if ($(this).hasClass('policy-privacy--active')) {
            $(this).removeClass('policy-privacy--active').siblings('input[name="PERSONAL_DATA"]').val('N')
        } else {
            $(this).addClass('policy-privacy--active').siblings('input[name="PERSONAL_DATA"]').val('Y')
        }
    }).on('input', '.profile-field__input[data-type="password"]', function () {
        let th = $(this);
        let thWrapper = th.closest('.profile-field__input-wrap');

        removeAllHint(thWrapper);

        if (th.val().length < 6 && th.val().length > 0) {
            thWrapper.find('.profile-field__input--error').remove();
            thWrapper.append('<span class="profile-field__input--error"></span>');
            createHint(thWrapper, message.ERROR_PASS_LENGTH);
        } else {
            thWrapper.find('.profile-field__input--error').remove();
            if (this.name === 'NEW_PASSWORD' || this.name === 'NEW_PASSWORD_CONFIRM') {
                let passBlockWrap = th.closest('.main-profile__block'),
                    newPassField = passBlockWrap.find('[name="NEW_PASSWORD"]'),
                    confirmPassField = passBlockWrap.find('[name="NEW_PASSWORD_CONFIRM"]'),
                    newPassFieldWrapper = newPassField.closest('.profile-field__input-wrap'),
                    confirmPassFieldWrapper = confirmPassField.closest('.profile-field__input-wrap');
                newPassFieldWrapper.find('.profile-field__input--error').remove();
                confirmPassFieldWrapper.find('.profile-field__input--error').remove();

                if (newPassField.val() !== confirmPassField.val() && (newPassField.val().length > 5 || confirmPassField.val().length > 5)) {
                    newPassFieldWrapper.append('<span class="profile-field__input--error"></span>');
                    createHint(newPassFieldWrapper, message.ERROR_PASS_NOT_EQ);

                    confirmPassFieldWrapper.append('<span class="profile-field__input--error"></span>');
                    createHint(confirmPassFieldWrapper, message.ERROR_PASS_NOT_EQ);
                }
            }
        }
    });
}

function placeholderInit(item) {
    if ($(item).val() !== '') {
        placeholderToTop(item)
    } else {
        placeholderReturnFromTop(item)
    }
}

function placeholderToTop(item) {
    placeholderReturnFromTop(item);
    $(item).closest('.profile-field__inner').addClass('profile-field__inner--change');
    if (item.dataset.type == 'password') {
        $(item).closest('.profile-field__input-wrap').find('.main-profile__show-password').remove();
        $(item).after('<span class="main-profile__show-password" onclick="showPassword(this)"></span>');
    }
}

function placeholderReturnFromTop(item) {
    if ($(item).val() === '') {
        $(item).closest('.profile-field__inner').removeClass('profile-field__inner--change');
        $(item).siblings('.main-profile__show-password').remove();
    }
}

function addConfirmeButton(node) {
    let confirmTitle = '<div class="main-profile__confirm">' + message.REMOVE_CONFIRM_TITLE + '</div>';
    let confirmContinue = '<div class="main-profile__save-half" onclick="confirmContinue(this)" data-event="' + node.dataset.event + '">' + message.REMOVE_CONFIRM_CONTINUE + '</div>';
    let confirmReturn = '<div class="main-profile__remove" onclick="confirmReturn(this)" data-event="' + node.dataset.event + '">' + message.REMOVE_CONFIRM_RETURN + '</div>';
    $('.main-profile-block__desc').remove();
    $(node).closest('.main-profile-block__footer').html(confirmTitle + confirmContinue + confirmReturn);
}

function confirmReturn(node) {
    let confirmReturn = '<div class="main-profile__save" onclick="removeProfileBlock(this)" data-event="' + node.dataset.event + '">' + message.REMOVE_BUTTON + '</div>';
    $(node).closest('.main-profile-block__footer').html(confirmReturn);
}

function confirmContinue(node) {
    confirmReturn(node);
    saveProfileBlock(node);
}

function addSaveButton(node, event, mess) {
    if (mess == undefined)
        mess = message.SAVE_BLOCK;

    node.html('<div class="main-profile__save" data-event="' + event + '" onclick="saveProfileBlock(this)">' + mess + '</div>')
    $(node).removeClass('saving');
}

function addSavingButton(node, mess) {
    if (mess == undefined)
        mess = message.SAVE_BLOCK;

    node.html('<div class="main-profile__save">' + mess + '</div>')
}

function removeProfileBlock(node) {
    addConfirmeButton(node);
}

function saveProfileBlock(node) {
    $(node).addClass('saving');

    let data = {};

    inputsFieldNode = $(node).closest('.main-profile-block');
    inputs = inputsFieldNode.find('input.profile-field__input');

    if (inputs.length) {
        inputs.each(function (i, e) {
            data[e.name] = e.value;
        });
    }

    data['event'] = node.dataset.event;

    let editNode = $(node).closest('.main-profile__edit');

    if (!editNode.length) {
        editNode = $(node).closest('.main-profile-block__footer');
    }

    if (data['event'] == 'UPDATE_PROFILE' || data['event'] == 'UPDATE_LOCATION') {
        addSavingButton(editNode, message.AJAX_SAVING);
    } else {
        $(node).html(message.AJAX_SAVING);
    }

    ajaxSend(data, editNode);
}

function ajaxSend(data, editNode) {
    let node = $(editNode).closest('.main-profile-block')
    $.ajax({
        type: 'POST',
        url: params.TEMPLATE_PATH + '/ajax.php',
        data: data,
        dataType: 'json',
        success: function (result) {
            removeAllHint(editNode);
            if (result.success) {
                $(editNode).addClass('saved');
                addSaveButton(editNode, data['event'], message.AJAX_SAVED);
                if (data['event'] == 'REMOVE_USER') {
                    location.href = '/';
                }
            } else {
                if (result.message !== undefined) {
                    addSaveButton(editNode, data['event']);
                    createHint(editNode, message.ERROR_UNDEFINED_EVENT);
                } else {
                    addSaveButton(editNode, data['event']);
                    let error = result.error;

                    for (key in error) {
                        createHint(node.find('[name="' + key + '"]'), message[error[key]]);
                    }
                }
            }
        },
        error: function (result) {
            console.log(result);
        }
    });
}

function createHint(node, title) {
    let hintNode = '<span class="main-profile__hint">' + title + '</span>';
    if (node.prop('tagName') == 'INPUT') {
        node.after(hintNode);
    } else {
        node.append(hintNode);
    }
}

function removeAllHint(node) {
    $(node).closest('.main-profile-block').find('.main-profile__hint').remove();
}

function changeBithday(node) {
    let bithdayField = $(node).closest('.profile-field__input-wrap').find('input[name="PERSONAL_BIRTHDAY"]')
    let currentBithday = bithdayField.val();
    let fieldType = node.dataset.type;
    let currentBithdayArray = ['', '', ''];

    if (currentBithday != '') {
        currentBithdayArray = currentBithday.split('.');
    }

    switch (fieldType) {
        case 'day':
            currentBithdayArray[0] = node.value;
            break;
        case 'month':
            currentBithdayArray[1] = node.value;
            break;
        case 'year':
            currentBithdayArray[2] = node.value;
            break;
    }

    bithdayField.val(currentBithdayArray.join('.'));
}

function showPassword(node) {
    inputWrap = $(node).closest('.profile-field__input-wrap');
    if (inputWrap.hasClass('main-profile__show-password--active')) {
        inputWrap.removeClass('main-profile__show-password--active').find('input').prop('type', 'password');
    } else {
        inputWrap.addClass('main-profile__show-password--active').find('input').prop('type', 'text');
    }
}