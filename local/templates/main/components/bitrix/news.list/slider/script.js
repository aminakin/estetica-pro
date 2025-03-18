$(document).ready(function () {
    $('#myCarousel').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: false,
        infinite: true,
        arrows: false,
        autoplay: true,
        autoplaySpeed: 4000,
        LazyLoad: 'ondemand',
        prevArrow: '<button type="button" data-role="none" class="slick-prev slick-arrow main-slider" aria-label="Prev" role="button" style="" aria-disabled="false">' +
            '<svg width="12" height="22" viewBox="0 0 12 22" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M10 1.5L1 11L10 20.5" stroke="#221F20" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
        nextArrow: '<button type="button" data-role="none" class="slick-next slick-arrow main-slider" aria-label="Next" role="button" style="" aria-disabled="false">' +
            '<svg width="12" height="22" viewBox="0 0 12 22" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M2 1.5L11 11L2 20.5" stroke="#221F20" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
    });
});