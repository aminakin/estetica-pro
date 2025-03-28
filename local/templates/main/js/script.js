function IsMobileWidth() {
    if (window.innerWidth <= 991)
        return 1;
    else
        return 0;
}
$(window).on("load", function () {
    if (window.innerWidth <= 1024) {
        $('.header-mobile-search').html($('.header-search-line').html());
        $('.header-search-line').html('');
    }
});
//очистка всплывающей подсказки при клике на избранное 
function delInfav() {
    $('.inFavoriteMes').remove();
    $('.new-block').css('z-index', 2);
    $('.add-block').css('z-index', 2);
}

var addFav;
$(function () {
    $('#authForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/local/ajax/login.php',
            data: {
                mode: 'login',
                login: $('#USER_LOGIN').val(),
                password: $('#USER_PASSWORD').val()
            },
            dataType: 'json',
            success: function (result) {

                if (result.status) {
                    location.reload();
                } else {
                    $('.authErrorMessage').show();
                }
            }
        });

        e.preventDefault();
        return false;
    });
});



$(function () {
    $('#btnForpasswd').submit(function (e) {
        e.preventDefault();
        var method = $(this).attr('method');
        var action = '/local/ajax/forpasswd.php?forgot_password=yes';
        var data = $(this).serialize();
        $.ajax({
            type: method,
            url: action,
            data: data,
            dataType: 'json',
            success: function (result) {
                $('.bx-authform.forpass').html('<div class="forgot-ok">Мы выслали Вам ссылку для сброса пароля. <br><br><b>Если письма нет во входящих сообщениях, проверьте папку Спам.</b></div>')
                $('#btnForpasswd').remove()
            }
        });
    });
});

function show_more() {
    $('.show-more__wrapper').remove()
    let loaded = $('[data-entity="catalog-top-container"]').data('loaded')
    if (window.innerWidth > 767) {
        $('[data-entity="catalog-top-container"]').children().eq(loaded).fadeIn()
        $('[data-entity="catalog-top-container"]').children().eq(loaded + 1).fadeIn()
        $('[data-entity="catalog-top-container"]').data('loaded', loaded + 2)
        if ($('[data-entity="catalog-top-container"]').children().eq(loaded + 2).index() > 0) {
            $('[data-entity="catalog-top-container"]').parent().append('<div class="show-more__wrapper"><button class="show-more__button">Показать еще</button></div>')
        }
    } else {
        $('[data-entity="catalog-top-container"]').children().eq(loaded).fadeIn()
        $('[data-entity="catalog-top-container"]').data('loaded', loaded + 1)
        if ($('[data-entity="catalog-top-container"]').children().eq(loaded + 1).index() > 0) {
            $('[data-entity="catalog-top-container"]').parent().append('<div class="show-more__wrapper"><button class="show-more__button">Показать еще</button></div>')
        }
    }
}

function show_cookie() {
    $('.wrapper_modal_cookie').fadeIn("fast");
}

