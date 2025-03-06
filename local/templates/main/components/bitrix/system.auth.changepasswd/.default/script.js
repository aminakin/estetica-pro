
function showPassword(node) {
    inputWrap = $(node).closest('.input-container');
    if (inputWrap.hasClass('show-password--active')) {
        inputWrap.removeClass('show-password--active').find('input').prop('type', 'password');
    } else {
        inputWrap.addClass('show-password--active').find('input').prop('type', 'text');
    }
}
/*$(document).on('input', '.input-container.user-password input[type="password"]', function () {
    let th = $(this);
    let thWrapper = th.closest('.input-container.user-password');

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
});*/
function createHint(node, title) {
    let hintNode = '<span class="main-profile__hint">' + title + '</span>';
    if (node.prop('tagName') == 'INPUT') {
        node.after(hintNode);
    } else {
        node.append(hintNode);
    }
}

function removeAllHint(node) {
    $(node).closest('.input-container.user-password').find('.main-profile__hint').remove();
}