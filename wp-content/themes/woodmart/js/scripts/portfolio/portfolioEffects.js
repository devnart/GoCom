/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioLoadMoreLoaded wdPortfolioPjaxComplete', function () {
		woodmartThemeModule.portfolioEffects();
	});

	$.each([
		'frontend/element_ready/wd_portfolio.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.portfolioEffects();
		});
	});

	woodmartThemeModule.portfolioEffects = function() {
		if (typeof ($.fn.panr) === 'undefined') {
			return;
		}

		$('.wd-portfolio-holder .portfolio-parallax').panr({
			sensitivity         : 15,
			scale               : false,
			scaleOnHover        : true,
			scaleTo             : 1.12,
			scaleDuration       : 0.45,
			panY                : true,
			panX                : true,
			panDuration         : 1.5,
			resetPanOnMouseLeave: true
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.portfolioEffects();
	});
})(jQuery);
