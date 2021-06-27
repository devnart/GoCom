/* global woodmart_settings */
(function($) {
	woodmartThemeModule.productVideo = function() {
		if ('undefined' === typeof $.fn.magnificPopup) {
			return;
		}

		$('.product-video-button a').magnificPopup({
			tClose         : woodmart_settings.close,
			tLoading       : woodmart_settings.loading,
			type           : 'iframe',
			iframe         : {
				patterns: {
					youtube: {
						index: 'youtube.com/',
						id   : 'v=',
						src  : '//www.youtube.com/embed/%id%?rel=0&autoplay=1'
					}
				}
			},
			preloader      : false,
			fixedContentPos: false
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.productVideo();
	});
})(jQuery);
