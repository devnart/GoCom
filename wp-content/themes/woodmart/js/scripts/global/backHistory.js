/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdBackHistory', function () {
		woodmartThemeModule.backHistory();
	});

	woodmartThemeModule.backHistory = function() {
		$('.wd-back-btn > a').on('click', function(e) {
			e.preventDefault();

			history.go(-1);

			setTimeout(function() {
				$('.filters-area').removeClass('filters-opened').stop().hide();
				if (woodmartThemeModule.$window.width() <= 1024) {
					$('.wd-nav-product-cat').removeClass('categories-opened').stop().hide();
					$('.wd-btn-show-cat').removeClass('button-open');
				}

				woodmartThemeModule.$document.trigger('wdBackHistory');
			}, 20);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.backHistory();
	});
})(jQuery);
