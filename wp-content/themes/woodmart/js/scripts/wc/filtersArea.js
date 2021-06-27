/* global woodmart_settings */
(function($) {
	woodmartThemeModule.filtersArea = function() {
		var filters = $('.filters-area'),
		    time    = 200;

		woodmartThemeModule.$body.on('click', '.open-filters', function(e) {
			e.preventDefault();

			if (isOpened()) {
				closeFilters();
			} else {
				woodmartThemeModule.openFilters(time);
				setTimeout(function() {
					woodmartThemeModule.$document.trigger('wdFiltersOpened');
				}, time);
			}
		});

		if (woodmart_settings.shop_filters_close === 'no') {
			woodmartThemeModule.$body.on('click', woodmartThemeModule.ajaxLinks, function() {
				if (isOpened()) {
					closeFilters();
				}
			});
		}

		var isOpened = function() {
			filters = $('.filters-area');
			return filters.hasClass('filters-opened');
		};

		var closeFilters = function() {
			filters = $('.filters-area');
			filters.removeClass('filters-opened');
			filters.stop().slideUp(time);
		};
	};

	woodmartThemeModule.openFilters = function(time) {
		var filters = $('.filters-area');
		filters.stop().slideDown(time);

		setTimeout(function() {
			filters.addClass('filters-opened');
			woodmartThemeModule.$document.trigger('wdFiltersOpened');

			woodmartThemeModule.$body.removeClass('body-filters-opened');
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		}, time);
	};

	$(document).ready(function() {
		woodmartThemeModule.filtersArea();
	});
})(jQuery);
