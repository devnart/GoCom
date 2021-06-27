/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function() {
		woodmartThemeModule.categoriesDropdowns();
	});

	woodmartThemeModule.categoriesDropdowns = function() {
		$('.dropdown_product_cat').on('change', function() {
			var $this = $(this);

			if ('' !== $this.val()) {
				var this_page;
				var home_url = woodmart_settings.home_url;

				if (home_url.indexOf('?') > 0) {
					this_page = home_url + '&product_cat=' + $this.val();
				} else {
					this_page = home_url + '?product_cat=' + $this.val();
				}

				location.href = this_page;
			} else {
				location.href = woodmart_settings.shop_url;
			}
		});

		$('.widget_product_categories').each(function() {
			var $select = $(this).find('select');

			if ($().selectWoo) {
				$select.selectWoo({
					minimumResultsForSearch: 5,
					width                  : '100%',
					allowClear             : true,
					placeholder            : woodmart_settings.product_categories_placeholder,
					language               : {
						noResults: function() {
							return woodmart_settings.product_categories_no_results;
						}
					}
				});
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.categoriesDropdowns();
	});
})(jQuery);