function ajaxUpdate() {
    $('[data-src]').lazy({
        afterLoad: function (element) {
            $(element).removeClass('preload');
        },
    });
}
function addFavorite(id, action) {
    var param = 'id=' + id + "&action=" + action;

    $.ajax({
        url: '/local/ajax/favorites.php', // URL отправки запроса
        type: "GET",
        dataType: "html",
        data: param,
        success: function (response) { // Если Данные отправлены успешно
            var result = $.parseJSON(response);

            if (result == 1) { // Если всё чётко, то выполняем действия, которые показывают, что данные отправлены
                $('.product-item-favorite-image-block[data-item="' + id + '"]').addClass('active');
            }
            if (result == 2) {
                $('.product-item-favorite-image-block[data-item="' + id + '"]').removeClass('active');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) { // Ошибка
            console.log('Error: ' + errorThrown);
        }
    });
}
function addFavoriteDetail(id, action) {
    var param = 'id=' + id + "&action=" + action;

    $.ajax({
        url: '/local/ajax/favorites.php', // URL отправки запроса
        type: "GET",
        dataType: "html",
        data: param,
        success: function (response) { // Если Данные отпраsвлены успешно
            var result = $.parseJSON(response);

            if (result == 1) { // Если всё чётко, то выполняем действия, которые показывают, что данные отправлены
                $('.product-detail-item-favorite-image-block[data-item="' + id + '"]').addClass('active');
            }
            if (result == 2) {
                $('.product-detail-item-favorite-image-block[data-item="' + id + '"]').removeClass('active');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) { // Ошибка
            console.log('Error: ' + errorThrown);
        }
    });
}
/* Избранное */

$(document).ready(function () {
    //let elementWrapper = document.querySelector("#basket-items-list-wrapper");
    //let element = document.querySelector(".basket-checkout-container");
    //document.addEventListener("scroll", (event) => {
    //    let height = elementWrapper.getBoundingClientRect();
    //    console.log(height.y);
    //
    //    if(height.y < 210){
    //        element.style.position = "absolute";
    //
    //    }else{
    //        element.style.position = "static";
    //
    //    }
    //});
    $('.reviews-link_link').on('click', function (e) {
        e.preventDefault();
        var anchor = $(this).attr('href');
        $('html, body').stop().animate({ scrollTop: $(anchor).offset().top - 200 }, 0);
    });
    let menuHeight = 0;
    $('.header-menu .header-menu__item-modal').each(function () {
        menuHeight = $(this).height();
        $(this).find('.second-ul').each(function () {
            $(this).css('max-height', menuHeight);
        });
    });
    (function () {  // анонимная функция (function(){ })(), чтобы переменные "a" и "b" не стали глобальными
        var a = document.querySelector('header'), b = null;  // селектор блока, который нужно закрепить
        window.addEventListener('scroll', Ascroll, false);
        document.body.addEventListener('scroll', Ascroll, false);  // если у html и body высота равна 100%
        function Ascroll() {
            if (b == null) {  // добавить потомка-обёртку, чтобы убрать зависимость с соседями
                var Sa = getComputedStyle(a, ''), s = '';
                for (var i = 0; i < Sa.length; i++) {  // перечислить стили CSS, которые нужно скопировать с родителя
                    if (Sa[i].indexOf('overflow') == 0 || Sa[i].indexOf('border') == 0 || Sa[i].indexOf('outline') == 0 || Sa[i].indexOf('box-shadow') == 0) {
                        s += Sa[i] + ': ' + Sa.getPropertyValue(Sa[i]) + '; '
                    }
                }
                b = document.createElement('div');  // создать потомка
                b.style.cssText = s + ' box-sizing: border-box; width: ' + a.offsetWidth + 'px;';
                a.insertBefore(b, a.firstChild);  // поместить потомка в цепляющийся блок первым
                var l = a.childNodes.length;
                for (var i = 1; i < l; i++) {  // переместить во вновь созданного потомка всех остальных потомков (итого: создан потомок-обёртка, внутри которого по прежнему работают скрипты)
                    b.appendChild(a.childNodes[1]);
                }
                a.style.height = b.getBoundingClientRect().height + 'px';  // если под скользящим элементом есть другие блоки, можно своё значение
                a.style.padding = '0';
                a.style.border = '0';  // если элементу присвоен padding или border
            }

            if (a.getBoundingClientRect().top < -57) { // elem.getBoundingClientRect() возвращает в px координаты элемента относительно верхнего левого угла области просмотра окна браузера
                b.className = 'sticky';
                $('.mainPage').addClass('scroll');
                $('.header-menu__item-modal').css('top', $('.sticky').height());
            } else {
                b.className = '';
                $('.mainPage').removeClass('scroll');
                $('.header-menu__item-modal').css('top', $('.header-wrap').height());
            }
            window.addEventListener('resize', function () {
                a.children[0].style.width = getComputedStyle(a, '').width
            }, false);  // если изменить размер окна браузера, измениться ширина элемента
        }
    })()
    $('#LOGICTIM_PAYMENT_BONUS').on('click', function () {
        let valueInput = $(this).val();
        if (valueInput == 0) {
            $(this).val('');
        }
    });
    $('.pay-popup-close').on('click', function () {
        $('.info-online-pay-popup').removeClass('bx-selected');
        $('.pay-popup-shadow').removeClass('bx-selected');
    });


    $('.fliter-mobile-btn').on('click', function () {
        $('.smartfilter.bx-filter').addClass('active');
        $('.filter-shadow').addClass('active');
    });
    $('.filter-shadow, .close-filter').on('click', function () {
        $('.smartfilter.bx-filter').removeClass('active');
        $('.filter-shadow').removeClass('active');
    });
    //ajaxUpdate();
    if ($('.bx_sitemap_ul_oneline .first:has(ul)')) {
        $('.bx_sitemap_ul_oneline .first').addClass('yesimg');
    }
    $('.bx_sitemap_ul_oneline .second-ul').prev().find('svg').css('display', 'block')
    $('.product-detail-item-favorite-image-block').on('click', function () {
        $('.enterLink-favorites-header').addClass('favorites_not-empty')
    })
    $('.product-item-favorite-image-block').on('click', function () {
        $('.enterLink-favorites-header').addClass('favorites_not-empty')
    })
    if ($.cookie('COOKIE_SET') == null) {
        $.cookie('COOKIE_SET', 'Y', { path: '/' });
        show_cookie();
    }
    $('.modal-cookie_close, .modal-cookie_button').on('click', function () {
        $('.wrapper_modal_cookie').fadeOut();
    });
    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
            $('#scroller').css('display', 'flex');
        } else {
            $('#scroller').fadeOut();
        }
    });
    $('#scroller').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
    $('.tab-item .title-tab').on('click', function () {
        $(this).toggleClass('active');
    });

    $('.footer-container-menu').on('click', function () {
        $(this).find('nav').toggleClass('active')
    });

    $('.catalog-menu-background').on('click', function (event) {
        $('.catalog-top-menu-catalog, .catalog-top-menu-palette, .catalog-menu-modal-container-example, .catalog-menu-modal-container-example-palette').removeClass('active')
        $('body').removeClass('body-modal')
    });

    let timeout;
    $(document).on('mouseleave', '.basket-items-list-item-container-expend', function () {

        let basketItem = $(this);
        timeout = setTimeout(function () {
            basketItem.fadeOut()
        }, 2000);
    })
    $(document).on('mouseover', '.basket-items-list-item-container-expend', function () {
        clearTimeout(timeout)
    })
    $(document).on('mouseover', '.bx_sitemap_ul_oneline .first', function () {
        let curHeight = $(this).find('ul.second-ul');
        curHeight = curHeight.height();
        //let baseHeight = $('.bx_sitemap_oneline').height(curHeight);
    })
    $(document).on('mouseleave', '.basket-items-list-item-container-expend', function () {

    })
    //baseHeight.height(curHeight);

    $(document).on('keypress', '#regform input', function () {
        $(this).closest('form').find('input').each(function () {
            if ($(this).val() != '') {
                $('.button-submit-registration_disabled').css({ 'display': 'none' }).siblings('.button-submit-registration').css({ 'display': 'block' })
            }
        })
    })

    $(document).on('click', '.toggle-filter', function () {
        $(this).find('.toggle-filter__modal').toggleClass('toggle-filter__modal--active');
    });

    show_more()

    $('.catalog-menu-flex-element_mobile .catalog-menu-flex-element-block-brands').slick({
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        rows: 1,
        prevArrow: '<button type="button" data-role="none" class="slick-prev slick-arrow" aria-label="Prev" role="button" style="" aria-disabled="false">' +
            '<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M6.9043 13L1.09588 6.94897" stroke="black" stroke-width="0.75" stroke-linecap="round"/>\n' +
            '<path d="M1.09082 6.87183L6.91308 1.00005" stroke="black" stroke-width="0.75" stroke-linecap="round"/>\n' +
            '</svg>\n</button>',
        nextArrow: '<button type="button" data-role="none" class="slick-next slick-arrow" aria-label="Next" role="button" style="" aria-disabled="false">' +
            '<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M1.19336 1.08984L7.00177 7.14087" stroke="black" stroke-width="0.75" stroke-linecap="round"/>\n' +
            '<path d="M7.00684 7.21802L1.18457 13.0898" stroke="black" stroke-width="0.75" stroke-linecap="round"/>\n' +
            '</svg>\n</button>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    infinite: false,
                    variableWidth: true,
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 500,
                settings: {
                    infinite: false,
                    variableWidth: true,
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ]
    })

    $(document).on('click', '.show-more__button', function () {
        show_more()
    })

    $('.search_slider').slick({
        infinite: false,
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: '<button type="button" data-role="none" class="slick-prev slick-arrow" aria-label="Prev" role="button" style="" aria-disabled="false">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n' +
            '<path d="M8.58984 4L16.5898 12L8.58984 20" stroke="black" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
        nextArrow: '<button type="button" data-role="none" class="slick-next slick-arrow" aria-label="Next" role="button" style="" aria-disabled="false">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n' +
            '<path d="M8.58984 4L16.5898 12L8.58984 20" stroke="black" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
        rows: 1,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });

    $('a[href="#new"]').on('click', function (event) {
        event.preventDefault();
        var sc = $(this).attr("href"),
            dn = $(sc).offset().top - 157;
        $('body,html').animate({ scrollTop: dn }, 1000);
    });

    $('.bx-soa-coupon-input input').on("change keydown keyup input click", function (e) {
        // if (this.value.length > 4 && e.key != 'Backspace' && e.key != 'ArrowLeft' && e.key != 'Delete' && e.key != 'ArrowRight') {
        //     e.preventDefault();
        //     return;
        // }

        // if (this.value.match(/[^0-9]/g)) {
        //     this.value = this.value.replace(/[^0-9]/g, '');
        // }
    });
    $('.bx-soa-coupon-input input').on("focusout", function (e) {
        if (this.value.length < 5) {
            less = 5 - this.value.length;
        }
    });

    $(document).on('mouseenter click', '.coupon-tooltip__icon', function (e) {
        let tooltip = $(this).find('.coupon-tooltip__block');
        if ($(e.target).hasClass('coupon-tooltip__close')) {
            tooltip.removeClass('active').animate({ opacity: 0 }, 500, function () {
                tooltip.css({ 'display': 'none' })
            });
        } else {
            tooltip.addClass('active').css({ 'opacity': 0, 'display': 'block' }).animate({ opacity: 1 }, 300);
        }
    });

    $('.error-popup__close').click(function () {
        $('.error-popup').css({ 'display': 'none' });
    });
    let activeMenuItem = $('.bx_sitemap_ul a.active');
    activeMenuItem.closest('ul').siblings('a').addClass('active').closest('ul').siblings('a').addClass('active');

    $('.bx_sitemap_ul a.active').closest()
    $(document).on('click', '.personal-field-wrapper .edit-link', function () {
        $(this).addClass('active').siblings('input').addClass('active').removeAttr('disabled').focus();
    });
    $(document).on('focusout keypress', '.personal-field-wrapper input', function (event) {
        if (event.key == "Enter" && event.type == 'keypress' || event.type == 'focusout') {
            var thisInput = this;
            $(thisInput).removeClass('active').attr('disabled', '');
            $(thisInput).siblings('.edit-link').prepend('<span class="loading"><img style="height: 20px;" src="/local/templates/main/images/loading.gif" alt="Loading"></span>');
            var fieldValue = $(this).val();
            if ($(this).attr('name') == 'PERSONAL_PHONE') {
                var fieldValue = $(this).val().replace(/\D+/g, "").replace(/^.{1}/, '');
            }
            var ajaxPath = '/ajax/edit_personal_field.php';
            var ajaxData = 'field_name=' + $(this).attr('name') + '&field_value=' + fieldValue;
            if ($(thisInput).hasClass('address-input')) {
                ajaxPath = '/ajax/edit_personal_address_field.php';
                ajaxData = 'user_id=' + $(this).data('user-id') + '&field_id=' + $(this).data('field-id') + '&field_value=' + fieldValue + '&field_type=' + $(this).data('field-type');
            }
            $.ajax({
                type: 'POST',
                url: ajaxPath,
                data: ajaxData,
                success: function (result) {
                    $(thisInput).addClass('saved').siblings('.edit-link').addClass('saved').removeClass('edit-link').html('Изменения сохранены');
                }
            });
        }
    });
    $('.products-viewed__slider').slick({
        slidesToShow: 4,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });
    $('.courses-block__desktop .courses-block__slider').slick({
        slidesToShow: 3
    });
    $('.courses-block__mobile .courses-block__slider').slick({
        slidesToShow: 1
    });
    $('.tabs-block__tab').click(function () {
        $('.tabs-block__tab').removeClass('active');
        $(this).addClass('active');
        let index = $(this).index() + 1;
        $('.tabs-block__tab-content').removeClass('active');
        $('.tabs-block__tab-content:nth-child(' + index + ')').addClass('active');
    });
    $('.courses-block__tab').click(function () {

        $('.courses-block__tab').removeClass('active');
        $(this).addClass('active');
        let index = $(this).index() + 1;
        $('.courses-block__tab-content').removeClass('active');
        $('.courses-block__tab-content:nth-child(' + index + ')').addClass('active');
        $('.courses-block__desktop .courses-block__slider').slick('unslick');
        $('.courses-block__desktop .courses-block__slider').slick({
            slidesToShow: 3
        });
    });
    var $status = $('.pagingInfo');
    var $slickElement = $('.courses-detail-blocks .courses-detail__slider');

    $slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
        //currentSlide is undefined on init -- set it to 0 in this case (currentSlide is 0 based)
        var i = (currentSlide ? currentSlide : 0) + 1;
        if (slick.slideCount != 0)
            $status.html('<span class="numder">' + i + '</span>/' + slick.slideCount);
    });
    $slickElement.slick({
        centerMode: true,
        centerPadding: '220px',
        slidesToShow: 3,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 560,
                settings: {
                    slidesToShow: 1,
                    centerPadding: '90px',
                }
            }
        ]
    });

    $('.delivery-info').click(function (event) {
        $('.delivery-info-block').toggleClass('open');
    });
    $(document).on('click', '.delivery-info-block .close', function () {
        $(this).closest('.delivery-info-block').removeClass('open');
    });

    $('[autocomplete="tel"]').mask("+7 (999)999-99-99");

    var hrSearch = window.location.search;

    $.get('/local/ajax/getBasketCount.php', function (data) {
        //$('.small-basket-quantity').html(data);
    });
    $.get('/local/ajax/getFavoriteCount.php', function (data) {
        $('#smallFavoriteHeader span').html(data);
    });

    $(document).on('click', '.header-desktop .header-menu__item .catalog-top-menu-catalog', function (e) {
        e.preventDefault();
        let parentMenu = $(this).parent('.header-menu__item');

        parentMenu.addClass('active');

        $('.header-menu__item-modal').each(function () {
            $('.header-menu__item-modal').css({ "visibility": "hidden", "opacity": "0" });
        });

        let menuChild = parentMenu.children('.header-menu__item-modal');

        let headerH = 0;
        if ($('#bx-panel').length) {
            headerH = $('#bx-panel').height();
        }

        $('.header-menu__item-modal').addClass('active');
        menuChild.css({ 'top': $('.header-wrap').height() });

        menuChild.css({ "visibility": "visible", "opacity": "1" });
    });

    $(document).click(function (e) {
        if ($(e.target).closest(".header-menu__item.active").length) {
            return;
        } else {
            $('.header-menu__item-modal').css({ "visibility": "hidden", "opacity": "0" });
            $(".header-menu__item-modal.active").removeClass('active');
            $(".header-menu__item.active").removeClass('active');
        }
    });

    $(document).on('click', '.header-menu__item.active', function (event) {

        if ($(event.target).closest('.header-menu__item-modal.active').length == 0) {

            $('.header-menu__item').each(function () {
                $(this).removeClass('active');
            });
            $('.header-menu__item-modal').each(function () {
                $(this).removeClass('active');
            });
            $('.header-menu__item-modal').css({ "visibility": "hidden", "opacity": "0" });
        }

    });

    $(document).on('mouseenter', '.bx_sitemap_ul_oneline .first', function () {
        $(this).addClass('first-active');
    });
    $(document).on('mouseleave', '.bx_sitemap_ul_oneline .first', function () {
        $(this).removeClass('first-active');
    });

    $(document).on('click', '.header-burger', function (e) {
        e.preventDefault();

        let headerH = 0;
        if ($('#bx-panel').length)
            headerH = $('#bx-panel').height();

        $('.modal-background').css({ 'top': $('.header-mobile-top').height() + headerH + 1 });
        $('.header-mobile-menu').css({ 'top': $('.header-mobile-top').height() + headerH + 1 });

        $(this).addClass('active');
        $('header').addClass('active');
        $('.header-mobile-menu').addClass('active');
        $('.header-mobile-menu').slideDown('fast');
        $('body').css('overflow', 'hidden');
        $('.burger-menu').hide();
        $('.close-menu').show();
    });
    $(document).on('click', '.header-burger.active', function (e) {
        $('header').removeClass('active');
        $(this).removeClass('active');
        $('body').css('overflow', 'auto');
        $('.header-mobile-menu').removeClass('active');
        $('.header-mobile-menu').slideUp('fast');
        $('.burger-menu').show();
        $('.close-menu').hide();
        $('.top-mobile-menu__catalog-list').each(function () {
            $(this).slideUp('fast');
        });
        $('.top-mobile-menu__catalog_opener').each(function () {
            $(this).removeClass('.top-mobile-menu__catalog_active');
        });
    });

    $('.top-mobile-menu__catalog_opener').on('click', function (e) {

        let menuWrap = $(this).siblings('.top-mobile-menu__catalog-list');

        e.preventDefault();
        $(this).addClass('top-mobile-menu__catalog_active');

        let headerH = 0;
        if ($('#bx-panel').length)
            headerH = $('#bx-panel').height();

        menuWrap.css({ 'top': $('.header-mobile-top').height() + headerH + 1 }).slideDown('fast');
    });

    $('.back-menu').on('click', function () {
        let menuWrap = $(this).parent('.top-mobile-menu__catalog-list');
        menuWrap.prev().removeClass('top-mobile-menu__catalog_active');
        menuWrap.slideUp('fast');
    });

    $('.top-mobile-menu__catalog-list li a').on('click', function (e) {
        let menuWrap = $(this).siblings('.top-mobile-menu__catalog-list');

        if (menuWrap.length) {
            e.preventDefault();
            $(this).addClass('top-mobile-menu__catalog_active');

            let headerH = 0;
            if ($('#bx-panel').length)
                headerH = $('#bx-panel').height();

            menuWrap.css({ 'top': $('.header-mobile-top').height() + headerH + 1 }).slideDown('fast');
        }
    });

    $('.back-menu').on('click', function () {
        let menuWrap = $(this).parent('.top-mobile-menu__catalog-list');
        menuWrap.prev().removeClass('top-mobile-menu__catalog_active');
        menuWrap.slideUp('fast');
    });

    /*$('.header-menu__item').on('click', function(){
        $(this).children('.top-mobile-menu__catalog-list').show();
    });*/

    $('.mainSlider').each(function () {
        var that = $(this);
        that.find('.slider').slick({
            infinite: true,
            autoplay: true,
            slidesToShow: 1,
            centerMode: false,
            variableWidth: false,
            arrows: false,
            dots: true,
            vertical: true,
            speed: 1000,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        vertical: false
                    }
                }
            ]
        });
    });

    var toggleOpen = $('.toggleOpen');
    var linkOpen = $('.linkOpen');

    var params = window
        .location
        .search
        .replace('?', '')
        .split('&')
        .reduce(
            function (p, e) {
                var a = e.split('=');
                p[decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );

    if ($('#change .notetext').length != 0) {
        $('#change').addClass('success').find('.bx-auth').html('<svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M11 20.0345L17.3636 25L26 13" stroke="#33CCCC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <circle cx="18.5" cy="18.5" r="17.5" stroke="#33CCCC" stroke-width="1.5"/> </svg>')
        $('#change .auth-container').addClass('success').html('<h3>Пароль успешно изменен</h3>')
    }

    $('.bx-changepass-table [name="USER_PASSWORD"]').on('change', function () {
        $(this).closest('.bx-changepass-table').find('[name="USER_CONFIRM_PASSWORD"]').val($(this).val())
    })

    $(document).on('click', '.linkOpen', function () {
        event.preventDefault();
        $('body').addClass('body-modal')
        if ($(this).hasClass('baskLink') && window.innerWidth < 1024) {
            window.location.href = "/personal/cart/";
            return;
        }



        /*const prevent = ev => ev.preventDefault();
        document.body.addEventListener('wheel', prevent, { passive: false });

        if (document.body.clientWidth < 1024) {
            document.querySelector('body').style.overflow = 'hidden'
        }*/

        $('.detail-popup.bonus').hide();
        var that = $(this);
        var href = that.attr('href');

        if (href == '#auth' || href == '#about_bonus' || href == '#basket') {
            $('.modal-background').fadeIn()
        }
        if (href == '#menu' && !$(this).hasClass('opened')) {
            $('.header-mobile').addClass('hide-icons')
        }
        if (href == '#menu' && $(this).hasClass('opened')) {
            $('.header-mobile').removeClass('hide-icons')
            $('body').removeClass('body-modal')
        }

        toggleOpen.filter('.opened').not(href).removeClass('opened');
        linkOpen.filter('.opened').not(that).removeClass('opened');
        $(href).toggleClass('opened');
        that.toggleClass('opened');


        if (href == '#about_bonus') {
            $('.toggleOpen.about_bonus').addClass('opened');
        }
        if (href == '#no_fav_popup') {
            $('.toggleOpen.no_fav_popup').addClass('opened');
        }
        if (href == '#search')
            $('#title-search-input').focus();
        return false;
    });

    $(document).on('click', '.showPreAuthForm', function (e) {
        if (window.innerWidth < 1024) {
            window.location.href = "/personal/profile/";
            return;
        } else {
            e.preventDefault();
            $('body').addClass('body-modal')
            $('.auth-form').addClass('auth-form--showed').find('.auth-form__inner').css({
                'right': $(window).width() - $(this).offset().left - 110,
                'top': $(this).height() + $(this).position().top + 55,
            });
        }
    })

    $(document).on('click', '.auth-form__layout', function (e) {
        $('body').removeClass('body-modal')
        $('.auth-form').removeClass('auth-form--showed')
    })

    $(document).on('click', '.auth-form__button, .auth-form__link', function (e) {
        $('.auth-form').removeClass('auth-form--showed');
        $('.enterLink.linkOpen').not('.user-icon').trigger('click')
    })

    $(document).on('click', '.clear-basket', function () {
        $.ajax({
            url: '/local/ajax/clear_basket.php', // URL отправки запроса
            type: "POST",
            dataType: "html",
            success: function (response) { // Если Данные отправлены успешно
                window.location.reload()
            },

            error: function (jqXHR, textStatus, errorThrown) { // Ошибка
                console.log('Error: ' + errorThrown);
            }
        });
    })

    $('.close, .modal-background').click(function () {
        if ($(this).closest('.toggleOpen.success#change').length != 0) {
            window.location = '/'
            return false
        }
        
        $('.modal-background').fadeOut()
        $('body').removeClass('body-modal');
        $('.toggleOpen.opened').removeClass('opened');
        $('.custom_popup_block.opened').removeClass('opened');
        $("#basket.custom_popup_block").animate({
            right: "-700px",
        }, 300);
        linkOpen.removeClass('opened');
        return false;
    });
    var UA = navigator.userAgent,
        iOS = !!(UA.match(/iPad|iPhone/i));

    if (iOS) {
        $(document).on('touchstart', '.toggleLink', function (e) {
            $(this).trigger('click')
        });
    }

    $('.noteBlock').each(function () {
        var block = $(this);
        block.find('.close').on('click', function () {
            block.fadeOut('slow');
            return false;
        });
    });

    $('form input[type="email"]').blur(function () {
        if ($(this).val() != '') {
            var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if (!pattern.test($(this).val())) {

                $(this).css({ 'border': '1px solid #ff0000' });
                $('.noteBlock').css('display', 'block');
                $('.noteBlock .inner').text('Не верно указан адрес электронной почты');

            }
        } else {
            $(this).css({ 'border': '1px solid #ff0000' });
            $('.noteBlock').css('display', 'block');
            $('.noteBlock .inner').text('Поле email не должно быть пустым');

        }
    });

    // слайдер продукта в детальном просмотре
    $('#product-cart-slider-main').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: false,
        fade: true,
        asNavFor: '#product-cart-slider-thumbnails'
    });

    $('#product-cart-slider-thumbnails').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '#product-cart-slider-main',
        vertical: false,
        verticalSwiping: false,
        arrows: true,
        dots: false,
        focusOnSelect: true
    });

    // всплывающее окно с фото продукта из слайдера
    $('#product-cart-slider-main a').fancybox({
        openEffect: 'fade',
        prevEffect: 'fade',
        nextEffect: 'fade',
        maxWidth: 1200,
        margin: [151, 0, 0, 0],
        helpers: {
            title: {
                type: 'inside'
            }
        }
    });

    $('.basket-block .basket-item').last().addClass('last');


    //секции
    $(".bx_sitemap_ul li a").each(function () {
        if ($(this).parent().children("ul").length) {
            $(this).addClass("expand");
        } else {
            $(this).addClass("not_expand");
        }

        if ($(this).hasClass("active")) {
            $(this).parents().show();
        }

    });

    $('.adult-modal-block-link-success').on('click', function () {
        const adultView = 'adultView=' + true;

        $.ajax({
            url: '/local/ajax/adultProduct.php', // URL отправки запроса
            type: "GET",
            dataType: "html",
            data: adultView,
            success: function (response) { // Если Данные отправлены успешно
                var result = $.parseJSON(response);

                if (result === 1) {
                    let modalWindow = document.querySelectorAll('.product-item-adult-modal');
                    const adress = window.location.href;

                    modalWindow[0].style.display = 'none';
                    window.location.href = adress;
                }
            },

            error: function (jqXHR, textStatus, errorThrown) { // Ошибка
                console.log('Error: ' + errorThrown);
            }
        });
    })

    $('.personal-favorites-header-menu-del').on('click', function () {
        const deleteAllFavorits = 'del=' + true;

        $.ajax({
            url: '/local/ajax/favorites.php',
            type: 'GET',
            dataType: "html",
            data: deleteAllFavorits,
            success: function () {
                const adress = window.location.href;

                window.location.href = adress;
            },
            error: function (jqXHR, textStatus, errorThrown) { // Ошибка
                console.log('Error: ' + errorThrown);
            }
        });
    })

});

