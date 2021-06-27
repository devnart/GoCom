/* global woodmart_settings */
(function($) {
	woodmartThemeModule.$document.on('wdPortfolioPjaxComplete', function () {
		woodmartThemeModule.portfolioPhotoSwipe();
	});

	$.each([
		'frontend/element_ready/wd_portfolio.default',
	], function(index, value) {
		woodmartThemeModule.wdElementorAddAction(value, function() {
			woodmartThemeModule.portfolioPhotoSwipe();
		});
	});

	woodmartThemeModule.portfolioPhotoSwipe = function() {
		woodmartThemeModule.$document.on('click', '.portfolio-enlarge', function(e) {
			e.preventDefault();
			var $this = $(this);
			var $parent = $this.parents('.owl-item');

			if ($parent.length === 0) {
				$parent = $this.parents('.portfolio-entry');
			}

			var index = $parent.index();
			var items = getPortfolioImages();

			woodmartThemeModule.callPhotoSwipe(index, items);
		});

		var getPortfolioImages = function() {
			var items = [];

			$('.portfolio-entry').find('figure a img').each(function() {
				var $this = $(this);

				items.push({
					src: $this.attr('src'),
					w  : $this.attr('width'),
					h  : $this.attr('height')
				});
			});

			return items;
		};
	};

	$(document).ready(function() {
		woodmartThemeModule.portfolioPhotoSwipe();
	});
})(jQuery);
