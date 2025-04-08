$(document).ready(function () {
    let widthWindow = $(window).width();
    if (widthWindow < 701) {
        $('.cources-blocks-slide').slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            centerMode: true,
            centerPadding: '100px',
        });
    }
});