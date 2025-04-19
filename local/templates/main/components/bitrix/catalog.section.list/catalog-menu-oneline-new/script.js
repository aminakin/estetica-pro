$(document).ready(function() {
    let firstBlock = $('.bx_sitemap_oneline.brand .first-block');
    let secondBlock = $('.bx_sitemap_oneline.brand .second-block');
    let thirdBlock = $('.bx_sitemap_oneline.brand .third-block');


    $('.first-block li span a').click(function(event) {
        if(!$(this).parents('li').hasClass('no_children')) {
            event.preventDefault();
        }

        firstBlock.children('li').each(function() {
            $(this).removeClass('elem-active-parent');
        });

        $(this).parents('li').addClass('elem-active-parent');

        let parId = $(this).parents('li').data('parents');

        secondBlock.children('li').each(function() {
            $(this).hide();
            if($(this).data('children') == parId) {
                $(this).show();
            }
        });

        thirdBlock.children('li').each(function() {
            $(this).hide();
        });
    });

    /*$('.second-block li span a').click(function(event) {
        if(!$(this).parents('li').hasClass('no_children')) {
            event.preventDefault();
        }

        secondBlock.children('li').each(function() {
            $(this).removeClass('elem-active-children');
        });

        $(this).parents('li').addClass('elem-active-children');

        let parId = $(this).parents('li').data('parents');

        thirdBlock.children('li').each(function() {
            $(this).hide();
            if($(this).data('children') == parId) {
                $(this).show();
            }
        });
    });

    secondBlock.hover(
        function() {
            firstBlock.find('.elem-active-parent').addClass('hover-elem')
            firstBlock.addClass('hover');
        }, function() {
            firstBlock.find('.elem-active-parent').removeClass('hover-elem')
            firstBlock.removeClass('hover');
        }
    );

    thirdBlock.hover(
        function() {
            firstBlock.find('.elem-active-parent').addClass('hover-elem')
            firstBlock.addClass('hover');

            secondBlock.find('.elem-active-children').addClass('hover-elem')
            secondBlock.addClass('hover');
        }, function() {
            firstBlock.find('.elem-active-parent').removeClass('hover-elem')
            firstBlock.removeClass('hover');

            secondBlock.find('.elem-active-children').removeClass('hover-elem')
            secondBlock.removeClass('hover');
        }
    );*/
});