function createToast() {
    var widthWindow = $(window).width();
    if (widthWindow < 768) {
        if ($('.fly-toast-prod').length === 0) {
            var newToast = $("<div class='fly-toast-prod'></div>");
            $('.header-mobile-top').append(newToast);
        }
    } else {
        if ($('.fly-toast-prod').length === 0) {
            var newToast = $("<div class='fly-toast-prod'></div>");
            $('.fly-prod-wrap').append(newToast);
        }
    }
}

$(document).on('click', '.close-prod', function () {
    removeFlyProd()
});

$(document).on('click', '.product-item-amount-field-btn-disabled', function () {
    $('.product-item-detail-slider-image').clone().insertAfter('.image-close-buy');
    var text = $('.image-close-buy').html();
    $('.image-close-buy').html(text);
    //setTimeout(removeProdAvail, 5000);
});
function removeProdAvail() {
    $('.quantity-wrappper').fadeOut().remove();
}
function createFlyProd(strJSON) {
    let productIdQty = strJSON.qtyprod;
    let textFlyProd = productIdQty > 1 ? "Доступно для заказа " + productIdQty + " шт." : "Товар добавлен в корзину";
    let prodElems = $("<div class='fly-prod'style='display:none;'></div>")
        .append("<div class='images-wrap'></div>")
        .append(`<div class='text-prod'>${textFlyProd}</div>`)
        .append(`<div class='close-prod'>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.7071 6.70711C19.0976 6.31658 19.0976 5.68342 18.7071 5.29289C18.3166 4.90237 17.6834 4.90237 17.2929 5.29289L12 10.5858L6.70711 5.29289C6.31658 4.90237 5.68342 4.90237 5.29289 5.29289C4.90237 5.68342 4.90237 6.31658 5.29289 6.70711L10.5858 12L5.29289 17.2929C4.90237 17.6834 4.90237 18.3166 5.29289 18.7071C5.68342 19.0976 6.31658 19.0976 6.70711 18.7071L12 13.4142L17.2929 18.7071C17.6834 19.0976 18.3166 19.0976 18.7071 18.7071C19.0976 18.3166 19.0976 17.6834 18.7071 17.2929L13.4142 12L18.7071 6.70711Z" fill="black"/>
            </svg>
            </div>`);
    $('.fly-toast-prod').html(prodElems);
    $('.product-detail-item-by-button span').text('Добавлено');
    $('.product-detail-item-by-button').css("background-color", "#D8ABB7");
    $('.images-wrap').css("background-image", "url(" + strJSON.img + ")");
    prodElems.fadeIn();
}

