/* global woodmart_settings */
(function($) {
	woodmartThemeModule.woodSliderLazyLoad = function() {
		woodmartThemeModule.$window.on('wdEventStarted', function() {
			var $slider = $('.wd-slider');
			$slider.on('changed.owl.carousel', function(event) {
				var $this = $(this);
				var active = $this.find('.owl-item').eq(event.item.index + 1);
				var id = active.find('.wd-slide').attr('id');
				var $els = $this.find('[id="' + id + '"]');

				active.find('.wd-slide').addClass('woodmart-loaded');
				$this.find('.owl-item').eq(event.item.index).find('.wd-slide').addClass('woodmart-loaded');

				$els.each(function() {
					$(this).addClass('woodmart-loaded');
				});
			});

			$slider.trigger('refresh.owl.carousel');
		});
	};

	$(document).ready(function() {
		woodmartThemeModule.woodSliderLazyLoad();
	});
})(jQuery);
