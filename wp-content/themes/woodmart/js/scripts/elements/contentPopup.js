/* global woodmart_settings */
(function($) {
	$.each([
		'frontend/element_ready/wd_popup.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.contentPopup();
		});
	});

	woodmartThemeModule.contentPopup = function() {
		if ('undefined' === typeof $.fn.magnificPopup) {
			return;
		}

		$('.wd-open-popup').magnificPopup({
			type        : 'inline',
			removalDelay: 500, //delay removal by X to allow out-animation
			tClose      : woodmart_settings.close,
			tLoading    : woodmart_settings.loading,
			callbacks   : {
				beforeOpen: function() {
					this.st.mainClass = 'mfp-move-horizontal content-popup-wrapper';
				},
				open      : function() {
					woodmartThemeModule.$document.trigger('wood-images-loaded');
				}
			}
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.contentPopup();
	});
})(jQuery);
