$(document).ready(function () {
    $('#cources-blocks-slider').slick({
        centerMode: true,
        centerPadding: '320px',
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    centerPadding: '0px',
                    arrows: false
                }
            }
        ]
    });
});