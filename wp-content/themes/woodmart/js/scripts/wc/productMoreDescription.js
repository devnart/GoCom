/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit wdLoadMoreLoadProducts wdArrowsLoadProducts wdProductsTabsLoaded wdUpdateWishlist', function () {
		woodmartThemeModule.productMoreDescription();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.productMoreDescription();
		});
	});

	woodmartThemeModule.productMoreDescription = function() {
		$('.wd-hover-base').on('mouseenter touchstart', function() {
			var $content = $(this).find('.wd-more-desc');
			var $inner = $content.find('.wd-more-desc-inner');
			var $moreBtn = $content.find('.wd-more-desc-btn');

			if ($content.hasClass('wd-more-desc-calculated')) {
				return;
			}

			var contentHeight = $content.outerHeight();
			var innerHeight = $inner.outerHeight();
			var delta = innerHeight - contentHeight;

			if (delta > 30) {
				$moreBtn.addClass('wd-shown');
			} else if (delta > 0) {
				$content.css('height', contentHeight + delta);
			}

			$content.addClass('wd-more-desc-calculated');
		});

		woodmartThemeModule.$body.on('click', '.wd-more-desc-btn', function(e) {
			e.preventDefault();
			var $this = $(this);

			$this.parent().addClass('wd-more-desc-full');

			woodmartThemeModule.$document.trigger('wdProductMoreDescriptionOpen', [$this.parents('.wd-hover-base')]);
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productMoreDescription();
	});
})(jQuery);
