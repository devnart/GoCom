/* global woodmart_settings */
(function($) {
	woodmartThemeModule.onRemoveFromCart = function() {
		if ('no' === woodmart_settings.woocommerce_ajax_add_to_cart) {
			return;
		}

		woodmartThemeModule.$document.on('click', '.widget_shopping_cart .remove', function(e) {
			e.preventDefault();
			$(this).parent().addClass('removing-process');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.onRemoveFromCart();
	});
})(jQuery);
