/* global woodmart_settings */
(function($) {
	woodmartThemeModule.singleProductTabsCommentsFix = function() {
		var url = window.location.href;
		var hash = window.location.hash;
		var $tabs = woodmartThemeModule.$body.find('.wc-tabs, ul.tabs').first();

		if (!$('.single-product-page').hasClass('reviews-location-separate')) {
			return;
		}

		if (hash.toLowerCase().indexOf('comment-') >= 0 || hash === '#reviews' || hash === '#tab-reviews') {
			$tabs.find('li:first a').click();
		} else if (url.indexOf('comment-page-') > 0 || url.indexOf('cpage=') > 0) {
			$tabs.find('li:first a').click();
		}
	};

	$(document).ready(function() {
		woodmartThemeModule.singleProductTabsCommentsFix();
	});
})(jQuery);
