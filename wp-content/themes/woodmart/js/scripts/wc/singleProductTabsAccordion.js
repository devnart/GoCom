/* global woodmart_settings */
(function($) {
	$(window).on('elementor/frontend/init', function() {
		if (!elementorFrontend.isEditMode()) {
			return;
		}

		$(window).on('resize', function() {
			woodmartThemeModule.singleProductTabsAccordion();
		});
	});

	woodmartThemeModule.singleProductTabsAccordion = function() {
		var $wcTabs = $('.woocommerce-tabs');
		if (woodmartThemeModule.windowWidth > 1024 || $wcTabs.length <= 0) {
			return;
		}

		$wcTabs.removeClass('tabs-layout-tabs').addClass('tabs-layout-accordion');
		$('.single-product-page').removeClass('tabs-type-tabs').addClass('tabs-type-accordion');
	};

	$(document).ready(function() {
		woodmartThemeModule.singleProductTabsAccordion();
	});
})(jQuery);
