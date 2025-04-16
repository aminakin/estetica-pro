function initMainProfileForm() {

    $(document).on('click', '.subscribe-block__subscribe:not(.subscribe-block--active)', function () {
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
            createHint(thWrapper, message.ERROR_PASS_LENGTH);
        } else {
            if (this.name === 'NEW_PASSWORD' || this.name === 'NEW_PASSWORD_CONFIRM') {
                let passBlockWrap = th.closest('.main-profile__block'),
                    newPassField = passBlockWrap.find('[name="NEW_PASSWORD"]'),
                    confirmPassField = passBlockWrap.find('[name="NEW_PASSWORD_CONFIRM"]'),
                    newPassFieldWrapper = newPassField.closest('.profile-field__input-wrap'),
                    confirmPassFieldWrapper = confirmPassField.closest('.profile-field__input-wrap');

                if (newPassField.val() !== confirmPassField.val() && (newPassField.val().length > 5 || confirmPassField.val().length > 5)) {
                    createHint(newPassFieldWrapper, message.ERROR_PASS_NOT_EQ);

                    createHint(confirmPassFieldWrapper, message.ERROR_PASS_NOT_EQ);
                }
            }
        }
    });

    $( ".profile-field__input.date" ).on('blur, input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        changeBithday(this);
    });
}

function addConfirmeButton(node) {
    let confirmTitle = '<div class="main-profile__confirm">' + message.REMOVE_CONFIRM_TITLE + '</div>';
    let confirmContinue = '<div class="main-profile__save saving" onclick="confirmContinue(this)" data-event="' + node.dataset.event + '">' + message.REMOVE_CONFIRM_CONTINUE + '</div>';
    let confirmReturn = '<div class="main-profile__remove" onclick="confirmReturn(this)" data-event="' + node.dataset.event + '">' + message.REMOVE_CONFIRM_RETURN + '</div>';
    $('.main-profile-block__head.remove').append(confirmTitle);
    $(node).closest('.main-profile-block__footer').html(confirmContinue + confirmReturn);
}

function confirmReturn(node) {
    let confirmReturn = '<div class="main-profile__save" onclick="removeProfileBlock(this)" data-event="' + node.dataset.event + '">' + message.REMOVE_BUTTON + '</div>';
    $(node).closest('.main-profile-block__footer').html(confirmReturn);
    $('.main-profile__confirm').remove();
}

function confirmContinue(node) {
    confirmReturn(node);
    saveProfileBlock(node);
}

function addSaveButton(node, event, mess) {
    if (mess == undefined)
        mess = message.SAVE_BLOCK;
    
    $(node).html(mess);
    $(node).removeClass('saving');
}

function addSavingButton(node, mess) {
    if (mess == undefined)
        mess = message.SAVE_BLOCK;
    
    $(node).html(mess)
}

function removeProfileBlock(node) {
    addConfirmeButton(node);
}

function saveProfileBlock(node) {
    $(node).addClass('saving');

    let data = {};

    if(node.dataset.event == 'UPDATE_SUBSCRIBE'){
        inputsFieldNode = $(node).closest('.main-profile-block');
        inputs = inputsFieldNode.find('input.profile-field__input');
    
        if (inputs.length) {
            inputs.each(function (i, e) {
                data[e.name] = e.value;
            });
        }
    }else{
        inputsFieldNode = $(node).closest('.main-profile__form');
        inputs = inputsFieldNode.find('input.profile-field__input');
    
        if (inputs.length) {
            inputs.each(function (i, e) {
                data[e.name] = e.value;
            });
        }
    }

    data['event'] = node.dataset.event;

    let editNode = $(node).closest('.main-profile-block__footer');
    
    if (!editNode.length) {
        editNode = $(node).closest('.main-profile__form');
    }

    $(node).html(message.AJAX_SAVING);

    ajaxSend(data, editNode, node);
}

function ajaxSend(data, editNode, node) {

   $.ajax({
        type: 'POST',
        url: params.TEMPLATE_PATH + '/ajax.php',
        data: data,
        dataType: 'json',
        success: function (result) {
            console.log(node);
            removeAllHint(editNode);
            if (result.success) {
                $(editNode).addClass('saved');
                addSaveButton(node, data['event'], message.AJAX_SAVED);
                if (data['event'] == 'REMOVE_USER') {
                    location.href = '/';
                }
            } else {
                if (result.message !== undefined) {
                    addSaveButton(node, data['event']);
                    createHint(editNode, message.ERROR_UNDEFINED_EVENT);
                } else {
                    addSaveButton(node, data['event']);
                    let error = result.error;
                    
                    for (key in error) {
                        createHint(editNode.find('[name="' + key + '"]'), message[error[key]]);
                    }
                }
            }
        },
        error: function (result) {
            console.log(result);
        }
    });
}

function createHint(editNode, title) {
    let hintNode = '<span class="main-profile__hint">' + title + '</span>';
    if (editNode.prop('tagName') == 'INPUT') {
        editNode.after(hintNode);
    } else {
        editNode.append(hintNode);
    }
}

function removeAllHint(node) {
    $(node).closest('.main-profile-block').find('.main-profile__hint').remove();
}

function changeBithday(node) {
    let bithdayField = $('input[name="PERSONAL_BIRTHDAY"]')
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