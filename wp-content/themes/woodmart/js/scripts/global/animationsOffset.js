/* global woodmart_settings */
(function($) {
	woodmartThemeModule.animationsOffset = function() {
		if (typeof ($.fn.waypoint) == 'undefined') {
			return;
		}

		$('.wpb_animate_when_almost_visible:not(.wpb_start_animation)').waypoint(function() {
			var $this = $($(this)[0].element);
			$this.addClass('wpb_start_animation animated');
		}, {
			offset: '100%'
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.animationsOffset();
	});
})(jQuery);
