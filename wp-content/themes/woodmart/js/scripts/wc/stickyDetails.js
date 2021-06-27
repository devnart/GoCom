/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdHeaderBuilderInited', function () {
		woodmartThemeModule.stickyDetails();
	});

	woodmartThemeModule.stickyDetails = function() {
		if (!woodmartThemeModule.$body.hasClass('woodmart-product-sticky-on') || woodmartThemeModule.$window.width() <= 1024) {
			return;
		}

		var details = $('.entry-summary');

		details.each(function() {
			var $column = $(this),
			    offset  = 40,
			    $inner  = $column.find('.summary-inner'),
			    $images = $column.parent().find('.product-images-inner');

			$inner.trigger('sticky_kit:detach');
			$images.trigger('sticky_kit:detach');

			if (woodmartThemeModule.$body.hasClass('enable-sticky-header') || $('.whb-sticky-row').length > 0 || $('.whb-sticky-header').length > 0) {
				offset = parseInt(woodmart_settings.sticky_product_details_offset);
			}

			$images.imagesLoaded(function() {
				var diff = $inner.outerHeight() - $images.outerHeight();

				if (diff < -100) {
					$inner.stick_in_parent({
						offset_top: offset
					});
				} else if (diff > 100) {
					$images.stick_in_parent({
						offset_top: offset
					});
				}

				woodmartThemeModule.$window.on('resize', woodmartThemeModule.debounce(function() {
					if (woodmartThemeModule.$window.width() <= 1024) {
						$inner.trigger('sticky_kit:detach');
						$images.trigger('sticky_kit:detach');
					} else if ($inner.outerHeight() < $images.outerHeight()) {
						$inner.stick_in_parent({
							offset_top: offset
						});
					} else {
						$images.stick_in_parent({
							offset_top: offset
						});
					}
				}, 300));
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.stickyDetails();
	});
})(jQuery);
