/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdProductsTabsLoaded wdArrowsLoadProducts wdLoadMoreLoadProducts wdUpdateWishlist', function () {
		woodmartThemeModule.gridQuantity();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.gridQuantity();
		});
	});

	woodmartThemeModule.gridQuantity = function() {
		$('.product-grid-item').on('change input', '.quantity .qty', function() {
			var $this = $(this);
			var add_to_cart_button = $this.parent().parent().find('.add_to_cart_button');

			add_to_cart_button.attr('data-quantity', $this.val());
			add_to_cart_button.attr('href', '?add-to-cart=' + add_to_cart_button.attr('data-product_id') + '&quantity=' + $this.val());
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.gridQuantity();
	});
})(jQuery);
