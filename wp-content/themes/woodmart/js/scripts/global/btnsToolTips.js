/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdBackHistory wdProductsTabsLoaded wdActionAfterAddToCart wdShopPageInit wdArrowsLoadProducts wdLoadMoreLoadProducts wdUpdateWishlist wdQuickViewOpen wdQuickShopSuccess wdProductBaseHoverIconsResize', function () {
		woodmartThemeModule.btnsToolTips();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.btnsToolTips();
		});
	});

	woodmartThemeModule.btnsToolTips = function() {
		$('.woodmart-css-tooltip, .wd-buttons[class*="wd-pos-r"] div > a').on('mouseenter touchstart', function () {
			var $this = $(this);

			if (!$this.hasClass('wd-add-img-msg') && $(window).width() <= 1024 || $this.hasClass('wd-tooltip-inited')) {
				return;
			}

			$this.find('.wd-tooltip-label').remove();
			$this.addClass('wd-tltp').prepend('<span class="wd-tooltip-label">' + $this.text() + '</span>');

			var $label = $this.find('.wd-tooltip-label');

			if ($this.hasClass('wd-tltp-top')) {
				var width = $label.outerWidth();
				$label.css({
					marginLeft: - parseInt(width / 2)
				})
			}

			$this.addClass('wd-tooltip-inited');
		});

		// Bootstrap tooltips
		if (woodmartThemeModule.windowWidth <= 1024) {
			return;
		}

		$('.wd-tooltip, .wd-hover-icons .wd-buttons .wd-action-btn:not(.wd-add-btn) > a, .wd-hover-icons .wd-buttons .wd-add-btn, body:not(.catalog-mode-on):not(.login-see-prices) .wd-hover-base .wd-bottom-actions .wd-action-btn.wd-style-icon:not(.wd-add-btn) > a, body:not(.catalog-mode-on):not(.login-see-prices) .wd-hover-base .wd-bottom-actions .wd-action-btn.wd-style-icon.wd-add-btn, .wd-hover-base .wd-compare-btn > a, .wd-products-nav .wd-back-btn').on('mouseenter touchstart', function() {
			var $this = $(this);

			if ($this.hasClass('wd-tooltip-inited')) {
				return;
			}

			$this.tooltip({
				animation: false,
				container: 'body',
				trigger  : 'hover',
				boundary: 'window',
				title    : function() {
					var $this = $(this);

					if ($this.find('.added_to_cart').length > 0) {
						return $this.find('.add_to_cart_button').text();
					}

					return $this.text();
				}
			});

			$this.tooltip('show');

			$this.addClass('wd-tooltip-inited');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.btnsToolTips();
	});
})(jQuery);

