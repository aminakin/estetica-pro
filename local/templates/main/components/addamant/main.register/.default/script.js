function inputsRequiredFields(z) {

  
  
  let inputs = document.querySelectorAll('.form-regform');

  let notHiddenInputs = [];
  if(inputs.length != 0) {
    [...inputs[0].elements].forEach(input => {
      let className = `${input.className}`;
      let passInput = document.querySelector('input[name="REGISTER[PASSWORD]"]')
      let passConfInput = document.querySelector('input[name="REGISTER[CONFIRM_PASSWORD]"]')

      if (input.type !== 'submit' &&
          input.type !== 'hidden' &&
          input.name !== 'REGISTER[PASSWORD]' &&
          input.name !== 'REGISTER[CONFIRM_PASSWORD]' &&
          input.className !== 'PERSONAL_PHONE' &&
          input.type !== 'email' &&
          className != '')
      {
        input.onkeyup = function(elem) {
          let message = document.querySelectorAll(`.div${className}`);
          
          if (input.value.length <= 1) {
            input.nextElementSibling.style.display = 'block';
            input.classList.add('error');
            //message[0].style.display = 'block';
          }
          if (input.value.length > 1) {
            input.nextElementSibling.style.display = 'none';
            input.classList.remove('error');
            //message[0].style.display = 'none';
          }
        }
      }

      if(passInput.value !== passConfInput.value){
        passConfInput.nextElementSibling.style.display = 'block';
        passConfInput.classList.add('error');
      }

      /*input.oninput = function (event) {
        let sibling = startElem.nextElementSibling;
        while (sibling) {
            if (sibling.classList.contains(className)) {
                return sibling;
            }
        }
      }*/

      if (input.name === 'REGISTER[PASSWORD]') {
        input.oninput = function (event) {
          let message = document.querySelectorAll('.bx-auth-input ~ div')

          if (!event.target.validity.valid) {
            message[0].style.display = 'block';
          } else {
            pass = event.target.value;
            message[0].style.display = 'none';
            input.classList.remove('error');
          }
        }
      }

      if (input.name === 'REGISTER[CONFIRM_PASSWORD]') {
        input.oninput = function (event) {
          let message = document.querySelectorAll('.bx-auth-confirm-input ~ div')
          
          pass = event.target.value;
          console.log(passInput.value);
          
          if (passInput.value !== event.target.value) {
            input.classList.add('error');
            message[0].style.display = 'block';
          } else {

            message[0].style.display = 'none';
            input.classList.remove('error');
          }

          if (pass.length === 0 && event.target.value.length === 0) {
            messageConfirmIcon[1].style.display = 'none';
            messageErrorIcon[1].style.display = 'none';
            messageErrorIcon[0].style.display = 'none';
            messageConfirmIcon[0].style.display = 'none';
          }
        }
      }

      if (input.type === 'text' && input.className === 'PERSONAL_PHONE') {
        input.oninput = function () {
        }
      }

      if (input.type === 'text' && input.className === 'LOGIN') {
        input.oninput = function () {
          let messageLogin = document.querySelectorAll(`.div${className}`);

          if (input.value.length < 3) {
            messageLogin[0].style.display = 'block';
          } else {
            messageLogin[0].style.display = 'none';
          }
        }
      }

      if (input.type === 'email') {
        input.oninput = function () {
          let messageMail = document.querySelectorAll(`.div${className}`);
          let re = /^[\w-\.]+@[\w-]+\.[a-z]{2,4}$/i;
          let valid = re.test(input.value);

          if (!valid) {
            input.classList.add('error');
            if(messageMail[0]){
              messageMail[0].style.display = 'block';
            }
          } else {
            if(messageMail[0]){
              messageMail[0].style.display = 'none';
            }
            input.classList.remove('error');
          }
        }
      }

    })
  }


}

function handleChangeVisiblePass(event) {
  let inputPass = document.querySelectorAll('#register .bx-auth-input, #change [name="USER_PASSWORD"]');
  let inputConfirmPass = document.querySelectorAll('#register .bx-auth-confirm-input, #change [name="USER_PASSWORD"]');
  let openEyeIcon = document.querySelectorAll('.registration__password__eye-open');
  let closeEyeIcon = document.querySelectorAll('.registration__password__eye-close');

  if (event.className === 'registration__password__eye-open') {

    inputPass.forEach(value => {
      value.type = 'text'
    })
    inputConfirmPass.forEach(value => {
      value.type = 'text'
    })

    openEyeIcon.forEach(value => {
      value.style.display = 'none';
    })
    closeEyeIcon.forEach(value => {
      value.style.display = 'block';
    })
  } else {
    inputPass.forEach(value => {
      value.type = 'password'
    })
    inputConfirmPass.forEach(value => {
      value.type = 'password'
    })

    openEyeIcon.forEach(value => {
      value.style.display = 'block';
    })
    closeEyeIcon.forEach(value => {
      value.style.display = 'none';
    })
  }
}
function changeSubcribe(th) {
    if (th.checked){
      th.value = 'N'
    } else {
      th.value = 'Y'
    }
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
$(document).ready(function(){
  $(".PERSONAL_PHONE").focusin(function (e){ 
    $(".divPERSONAL_PHONE").show();
  });
  $(".PERSONAL_PHONE").focusout(function (e){ 
    $(".divPERSONAL_PHONE").hide();
  });
});