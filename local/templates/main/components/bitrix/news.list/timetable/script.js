$( document ).ready(function() {
    $(".button-nail").on('click', function(){
    	$('.timetable-list.course-nail').show();
    	$('.timetable-list.course-lash').hide();
    	$(".button-lash").removeClass('active');
    	$(".button-nail").addClass('active');
	});
	$(".button-lash").on('click', function(){
    	$('.timetable-list.course-nail').hide();
    	$('.timetable-list.course-lash').show();
    	$(".button-nail").removeClass('active');
    	$(".button-lash").addClass('active');
	});
});
