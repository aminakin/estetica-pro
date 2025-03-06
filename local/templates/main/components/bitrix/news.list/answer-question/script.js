BX.ready(function () {
    $('.answer-item').on('click', function(){
        if($(this).hasClass('active')){
            $(this).children('.answer-item_text').slideUp();
            $(this).removeClass('active');
        }else{
            $('.answer-item_text').each(function(){
                $(this).slideUp();
            });
            $('.answer-item').each(function(){
                $(this).removeClass('active');
            });
            $(this).children('.answer-item_text').slideDown();
            $(this).addClass('active');
        }
    });
});