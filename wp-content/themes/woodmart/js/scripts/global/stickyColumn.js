/* global woodmart_settings */
(function($) {
	woodmartThemeModule.stickyColumn = function() {
		$('.woodmart-sticky-column').each(function() {
			var $column = $(this),
			    offset  = 0;

			if (woodmartThemeModule.$body.hasClass('enable-sticky-header') || $('.whb-sticky-row').length > 0 || $('.whb-sticky-header').length > 0) {
				offset = 150;
			}

			$column.find(' > .vc_column-inner > .wpb_wrapper').stick_in_parent({
				offset_top: offset
			});
		});

		$('.wd-elementor-sticky-column').each(function() {
			var $column = $(this);
			var offset = 150;
			var classes = $column.attr('class').split(' ');

			for (var index = 0; index < classes.length; index++) {
				if (classes[index].indexOf('wd_sticky_offset_') >= 0) {
					var data = classes[index].split('_');
					offset = parseInt(data[3]);
				}
			}

			var $widgetWrap = $column.find('> .elementor-column-wrap > .elementor-widget-wrap');

			if ($widgetWrap.length <= 0) {
				$widgetWrap = $column.find('> .elementor-widget-wrap');
			}

			$widgetWrap.stick_in_parent({
				offset_top: offset
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.stickyColumn();
	});
})(jQuery);
