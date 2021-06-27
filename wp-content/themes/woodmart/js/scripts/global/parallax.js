/* global woodmart_settings */
(function($) {
	woodmartThemeModule.parallax = function() {
		if (woodmartThemeModule.windowWidth <= 1024) {
			return;
		}

		$('.wd-parallax').each(function() {
			var $this = $(this);

			if ($this.hasClass('wpb_column')) {
				$this.find('> .vc_column-inner').parallax('50%', 0.3);
			} else {
				$this.parallax('50%', 0.3);
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.parallax();
	});
})(jQuery);