function removeFlyProd() {
    $('.fly-prod').fadeOut().remove();
    $('.product-detail-item-by-button span').text('Добавить ещё');
    $('.product-detail-item-by-button').css("background-color", "#EBCFD5");
}

// catalog actions
$(document)
    .on('click', '.buyBtn', function () {

        var id = $(this).attr('data-id') || false;
        if (!id) return false;

        var qty = parseInt($(this).parents('.product-item:first, .product-item-detail-pay-block').find('input[type=number]').val());
        if (isNaN(qty)) qty = parseInt($(this).closest('.other').find('input[type=number]').val());
        if (isNaN(qty)) qty = parseInt($(this).closest('.detail-text').find('input[type=number]').val());
        if (qty < 1 || isNaN(qty)) qty = 1;

        var that = $(this);
        var docHref = document.location.href;
        var toggleOpen = $('.toggleOpen');
        var headerpx = $('header').height();
        var topwindow = $(window).scrollTop();
        var topwindow = 0;

        let _this = $(this);

        //$('.small-basket-quantity').css({ 'display': 'block' });
        $.post('/local/ajax/add2basket.php', { id: id, quantity: qty }, function (response) {
            if (response.trim() != '0') {

                $("a.buyBtn[data-id = " + id + "]").addClass('inB');

                var winH = $(window).height();
                var winW = $(window).width();

                var strJSON = response;
                strJSON = JSON.parse(strJSON);

                $('#basket_add').find('.img').attr('href', strJSON.url);
                $('#basket_add').find('.title').attr('href', strJSON.url);
                $('#basket_add').find('.title').html(strJSON.name);
                $('#basket_add').find('img').attr('src', strJSON.img);
                $('#basket_add').find('.cat').html(strJSON.sect);
                $('#basket_add').find('[type="number"]').val(strJSON.count);
                $('#basket_add').find('[type="number"]').prop('max', strJSON.max_count);
                $('#basket_add').find('[type="number"]').attr('id', strJSON.nid);
                $('#basket_add').find('.price-val').html(strJSON.price);

                createToast();
                createFlyProd(strJSON);
                setTimeout(removeFlyProd, 3000);

                $('.window .close').click(function (e) {
                    e.preventDefault();
                    $('#mask, .window').hide();
                });

                $('.closeBtn').click(function (e) {
                    $('.toggleOpen').removeClass('opened');
                    $('.linkOpen').removeClass('opened');
                    return false;
                });

                $('#mask').click(function () {
                    $(this).hide();
                    $('.window').hide();
                });

                $("a.inFavorite[data-favorite-id = " + id + "]").css("background-image", "url('/local/templates/main/images/icons/heart.svg')");

                $.get('/local/ajax/getBasketCount.php', { urlPage: docHref }, function (dataBasket) {
                    //$('.small-basket-quantity').html(dataBasket)
                });

                $.get('/local/ajax/getAllSum.php', { urlPage: docHref }, function (dataBasket) {
                    $('.basket-small__price').html(dataBasket)
                    if (window.innerWidth < 769) {
                        $('#basket-small').addClass('opened')
                        setTimeout(function () {
                            $('#basket-small').removeClass('opened');
                        }, 2000);
                    }
                });

                jQuery.fn.outerHTML = function (s) {
                    return s
                        ? this.before(s).remove()
                        : jQuery("<p>").append(this.eq(0).clone()).html();
                };



                $.get('/local/ajax/getBasketModal.php', { urlPage: docHref }, function (dataBasket) {
                    let itemsCanceled,
                        htmlCanceled = '',
                        thisItemReturned;

                    thisItemReturned = _this.closest('.basket-item__deleted');



                    if ($('#basket_form_small .basket-item__deleted').not(thisItemReturned).length > 0) {

                        $('#basket_form_small .basket-item__deleted').not(thisItemReturned).each(function (i, e) {

                            if ($(e).length > 0) {
                                htmlCanceled += $(e).closest('.basket-item').outerHTML()
                            }

                        })

                    }

                    if ($('#basket_form_small .basket-item__deleted').not(thisItemReturned).length == 1) {
                        htmlCanceled = $('#basket_form_small .basket-item__deleted').not(thisItemReturned).closest('.basket-item').outerHTML()
                    }

                    $('#basket').html('<a href="#" class="close basket-close"><span></span><span></span></a>' + dataBasket);

                    $('.basket-close').on('click', function () {
                        $("#basket.custom_popup_block").animate({
                            right: "-700px",
                        }, 300);
                    });

                    if (htmlCanceled != 'undefined' && typeof htmlCanceled != undefined) {
                        $('#basket_form_small .basket-items').prepend(htmlCanceled);
                    }

                    $('#basket input').styler();

                    var that = $('#basket');
                    that.find('a.close').click(function () {
                        that.removeClass('opened');
                        $('#smallBasketHeader').removeClass('opened');
                        $('.modal-bg').fadeOut();
                        return false;

                    });

                    var thatF = $('#favorite');
                    thatF.find('a.close').click(function () {
                        thatF.removeClass('opened');
                        $('#smallFavoriteHeader').removeClass('opened');
                        return false;
                    });

                });
                $.get('/local/ajax/getFavoriteModal.php', { urlPage: docHref }, function (dataFavorite) {
                    $('#favorite').html(dataFavorite);

                    var th = $('#basket');
                    th.find('a.close').click(function () {
                        th.removeClass('opened');
                        $('#smallBasketHeader').removeClass('opened');
                        return false;
                    });

                    var thF = $('#favorite');
                    thF.find('a.close').click(function () {
                        thF.removeClass('opened');
                        $('#smallFavoriteHeader').removeClass('opened');
                        return false;
                    });

                });

                setTimeout(function () {
                    $('#basket_add').removeClass('opened');
                }, 500000);
            }
        });

        return false;
    })

    // catalog favorite
    .on('click', '.favoriteBtn', function (e) {
        e.preventDefault();
        var id = $(this).attr('data-favorite-id');
        var btn = $(this);
        if (!$(this).hasClass('inFv')) {
            $.post('/local/ajax/favorite.php', { id: id }, function (response) {
                if (response == '1') {
                    $("a.inFavorite[data-favorite-id = " + id + "]").css("background-image", "url('/local/templates/main/images/icons/like_bg.png')");
                    $("a.inFavorite[data-favorite-id = " + id + "]").addClass("inFv");
                    $("a.buyBtn[data-id = " + id + "]").html('<svg class="product-item-button-basket-svg" xmlns="http://www.w3.org/2000/svg" width="15" height="17" viewBox="0 0 15 17" fill="none"> <path d="M1 5H14V16H1V5Z" stroke="white" stroke-linecap="round"></path> <path d="M4 4.5C4 2.567 5.567 1 7.5 1V1C9.433 1 11 2.567 11 4.5V5H4V4.5Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path> </svg>');
                    $.get('/local/ajax/getList.php', function (dataFavoriteList) {

                        var strJSON = dataFavoriteList;
                        strJSON = JSON.parse(strJSON);
                        addFav = strJSON;

                        $('#smallFavoriteHeader').find('span').html(strJSON.fav.length);
                        $('#smallBasketHeader').find('span').html(strJSON.bas.length);
                        $('.small_basket_price').html(strJSON.bassumm + ' руб.');

                    });

                    var idModal = '#dialog';
                    var textModal = 'Товар добавлен в избранное!';
                    var winH = $(document).height();
                    var winW = $(window).width();
                    $(idModal).css('top', '20px');
                    $(idModal).css('right', '15px');
                    $(idModal).css('position', 'fixed');
                    $(idModal).show();
                    $('#dialog .content p').text(textModal);
                    $(idModal).show(1000, function () {
                        setTimeout(function () {
                            $(idModal).fadeOut(500);
                        }, 3000);
                    });

                    $('.window .close').click(function (e) {
                        e.preventDefault();
                        $('#mask, .window').hide();
                    });

                    $('#mask').click(function () {
                        $(this).hide();
                        $('.window').hide();
                    });

                    $.get('/local/ajax/getBasketModal.php', function (dataBasket) {
                        $('#basket').html(dataBasket);
                        $('#basket input').styler();
                        var th = $('#basket');
                        th.find('a.close').click(function () {
                            th.removeClass('opened');
                            $('#smallBasketHeader').removeClass('opened');
                            return false;
                        });

                        var thF = $('#favorite');
                        thF.find('a.close').click(function () {
                            thF.removeClass('opened');
                            $('#smallFavoriteHeader').removeClass('opened');
                            return false;
                        });
                    });
                    $.get('/local/ajax/getFavoriteModal.php', function (dataFavorite) {
                        $('#favorite').html(dataFavorite);

                        var th = $('#basket');
                        th.find('a.close').click(function () {
                            th.removeClass('opened');
                            $('#smallBasketHeader').removeClass('opened');
                            return false;
                        });

                        var thF = $('#favorite');
                        thF.find('a.close').click(function () {
                            thF.removeClass('opened');
                            $('#smallFavoriteHeader').removeClass('opened');
                            return false;
                        });
                    });
                } else {
                    if (response == '2') {
                        $('.inFavoriteMes').remove();
                        btn.closest('.new-block').css('z-index', 5);
                        btn.closest('.add-block').css('z-index', 5);
                        btn.closest('.add-block').find('.inFavorite').append('<div class="inFavoriteMes">чтобы добавить товар в избранное, <a href="#auth" class="linkOpen">авторизуйтесь</a> на сайте</div>');
                        btn.append('<div class="inFavoriteMes">чтобы добавить товар в избранное, <a href="#auth" class="linkOpen">авторизуйтесь</a> на сайте</div>');
                        var width_mes = 0;
                        if (btn.closest('.new-block').width() != null) {
                            width_mes += parseInt(btn.closest('.new-block').width()) - 28.5;
                        }
                        if (btn.closest('.add-block').width() != null) {
                            width_mes += btn.closest('.add-block').width() + 11.5;
                        }
                        if (width_mes < 150) width_mes = 200;
                        $('.inFavoriteMes').width(width_mes);
                        setTimeout(delInfav, 2000);
                        return false;
                    }
                }
            });
        } else {
            $.post(
                "/local/ajax/delFav.php",
                {
                    catid: id
                },
                function () {
                    $("a.inFavorite[data-favorite-id = " + id + "]").css("background-image", "");
                    $("a.inFavorite[data-favorite-id = " + id + "]").removeClass("inFv");
                    $.get('/local/ajax/getList.php', function (dataFavoriteList) {

                        var strJSON = dataFavoriteList;
                        strJSON = JSON.parse(strJSON);
                        $('#smallFavoriteHeader').find('span').html(strJSON.fav.length);
                        $('#smallBasketHeader').find('span').html(strJSON.bas.length);
                        $('.small_basket_price').html(strJSON.bassumm + ' руб.');
                        addFav = strJSON;

                    });
                    $.get('/local/ajax/getFavoriteModal.php', function (dataFavorite) {
                        $('#favorite').html(dataFavorite);

                        var th = $('#basket');
                        th.find('a.close').click(function () {
                            th.removeClass('opened');
                            $('#smallBasketHeader').removeClass('opened');
                            return false;
                        });

                        var thF = $('#favorite');
                        thF.find('a.close').click(function () {
                            thF.removeClass('opened');
                            $('#smallFavoriteHeader').removeClass('opened');
                            return false;
                        });
                    });
                }
            );
        }
    })

    // cart
    .on('change', '.basket-item [type="number"]', function () {
        var quantityItem = parseInt($(this).val());
        if (quantityItem < 1 || isNaN(quantityItem)) quantityItem = 1;
        if ($(this).prop('max') == "") return;
        if (quantityItem > $(this).prop('max')) {
            prodquant($(this).prop('max'));
            quantityItem = $(this).prop('max');
        }
        $(this).val(quantityItem);
        var id = $(this).attr('id');
        var frm = $(this).closest('.basket');
        var item = $(this).closest('.basket-item');
        $.post(
            "/local/ajax/updateCount.php",
            {
                id: id,
                quantity: quantityItem
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            item.find('.sum').html(data);
            $.get('/local/ajax/getAllSum.php', function (allSum) {
                $('#allSum_FORMATED').html(allSum);

                /*if (allSum.replace(/\D+/g, "") > 1499) {
                    $(".bottom-buttons .button").show();
                    $(".minsum").hide();

                } else {
                    $(".bottom-buttons .button").hide();
                    $('.minsum__value').html(1500 - allSum.replace(/\D+/g, ""))
                    $(".minsum").show();

                }*/
            });
        }
    })

    //обновление количества товаров в корзине на лету
    .on('keyup', '.basket-item [type="number"]', function () {
        var quantityItem = parseInt($(this).val());
        if (quantityItem < 1 || isNaN(quantityItem)) quantityItem = 1;

        $(this).val(quantityItem);

        var id = $(this).attr('id');
        var frm = $(this).closest('.basket');
        var item = $(this).closest('.basket-item');

        $.post(
            "/local/ajax/updateCount.php",
            {
                id: id,
                quantity: quantityItem
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            item.find('.sum').html(data);
            $.get('/local/ajax/getAllSum.php', function (allSum) {
                $('#allSum_FORMATED').html(allSum);

                /*if (allSum.replace(/\D+/g, "") > 1499) {
                    $(".bottom-buttons .button").show();
                    $(".minsum").hide();

                } else {
                    $(".bottom-buttons .button").hide();
                    $('.minsum__value').html(1500 - allSum.replace(/\D+/g, ""))
                    $(".minsum").show();

                }*/
            });
        }
    })

//быстрый просмотр каталога
$(document).on('click', '.quick', function () {
    var top = 0;
    top = $(this).closest('.add-block').offset().top - $('.add-block:first').offset().top;
    $('.quick_view').css('display', 'none');
    $('[data-qid="' + $(this).data('id') + '"]').css('display', 'block');
    $('[data-qid="' + $(this).data('id') + '"]').css('top', top + 'px');
    return false;
});

//зыкрыть быстрый просмотр каталога
$(document).on('click', '.qclose', function () {
    $('.quick_view').css('display', 'none');
    return false;
});

//модал комментариев
$(document).on('click', '[href="#comment"]', function () {
    var id = $(this).data('id');
    $.ajax({
        url: '/local/ajax/addComment.php',
        type: "POST",
        data: { id: id },
        cache: false,
        dataType: "html",
        success: function (data) {
            $('.addcomment').html(data);
            if ($(this).attr('href') == '#comment') window.scrollTo(0, 0);
        },
        error: function (msg) {
            alert("Ошибка отправки, попробуйте позже");
        },
        complete: function () {
            Recaptchafree.reset();
        }
    });
});

//фильтр каталога в мобильной версии
$(document).on('click', '.filterblock', function () {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $('.filterbody').removeClass('active');
    } else {
        $(this).addClass('active');
        $('.filterbody').addClass('active');
    }
});

//сортировка в каталоге
$(document).on('change', '#sortc', function () {
    window.location.href = $(this).val();
});


//пагинация
function Loader(p) {
    var o = this;
    this.root = $(p.root);
    if (typeof p.path == "undefined") {
        this.path = '/news/ajax.php';
    } else {
        this.path = p.path;
    }
    this.newsBlock = $(p.newsBlock, this.root);
    this.newsLoader = $(p.newsLoader);
    this.ajaxLoader = $(p.ajaxLoader);
    this.curPage = 1;
    if (typeof p.COUNT == "undefined")
        this.COUNT = "";
    else
        this.COUNT = p.COUNT;
    this.loadSett = (p.loadSett);
    if (typeof p.SECTID == "undefined")
        this.SECTID = "";
    else
        this.SECTID = p.SECTID;

    this.loadMoreProject = function () {
        var loadUrl = o.path;
        loadUrl += '?PAGEN_1=' + (++o.curPage);
        o.ajaxLoader.addClass('loaded');
        $.ajax({
            url: loadUrl,
            type: "POST",
            data: {
                AJAX: 'Y',
                SECTID: o.SECTID,
                COUNT: o.COUNT,
            }
        }).done(function (html) {
            o.newsBlock.append(html);
            o.ajaxLoader.removeClass('loaded');

            if (o.curPage == o.loadSett.endPage) {
                o.ajaxLoader.hide();
            }
        });
    };
    this.init = function () {
        o.ajaxLoader.click(function (event) {
            o.loadMoreProject();
            event.preventDefault();
        })
    }
}

//фильтр заказов в списке заказов
$(document).on('change', '#order_filter', function () {
    var url = '';
    switch ($(this).val()) {
        case '':
            break;
        case 'all':
            url = '?show_all=Y';
            break;
        case 'can':
            url = '?filter_history=Y&show_canceled=Y';
            break;
        case 'fin':
            url = '?filter_history=Y';
            break;
        default:
            url = '?filter_status=' + $(this).val();
            break;
    }
    $.post(
        "/local/ajax/order_list.php" + url,
        function (data) {
            $('#ord-list').html(data);
        }
    );
});

//обработки после успешных аякс запросов
BX.addCustomEvent('onAjaxSuccessFinish', function () {

});
BX.addCustomEvent('onAjaxSuccess', function () {
    $('[autocomplete="tel"]').mask("+7(999) 999-9999");
    if ($('[data-property-id-row="2"]').hasClass('has-error')) {
        $('.auth-block.need-auth').addClass('has-error');
    } else {
        $('.auth-block.need-auth').removeClass('has-error');
    }
});

//удаление из избранного или из корзины
$(document).on('click', '.delete_fav', function (e) {
    var id = $(this).data('id');
    var prod = $(this).data('product');
    var fav = $(this).data('type');
    var del = $(this).parent();
    e.preventDefault();
    $.post(
        "/local/ajax/delFav.php",
        {
            id: id
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
        if (data != 0) {
            $(del).closest('.basket-item').html('<div class="basket-item__deleted"><div class="basket-item__deleted-text">Товар удалён</div><div class="buyBtn basket-item__deleted-return" data-id="' + $(del).find('.delete_fav').data('product') + '">Отмена</div></div>')
            // del.remove();
            if (fav == 'fav') {
                $('#favorite .fcount').html(parseInt($('#favorite .fcount').html()) - 1);
                $('#smallFavoriteHeader span').html(parseInt($('#smallFavoriteHeader span').html()) - 1);
                $("a.inFavorite[data-favorite-id = " + prod + "]").css("background-image", "");
                $("a.inFavorite[data-favorite-id = " + prod + "]").removeClass("inFv");
            } else if (fav == 'bask') {
                //$('.small-basket-quantity').html(parseInt($('.small-basket-quantity').html()) - 1);
                $('#smallBasketHeader span').html(parseInt($('#smallBasketHeader span').html()) - 1);

                $(".buyBtn[data-id = " + prod + "]").removeClass("inB");
                var count = -1;
                var last;
                $('#basket .basket-item').each(function (i, e) {
                    count = i;
                    last = $(this);
                });

                if (count == -1) {
                    $('#basket_form_small .bottom').remove();
                    $('#basket').append('<center class="empty_basket">Ваша корзина пуста</center><div class="continue-purchase-wrapper"><a href="/catalog/" class="continue-purchase">Продолжить покупки</a></div>');
                } else {
                    last.addClass('last');
                }

                $.get('/local/ajax/getAllSum.php', function (allSum) {
                    $('#allSum_FORMATED').html(allSum);

                    /*if (allSum.replace(/\D+/g, "") > 1499) {
                        $(".bottom-buttons .button").show();
                        $(".minsum").hide();

                    } else {
                        $(".bottom-buttons .button").hide();
                        $('.minsum__value').html(1500 - allSum.replace(/\D+/g, ""))
                        $(".minsum").show();

                    }*/
                });
            }
            $('.basket-item').each(function (index, el) {
                $(this).find('.product-number').text(++index + '.');
            });

            if (parseInt($('.small-basket-quantity').text()) <= 0) {
                $('.small-basket-quantity').css({ 'display': 'none' });
            }
        }

    }

});

/*Закрыть меню на ESC*/
$(document).keyup(function (e) {
    if (e.keyCode === 27) {
        $('.toggleOpen').removeClass('opened');
        $('.auth-form').removeClass('auth-form--showed');
        $('body').removeClass('body-modal')
        $('.modal-background').fadeOut()
    }
});

function changeTotalPrice(el) {
    let prodQuantity = $(el).get(0).value;
    let prodPrice = "";
    let prodPriceArray = $(el).closest('.basket-item').find('.price-value').text().split(" ");
    if (prodPriceArray.length > 1) {
        for (var i = 0; i < prodPriceArray.length; i++) {
            prodPrice += prodPriceArray[i];
        }
    } else {
        prodPrice = prodPriceArray[0];
    }

    prodPrice = parseFloat(prodPrice);
    if (prodQuantity <= 1) {
        $(el).closest('.basket-item').find('.price').css({ 'opacity': '0', 'cursor': 'default' });
    } else {
        $(el).closest('.basket-item').find('.price').css({ 'opacity': '1', 'cursor': 'text' });
    }
    let priceItem = prodQuantity * prodPrice;
    $(el).closest('.basket-item').find('.basket-item__price').children('span').text(BX.Currency.currencyFormat(priceItem, 'RUB', true));
}


$(document).ready(function () {

    $('.basket-close').on('click', function () {
        $("#basket.custom_popup_block").animate({
            right: "-700px",
        }, 300);
    });
    $('.baskLink').on('click', function (e) {
        e.preventDefault();
        $("#basket.custom_popup_block").animate({
            right: "0px",
        }, 300);

        $.get('/local/ajax/smallBasketContent.php', function (data) {
            data = JSON.parse(data);

            if (data['HTML']) {
                $basketContent = $('#small-basket-content');
                $basketContent.html(data['HTML']);
                $('#basket input').styler();

                $('.jq-number__field input', $basketContent).each(function (event) {
                    changeTotalPrice(this);
                });
                $('.jq-number__field input', $basketContent).change(function (event) {
                    changeTotalPrice(this);

                    $.get('/local/ajax/getAllSum.php', function (allSum) {
                        $basketContent.find('#allSum_FORMATED').html(allSum);
                    });
                });

                $.get('/local/ajax/getAllSum.php', function (allSum) {
                    $('#allSum_FORMATED').html(allSum);

                    /*if (allSum.replace(/\D+/g, "") > 1499) {
                        $(".bottom-buttons .button").show();
                        $(".minsum").hide();
    
                    } else {
                        $(".bottom-buttons .button").hide();
                        $('.minsum__value').html(1500 - allSum.replace(/\D+/g, ""))
                        $(".minsum").show();
    
                    }*/
                });

            }
        });
    });
    $('.cart-element').styler();
    $('[href = "#about_bonus"]').on('click', function () {
        $('[href = "#about_bonus"]').toggleClass('opened');
        $('#about_bonus').toggleClass('opened');
    });
    if (parseInt($('.small-basket-quantity').text()) <= 0) {
        $('.small-basket-quantity').css({ 'display': 'none' });
    }
    $('.jq-number__field input').each(function (event) {
        changeTotalPrice(this);
    });
    $('.jq-number__field input').change(function (event) {
        changeTotalPrice(this);
    });

    $(document).on('mouseenter', '.basket-item__delete', function () {
        $(this).find('.basket-item__delete-text').fadeIn(200)
    })
    $(document).on('mouseleave', '.basket-item__delete', function () {
        $(this).find('.basket-item__delete-text').fadeOut(200)
    })

    setTimeout(scrollAfterLink, 300);
    $('.i-icon.kirov').on('click', function () {
        $('.delivery-popup.kirov').show();
        $('.site-shadow').show();
        $('.site-shadow').css('z-index', '7');
    });
    $('.i-icon.pochta').on('click', function () {
        $('.delivery-popup.pochta').show();
        $('.site-shadow').show();
        $('.site-shadow').css('z-index', '7');
    });
    $('.delivery-popup-close').on('click', function () {
        $('.delivery-popup').hide();
        $('.site-shadow').hide();
        $('.site-shadow').css('z-index', '5');
    });
    $('.basket-checkout-container.delivery').on('click', function () {
        $('.detail-popup.delivery').show();
    });
    $('.basket-checkout-container.bonus').on('click', function () {
        $('.detail-popup.bonus').show();
    });
    $('.detail-popup-close').on('click', function () {
        $('.detail-popup.delivery').hide();
        $('.detail-popup.bonus').hide();
        $('.delivery-popup.pochta').hide();
        $('.delivery-popup.kirov').hide();
        $('.delivery-page').show();
    });
    $(".detail-popup-kirov").on('click', function () {
        $('.delivery-popup.kirov').show();
        $('.delivery-page').hide();
    });
    $(".detail-popup-pochta").on('click', function () {
        $('.delivery-popup.pochta').show();
        $('.delivery-page').hide();
    });
    $(".delivery-popup.kirov .back-window").on('click', function () {
        $('.delivery-popup.kirov').hide();
        $('.delivery-page').show();
    });
    $(".delivery-popup.pochta .back-window").on('click', function () {
        $('.delivery-popup.pochta').hide();
        $('.delivery-page').show();
    });
    $('.element-detail-title, .element-detail-text-more').on("click", function () {

        $('.element-detail-title').toggleClass('active');
        $('.detail-text-wrap').toggleClass('active');
        $('.shadow-text').toggleClass('active');
        $('.element-detail-text-more').toggleClass('open');
        $('.see-more').toggleClass('active');
        //$('.element-detail-title.back').toggleClass('active');
    });
    reportWindowSize();
    if ($('#search-page-value').length) {
        $('.search-line').each(function () {
            $('.search-line').val($('#search-page-value').val());
        });
    }
    $('.product-item-scu-item-selected').text($('.product-item-scu-item-text-container.selected .product-item-scu-item-text').text());

    $('.product-item-scu-list.single_props').on('click', '.product-item-scu-item-selected', function () {
        if ($(this).hasClass('open')) {
            $(this).removeClass('open');
            $('.product-item-scu-item-list.single_props').fadeOut();
        } else {
            $('.product-item-scu-item-selected').removeClass('open');
            $('.product-item-scu-item-list.single_props').fadeOut();
            $(this).addClass('open');
            $('.product-item-scu-item-list.single_props').fadeIn();
        }
    });
    $('.product-item-scu-list.single_props').on('click', '.product-item-scu-item-text-container.selected .product-item-scu-item-text', function () {
        $('.product-item-scu-item-selected').removeClass('open');
        $('.product-item-scu-item-list.single_props').fadeOut();
        $('.product-item-scu-item-selected').text($(this).text());
    });
    $('.product-item-scu-list.single_props').on('click', '.product-item-scu-item-text-container.notallowed.selected .product-item-scu-item-text', function () {
        $('.product-item-scu-item-selected').removeClass('open');
        $('.product-item-scu-item-list.single_props').fadeOut();
        $('.product-item-scu-item-selected').text($(this).text());
    });

});

function scrollAfterLink() {
    var url_string = window.location.href;
    var url = new URL(url_string);
    var c = url.hash.replace('__', '');
    var $page = $('html, body');
    if ($(c).length > 0)
        $page.animate({
            scrollTop: $(c).offset().top - 150
        }, 700);
    return false;
}
function prodquant(countprod) {
    removeQuatWrap();
    removeFlyProd();
    var countprodwin = $("<div id='content-wrap' class='quantity-wrappper'><div class='quantity-wrappper-inner'><div class='close' onclick='removeQuatWrap();'><div class='line'></div><div class='line'></div></div><div class='image-close-buy'>Доступное для заказа <br>количество товара <br><span>" + num_word(countprod, ['штука', 'штуки', 'штук']) + "</span></div></div></div>");
    countprodwin.appendTo($("body"));
    countprodwin.show();
}

function removeQuatWrap() {
    $("body .quantity-wrappper").remove();
}

function reportWindowSize() {
    if (document.documentElement.clientWidth < 992) {

        var widthslide = $('.slick-slide').width();
        $('.slick-slide').height(widthslide + 'px');
    }
}

window.onresize = reportWindowSize;

function num_word($value, $words, $show = true) {
    $num = $value % 100;
    if ($num > 19) {
        $num = $num % 10;
    }

    $out = ($show) ? $value + ' ' : '';
    switch ($num) {
        case 1: $out += $words[0]; break;
        case 2:
        case 3:
        case 4: $out += $words[1]; break;
        default: $out += $words[2]; break;
    }

    return $out;
}

$(document).ready(function () {
    $(document).click(function (e) {
        var popupBasketBlock = $('#basket.toggleOpen.custom_popup_block');
        if (popupBasketBlock.hasClass('opened')) {
            if ($(e.target).closest(popupBasketBlock).length) {
                return;
            }
            $(popupBasketBlock).removeClass('opened');
        }

        var deliveryPopupBlockKirov = $('.delPay-page .delivery-popup.kirov');
        if (deliveryPopupBlockKirov.css('display') == 'block') {
            if ($(e.target).closest(deliveryPopupBlockKirov).length || $(e.target).closest('.i-icon.kirov').length) {
                return;
            }
            $(deliveryPopupBlockKirov).hide();
        }

        var deliveryPopupBlockPochta = $('.delPay-page .delivery-popup.pochta');
        if (deliveryPopupBlockPochta.css('display') == 'block') {
            if ($(e.target).closest(deliveryPopupBlockPochta).length || $(e.target).closest('.i-icon.pochta').length) {
                return;
            }
            $(deliveryPopupBlockPochta).hide();
        }
    });

    $('.detail-popup .detail-popup-wrap').click(function (e) {
        var detailPopupDelivery = $('.detail-popup.delivery');
        if (detailPopupDelivery.css('display') == 'block') {
            if (
                $(e.target).closest('.detail-popup .detail-popup-wrap .delivery-page').length ||
                $(e.target).closest('.detail-popup .i-icon.detail-popup-kirov').length ||
                $(e.target).closest('.detail-popup .i-icon.detail-popup-pochta').length ||
                $(e.target).closest('.detail-popup .delivery-popup.kirov').length ||
                $(e.target).closest('.detail-popup .delivery-popup.pochta').length
            ) {
                return;
            }
            $('.detail-popup .detail-popup-wrap .delivery-page').show();
            $('.detail-popup .delivery-popup.kirov').hide();
            $('.detail-popup .delivery-popup.pochta').hide();
            $(detailPopupDelivery).hide();
        }

        var detailPopupBonus = $('.detail-popup.bonus');
        if (detailPopupBonus.css('display') == 'block') {
            if (
                $(e.target).closest('.detail-popup .detail-popup-wrap .bonus-page').length
            ) {
                return;
            }
            $('.detail-popup .detail-popup-wrap .delivery-page').show();
            $('.detail-popup .delivery-popup.kirov').hide();
            $('.detail-popup .delivery-popup.pochta').hide();
            $(detailPopupBonus).hide();
        }
    });
});
