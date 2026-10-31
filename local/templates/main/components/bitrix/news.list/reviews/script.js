$(document).ready(function () {
    $('.reviews-list-slide').slick({
        infinite: false,
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
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 700,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $('.review-text').each(function(){
        let size = 80;
        let fulltext = $(this).text();

        if(fulltext.length > size){
            $(this).html(fulltext.slice(0, size) + '<span>...</span>');
        }
    });
    $(document).on('click', '.read-more', function(){
        let container = $(this).parent();
        let fulltext = container.find('.review-text-full').text();
        container.find('.review-text').text(fulltext);
        container.addClass('active');
     });
     $(document).on('click', '.less-more', function(){
        let container = $(this).parent();
        let fulltext = container.find('.review-text').text();
        let size = 100;

        if(fulltext.length > size){
            container.find('.review-text').html(fulltext.slice(0, size) + '<span>...</span>');
        }
        container.removeClass('active');
     });
});