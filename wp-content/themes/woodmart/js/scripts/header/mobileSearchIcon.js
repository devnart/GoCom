/* global woodmart_settings */
(function($) {
	woodmartThemeModule.mobileSearchIcon = function() {
		$('.wd-header-search-mobile').on('click', function(e) {
			e.preventDefault();
			var $nav = $('.mobile-nav');

			if (!$nav.hasClass('wd-opened')) {
				$nav.addClass('wd-opened');
				$('.wd-close-side').addClass('wd-close-side-opened');
				$('.mobile-nav .searchform').find('input[type="text"]').focus();
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.mobileSearchIcon();
	});
})(jQuery);
