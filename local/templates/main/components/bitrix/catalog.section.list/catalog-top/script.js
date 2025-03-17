$(document).ready(function () {
    $('.bx_catalog_tile_ul_slider').slick({
        infinite: false,
        slidesToShow: 5,
        slidesToScroll: 3,
        prevArrow: '<button type="button" data-role="none" class="slick-prev slick-arrow catalog-section" aria-label="Prev" role="button" style="" aria-disabled="false">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n' +
            '<path d="M8.58997 4L16.59 12L8.58997 20" stroke="black" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
        nextArrow: '<button type="button" data-role="none" class="slick-next slick-arrow catalog-section" aria-label="Next" role="button" style="" aria-disabled="false">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">\n' +
            '<path d="M8.58997 4L16.59 12L8.58997 20" stroke="black" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>\n' +
            '</svg>\n</button>',
        centerMode: false,
        centerPadding: '90px',
        arrows: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 910,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 3,
                    swipeToSlide: true,
                    TouchMove: true,
                    swipe: true,
                    
                }
            },
            {
                breakpoint: 700,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 2,
                    swipeToSlide: true,
                    TouchMove: true,
                    swipe: true,
                    centerMode: true,
                    infinite: true,
                    centerPadding: '40px',
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToScroll: 1,
                    slidesToShow: 2,
                    swipeToSlide: true,
                    TouchMove: true,
                    swipe: true,
                    centerMode: true,
                    infinite: true,
                    centerPadding: '20px',
                }
            }
        ]
    });
});