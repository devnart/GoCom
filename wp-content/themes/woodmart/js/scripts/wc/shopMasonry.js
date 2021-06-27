/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdProductsTabsLoaded wdShopPageInit', function () {
		woodmartThemeModule.shopMasonry();
	});

	$.each([
		'frontend/element_ready/wd_products.default',
		'frontend/element_ready/wd_products_tabs.default',
		'frontend/element_ready/wd_products_brands.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.shopMasonry();
		});
	});

	woodmartThemeModule.shopMasonry = function() {
		if (typeof ($.fn.isotope) == 'undefined' || typeof ($.fn.packery) == 'undefined' || typeof ($.fn.imagesLoaded) == 'undefined') {
			return;
		}

		var $container = $('.elements-grid.grid-masonry');
		$container.imagesLoaded(function() {
			$container.isotope({
				isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
				itemSelector: '.category-grid-item, .product-grid-item'
			});
		});

		woodmartThemeModule.$window.on('resize', function() {
			initMasonry();
		});

		initMasonry();

		function initMasonry() {
			var $catsContainer = $('.categories-masonry');
			var colWidth = ($catsContainer.hasClass('categories-style-masonry')) ? '.category-grid-item' : '.col-lg-3.category-grid-item';
			$catsContainer.imagesLoaded(function() {
				$catsContainer.packery({
					resizable   : false,
					isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
					packery     : {
						gutter     : 0,
						columnWidth: colWidth
					},
					itemSelector: '.category-grid-item'
				});
			});
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.shopMasonry();
	});
})(jQuery);
