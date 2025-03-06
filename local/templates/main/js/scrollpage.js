    $(document).on('scroll', function(){
        sessionStorage['scrollTop'] = $(document).scrollTop();
    });
    $(document).ready( function(){
    if (sessionStorage['goToBack'] != undefined && sessionStorage['goToBack'] == "true") {
        if (navigator.userAgent.match(/(iPod|iPhone|iPad|Android|Mac)/)) {     
            setTimeout(function() { 
                $('html,body').animate({
                    scrollTop: sessionStorage['scrollLastTop'],
                }, 800, function(){
                    $('html,body').clearQueue();
                });
            }, 500);
        }else{
            $(document).scrollTop(sessionStorage['scrollLastTop']);
        }
        sessionStorage['goToBack'] = false; 
    }
    });
