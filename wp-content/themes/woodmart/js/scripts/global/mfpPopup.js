/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioLoadMoreLoaded', function () {
		woodmartThemeModule.mfpPopup();
	});

	woodmartThemeModule.mfpPopup = function() {
		if ('undefined' === typeof $.fn.magnificPopup) {
			return;
		}

		$('.gallery').magnificPopup({
			delegate    : ' > a',
			type        : 'image',
			removalDelay: 500,
			tClose      : woodmart_settings.close,
			tLoading    : woodmart_settings.loading,
			callbacks   : {
				beforeOpen: function() {
					this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
					this.st.mainClass = 'mfp-move-horizontal';
				}
			},
			image       : {
				verticalFit: true
			},
			gallery     : {
				enabled           : true,
				navigateByImgClick: true
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.mfpPopup();
	});
})(jQuery);
