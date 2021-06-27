/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdArrowsLoadProducts wdLoadMoreLoadProducts wdProductsTabsLoaded', function () {
		woodmartThemeModule.swatchesLimit();
	});

	woodmartThemeModule.swatchesLimit = function() {
		$('.wd-swatches-divider').on('click', function() {
			var $this = $(this).parent();

			$this.find('.wd-swatch').removeClass('wd-hidden');
			$this.addClass('wd-all-shown');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.swatchesLimit();
	});
})(jQuery);
