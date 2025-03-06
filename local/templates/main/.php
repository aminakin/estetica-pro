<?
use Bitrix\Main\Page\Asset; 
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<!DOCTYPE html>
<html lang="<?=LANGUAGE_ID;?>">
	<head>
		<meta charset="utf-8"/>
	    <title><?$APPLICATION->ShowTitle();?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <link rel="shortcut icon" href="<?=SITE_TEMPLATE_PATH;?>/images/favi/var1f.ico" type="image/x-icon">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700,800&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
		
        <?
        $assetManager = Asset::getInstance();
        $styles = [
            //SITE_TEMPLATE_PATH . '/fonts/fonts.css',
            SITE_TEMPLATE_PATH . '/js/formstyler/jquery.formstyler.css',
            SITE_TEMPLATE_PATH . '/js/formstyler/jquery.formstyler.theme.css',
            SITE_TEMPLATE_PATH . '/js/slick/slick.css',
            SITE_TEMPLATE_PATH . '/js/fancy/jquery.fancybox.css',
            SITE_TEMPLATE_PATH . '/bootstrap.css',
            SITE_TEMPLATE_PATH . '/styles.css'
        ];
        foreach ($styles as $style) {
            $assetManager->addCss($style);
        }
        
        $scripts = [
            'http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js',
            SITE_TEMPLATE_PATH . '/js/formstyler/jquery.formstyler.js',
            "/bitrix/js/main/ajax.js",
            SITE_TEMPLATE_PATH . '/js/slick/slick.min.js',
            SITE_TEMPLATE_PATH . '/js/tablesaw/tablesaw.jquery.js',
            SITE_TEMPLATE_PATH . '/js/tablesaw/tablesaw-init.js',
            SITE_TEMPLATE_PATH . '/js/fancy/jquery.fancybox.min.js',
            SITE_TEMPLATE_PATH . '/js/fancy/jquery.fancybox-media.js',
            SITE_TEMPLATE_PATH . '/js/jquery.maskedinput.min.js',
            SITE_TEMPLATE_PATH . '/js/script.js'
        ];

        foreach ($scripts as $script) {
            $assetManager->addJs($script);
        }
		?>
        <!--[if	lt	IE	9]>
            <script src="<?echo SITE_TEMPLATE_PATH;?>/js/html5.js" data-skip-moving="true"></script>
	    <![endif]-->

		<?$APPLICATION->ShowHead();?>
		<script src="/carousel/bootstrap.min.js"></script>
    <script type="text/javascript">
	     $(document).ready(function() {	
			    $(window).scroll(function () {
			        if ($(this).scrollTop() > 0) {
			            $('#scroller').fadeIn();
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
	           	
	           	
				 $('#myCarousel').carousel({
				    interval: 4000
				});
	     })
	     
	     
	</script> 
		
	</head>
<body <?if(Page::IsMain()){?> class="mainPage" <?}?>>
<?$APPLICATION->ShowPanel();?>
<div id="layer"<?=(CSite::inDir('/courses/') || CSite::inDir('/brands/') || CSite::inDir('/catalog/') || CSite::inDir('/sale/') || CSite::inDir('/news/'))?"":" class='leaf'"?>>
    <div class="layer_body">    	    
        <header>
        	<div class="container">
	        	<div class="col-sm-4 col-md-3 col-lg-2">
	        	    <div class="left">
	                  <a href="#menu" class="openMenu linkOpen">
			          	<img src="<?=SITE_TEMPLATE_PATH;?>/images/catalog.png" alt="Каталог"/>
			          	Каталог
			          </a>
	                    
	                    
	                    <a href="#search" class="searchLink linkOpen">
	                        <svg width="20px" height="20px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"  viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve">
	                            <path class="fil0" d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23
	                                s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92
	                                c0