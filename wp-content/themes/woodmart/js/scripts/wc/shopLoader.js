/* global wd_settings */
(function($) {
	woodmartThemeModule.$document.on('wdFiltersOpened wdShopPageInit wdPjaxStart', function () {
		woodmartThemeModule.shopLoader();
	});

	woodmartThemeModule.shopLoader = function() {
		var loaderVerticalPosition = function() {
			var $products = $('.products[data-source="main_loop"], .wd-portfolio-holder[data-source="main_loop"]');
			var $loader = $products.parent().find('.wd-sticky-loader');

			if ($products.length < 1) {
				return;
			}

			var offset = woodmartThemeModule.$window.height() / 2;
			var scrollTop = woodmartThemeModule.$window.scrollTop();
			var holderTop = $products.offset().top - offset + 45;
			var holderHeight = $products.height();
			var holderBottom = holderTop + holderHeight - 170;

			if (scrollTop < holderTop) {
				$loader.addClass('wd-position-top');
				$loader.removeClass('wd-position-stick');
			} else if (scrollTop > holderBottom) {
				$loader.addClass('wd-position-bottom');
				$loader.removeClass('wd-position-stick');
			} else {
				$loader.addClass('wd-position-stick');
				$loader.removeClass('wd-position-top wd-position-bottom');
			}
		};

		woodmartThemeModule.$window.off('scroll.loaderVerticalPosition');

		woodmartThemeModule.$window.on('scroll.loaderVerticalPosition', loaderVerticalPosition);
	};

	$(document).ready(function() {
		woodmartThemeModule.shopLoader();
	});
})(jQuery);