$(document).ready(function () {
    $('.works-list-slide').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        rows: 1,
        prevArrow: '<button type="button" data-role="none" class="slick-prev slick-arrow" aria-label="Prev" role="button" style="" aria-disabled="false">' +
            '<svg width="12" height="22" viewBox="0 0 12 22" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M10 1.5L1 11L10 20.5" stroke="#221F20" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
        nextArrow: '<button type="button" data-role="none" class="slick-next slick-arrow" aria-label="Next" role="button" style="" aria-disabled="false">' +
            '<svg width="12" height="22" viewBox="0 0 12 22" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path d="M2 1.5L11 11L2 20.5" stroke="#221F20" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 500,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('.more-program').on('click', function(){
        $('.detail-text').addClass('active');
        $('.detail-text ul').animate({
            height: '100%'
        }, 1000);
    });
});