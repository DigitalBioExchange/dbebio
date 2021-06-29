;(function ($) {

	// play slidebars for mobile
	$.slidebars({
		scrollLock: true,
		containerSlector: '.header'
	});

	$('#top_wrap_obj').width($('.header').width());

	// navigation toggle event
	var $gnb = $('#gnb');
	$gnb.find('li').each(function(){
		if ($(this).children('div').length)
		{
			$(this).addClass('is-child')
		}
	});
	$gnb.find('.dep-1 > li > a, .dep-2 > li > a').on('click', function(){
		if ($(this).parent().hasClass('active'))
		{
			$(this).parent().removeClass('active');
		}
		else
		{
			$(this).parent().parent().find('li').removeClass('active');
			$(this).parent().addClass('active');
		}
	});
})(jQuery);
