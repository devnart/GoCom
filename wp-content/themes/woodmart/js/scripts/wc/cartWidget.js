/* global woodmart_settings */
(function($) {
	woodmartThemeModule.cartWidget = function() {
		var body = woodmartThemeModule.$body;

		var cartWidgetSide = $('.cart-widget-side');
		var closeSide = $('.wd-close-side');

		body.on('click', '.cart-widget-opener', function(e) {
			if (!isCart() && !isCheckout()) {
				e.preventDefault();
			}

			if (isOpened()) {
				closeWidget();
			} else {
				setTimeout(function() {
					openWidget();
				}, 10);
			}
		});

		body.on('click touchstart', '.wd-close-side', function() {
			if (isOpened()) {
				closeWidget();
			}
		});

		body.on('click', '.close-side-widget', function(e) {
			e.preventDefault();
			if (isOpened()) {
				closeWidget();
			}
		});

		woodmartThemeModule.$document.on('keyup', function(e) {
			if (e.keyCode === 27 && isOpened()) {
				closeWidget();
			}
		});

		var closeWidget = function() {
			cartWidgetSide.removeClass('wd-opened');
			closeSide.removeClass('wd-close-side-opened');
		};

		var openWidget = function() {
			if (isCart() || isCheckout()) {
				return false;
			}
			cartWidgetSide.addClass('wd-opened');
			closeSide.addClass('wd-close-side-opened');
		};

		var isOpened = function() {
			return cartWidgetSide.hasClass('wd-opened');
		};

		var isCart = function() {
			return woodmartThemeModule.$body.hasClass('woocommerce-cart');
		};

		var isCheckout = function() {
			return woodmartThemeModule.$body.hasClass('woocommerce-checkout');
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.cartWidget();
	});
})(jQuery);
