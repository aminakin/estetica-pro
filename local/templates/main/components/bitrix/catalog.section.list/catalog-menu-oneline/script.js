$(document).ready(function() {
    $('.bx_sitemap_ul_oneline li.first span a').on('click', function(e){
        //console.log($(this).parents('span').next());
        if($(this).parents('span').next().length){
            e.preventDefault();
        }
		$('.bx_sitemap_ul_oneline li.first').removeClass('active');
		$(this).parents('.bx_sitemap_ul_oneline li.first').addClass('active');
	});
    $('.bx_sitemap_ul_oneline li.second span a').on('click', function(e){
        //console.log($(this).parents('span').next());
        if($(this).parents('span').next().length){
            e.preventDefault();
        }
        $('.bx_sitemap_ul_oneline li.second').removeClass('active');
        $(this).parents('.bx_sitemap_ul_oneline li.second').addClass('active');
    });
    $('.bx_sitemap_ul_oneline li>ul').each(function() {
       $(this).parents('li').find('a').addClass('active');
    });
});