/* global woodmart_settings */
(function($) {
	woodmartThemeModule.stickySocialButtons = function() {
		$('.wd-sticky-social').addClass('buttons-loaded');
	};

	$(document).ready(function() {
		woodmartThemeModule.stickySocialButtons();
	});
})(jQuery);
