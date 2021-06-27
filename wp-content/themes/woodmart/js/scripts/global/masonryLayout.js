/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_blog.default',
		'frontend/element_ready/wd_portfolio.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.masonryLayout();
		});
	});

	woodmartThemeModule.masonryLayout = function() {
		if (typeof ($.fn.isotope) === 'undefined' || typeof ($.fn.imagesLoaded) === 'undefined') {
			return;
		}

		var $container = $('.masonry-container');

		$container.imagesLoaded(function() {
			$container.isotope({
				gutter      : 0,
				isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
				itemSelector: '.blog-design-masonry, .blog-design-mask, .masonry-item'
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.masonryLayout();
	});
})(jQuery);
