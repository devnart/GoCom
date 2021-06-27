/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_images_gallery.default'
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.imagesGalleryMasonry();
			woodmartThemeModule.imagesGalleryJustified();
		});
	});

	woodmartThemeModule.imagesGalleryMasonry = function() {
		if (typeof ($.fn.isotope) == 'undefined' || typeof ($.fn.imagesLoaded) == 'undefined') {
			return;
		}

		var $container = $('.view-masonry .gallery-images');

		$container.imagesLoaded(function() {
			$container.isotope({
				gutter      : 0,
				isOriginLeft: !woodmartThemeModule.$body.hasClass('rtl'),
				itemSelector: '.wd-gallery-item'
			});
		});
	};

	woodmartThemeModule.imagesGalleryJustified = function() {
		$('.view-justified').each(function() {
			$(this).find('.gallery-images').justifiedGallery({
				margins     : 1,
				cssAnimation: true
			});
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.imagesGalleryMasonry();
		woodmartThemeModule.imagesGalleryJustified();
	});
})(jQuery);
