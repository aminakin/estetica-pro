$(document).ready(function () {

    $('.subscribe-popup__form input[type="text"]').mask("+7 (999) 999-99-99");
    function shouldShowModal() {
        const lastShownMonthly = localStorage.getItem('modalLastShownMonthly');
        const lastShownYear = localStorage.getItem('modalLastShownYear');
        
        if (lastShownYear) return false;
        if (!lastShownMonthly) return true;

        const lastDate = new Date(lastShownMonthly);
        const currentDate = new Date();
        return currentDate.getMonth() !== lastDate.getMonth();
    }

    function showModal() {
        $('.subscribe-popup__form').fadeIn();
        $('.site-shadow').fadeIn();
        $('.site-shadow').css('zIndex', '7');
    }

    $('.subscribe-popup__form .close, .site-shadow').click(function () {
        $('.subscribe-popup__form').fadeOut();
        $('.site-shadow').fadeOut();
        $('.site-shadow').css('zIndex', '6');
        localStorage.setItem('modalLastShownMonthly', new Date().toString());
    });

    $(document).keyup(function (e) {
        if (e.key === "Escape") {
            $('.subscribe-popup__form').fadeOut();
            $('.site-shadow').fadeOut();
            $('.site-shadow').css('zIndex', '6');
        }
    });

    if (shouldShowModal()) {
        setTimeout(showModal, 1000);
    }

    if ($('.subscribe-popup__success').length > 0 || $('.subscribe-popup__error').length > 0) {
        setTimeout(showModal, 1000);
    }

    if($('.subscribe-popup__success').length > 0){
        localStorage.setItem('modalLastShownYear', new Date().toString());
    }
});