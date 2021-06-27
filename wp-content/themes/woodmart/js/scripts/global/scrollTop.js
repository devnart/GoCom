/* global woodmart_settings */
(function($) {
	woodmartThemeModule.scrollTop = function() {
		var $scrollTop = $('.scrollToTop');

		woodmartThemeModule.$window.on('scroll', function() {
			if ($(this).scrollTop() > 100) {
				$scrollTop.addClass('button-show');
			} else {
				$scrollTop.removeClass('button-show');
			}
		});

		$scrollTop.on('click', function() {
			$('html, body').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.scrollTop();
	});
})(jQuery);
