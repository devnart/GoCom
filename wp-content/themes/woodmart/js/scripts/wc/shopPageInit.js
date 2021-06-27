/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdShopPageInit', function () {
		woodmartThemeModule.shopPageInit();
	});

	woodmartThemeModule.shopPageInit = function() {
		woodmartThemeModule.clickOnScrollButton(woodmartThemeModule.shopLoadMoreBtn, false, woodmart_settings.infinit_scroll_offset);

		$('body > .tooltip').remove();

		woodmartThemeModule.$body.on('updated_wc_div', function() {
			woodmartThemeModule.$document.trigger('wood-images-loaded');
		});

		woodmartThemeModule.$document.trigger('resize.vcRowBehaviour');
	};
})(jQuery);
