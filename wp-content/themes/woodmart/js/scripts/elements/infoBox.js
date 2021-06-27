/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_infobox_carousel.default',
		'frontend/element_ready/wd_infobox.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.infoboxSvg();
		});
	});

	woodmartThemeModule.infoboxSvg = function() {
		$('.wd-info-box.with-animation').each(function() {
			var $this = $(this);

			if ($this.find('.info-svg-wrapper > svg').length > 0) {
				new Vivus($this.find('.info-svg-wrapper > svg')[0], {
					type              : 'delayed',
					duration          : 200,
					start             : 'inViewport',
					animTimingFunction: Vivus.EASE_OUT
				}, function() {});
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.infoboxSvg();
	});
})(jQuery);
