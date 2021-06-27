/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woocommerceWrappTable = function() {
		$('.shop_table:not(.shop_table_responsive):not(.woocommerce-checkout-review-order-table)').wrap('<div class=\'responsive-table\'></div>');
	};

	$(document).ready(function() {
		woodmartThemeModule.woocommerceWrappTable();
	});
})(jQuery);
