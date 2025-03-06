$(function(){
	var form = $('#subscribe-form');
	var successContainer = form.siblings('.js-successbox');
	var errorContainer = $('.js-errorbox-all', form);

	form.on('submit', function(event){
		event.preventDefault();
		successContainer.hide();
		errorContainer.hide();

		$.post(form.attr('action'), form.serialize(), function(response){
			try 
			{
				response = JSON.parse(response);
				if (response.status == 'ok')
				{
					successContainer.fadeIn('fast');
					form.hide();
				}
				else
				{
					errorContainer.html(response.message);
					errorContainer.fadeIn('fast');
				}
			}
			catch (err)
			{
				errorContainer.html('Что-то пошло не так. Пожалуйста, попробуйте обновить страницу и подписаться еще раз');
				errorContainer.fadeIn('fast');
			}
		});
	});
});