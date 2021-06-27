/* global woodmart_settings */
(function($) {
	woodmartThemeModule.miniCartQuantity = function() {
		var timeout;

		woodmartThemeModule.$document.on('change input', '.woocommerce-mini-cart .quantity .qty', function() {
			var input = $(this);
			var qtyVal = input.val();
			var itemID = input.parents('.woocommerce-mini-cart-item').data('key');
			var cart_hash_key = woodmart_settings.cart_hash_key;
			var fragment_name = woodmart_settings.fragment_name;

			clearTimeout(timeout);

			timeout = setTimeout(function() {
				input.parents('.mini_cart_item').addClass('wd-loading');

				$.ajax({
					url     : woodmart_settings.ajaxurl,
					data    : {
						action : 'woodmart_update_cart_item',
						item_id: itemID,
						qty    : qtyVal
					},
					success : function(data) {
						if (data && data.fragments) {
							$.each(data.fragments, function(key, value) {
								if ($(key).hasClass('widget_shopping_cart_content')) {
									var dataItemValue = $(value).find('.woocommerce-mini-cart-item[data-key="' + itemID + '"]');
									var dataFooterValue = $(value).find('.shopping-cart-widget-footer');
									var $itemSelector = $(key).find('.woocommerce-mini-cart-item[data-key="' + itemID + '"]');

									if (!data.cart_hash) {
										$(key).replaceWith(value);
									} else {
										$itemSelector.replaceWith(dataItemValue);
										$('.shopping-cart-widget-footer').replaceWith(dataFooterValue);
									}
								} else {
									$(key).replaceWith(value);
								}
							});

							if (woodmartThemeModule.supports_html5_storage) {
								sessionStorage.setItem(fragment_name, JSON.stringify(data.fragments));
								localStorage.setItem(cart_hash_key, data.cart_hash);
								sessionStorage.setItem(cart_hash_key, data.cart_hash);

								if (data.cart_hash) {
									sessionStorage.setItem('wc_cart_created', (new Date()).getTime());
								}
							}
						}
					},
					dataType: 'json',
					method  : 'GET'
				});
			}, 500);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.miniCartQuantity();
	});
})(jQuery);
